<?php

/*
Template Name: Proyectos por Servicio
*/

add_action( 'wp_head', function(){
  if ( is_page_template( 'proyectos-por-servicio.php' ) && get_query_var('paged') > 1 ) {
       echo '<link rel="canonical" href="'. esc_url( get_pagenum_link(1) ) .'">';
  }
});

get_header(); 

?>

<main class="proyectos-galeria">

  <!-- Sección Banner -->
  <?php echo do_shortcode('[page_banner]'); ?>

  <div class="wrapper-contenido">

    <?php

    $page_slug = get_queried_object()->post_name; 

    $taxonomy_slug = 'reformas-' . str_replace('proyectos-', '', $page_slug);

    $term = get_term_by('slug', $taxonomy_slug, 'servicio');

    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

    ?>


    <header class="proyectos-header">
  <h2>
    <?php 
      if ( $term ) {
        echo 'Proyectos de ' . esc_html( $term->name );
      } else {
        echo 'Proyectos';
      }
    ?>
  </h2>

  <?php if ( $term ): ?>
    <div class="proyectos-seo-text">
    <p>
  Estos <strong>proyectos de <?php echo esc_html( $term->name ); ?></strong> muestran
  cómo renovamos viviendas y locales en Barcelona combinando materiales de primer nivel
  y mano de obra especializada. Inspírate con nuestras últimas obras y <strong>solicita tu
  presupuesto</strong> sin compromiso.
</p>
    </div>
  <?php endif; ?>
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
while ( $query->have_posts() ) : $query->the_post();
	$post_id = get_the_ID();

	/* ---------- Obtener imagen ---------- */
	$img_html = '';                          

	// 1) 1.ª foto de la galería personalizada
	$gal_ids = get_post_meta( $post_id, '_proyecto_galeria', true );
	if ( $gal_ids ) {
		$ids_arr  = array_map( 'trim', explode( ',', $gal_ids ) );
		$img_id   = (int) $ids_arr[0];
		$img_html = wp_get_attachment_image(
			$img_id,
			'medium_large',                   
			false,
			[ 'loading' => 'lazy', 'alt' => get_the_title() ]
		);
	}

	// 2) Destacada si no había galería
	if ( ! $img_html && has_post_thumbnail() ) {
		$img_html = get_the_post_thumbnail(
			$post_id,
			'medium_large',
			[ 'loading' => 'lazy', 'alt' => get_the_title() ]
		);
	}

	// 3) Fallback
	if ( ! $img_html ) {
		$img_html = '<img src="' . esc_url( site_url( '/wp-content/uploads/default-project.jpg' ) ) . '" 
		                 alt="Proyecto de reforma"
		                 loading="lazy">';
	}

	/* ---------- Clases grande / ancha ---------- */
	$cls = '';
	if ( $count_page >= 11 ) {
		if   ( $i % 7 === 0 ) $cls = ' grid-item-large';
		elseif ( $i % 5 === 0 ) $cls = ' grid-item-wide';
	}
	?>
	<div class="grid-item<?php echo esc_attr( $cls ); ?>">
		<a href="<?php the_permalink(); ?>">
			<?php echo $img_html; ?>
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

 <!-- Sección SEO antes del formulario -->
 <?php echo do_shortcode('[seo_cta]'); ?>

<!-- Sección: Contáctanos -->
<?php echo do_shortcode('[contact_section]'); ?>

</main>

<?php get_footer(); ?>
