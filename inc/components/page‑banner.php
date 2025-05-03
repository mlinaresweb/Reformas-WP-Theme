<?php
/* ------------------------------------------------------------------
 *  Shortcode  ➜  [page_banner]  |  [page_banner title="Mi Título"]
 * -----------------------------------------------------------------*/
function reformas_render_page_banner( $atts = [] ) {

	$atts = shortcode_atts( [
		'title' => '',                // título opcional
	], $atts );

	/* ─ Imagen (campo ACF) ───────────────────────────────────────── */
	$banner_field = get_field( 'banner_image' );
	$banner_url   = ( $banner_field && isset( $banner_field['url'] ) )
		          ? $banner_field['url']
		          : site_url( '/wp-content/uploads/carpinteria.webp' );

	/* ─ Título a mostrar ─────────────────────────────────────────── */
	if ( ! empty( $atts['title'] ) ) {
		$title = $atts['title'];                    
	} elseif ( is_home() ) {
		$title = 'Blog';                            
	} else {
		$title = get_the_title();                    
	}

	ob_start(); ?>
	<section class="proyecto-banner"
	         style="background-image:url('<?php echo esc_url( $banner_url ); ?>')">
	  <div class="banner-content">
	    <h1><?php echo esc_html( $title ); ?></h1>
	  </div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'page_banner', 'reformas_render_page_banner' );
