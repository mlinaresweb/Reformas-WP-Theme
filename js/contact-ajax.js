document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#contact-form');
    if (!form) return;
  
    /* ---------- Caja de mensajes ---------- */
    const msgBox = document.createElement('div');
    msgBox.id = 'contact-msg';
    msgBox.style.cssText =
      'margin-top:1rem;padding:.8rem;border-radius:4px;display:none';
    form.append(msgBox);
  
    let countdownId;        // setInterval
    let hideMsgId;          // setTimeout 10 s
    const KEY = 'cf-retry-until';
  
    /* ---------- Recuperar bloqueo almacenado ---------- */
    let retryUntil = Number(sessionStorage.getItem(KEY) || 0);
    const now = Date.now();
    if (retryUntil && now < retryUntil) {
      const remaining = Math.ceil((retryUntil - now) / 1000);
      startCountdown(remaining);          // inicia temporizador al cargar
    } else {
      retryUntil = 0;                     // caducado o inexistente
      sessionStorage.removeItem(KEY);
    }
  
    /* ---------- Función temporizador ---------- */
    function startCountdown(remaining) {
      clearInterval(countdownId);
  
      msgBox.style.display = 'block';
      msgBox.style.background = '#f8d7da';
      msgBox.style.color = '#842029';
      updateText(remaining);
  
      countdownId = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
          clearInterval(countdownId);
          msgBox.style.display = 'none';
          retryUntil = 0;
          sessionStorage.removeItem(KEY);
        } else {
          updateText(remaining);
        }
      }, 1000);
    }
  
    function updateText(sec) {
      msgBox.textContent =
        `Por favor, espera ${sec} segundo${sec === 1 ? '' : 's'} antes de volver a enviar.`;
    }
  
    /* ---------- Submit ---------- */
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
  
      /* 0 ▸ Sigue el bloqueo local? */
      if (retryUntil && Date.now() < retryUntil) {
        const remaining = Math.ceil((retryUntil - Date.now()) / 1000);
        startCountdown(remaining);
        return;                                 
      }
  
      /* 1 ▸ Validación */
      form.reportValidity();
      if (!form.checkValidity()) return;
  
      clearInterval(countdownId);
      clearTimeout(hideMsgId);
  
      /* 2 ▸ Ajax */
      const fd = new FormData(form);
      fd.set('action', 'reformas_handle_contact_ajax');
  
      try {
        const res  = await fetch(reformasAjax.ajaxUrl, { method: 'POST', body: fd });
        const data = await res.json();
  
        if (data.success) {
          msgBox.style.display = 'block';
          msgBox.textContent   = data.data.message;
          msgBox.style.background = '#d1e7dd';
          msgBox.style.color      = '#0f5132';
          form.reset();
          hideMsgId = setTimeout(() => (msgBox.style.display = 'none'), 10000);
          return;
        }
  
        /* 429 ▸ servidor indica tiempo restante */
        if (res.status === 429 && data.data.retry_after) {
          const remaining = data.data.retry_after;
          retryUntil = Date.now() + remaining * 1000;
          sessionStorage.setItem(KEY, retryUntil);   // ⬅️ persiste
          startCountdown(remaining);
          return;
        }
  
        /* Otros errores */
        msgBox.style.display   = 'block';
        msgBox.textContent     = data.data?.message || 'Error inesperado.';
        msgBox.style.background = '#f8d7da';
        msgBox.style.color      = '#842029';
  
      } catch {
        alert('Error de red. Inténtalo más tarde.');
      }
    });
  });
  