<?php
/*
Template Name: Proyectos por Servicio
*/
get_header(); ?>

<main class="proyectos-galeria">
  <div class="wrapper-contenido">

    <?php
    // 1) Obtener slug de la página actual: "proyectos-albanileria", etc.
    $page_slug = get_queried_object()->post_name; // p.ej. proyectos-albanileria :contentReference[oaicite:1]{index=1}

    // 2) Convertir a slug de taxonomía: "reformas-albanileria"
    $taxonomy_slug = 'reformas-' . str_replace('proyectos-', '', $page_slug);

    // 3) Obtener objeto término para mostrar nombre
    $term = get_term_by('slug', $taxonomy_slug, 'servicio');

    // 4) Detectar paged para paginación
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    ?>

    <!-- Título dinámico -->
    <header class="proyectos-header">
      <h1>
        <?php 
          if ( $term ) {
            echo 'Proyectos de ' . esc_html( $term->name );
          } else {
            echo 'Proyectos';
          }
        ?>
      </h1>
    </header>

    <?php
    // 5) WP_Query filtrada por taxonomía y paginada
    $args = array(
      'post_type'      => 'proyecto',
      'posts_per_page' => 12,
      'paged'          => $paged,
      'orderby'        => 'date',
      'order'          => 'DESC',
    );
    if ( $term ) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'servicio',
          'field'    => 'slug',
          'terms'    => $taxonomy_slug,
        ),
      );
    }

    $query = new WP_Query($args);

    if ( $query->have_posts() ):
      $count_page = $query->post_count;
      ?>

      <!-- Galería Irregular -->
      <div class="galeria-irregular">
        <?php
        $i = 0;
        while ( $query->have_posts() ): $query->the_post();
          $id   = get_the_ID();
          // Imagen (galería → destacada → default)
          $gal_ids = get_post_meta($id, '_proyecto_galeria', true);
          if ( $gal_ids ) {
            $ids = array_map('trim', explode(',', $gal_ids));
            $img = wp_get_attachment_url($ids[0]);
          }
          if ( empty($img) ) {
            $img = get_the_post_thumbnail_url($id, 'medium');
          }
          if ( empty($img) ) {
            $img = site_url('/wp-content/uploads/default-project.jpg');
          }
          // Asignar clases “large”/“wide” si hay suficientes items
          $cls = '';
          if ( $count_page >= 9 ) {
            if( $i % 7 === 0 )      $cls = ' grid-item-large';
            elseif( $i % 5 === 0 )  $cls = ' grid-item-wide';
          }
        ?>
          <div class="grid-item<?php echo esc_attr($cls); ?>">
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url($img); ?>"
                   alt="<?php echo esc_attr(get_the_title()); ?>">
              <div class="item-overlay">
                <h3 class="item-title"><?php the_title(); ?></h3>
              </div>
            </a>
          </div>
        <?php
          $i++;
        endwhile;
        wp_reset_postdata();
        ?>
      </div><!-- .galeria-irregular -->

      <!-- Paginación -->
      <div class="paginacion-proyectos">
        <?php
        $pages = paginate_links(array(
          'total'   => $query->max_num_pages,
          'current' => $paged,
          'format'  => '?paged=%#%',
          'type'    => 'list',
          'prev_text' => '« Anterior',
          'next_text' => 'Siguiente »',
        ));
        echo $pages;
        ?>
      </div>

    <?php else: ?>
      <p>No hay proyectos disponibles para este servicio.</p>
    <?php endif; ?>

  </div><!-- .wrapper-contenido -->
</main>

<?php get_footer(); ?>
