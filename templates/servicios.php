<?php
/*
Template Name: Página de Servicios
*/
get_header();
?>

<main class="servicios-overall">
  <div class="wrapper-contenido">
    <header class="servicios-header">
      <h1>Nuestros Servicios</h1>
      <p>Conoce los servicios de reformas integrales que ofrecemos para transformar tu hogar. Descubre cómo podemos ayudarte en albañilería, carpintería, fontanería, electricidad y pintura.</p>
    </header>
    
    <div class="servicios-cards">
      <?php
        // Recuperamos todos los términos de la taxonomía "servicio"
        $servicios = get_terms( array(
          'taxonomy'   => 'servicio',
          'hide_empty' => false,
        ) );
        if( ! empty( $servicios ) && ! is_wp_error( $servicios ) ) :
          foreach( $servicios as $servicio ) :
            // En este ejemplo usamos el nombre y la descripción (si la hay)
            $term_link = get_term_link( $servicio );
      ?>
      <div class="servicio-card">
        <div class="servicio-card-inner">
          <h2 class="servicio-card-title"><?php echo esc_html( $servicio->name ); ?></h2>
          <?php if( $servicio->description ) : ?>
          <p class="servicio-card-desc"><?php echo esc_html( $servicio->description ); ?></p>
          <?php else: ?>
          <p class="servicio-card-desc">Descubre más sobre este servicio.</p>
          <?php endif; ?>
          <a href="<?php echo esc_url($term_link); ?>" class="btn-servicio-card">Ver servicio</a>
        </div>
      </div>
      <?php
          endforeach;
        else:
          echo '<p>No se han configurado servicios aún.</p>';
        endif;
      ?>
    </div><!-- .servicios-cards -->
  </div><!-- .wrapper-contenido -->
</main>

<?php get_footer(); ?>
