<?php
/**
 * Shortcode  ➜  [service_projects]
 * Muestra 3 proyectos destacados del servicio / taxonomía actual
 * con mejoras SEO (títulos ocultos, JSON‑LD, lazy‑loading…)
 */
function render_service_projects_component() {

	/* ───────────────────────── TÉRMINO ACTUAL ───────────────────────── */
	$term = get_queried_object();
	if ( ! $term || empty( $term->term_id ) ) { return ''; }

	/* ─────────────────────── QUERY: 3 PROYECTOS ─────────────────────── */
	$q = new WP_Query( [
		'post_type'      => 'proyecto',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => [
			[
				'taxonomy' => 'servicio',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			],
		],
	] );
	if ( ! $q->have_posts() ) { return ''; }

	$projects = $q->posts;
	wp_reset_postdata();

	/* ─────────────────────── IMAGEN DE CADA PROY ────────────────────── */
	$img_url = function ( $id ) {
		// 1) primera imagen de la galería
		$ids = get_post_meta( $id, '_proyecto_galeria', true );
		if ( $ids ) {
			$id_arr = array_filter( array_map( 'trim', explode( ',', $ids ) ) );
			if ( ! empty( $id_arr[0] ) ) {
				if ( $url = wp_get_attachment_image_url( $id_arr[0], 'large' ) ) { return $url; }
			}
		}
		// 2) imagen destacada
		if ( $thumb = get_the_post_thumbnail_url( $id, 'large' ) ) { return $thumb; }
		// 3) fallback
		return site_url( '/wp-content/uploads/default-project.jpg' );
	};

	/* ─────────── Asignamos posiciones (1 izq, 2 dcha) ─────────── */
	$left             = $projects[0];
	$right_top        = $projects[1] ?? null;
	$right_bottom     = $projects[2] ?? null;

	/* ───────────── URL “más proyectos” del servicio ───────────── */
	$slug_sin_prefijo = str_replace( 'reformas-', '', $term->slug );
	$more_url         = site_url( '/proyectos-' . $slug_sin_prefijo . '/' );

	/* ─────────────────── MARKUP del componente ─────────────────── */
	ob_start(); ?>
	<section class="service-projects" aria-labelledby="tit-proy-serv">
		<div class="wrapper-contenido">

			<h2 id="tit-proy-serv" class="section-title">
				Proyectos de <?php echo esc_html( $term->name ); ?>
			</h2>

			<div class="projects-grid">

				<!-- IZQUIERDA (grande) -->
				<div class="project-item project-left">
					<a href="<?php echo esc_url( get_permalink( $left ) ); ?>"
					   aria-label="<?php echo esc_attr( get_the_title( $left ) ); ?>">
						<div class="project-overlay">
							<h3 class="project-title"><?php echo esc_html( get_the_title( $left ) ); ?></h3>
						</div>
						<img src="<?php echo esc_url( $img_url( $left->ID ) ); ?>"
							 alt="<?php echo esc_attr( get_the_title( $left ) ); ?>"
							 loading="lazy" width="640" height="360">
					</a>
				</div>

				<?php if ( $right_top ) : ?>
				<div class="project-item project-right-top">
					<a href="<?php echo esc_url( get_permalink( $right_top ) ); ?>"
					   aria-label="<?php echo esc_attr( get_the_title( $right_top ) ); ?>">
						<div class="project-overlay">
							<h3 class="project-title"><?php echo esc_html( get_the_title( $right_top ) ); ?></h3>
						</div>
						<img src="<?php echo esc_url( $img_url( $right_top->ID ) ); ?>"
							 alt="<?php echo esc_attr( get_the_title( $right_top ) ); ?>"
							 loading="lazy" width="640" height="360">
					</a>
				</div>
				<?php endif; ?>

				<?php if ( $right_bottom ) : ?>
				<div class="project-item project-right-bottom">
					<a href="<?php echo esc_url( get_permalink( $right_bottom ) ); ?>"
					   aria-label="<?php echo esc_attr( get_the_title( $right_bottom ) ); ?>">
						<div class="project-overlay">
							<h3 class="project-title"><?php echo esc_html( get_the_title( $right_bottom ) ); ?></h3>
						</div>
						<img src="<?php echo esc_url( $img_url( $right_bottom->ID ) ); ?>"
							 alt="<?php echo esc_attr( get_the_title( $right_bottom ) ); ?>"
							 loading="lazy" width="640" height="360">
					</a>
				</div>
				<?php endif; ?>

			</div><!-- .projects-grid -->

			<div class="more-projects">
				<a href="<?php echo esc_url( $more_url ); ?>"
				   class="btn-more-projects"
				   rel="follow"
				   aria-label="Ver más proyectos de <?php echo esc_attr( $term->name ); ?>">
					Más proyectos
				</a>
			</div>
		</div>

		<?php
		/* ========== JSON‑LD ItemList (SEO) ========== */
		$item_list = [];
		$pos       = 1;
		foreach ( [ $left, $right_top, $right_bottom ] as $p ) {
			if ( ! $p ) continue;
			$item_list[] = [
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => get_the_title( $p ),
				'url'      => get_permalink( $p ),
			];
		}
		echo '<script type="application/ld+json">' .
			wp_json_encode( [
				'@context'        => 'https://schema.org',
				'@type'           => 'ItemList',
				'name'            => 'Proyectos de ' . $term->name,
				'itemListElement' => $item_list,
			], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
		'</script>'; ?>
	</section>
	<?php

	return ob_get_clean();
}
add_shortcode( 'service_projects', 'render_service_projects_component' );
