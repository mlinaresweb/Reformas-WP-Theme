<?php get_header(); ?>

<main class="servicio-individual">
    <?php
      // Término actual
      $term = get_queried_object();
      $banner_image = get_term_meta( $term->term_id, 'banner_image', true );
      $banner_heading = get_term_meta( $term->term_id, 'banner_heading', true );
      $banner_subheading = get_term_meta( $term->term_id, 'banner_subheading', true );
      $intro_text = get_term_meta( $term->term_id, 'intro_text', true );
      $side_image = get_term_meta( $term->term_id, 'side_image', true );
    ?>
    <!-- Banner -->
    <section class="servicio-banner" style="background-image: url('<?php echo esc_url( $banner_image ? $banner_image : site_url('/wp-content/uploads/default-banner.jpg') ); ?>');">
      <div class="banner-content">
        <h1><?php echo $banner_heading ? esc_html( $banner_heading ) : esc_html( $term->name ); ?></h1>
        <?php if( $banner_subheading ) : ?>
          <h2><?php echo esc_html( $banner_subheading ); ?></h2>
        <?php endif; ?>
      </div>
    </section>
    
    <!-- Introducción en dos columnas: Texto e Imagen Lateral -->
    <section class="servicio-intro">
    <div class="wrapper-contenido">

      <div class="intro-layout">
      <div class="intro-text">
  <?php 
    // Recupera el contenido del campo; si está vacío se usa la descripción del término
    $intro_text = get_term_meta( $term->term_id, 'intro_text', true );
    if( empty( $intro_text ) ) {
      $intro_text = $term->description;
    }
    // wpautop convierte los saltos de línea a <p> y <br>
    echo wpautop( $intro_text );
  ?>
</div>

        <div class="intro-image">
          <?php if( $side_image ) : ?>
            <img src="<?php echo esc_url($side_image); ?>" alt="<?php echo esc_attr($term->name); ?>">
          <?php endif; ?>
        </div>
      </div>
    </div><!-- .wrapper-contenido -->
    </section>
    
 <!-- CTA Presupuesto -->
 <?php echo do_shortcode('[cta_presupuesto]'); ?>


    <!-- Bloque de Proyectos -->
    <?php echo do_shortcode( '[service_projects]' ); ?>


    <!-- Sección SEO antes del formulario -->
<section class="seo-text-section">
  <div class="wrapper-contenido">
    <p class="seo-text">
      ¡Transforma tu hogar con nuestros servicios de reformas en Barcelona y alrededores!
    </p>
    <p class="seo-text">
      ¿Estás pensando en renovar tu hogar? <span class="span-marron"> ¡Nosotros te ayudamos! </span>
    </p>
    <p class="seo-text">
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
