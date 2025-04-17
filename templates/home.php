<?php
/*
Template Name: Plantilla Home (Reformas)
*/
get_header(); ?>

<main class="home-site main-content">
  <!-- Sección Hero con imagen de fondo editable -->
  <?php 
    // Obtener la imagen de fondo del campo ACF
    $hero_bg_image = get_field('hero_background_image'); 

    // Si no hay imagen, usar la imagen por defecto
    if( !$hero_bg_image ) {
      // Usamos site_url() para obtener la URL base y apuntar a la imagen en uploads
      $hero_bg_image = site_url('/wp-content/uploads/header-opt.webp');
    } else {
      // Si se selecciona una imagen en ACF, se obtiene el URL de la imagen (asumiendo que ACF devuelve un array)
      $hero_bg_image = $hero_bg_image['url'];
    }
  ?>

  <section class="hero-home">
  <div class="hero-wrrapper wrapper-contenido">
    <div class="hero-contenedor">
  <div class="hero-content">
    <h1><?php the_field('titulo_subhero'); ?></h1>
      <h2><?php the_field('titulo_hero'); ?></h2>
      <p><?php the_field('texto_hero'); ?></p>
      <a href="<?php the_field('link_boton_hero'); ?>" class="btn-hero">
        <?php the_field('texto_boton_hero'); ?>
      </a>
    </div>
    </div>
    </div>
  </section>

<!-- Sección: Qué Hacemos / Servicios de Reforma -->
<section class="servicios-de-reforma">
  <div class="wrapper-contenido">
    <h2 class="section-title">Nuestros Servicios</h2>
    <div class="servicios-layout">
      
      <!-- Columna Izquierda: Galería (imagen grande que cambia) -->
      <div class="servicios-gallery">
        <?php
          // Imagen por defecto para la galería
          $default_img = site_url('/wp-content/uploads/reformas.jpg');
        ?>
        <img src="<?php echo esc_url( $default_img ); ?>" alt="Servicio Destacado" id="servicio-img">
      </div>
      
      <!-- Columna Derecha: Lista de Servicios -->
      <div class="servicios-list">
        <?php 
          $servicios = get_terms( array(
            'taxonomy'   => 'servicio',
            'hide_empty' => false,
          ) );
          if( ! empty( $servicios ) && ! is_wp_error( $servicios ) ) :
            foreach( $servicios as $servicio ) :
              // Definir dos imágenes diferentes: una para la galería y otra para el icono.
              $gallery_image_url = '';
              $icon_image_url = '';
              switch( $servicio->slug ) {
                case 'reformas-albanileria':
                    $gallery_image_url = site_url('/wp-content/uploads/albañileria.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/albañil-marron.png');
                    break;
                case 'reformas-carpinteria':
                    $gallery_image_url = site_url('/wp-content/uploads/carpinteria.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/carpintero-marron.png');
                    break;
                case 'reformas-fontaneria':
                    $gallery_image_url = site_url('/wp-content/uploads/fontaneria.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/fontanero-marron.png');
                    break;
                case 'reformas-electricista':
                    $gallery_image_url = site_url('/wp-content/uploads/lampista.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/electricista-marron.png');
                    break;
                case 'reformas-pintor':
                    $gallery_image_url = site_url('/wp-content/uploads/pintura.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/pintor-marron.png');
                    break;
                default:
                    $gallery_image_url = site_url('/wp-content/uploads/reformas.jpg');
                    $icon_image_url    = site_url('/wp-content/uploads/albañil-marron.png');
                    break;
            }
            
        ?>
        <div class="servicio-item" data-image="<?php echo esc_url( $gallery_image_url ); ?>">
          <div class="servicio-icon">
            <img src="<?php echo esc_url( $icon_image_url ); ?>" alt="<?php echo esc_attr( $servicio->name ); ?> Icono">
          </div>
          <div class="servicio-info">
            <h3 class="servicio-title"><?php echo esc_html( $servicio->name ); ?></h3>
            <?php if( $servicio->description ) : ?>
              <p class="servicio-description"><?php echo esc_html( $servicio->description ); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php 
            endforeach;
          endif;
        ?>
      </div><!-- .servicios-list -->
      
    </div><!-- .servicios-layout -->
  </div><!-- .wrapper-contenido -->
</section>





<!-- Sección Por qué Elegirnos -->
<section class="porque-elegirnos">
  <div class="wrapper-contenido">
    <h2 class="section-title"><?php the_field('titulo_elegirnos'); ?></h2>
    <h2 class="section-title">¿ Por que elegirnos ?</h2>
    <div class="cards-container">
      <?php if( have_rows('elegirnos_cards') ): ?>
        <?php while( have_rows('elegirnos_cards') ) : the_row(); ?>
          <div class="card">
            <?php 
              $icon = get_sub_field('icono_card'); 
              if( $icon ): 
            ?>
              <div class="card-icon">
                <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
              </div>
            <?php endif; ?>
            <h3 class="card-title"><?php the_sub_field('titulo_card'); ?></h3>
            <p class="card-desc"><?php the_sub_field('descripcion_card'); ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <!-- Fallback: cards predeterminadas -->
        <div class="card">
          <div class="card-icon">
            <img src="http://localhost/reformas/wp-content/uploads/ahorro-marron.png" alt="Mejor Relación Calidad-Precio">
          </div>
          <h3 class="card-title">Mejor Relación Calidad-Precio</h3>
          <p class="card-desc">Obtén la máxima calidad en reformas sin comprometer tu presupuesto.</p>
        </div>
        <div class="card">
          <div class="card-icon">
            <img src="http://localhost/reformas/wp-content/uploads/presupuesto-marron.png" alt="Presupuestos Transparentes">
          </div>
          <h3 class="card-title">Presupuestos Transparentes</h3>
          <p class="card-desc">Conoce cada detalle y costo de tu proyecto desde el inicio, sin sorpresas.</p>
        </div>
        <div class="card">
          <div class="card-icon">
            <img src="http://localhost/reformas/wp-content/uploads/experiencia-marron.png" alt="Experiencia Profesional">
          </div>
          <h3 class="card-title">Experiencia Profesional</h3>
          <p class="card-desc">Años de experiencia respaldan nuestro compromiso con la excelencia.</p>
        </div>
        <div class="card">
          <div class="card-icon">
            <img src="http://localhost/reformas/wp-content/uploads/confianza-marron.png" alt="Calidad y Confianza">
          </div>
          <h3 class="card-title">Calidad y Confianza</h3>
          <p class="card-desc">Proyectos que garantizan la mejor calidad y la confianza de nuestros clientes.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Sección: Nuestros Proyectos -->
<section class="galeria-proyectos">
  <div class="wrapper-contenido">
    <div class="galeria-proyectos-layout">
      
      <!-- Columna Izquierda (30%) -->
      <div class="galeria-info">
        <h2 class="galeria-title">Descubre<br>nuestro<br>trabajo</h2>
        <p class="galeria-desc">
          Conoce cómo llevamos a cabo cada reforma, combinando experiencia y calidad para transformar espacios.
        </p>
        <a href="<?php echo esc_url(get_post_type_archive_link('proyecto')); ?>" class="btn-ver-proyectos">Ver proyectos</a>
      </div>
      
      <!-- Columna Derecha (70%) -->
      <div class="galeria-items">
        <?php
          // Primero, obtener proyectos destacados
          $args_featured = array(
            'post_type'      => 'proyecto',
            'posts_per_page' => 4,
            'meta_query'     => array(
              array(
                'key'     => '_proyecto_destacado',
                'value'   => 'yes',
                'compare' => '='
              )
            )
          );
          $featured_query = new WP_Query($args_featured);
          $featured_count = count($featured_query->posts);
          $projects = $featured_query->posts;
          
          // Si no hay suficientes destacados, rellenar con los más recientes
          if ( $featured_count < 4 ) {
            $needed = 4 - $featured_count;
            $exclude_ids = wp_list_pluck($featured_query->posts, 'ID');
            $args_recent = array(
              'post_type'      => 'proyecto',
              'posts_per_page' => $needed,
              'post__not_in'   => $exclude_ids,
              'orderby'        => 'date',
              'order'          => 'DESC'
            );
            $recent_query = new WP_Query($args_recent);
            $projects = array_merge($projects, $recent_query->posts);
          }
          
          if ( ! empty( $projects ) ) {
            foreach ( $projects as $project ) {
              // Intentar obtener la galería del proyecto (se guarda como cadena de IDs separados por comas)
              $gallery_ids = get_post_meta( $project->ID, '_proyecto_galeria', true );
              $project_img = '';
              if( !empty($gallery_ids) ) {
                  // Convertir la cadena a un array
                  $gallery_ids_array = array_map('trim', explode(',', $gallery_ids));
                  if( !empty($gallery_ids_array[0]) ) {
                      $project_img = wp_get_attachment_url( $gallery_ids_array[0] );
                  }
              }
              // Si no se obtuvo imagen de la galería, usar la imagen destacada
              if ( empty($project_img) ) {
                  $project_img = get_the_post_thumbnail_url($project->ID, 'medium');
              }
              // Si sigue sin haber imagen, usar la imagen por defecto
              if ( empty($project_img) ) {
                  $project_img = site_url('/wp-content/uploads/default-project.jpg');
              }
              ?>
              <div class="galeria-item">
                <a href="<?php echo esc_url(get_permalink($project->ID)); ?>">
                  <div class="item-overlay">
                    <h3 class="item-title"><?php echo esc_html(get_the_title($project->ID)); ?></h3>
                  </div>
                  <img src="<?php echo esc_url($project_img); ?>" alt="<?php echo esc_attr(get_the_title($project->ID)); ?>">
                </a>
              </div>
              <?php
            }
          }
          wp_reset_postdata();
        ?>
      </div><!-- .galeria-items -->
      
    </div><!-- .galeria-proyectos-layout -->
  </div><!-- .wrapper-contenido -->
</section>

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

<script>
document.addEventListener("DOMContentLoaded", function() {
  const serviceItems = document.querySelectorAll('.servicio-item');
  const galleryImg = document.getElementById('servicio-img');
  
  // Configuramos la transición (si aún no la tienes definida en CSS)
  galleryImg.style.transition = 'opacity 0.3s ease';

  serviceItems.forEach(function(item) {
    item.addEventListener('click', function() {
      // Remover la clase "active" de todos los elementos
      serviceItems.forEach(i => i.classList.remove('active'));
      // Agregar la clase "active" al elemento clicado
      this.classList.add('active');
      
      // Obtener la nueva imagen
      const newImage = this.getAttribute('data-image');
      if (newImage) {
        // Pre-cargar la imagen nueva para evitar saltos
        const preloader = new Image();
        preloader.onload = function() {
          // Una vez cargada, cambia la imagen de la galería
          galleryImg.style.opacity = 0; // Inicia fade out
          // Esperamos a que termine el fade out (0.3s) y actualizamos la imagen
          setTimeout(function() {
            galleryImg.src = newImage;
          }, 300);
        };
        preloader.src = newImage;
        
        // Cuando la nueva imagen esté en caché, usaremos onload del elemento de la galería
        galleryImg.onload = function() {
          galleryImg.style.opacity = 1; // Fade in cuando la imagen se actualice
        };
      }
    });
  });
});

</script>
