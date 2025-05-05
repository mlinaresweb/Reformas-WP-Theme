<?php
/**
 * Componente: CTA Presupuesto
 * Shortcode  : [cta_presupuesto text="" page="contacto"]
 */
function render_cta_presupuesto( $atts = [] ) {

	$atts = shortcode_atts( [
		'text'  => '¿Quieres transformar tu hogar con reformas integrales en Barcelona? Descubre nuestras soluciones personalizadas y de alta calidad.',
		'page'  => 'contacto',          
	], $atts, 'cta_presupuesto' );

	$contact_id  = url_to_postid( home_url( '/' . $atts['page'] . '/' ) );
	$contact_url = $contact_id ? get_permalink( $contact_id ) : home_url( '/contacto/' );

	ob_start(); ?>
	<section class="cta-presupuesto" aria-labelledby="cta-presupuesto-heading" role="region">
		<div class="wrapper-contenido">
			<h2 id="cta-presupuesto-heading" class="sr-only">
				Solicitar presupuesto de reformas
			</h2>

			<p class="cta-text"><?php echo esc_html( $atts['text'] ); ?></p>

			<a class="btn-cta-presupuesto"
			   href="<?php echo esc_url( $contact_url ); ?>"
			   rel="nofollow">
				Pide Presupuesto
			</a>
		</div>
	</section>
<?php
	/* ---------- JSON‑LD ContactAction (una sola vez) ---------- */
	if ( ! did_action( 'render_cta_presupuesto_schema' ) ) {
		do_action( 'render_cta_presupuesto_schema' );
		$schema = [
			'@context' => 'https://schema.org',
			'@type'    => 'ContactAction',
			'name'     => 'Pedir presupuesto de reformas',
			'target'   => $contact_url,
			'potentialAction' => [
				'@type'  => 'CommunicateAction',
				'target' => $contact_url,
			],
			'publisher' => [
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url(),
			],
		];
		echo '<script type="application/ld+json">' .
		     wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
		     '</script>';
	}

	return ob_get_clean();
}
add_shortcode( 'cta_presupuesto', 'render_cta_presupuesto' );
