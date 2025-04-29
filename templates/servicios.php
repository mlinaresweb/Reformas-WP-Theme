<?php
/*
Template Name: Página de Servicios
*/
get_header();

$banner_field = get_field('banner_image'); // ACF devuelve array si es tipo imagen
if ( $banner_field && isset($banner_field['url']) ) {
  $banner_url = $banner_field['url'];
} else {
  // fallback a imagen por defecto
  $banner_url = site_url('/wp-content/uploads/banner-servicios.jpg');
}

?>

<main class="servicios-overall">

<section class="proyecto-banner" style="background-image: url('<?php echo esc_url($banner_url); ?>');">
  <div class="banner-content">
    <h1><?php the_title(); ?></h1>
  </div>
</section>
  <div class="wrapper-contenido">
    <header class="servicios-header">
      <p>Conoce los servicios de reformas integrales que ofrecemos para transformar tu hogar. Descubre cómo podemos ayudarte en albañilería, carpintería, fontanería, electricidad y pintura.</p>
    </header>
    
    <div class="servicios-cards">
  <?php
    $servicios = get_terms([
      'taxonomy'   => 'servicio',
      'hide_empty' => false,
    ]);
    if ( ! empty( $servicios ) && ! is_wp_error( $servicios ) ) :
      foreach ( $servicios as $servicio ) :
        $term_link = get_term_link( $servicio );
        // Elegimos imagen según slug
        switch ( $servicio->slug ) {
          case 'reformas-albanileria':
            $img_url = site_url('/wp-content/uploads/albañil1.webp');
            break;
          case 'reformas-carpinteria':
            $img_url = site_url('/wp-content/uploads/carpintero1.webp');
            break;
          case 'reformas-fontaneria':
            $img_url = site_url('/wp-content/uploads/fontanero1.webp');
            break;
          case 'reformas-electricista':
            $img_url = site_url('/wp-content/uploads/electricista1.webp');
            break;
          case 'reformas-pintor':
            $img_url = site_url('/wp-content/uploads/pintor1.webp');
            break;
          default:
            $img_url = site_url('/wp-content/uploads/default-card.webp');
        }
  ?>
    <div class="servicio-card">
      <a href="<?php echo esc_url( $term_link ); ?>" class="servicio-card-link">
        <!-- Imagen 16:9 -->
        <div class="servicio-card-image">
          <img src="<?php echo esc_url( $img_url ); ?>"
               alt="<?php echo esc_attr( $servicio->name ); ?>">
        </div>
        <!-- Cuerpo flexible -->
        <div class="servicio-card-body">
          <h2 class="servicio-card-title"><?php echo esc_html( $servicio->name ); ?></h2>
          <p class="servicio-card-desc">
            <?php echo esc_html( $servicio->description ?: 'Descubre más sobre este servicio.' ); ?>
          </p>
          <span class="servicio-card-spacer"></span>
          <button class="btn-servicio-card">Ver servicio</button>
        </div>
      </a>
    </div>
  <?php
      endforeach;
    else:
      echo '<p>No se han configurado servicios aún.</p>';
    endif;
  ?>
</div>

  </div><!-- .wrapper-contenido -->

  <!-- Sección SEO antes del formulario -->
<section class="seo-text-section">
  <div class="wrapper-contenido">
    <p class="seo-text">
      ¡Transforma tu hogar con nuestros servicios de reformas en Barcelona y alrededores!
    </p>
    <p class="seo-text">
      ¿Estás pensando en renovar tu hogar? <span class="span-marron"> ¡Nosotros te ayudamos! </span>
    </p>
    <p class="seo-text seo-text-last">
      En El Amrani Khalid Reformas somos expertos en reformas integrales y servicios especializados en albañilería, carpintería, fontanería, electricidad y pintura. Nos encargamos de cada detalle de tu proyecto para ofrecerte soluciones personalizadas, profesionales y de alta calidad. Trabajamos en toda el área de Barcelona y alrededores, adaptándonos a tus necesidades y presupuesto.
    </p>
    <a href="http://localhost/reformas/contacto/" class="btn-cta-presupuesto">Pide Presupuesto</a>
  </div>
</section>

<!-- Sección: Contáctanos -->
<section class="seccion-contacto">
  <div class="wrapper-contenido">
    <div class="contact-layout">
      
      <!-- Columna Izquierda: Imagen -->
      <div class="contact-image" style="background-image: url('<?php echo site_url('/wp-content/uploads/reforma-contacto.webp'); ?>');">
        <!-- Este div se encargará de mostrar la imagen como fondo -->
      </div>
      
      <!-- Columna Derecha: Formulario -->
      <div class="contact-form">
        <h2>Pide Tu Presupuesto</h2>
        <?php echo do_shortcode('[custom_contact_form]'); ?>
      </div>
      
    </div><!-- .contact-layout -->
  </div><!-- .wrapper-contenido -->
</section>
</main>

<?php get_footer(); ?>
