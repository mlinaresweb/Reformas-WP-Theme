<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
  <div class="wrapper-contenido nav-container">
    <!-- Logo -->
    <div class="logo">
      <a href="<?php echo home_url(); ?>">
        <?php if ( has_custom_logo() ) the_custom_logo(); else bloginfo('name'); ?>
      </a>
    </div>

    <!-- Botón hamburguesa (mobile) -->
    <button class="nav-toggle" aria-label="Abrir menú">
      <span></span><span></span><span></span>
    </button>

    <!-- Menú Desktop -->
    <nav class="main-navigation desktop-menu">
      <?php wp_nav_menu([
        'theme_location'=>'menu_principal',
        'container'=>false,
        'menu_class'=>'nav-menu'
      ]); ?>
    </nav>
  </div>

  <!-- Overlay menú Mobile -->
  <div class="mobile-menu-overlay">
    <div class="mobile-menu-header">
      <div class="logo">
        <a href="<?php echo home_url(); ?>">
          <?php if ( has_custom_logo() ) the_custom_logo(); else bloginfo('name'); ?>
        </a>
      </div>
      <button class="nav-close" aria-label="Cerrar menú">&times;</button>
    </div>
    <nav class="main-navigation mobile-menu menu-level-0">
  <ul class="nav-menu">
    <?php 
      wp_nav_menu([
        'theme_location'=>'menu_principal',
        'container'=>false,
        'items_wrap'=>'%3$s',
        'depth'=>1, // solo primer nivel
      ]); 
    ?>
    <!-- Agregamos flechas manualmente en JS -->
  </ul>
</nav>

<!-- VISTA SUBMENU SERVICIOS -->
<nav class="main-navigation mobile-menu menu-level-1 servicios-submenu" style="display:none">
  <div class="mobile-submenu-header">
    <button class="submenu-back">&larr; Volver</button>
    <span class="mobile-submenu-title">Servicios</span>
  </div>
  <ul class="nav-menu">
    <?php 
      $terms = get_terms(['taxonomy'=>'servicio','hide_empty'=>false]);
      foreach($terms as $t): 
        // URL ya la generas con tu filtro o page
        $url = site_url('/proyectos-'.str_replace('reformas-','',$t->slug).'/');
    ?>
      <li><a href="<?php echo esc_url($url) ?>"><?php echo esc_html($t->name) ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>

<!-- VISTA SUBMENU PROYECTOS -->
<nav class="main-navigation mobile-menu menu-level-1 proyectos-submenu" style="display:none">
  <div class="mobile-submenu-header">
    <button class="submenu-back">&larr; Volver</button>
    <span class="mobile-submenu-title">Proyectos</span>
  </div>
  <ul class="nav-menu">
    <?php 
      // Reutilizamos el mismo término “servicio” para los proyectos
      foreach($terms as $t): 
        $url = site_url('/proyectos-'.str_replace('reformas-','',$t->slug).'/');
    ?>
      <li><a href="<?php echo esc_url($url) ?>">Proyectos de <?php echo esc_html($t->name) ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const overlay       = document.querySelector('.mobile-menu-overlay');
  const toggle        = document.querySelector('.nav-toggle');
  const closeBtn      = document.querySelector('.nav-close');
  const lvl0          = overlay.querySelector('.menu-level-0');
  const serviciosView = overlay.querySelector('.servicios-submenu');
  const proyectosView = overlay.querySelector('.proyectos-submenu');
  const backButtons   = overlay.querySelectorAll('.submenu-back');

  function showLevel0(){
    lvl0.style.display         = 'block';
    serviciosView.style.display = 'none';
    proyectosView.style.display  = 'none';
  }
  function showServicios(){
    lvl0.style.display         = 'none';
    serviciosView.style.display = 'flex';
    proyectosView.style.display  = 'none';
  }
  function showProyectos(){
    lvl0.style.display         = 'none';
    serviciosView.style.display = 'none';
    proyectosView.style.display  = 'flex';
  }

  // Inyectar botón de flecha en Servicios y Proyectos
  function markHasChildren() {
    lvl0.querySelectorAll('li > a').forEach(a => {
      const txt = a.textContent.trim();
      if ( txt === 'Servicios' || txt === 'Proyectos' ) {
        const li = a.parentElement;
        if (!li.classList.contains('menu-item-has-children')) {
          li.classList.add('menu-item-has-children');
          // Crear botón
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'submenu-toggle';
          btn.setAttribute('aria-label','Abrir submenú '+txt);
          btn.textContent = '►';
          // Insertar justo después de <a>
          a.insertAdjacentElement('afterend', btn);

          // Listener solo en el botón
          btn.addEventListener('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            if ( txt === 'Servicios' ) showServicios();
            else if ( txt === 'Proyectos' ) showProyectos();
          });
        }
      }
    });
  }

  // Abrir / cerrar overlay
  toggle.addEventListener('click', () => {
    overlay.classList.add('active');
    showLevel0();
    markHasChildren();
  });
  closeBtn.addEventListener('click', () => {
    overlay.classList.remove('active');
    showLevel0();
  });
  overlay.addEventListener('click', e => {
    if (e.target === overlay) {
      overlay.classList.remove('active');
      showLevel0();
    }
  });

  // Volver desde submenus
  backButtons.forEach(btn => {
    btn.addEventListener('click', showLevel0);
  });

  // Inicializar al cargar
  showLevel0();
});
</script>
