<?php
/*
Template Name: Página de Contacto
*/
get_header();



$banner_field = get_field('banner_image'); // ACF devuelve array si es tipo imagen
if ( $banner_field && isset($banner_field['url']) ) {
  $banner_url = $banner_field['url'];
} else {
  // fallback a imagen por defecto
  $banner_url = site_url('/wp-content/uploads/carpinteria.jpg');
}

?>

<main class="contact-page">
   <!-- Banner -->
<section class="proyecto-banner" style="background-image: url('<?php echo esc_url($banner_url); ?>');">
  <div class="banner-content">
    <h1><?php the_title(); ?></h1>
  </div>
</section>
  <div class="wrapper-contenido contact-layout">

    <!-- Columna Izquierda: Datos de Contacto -->
    <aside class="contact-info">
  <h2>Contáctanos</h2>
  <ul class="contact-list">
    <li class="contact-item">
      <img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/correo.png'); ?>" alt="Email">
      <div class="contact-text">
        <strong>Email:</strong><br>
        <a href="mailto:info@tudominio.com">info@tudominio.com</a>
      </div>
    </li>
    <li class="contact-item">
      <img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/whatsapp.png'); ?>" alt="WhatsApp">
      <div class="contact-text">
        <strong>WhatsApp:</strong><br>
        <a href="https://wa.me/34666666666?text=Hola%20quisiera%20más%20info" target="_blank" rel="noopener">
          +34 666 666 666
        </a>
      </div>
    </li>
    <li class="contact-item">
      <img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/facebook.png'); ?>" alt="Facebook">
      <div class="contact-text">
        <strong>Facebook:</strong><br>
        <a href="https://facebook.com/tuempresa" target="_blank" rel="noopener">
          facebook.com/tuempresa
        </a>
      </div>
    </li>
    <li class="contact-item">
      <img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/instagram.png'); ?>" alt="Instagram">
      <div class="contact-text">
        <strong>Instagram:</strong><br>
        <a href="https://instagram.com/tuempresa" target="_blank" rel="noopener">
          @tuempresa
        </a>
      </div>
    </li>
  </ul>
</aside>


    <!-- Columna Derecha: Formulario -->
    <section class="contact-form-section">
      <h2>Pide tu presupuesto</h2>
      <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="contact-form">
        <?php wp_nonce_field( 'contact_page_form', 'contact_page_nonce' ); ?>
        <input type="hidden" name="action" value="handle_contact_page_form">

        <p>
          <label for="cp_name">Nombre</label>
          <input type="text" id="cp_name" name="contact_name" required>
        </p>
        <p>
          <label for="cp_email">Correo electrónico</label>
          <input type="email" id="cp_email" name="contact_email" required>
        </p>
        <p>
          <label for="cp_phone">Teléfono</label>
          <input type="tel" id="cp_phone" name="contact_phone">
        </p>
        <p>
          <label for="cp_message">Mensaje</label>
          <textarea id="cp_message" name="contact_message" rows="5" required></textarea>
        </p>
        <p>
          <button type="submit" class="btn-contact-submit">Enviar</button>
        </p>
      </form>
    </section>

  </div><!-- .contact-layout -->
</main>

<?php get_footer(); ?>
