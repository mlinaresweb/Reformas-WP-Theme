<?php
/**
 * single-proyecto.php — Plantilla para mostrar un Proyecto individual
 */
get_header(); ?>

<main class="single-proyectos">
  <div class="wrapper-contenido">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <!-- Título del proyecto -->
      <h1 class="proyecto-title"><?php the_title(); ?></h1>

      <!-- Imagen destacada -->
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="proyecto-featured-image">
          <?php the_post_thumbnail( 'full' ); ?>
        </div>
      <?php endif; ?>

      <!-- Descripción del proyecto -->
      <section class="proyecto-descripcion">
        <?php
          $descripcion = get_post_meta( get_the_ID(), '_proyecto_descripcion', true );
          echo wpautop( wp_kses_post( $descripcion ) );
        ?>
      </section>

      <!-- Galería de imágenes -->
      <section class="proyecto-galeria">
  <h2>Galería de imágenes</h2>
  <div class="galeria-grid baguette-gallery">
    <?php
      $galeria_meta = get_post_meta( get_the_ID(), '_proyecto_galeria', true );
      if ( $galeria_meta ) {
        $ids = array_map( 'trim', explode( ',', $galeria_meta ) );
        foreach ( $ids as $img_id ) {
          $url = wp_get_attachment_url( $img_id );
          $thumb = wp_get_attachment_image_url( $img_id, 'medium' );
          if ( $url && $thumb ) : ?>
            <div class="galeria-item galeria-item-proyecto">
              <a href="<?php echo esc_url( $url ); ?>" data-caption="<?php echo esc_attr( get_the_title() ); ?>">
                <img src="<?php echo esc_url( $thumb ); ?>"
                     alt="<?php echo esc_attr( get_the_title() ); ?>">
              </a>
            </div>
    <?php   endif;
        }
      }
    ?>
  </div>
</section>


      <!-- Trabajos realizados en la reforma -->
      <?php
        $trabajos = get_post_meta( get_the_ID(), '_proyecto_trabajos', true );
        if ( $trabajos ) : ?>
          <section class="proyecto-trabajos">
            <h2>Trabajos realizados en la reforma</h2>
            <div class="trabajos-content">
              <?php echo wp_kses_post( $trabajos ); ?>
            </div>
          </section>
      <?php endif; ?>

      <!-- Materiales empleados -->
      <?php
        $materiales = get_post_meta( get_the_ID(), '_proyecto_materiales', true );
        if ( $materiales ) : ?>
          <section class="proyecto-materiales">
            <h2>Materiales empleados</h2>
            <div class="materiales-content">
              <?php echo wp_kses_post( $materiales ); ?>
            </div>
          </section>
      <?php endif; ?>

    <?php endwhile; endif; ?>

  </div><!-- .wrapper-contenido -->
</main>

<?php get_footer(); ?>
