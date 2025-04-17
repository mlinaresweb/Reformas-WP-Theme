<?php
/**
 * Componente: Proyectos del Servicio
 * Muestra 3 proyectos asignados al servicio actual en distribución:
 * - Columna izquierda: 1 proyecto (ocupa el 100% de esa columna).
 * - Columna derecha: 2 proyectos (uno arriba y uno abajo).
 * 
 * Además, el título mostrará "Proyectos de [Servicio]" y al final habrá un botón "Más proyectos"
 * que redirige a la página personalizada del servicio.
 */
function render_service_projects_component() {
  // Obtener el término (servicio) actual.
  $term = get_queried_object();
  
  // Configurar la consulta: obtener 3 proyectos asociados a este servicio.
  $args = array(
      'post_type'      => 'proyecto',
      'posts_per_page' => 3,
      'tax_query'      => array(
          array(
              'taxonomy' => 'servicio',
              'field'    => 'term_id',
              'terms'    => $term->term_id,
          ),
      ),
      'orderby'        => 'date',
      'order'          => 'DESC',
  );
  $service_projects = new WP_Query($args);
  
  if ($service_projects->have_posts()) {
    // Recopilar los proyectos.
    $projects = $service_projects->posts;
    wp_reset_postdata();
    
    // Aseguramos que tengamos al menos 3 proyectos.
    if ( count($projects) < 3 ) {
        // Opcional: se podría completar con otros proyectos si hace falta.
    }
    
    // Asignar posiciones:
    $project_left = $projects[0]; // Proyecto grande en columna izquierda.
    $project_right_top = isset($projects[1]) ? $projects[1] : null; // Proyecto superior en columna derecha.
    $project_right_bottom = isset($projects[2]) ? $projects[2] : null; // Proyecto inferior en columna derecha.
    
    // Función auxiliar para obtener la imagen del proyecto.
    function get_project_image_url( $project_id ) {
        $gallery_ids = get_post_meta( $project_id, '_proyecto_galeria', true );
        $project_img = '';
        if ( ! empty( $gallery_ids ) ) {
            $gallery_ids_array = array_map('trim', explode(',', $gallery_ids));
            if ( ! empty( $gallery_ids_array[0] ) ) {
                $project_img = wp_get_attachment_url( $gallery_ids_array[0] );
            }
        }
        if ( empty($project_img) ) {
            $project_img = get_the_post_thumbnail_url( $project_id, 'medium' );
        }
        if ( empty($project_img) ) {
            $project_img = site_url('/wp-content/uploads/default-project.jpg');
        }
        return $project_img;
    }
    
    // Preparar la URL para "Más proyectos"
    $slug = $term->slug;
    // Si el slug contiene "reformas-", eliminarlo para obtener por ejemplo "albanileria".
    if ( strpos($slug, 'reformas-') !== false ) {
        $slug = str_replace('reformas-', '', $slug);
    }
    $more_projects_url = site_url('/proyectos-' . $slug . '/');
    
    ob_start();
    ?>
    <section class="service-projects">
      <div class="wrapper-contenido">
        <h2 class="section-title">Proyectos de <?php echo esc_html( $term->name ); ?></h2>
        <div class="projects-grid">
          <!-- Proyecto grande en la columna izquierda -->
          <div class="project-item project-left">
            <a href="<?php echo esc_url(get_permalink($project_left->ID)); ?>">
              <div class="project-overlay">
                <h3 class="project-title"><?php echo esc_html(get_the_title($project_left->ID)); ?></h3>
              </div>
              <img src="<?php echo esc_url(get_project_image_url($project_left->ID)); ?>" alt="<?php echo esc_attr(get_the_title($project_left->ID)); ?>">
            </a>
          </div>
          
          <!-- Proyecto superior en la columna derecha -->
          <?php if($project_right_top): ?>
          <div class="project-item project-right-top">
            <a href="<?php echo esc_url(get_permalink($project_right_top->ID)); ?>">
              <div class="project-overlay">
                <h3 class="project-title"><?php echo esc_html(get_the_title($project_right_top->ID)); ?></h3>
              </div>
              <img src="<?php echo esc_url(get_project_image_url($project_right_top->ID)); ?>" alt="<?php echo esc_attr(get_the_title($project_right_top->ID)); ?>">
            </a>
          </div>
          <?php endif; ?>
          
          <!-- Proyecto inferior en la columna derecha -->
          <?php if($project_right_bottom): ?>
          <div class="project-item project-right-bottom">
            <a href="<?php echo esc_url(get_permalink($project_right_bottom->ID)); ?>">
              <div class="project-overlay">
                <h3 class="project-title"><?php echo esc_html(get_the_title($project_right_bottom->ID)); ?></h3>
              </div>
              <img src="<?php echo esc_url(get_project_image_url($project_right_bottom->ID)); ?>" alt="<?php echo esc_attr(get_the_title($project_right_bottom->ID)); ?>">
            </a>
          </div>
          <?php endif; ?>
        </div><!-- .projects-grid -->
        <!-- Botón de "Más proyectos" -->
        <div class="more-projects">
          <a href="<?php echo esc_url($more_projects_url); ?>" class="btn-more-projects">Más proyectos</a>
        </div>
      </div><!-- .wrapper-contenido -->
    </section>
    <?php
    return ob_get_clean();
  }
  return '';
}
add_shortcode('service_projects', 'render_service_projects_component');
?>
