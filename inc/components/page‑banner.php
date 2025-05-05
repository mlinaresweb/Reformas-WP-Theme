<?php
/* ------------------------------------------------------------------
 * Shortcode  ➜  [page_banner]  |  [page_banner title="Mi Título"]
 * Incluye:
 *  – imagen responsive (tamaño banner-large 1920×640 recortado)
 *  – aria‑label para accesibilidad
 *  – JSON‑LD WebPage con imagen principal (solo la 1.ª vez)
 * -----------------------------------------------------------------*/
add_action( 'after_setup_theme', function () {
	if ( ! has_image_size( 'banner-large' ) ) {
		add_image_size( 'banner-large', 1920, 640, true );
	}
} );

function reformas_render_page_banner( $atts = [] ) {

	$atts = shortcode_atts( [
		'title' => '',
	], $atts );

	/* ─ Imagen (campo ACF) ─ */
	$field = get_field( 'banner_image' );
	$img_id = $field['ID'] ?? 0;
	$src    = $img_id
		? wp_get_attachment_image_url( $img_id, 'banner-large' )
		: site_url( '/wp-content/uploads/carpinteria.webp' );
	$alt    = $img_id
		? get_post_meta( $img_id, '_wp_attachment_image_alt', true )
		: ( $atts['title'] ?: get_the_title() );

	/* ─ Título ─ */
	if ( ! empty( $atts['title'] ) ) {
		$title = $atts['title'];
	} elseif ( is_home() ) {
		$title = 'Blog';
	} else {
		$title = get_the_title();
	}

	/* ─ Salida ─ */
	ob_start(); ?>
	<section class="proyecto-banner"
	         role="img"
	         aria-label="<?php echo esc_attr( $alt ); ?>"
	         style="background-image:url('<?php echo esc_url( $src ); ?>')">
		<div class="banner-content">
			<h1><?php echo esc_html( $title ); ?></h1>
		</div>
	</section>
	<?php
	/* ---------- JSON‑LD (una sola vez por página) ---------- */
	if ( ! did_action( 'reformas_banner_schema' ) ) {
		do_action( 'reformas_banner_schema' ); 
		$schema = [
			'@context'    => 'https://schema.org',
			'@type'       => 'WebPage',
			'name'        => $title,
			'description' => get_bloginfo( 'description' ),
			'primaryImageOfPage' => [
				'@type' => 'ImageObject',
				'url'   => $src,
				'name'  => $alt,
			],
			'url' => get_permalink(),
		];
		echo '<script type="application/ld+json">'
		     . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		     . '</script>';
	}
	return ob_get_clean();
}
add_shortcode( 'page_banner', 'reformas_render_page_banner' );
