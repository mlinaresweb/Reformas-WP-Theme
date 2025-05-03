<?php
/*
Template Name: Página de Proyectos
*/

/* --- filtro por servicio ( ?servicio=slug ) ------------------------ */
$servicios   = get_terms( [ 'taxonomy'=>'servicio', 'hide_empty'=>false ] );
$filtro_slug = isset( $_GET['servicio'] ) ? sanitize_text_field( $_GET['servicio'] ) : '';

/* --- paginación ---------------------------------------------------- */
$paged = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

/* --- base canonical ------------------------------------------------ */
$base_url = get_permalink();                      
$canonical_base = $filtro_slug
    ? add_query_arg( 'servicio', $filtro_slug, $base_url )
    : remove_query_arg( [ 'servicio' ], $base_url );

/* --- WP_Query (se usará más abajo para mostrar la galería) --------- */
$args = [
    'post_type'      => 'proyecto',
    'posts_per_page' => 11,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ( $filtro_slug ) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'servicio',
            'field'    => 'slug',
            'terms'    => $filtro_slug,
        ],
    ];
}

$query_proyectos = new WP_Query( $args );

/* --- etiquetas canonical / prev / next ----------------------------- */
add_action(
    'wp_head',
    function () use ( $canonical_base, $paged, $query_proyectos ) {

        /* canonical de la página actual */
        $canonical = ( $paged > 1 )
            ? add_query_arg( 'paged', $paged, $canonical_base )
            : $canonical_base;

        echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . PHP_EOL;

        /* prev / next cuando proceda */
        if ( $paged > 1 ) {
            $prev = ( $paged - 1 ) === 1
                ? $canonical_base
                : add_query_arg( 'paged', $paged - 1, $canonical_base );
            echo '<link rel="prev" href="' . esc_url( $prev ) . '">' . PHP_EOL;
        }

        if ( $paged < $query_proyectos->max_num_pages ) {
            $next = add_query_arg( 'paged', $paged + 1, $canonical_base );
            echo '<link rel="next" href="' . esc_url( $next ) . '">' . PHP_EOL;
        }
    },
    9  
);

get_header();
?>

<main class="proyectos-galeria">

     <!-- Sección Banner -->
  <?php echo do_shortcode('[page_banner]'); ?>

  <div class="wrapper-contenido">

  <!-- Encabezado SEO -->
  <div class="proyectos-header">
	<h2 class="page-title-seo">Nuestros Proyectos de Reformas</h2>

	<?php if ( $filtro_slug && $serv_term = get_term_by( 'slug', $filtro_slug, 'servicio' ) ) : ?>
		<p class="seo-intro">
			Explora nuestra selección de <strong>proyectos de <?php echo esc_html( $serv_term->name ); ?></strong>
			en Barcelona y alrededores. Trabajos reales que muestran la calidad de nuestros acabados,
			la elección de materiales premium y la satisfacción de cada cliente.
		</p>
	<?php else : ?>
		<p class="seo-intro">
			Conoce cómo transformamos viviendas y negocios a través de <strong>reformas integrales
			y parciales</strong> en Barcelona y alrededores. Descubre ideas, materiales y soluciones que marcan la diferencia
			en cada espacio.
		</p>
	<?php endif; ?>
</div>

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
    if($query_proyectos->have_posts()):
      $projects = $query_proyectos->posts;
      wp_reset_postdata();
      // Cuántos proyectos en esta página
      $count_this_page = count($projects);
    ?>
<!-- Galería Irregular -->
<div class="galeria-irregular">
<?php
$idx = 0;
foreach ( $projects as $p ) :
	$post_id = $p->ID;

	/* ---------- Imagen responsive ---------- */
	$img_html = '';

	// ① 1.ª imagen de la galería personalizada
	$gallery_ids = get_post_meta( $post_id, '_proyecto_galeria', true );
	if ( $gallery_ids ) {
		$first_id  = (int) strtok( $gallery_ids, ',' );
		$img_html  = wp_get_attachment_image(
			$first_id,
			'medium_large',          // genera srcset
			false,
			[ 'loading' => 'lazy', 'alt' => get_the_title( $post_id ) ]
		);
	}

	// ② destacada
	if ( ! $img_html && has_post_thumbnail( $post_id ) ) {
		$img_html = get_the_post_thumbnail(
			$post_id,
			'medium_large',
			[ 'loading' => 'lazy', 'alt' => get_the_title( $post_id ) ]
		);
	}

	// ③ fallback
	if ( ! $img_html ) {
		$img_html = '<img src="' . esc_url( site_url( '/wp-content/uploads/default-project.jpg' ) ) . '"
		                  alt="Proyecto de reforma" loading="lazy">';
	}

	/* ---------- clases large / wide ---------- */
	$cls = '';
	if ( $count_this_page >= 11 ) {
		if     ( $idx % 7 === 0 ) $cls = ' grid-item-large';
		elseif ( $idx % 5 === 0 ) $cls = ' grid-item-wide';
	}
	?>
	<div class="grid-item<?php echo esc_attr( $cls ); ?>">
		<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
			<?php echo $img_html; ?>
			<div class="item-overlay">
				<h3 class="item-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
			</div>
		</a>
	</div>
	<?php
	$idx++;
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

  
 <!-- Sección SEO antes del formulario -->
 <?php echo do_shortcode('[seo_cta]'); ?>


    <!-- Sección: Contáctanos -->
<?php echo do_shortcode('[contact_section]'); ?>

</main>

<?php get_footer(); ?>
