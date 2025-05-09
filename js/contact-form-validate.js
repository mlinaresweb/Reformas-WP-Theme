document.addEventListener('DOMContentLoaded', () => {
	const form = document.querySelector('#contact-form');
	if (!form) return;

	const flash = document.getElementById('flash-msg');
	if (flash) setTimeout(() => flash.remove(), 10000);

	form.addEventListener('submit', e => {
		let valid = true;

		// re-set errores
		form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');

		form.querySelectorAll('input, textarea').forEach(field => {
			if (!field.checkValidity()) {
				valid = false;
				const msg = field.validationMessage || 'Campo inválido';
				field.parentElement.nextElementSibling.textContent = msg;
			}
		});

		if (!valid) e.preventDefault();
	});
});
