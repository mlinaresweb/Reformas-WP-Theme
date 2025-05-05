<?php
/*
Template Name: Página de Contacto
*/

/* ────── SEO DINÁMICO ────────────────────────────────────────── */
// 1. <title>
add_filter( 'document_title_parts', function ( $t ){
	if ( is_page_template( 'templates/contacto.php' ) ) {
		$t['title'] = 'Contacto | Reformas integrales low‑cost en Barcelona';
	}
	return $t;
} );

// 2. meta‑description + canonical + JSON‑LD
add_action( 'wp_head', function (){
	if ( ! is_page_template( 'templates/contacto.php' ) ) return;

	$desc = 'Escríbenos por email, WhatsApp o redes sociales y recibe tu presupuesto de reforma en 24 h. Atendemos Barcelona y alrededores.'; // 155 car.

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";

	// JSON‑LD ContactPage + Organization + ContactPoint
	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => 'ContactPage',
		'name'     => 'Página de contacto',
		'description' => $desc,
		'url'      => get_permalink(),
		'mainEntity' => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url(),
			'logo'  => get_theme_file_uri( '/screenshot.png' ),
			'contactPoint' => [
				'@type'       => 'ContactPoint',
				'telephone'   => '+34‑666‑666‑666',
				'contactType' => 'customer service',
				'areaServed'  => 'ES',
				'availableLanguage' => [ 'es', 'ca' ]
			]
		]
	];
	echo '<script type="application/ld+json">' .
	      wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
	     '</script>';
}, 9 );
/* ───────────────────────────────────────────────────────────── */

get_header(); ?>

<main class="contact-page">

	<?php echo do_shortcode( '[page_banner title="Contacto"]' ); ?>

	<div class="wrapper-contenido contact-layout">

		<!-- Datos de contacto -->
		<aside class="contact-info" aria-labelledby="contacto-datos">
			<h2 id="contacto-datos">Contáctanos</h2>

			<ul class="contact-list" itemscope itemtype="https://schema.org/Organization">
				<li class="contact-item" itemprop="email">
					<img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/correo.png'); ?>" width="40" height="40" alt="">
					<span class="contact-text">
						<strong>Email:</strong><br>
						<a href="mailto:info@tudominio.com">info@tudominio.com</a>
					</span>
				</li>

				<li class="contact-item" itemprop="telephone">
					<img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/whatsapp.png'); ?>" width="40" height="40" alt="">
					<span class="contact-text">
						<strong>WhatsApp:</strong><br>
						<a href="https://wa.me/34666666666?text=Hola%20quisiera%20más%20info" target="_blank" rel="noopener">
							+34 666 666 666
						</a>
					</span>
				</li>

				<li class="contact-item">
					<img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/facebook.png'); ?>" width="40" height="40" alt="">
					<span class="contact-text">
						<strong>Facebook:</strong><br>
						<a href="https://facebook.com/tuempresa" target="_blank" rel="noopener">
							facebook.com/tuempresa
						</a>
					</span>
				</li>

				<li class="contact-item">
					<img class="contact-icon" src="<?php echo site_url('/wp-content/uploads/instagram.png'); ?>" width="40" height="40" alt="">
					<span class="contact-text">
						<strong>Instagram:</strong><br>
						<a href="https://instagram.com/tuempresa" target="_blank" rel="noopener">
							@tuempresa
						</a>
					</span>
				</li>
			</ul>
		</aside>

		<!-- Formulario -->
		<section class="contact-form-section" aria-labelledby="form-presupuesto">
			<h2 id="form-presupuesto">Pide tu presupuesto</h2>
			<?php echo do_shortcode( '[custom_contact_form]' ); ?>
		</section>

	</div><!-- .contact-layout -->
</main>

<?php get_footer(); ?>
