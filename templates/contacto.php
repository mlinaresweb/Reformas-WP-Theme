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
      <?php echo do_shortcode('[custom_contact_form]'); ?>
    </section>

  </div><!-- .contact-layout -->
</main>

<?php get_footer(); ?>
