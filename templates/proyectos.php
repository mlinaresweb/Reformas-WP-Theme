<?php
/*
Template Name: Página de Proyectos con Query + Paginación
*/
get_header(); ?>

<main class="proyectos-galeria">
  <div class="wrapper-contenido">

    <?php
    // Obtener los términos de la taxonomía “servicio” para crear los filtros
    $servicios = get_terms(array(
      'taxonomy'   => 'servicio',
      'hide_empty' => false,
    ));

    // Detectar si hay un filtro en la query (ej: ?servicio=albanileria)
    $filtro_slug = isset($_GET['servicio']) ? sanitize_text_field($_GET['servicio']) : '';

    // Página actual (para la paginación)
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    ?>

    <!-- Barra de Filtros -->
    <div class="filtros-proyectos">
      <?php
        // Enlace para “Todos” (sin param ?servicio=)
        $base_url = get_permalink(); // la URL de esta página
        $link_todos = remove_query_arg('servicio', $base_url);
        $link_todos = remove_query_arg('paged', $link_todos);
      ?>
      <a 
        href="<?php echo esc_url($link_todos); ?>" 
        class="filtro <?php echo ($filtro_slug === '') ? 'active' : ''; ?>"
      >
        Todos los Proyectos
      </a>

      <?php if(!empty($servicios) && !is_wp_error($servicios)) :
        foreach($servicios as $serv):
          // Construir el enlace con ?servicio=slug
          $service_link = add_query_arg('servicio', $serv->slug, $base_url);
          $service_link = remove_query_arg('paged', $service_link);
      ?>
        <a 
          href="<?php echo esc_url($service_link); ?>" 
          class="filtro <?php echo ($filtro_slug === $serv->slug) ? 'active' : ''; ?>"
        >
          <?php echo esc_html($serv->name); ?>
        </a>
      <?php endforeach; endif; ?>
    </div><!-- .filtros-proyectos -->

    <?php
    // Construir WP_Query con paginación + filtro
    $args = array(
      'post_type'      => 'proyecto',
      'posts_per_page' => 11,
      'paged'          => $paged,
      'orderby'        => 'date',
      'order'          => 'DESC',
    );

    if($filtro_slug !== ''){
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'servicio',
          'field'    => 'slug',
          'terms'    => $filtro_slug,
        )
      );
    }
    $query_proyectos = new WP_Query($args);

    if($query_proyectos->have_posts()):
      $projects = $query_proyectos->posts;
      wp_reset_postdata();
      // Cuántos proyectos en esta página
      $count_this_page = count($projects);
    ?>
      <!-- Galería Irregular -->
      <div class="galeria-irregular">
        <?php
        $index = 0;
        foreach($projects as $p):
          $project_id = $p->ID;

          // Obtener slugs de los servicios (solo para data-service si quisieras filtrar local, pero aquí no hace falta)
          $terms = wp_get_post_terms($project_id, 'servicio', array('fields' => 'slugs'));
          $data_services = (!empty($terms)) ? implode(' ', $terms) : '';

          // Obtener imagen (galería -> destacada -> default)
          $gallery_ids = get_post_meta($project_id, '_proyecto_galeria', true);
          $project_img = '';
          if(!empty($gallery_ids)){
            $arr = array_map('trim', explode(',', $gallery_ids));
            if(!empty($arr[0])){
              $project_img = wp_get_attachment_url($arr[0]);
            }
          }
          if(empty($project_img)){
            $project_img = get_the_post_thumbnail_url($project_id, 'medium');
          }
          if(empty($project_img)){
            $project_img = site_url('/wp-content/uploads/default-project.jpg');
          }

          // Clases para “irregularidad”. Solo si hay ~8 o más en esta página
          $clase_size = '';
          if($count_this_page >= 9) {
            if($index % 7 == 0) $clase_size = ' grid-item-large';
            elseif($index % 5 == 0) $clase_size = ' grid-item-wide';
          }
        ?>
          <div class="grid-item<?php echo esc_attr($clase_size); ?>" 
               data-service="<?php echo esc_attr($data_services); ?>">
            <a href="<?php echo esc_url( get_permalink($project_id) ); ?>">
              <img 
                src="<?php echo esc_url($project_img); ?>" 
                alt="<?php echo esc_attr(get_the_title($project_id)); ?>"
              >
              <div class="item-overlay">
                <h3 class="item-title">
                  <?php echo esc_html( get_the_title($project_id) ); ?>
                </h3>
              </div>
            </a>
          </div>
        <?php
          $index++;
        endforeach;
        ?>
      </div><!-- .galeria-irregular -->

      <!-- Paginación (links) -->
      <div class="paginacion-proyectos">
        <?php
          // Crear links. Si $filtro_slug, debemos reinyectar “servicio=slug” en cada enlace
          $paginacion = paginate_links(array(
            'total'   => $query_proyectos->max_num_pages,
            'current' => $paged,
            'format'  => '?paged=%#%',
            'show_all'=> false,
            'type'    => 'list',
            'prev_text' => '« Anterior',
            'next_text' => 'Siguiente »'
          ));

          // Insertar param ?servicio=xxx si hace falta
          if($filtro_slug !== ''){
            $paginacion = preg_replace_callback('/href=[\'"]([^\'"]+)[\'"]/', function($matches) use($filtro_slug){
              $url = $matches[1];
              $url = add_query_arg('servicio', $filtro_slug, $url);
              return 'href="'.$url.'"';
            }, $paginacion);
          }
          echo $paginacion;
        ?>
      </div>

    <?php else: ?>
      <p>No se han encontrado proyectos para este filtro/página.</p>
    <?php endif; ?>

  </div><!-- .wrapper-contenido -->
</main>

<?php get_footer(); ?>
