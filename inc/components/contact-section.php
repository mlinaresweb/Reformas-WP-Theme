<?php
/**
 * Componente: Sección de Contacto
 * Shortcode  : [contact_section image_url="" heading="" form_shortcode=""]
 */
function render_contact_section( $atts ) {

	$atts = shortcode_atts( [
		'image_url'      => site_url( '/wp-content/uploads/reforma-contacto.webp' ),
		'heading'        => 'Pide Tu Presupuesto',
		'form_shortcode' => '[custom_contact_form]',
	], $atts, 'contact_section' );

	/* ====== preparación imagen ====== */
	$img_id  = attachment_url_to_postid( $atts['image_url'] );
	$img_alt = $atts['heading'] . ' – Reformas';

	ob_start(); ?>
	<section class="seccion-contacto" aria-labelledby="contacto-heading" role="region">
		<div class="wrapper-contenido">
			<div class="contact-layout">

				<!-- Columna Izquierda: Imagen (misma clase / fondo) -->
				<div class="contact-image"
					 style="background-image:url('<?php echo esc_url( $atts['image_url'] ); ?>');">
					<!-- 1px‑gif hack: aporta alt‑text y lazy sin afectar al diseño -->
					<img src="<?php echo esc_url( $atts['image_url'] ); ?>"
						 alt="<?php echo esc_attr( $img_alt ); ?>"
						 loading="lazy" decoding="async"
						 width="1" height="1" style="opacity:0;position:absolute;" />
				</div>

				<!-- Columna Derecha: Formulario -->
				<div class="contact-form">
					<h2 id="contacto-heading"><?php echo esc_html( $atts['heading'] ); ?></h2>
					<?php echo do_shortcode( $atts['form_shortcode'] ); ?>
				</div>

			</div><!-- .contact-layout -->
		</div><!-- .wrapper-contenido -->
	</section>
<?php
	/* ---------- JSON‑LD ContactPage *una sola vez* ---------- */
	if ( ! did_action( 'render_contact_section_schema' ) ) {
		do_action( 'render_contact_section_schema' ); // evita duplicados
		$schema = [
			'@context' => 'https://schema.org',
			'@type'    => 'ContactPage',
			'name'     => get_bloginfo( 'name' ) . ' – Contacto',
			'url'      => home_url( '/contacto/' ),
			'contactPoint' => [
				'@type'       => 'ContactPoint',
				'telephone'   => '+34 666 666 666',
				'contactType' => 'customer service',
				'areaServed'  => 'ES',
				'availableLanguage' => [ 'Spanish' ],
			],
		];
		echo '<script type="application/ld+json">' .
		     wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
		     '</script>';
	}

	return ob_get_clean();
}
add_shortcode( 'contact_section', 'render_contact_section' );
