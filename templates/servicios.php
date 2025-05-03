<?php

/*
Template Name: Página de Servicios
*/
get_header();

?>

<main class="servicios-overall">

  <!-- Sección Banner -->
  <?php echo do_shortcode('[page_banner]'); ?>

  <div class="wrapper-contenido">

    
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
 <?php echo do_shortcode('[seo_cta]'); ?>

<!-- Sección: Contáctanos -->
<?php echo do_shortcode('[contact_section]'); ?>

</main>

<?php get_footer(); ?>
