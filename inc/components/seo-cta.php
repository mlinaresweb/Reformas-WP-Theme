<?php
/**
 * Componente: SEO + CTA (versión mejorada)
 * Uso   : [seo_cta]  o  [seo_cta title="…" text="…" btn="…"]
 */
function reformas_render_seo_cta( $atts = [] ){

	$atts = shortcode_atts( [
		'title'   => 'Reformas low‑cost rápidas y de calidad en Barcelona',  
		'btn'     => 'Pide presupuesto',
		'btn_url' => site_url( '/contacto/' ),
	], $atts, 'seo_cta' );

	ob_start(); ?>

	<section class="seo-text-section" itemscope itemtype="https://schema.org/Service">
		<meta itemprop="serviceType" content="<?php echo esc_attr( $atts['title'] ); ?>">

		<div class="wrapper-contenido">

			<h2 class="sr-only"><?php echo esc_html( $atts['title'] ); ?></h2>

			<p class="seo-text">
				Reformas <strong>low‑cost</strong> en Barcelona y alrededores con acabados profesionales y tiempos de entrega ajustados.
			</p>
			<p class="seo-text">
				¿Quieres dar un nuevo aire a tu vivienda o negocio? <span class="span-marron">Nuestro equipo resuelve tu reforma rápido y bien,</span> manteniendo un presupuesto cerrado sin sorpresas.
			</p>
			<p class="seo-text seo-text-last" itemprop="description">
				Trabajamos albañilería, carpintería, fontanería, electricidad y pintura. Materiales de primera, operarios cualificados y trato cercano para conseguir la mejor relación calidad‑precio del mercado.
			</p>

			<a href="<?php echo esc_url( $atts['btn_url'] ); ?>"
			   class="btn-cta-presupuesto"
			   aria-label="<?php echo esc_attr( $atts['btn'] ); ?>"
			   itemprop="potentialAction"
			   itemscope itemtype="https://schema.org/ContactAction">
				<meta itemprop="target" content="<?php echo esc_url( $atts['btn_url'] ); ?>">
				<span itemprop="name"><?php echo esc_html( $atts['btn'] ); ?></span>
			</a>
		</div>
	</section>

	<?php
	/* JSON‑LD adicional */
	echo '<script type="application/ld+json">' .
		wp_json_encode( [
			'@context'         => 'https://schema.org',
			'@type'            => 'Service',
			'name'             => $atts['title'],
			'description'      => 'Reformas low‑cost rápidas y de calidad en Barcelona y alrededores.',
			'areaServed'       => 'Barcelona',
			'provider'         => [
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url(),
			],
			'potentialAction'  => [
				'@type'  => 'ContactAction',
				'name'   => $atts['btn'],
				'target' => esc_url( $atts['btn_url'] ),
			],
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
	'</script>';

	return ob_get_clean();
}
add_shortcode( 'seo_cta', 'reformas_render_seo_cta' );
