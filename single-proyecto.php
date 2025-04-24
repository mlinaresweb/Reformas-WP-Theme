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

      <?php
  // Recuperar meta
  $fecha     = get_post_meta( get_the_ID(), '_proyecto_fecha', true );
  $ubicacion = get_post_meta( get_the_ID(), '_proyecto_ubicacion', true );
  // Formatear fecha
  if ( $fecha ) {
    $fecha_fmt = date_i18n( get_option('date_format'), strtotime( $fecha ) );
  }
  // Solo si existe alguno mostramos el bloque
  if ( $fecha || $ubicacion ) : 
?>
  <div class="proyecto-meta">
    <?php if ( $fecha ) : ?>
      <span class="proyecto-fecha" aria-label="Fecha de realización">
        <i class="icon-calendar"></i>
        <?php echo esc_html( $fecha_fmt ); ?>
      </span>
    <?php endif; ?>
    <?php if ( $ubicacion ) : ?>
      <span class="proyecto-ubicacion" aria-label="Ubicación">
        <i class="icon-location"></i>
        <?php echo esc_html( $ubicacion ); ?>
      </span>
    <?php endif; ?>
  </div>
<?php endif; ?>

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

<div class="wrapper-listas-proyectos">
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
      </div>
    <?php endwhile; endif; ?>

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
      <div class="contact-image" style="background-image: url('<?php echo site_url('/wp-content/uploads/reforma-contacto.jpg'); ?>');">
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
