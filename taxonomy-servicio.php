<?php
/* =====================================================
 *   SEO  <title>  |  <meta name="description">  | canonical
 * ===================================================== */
add_filter( 'document_title_parts', function ( $parts ) {
	if ( is_tax( 'servicio' ) ) {
		$term           = get_queried_object();
		$parts['title'] = 'Reformas de ' . $term->name . ' en Barcelona';
	}
	return $parts;
} );

add_action( 'wp_head', function () {
	if ( ! is_tax( 'servicio' ) ) return;

	$term  = get_queried_object();
	$desc  = get_term_meta( $term->term_id, 'intro_text', true );
	if ( empty( $desc ) ) {
		$desc = wp_trim_words( $term->description, 28 );
	}
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( get_term_link( $term ) ) . '">' . "\n";
}, 9 );
/* ===================================================== */
?>

<?php get_header(); ?>

<?php
/* ---------- datos base ---------- */
$term        = get_queried_object();
$desc_global = get_term_meta( $term->term_id, 'intro_text', true );
if ( empty( $desc_global ) ) {
	$desc_global = wp_trim_words( $term->description, 28 );
}

/* ---------- banner ---------- */
$banner_url = get_term_meta( $term->term_id, 'banner_image', true );
if ( $banner_url ) {
	$banner_id = attachment_url_to_postid( $banner_url );
	$banner_url = wp_get_attachment_image_url( $banner_id, 'service-banner' );
}
if ( ! $banner_url ) {
	$banner_url = site_url( '/wp-content/uploads/default-banner.jpg' );
}
?>

<main class="servicio-individual">

	<!-- Banner -->
	<section class="servicio-banner" style="background-image:url('<?php echo esc_url( $banner_url ); ?>')">
		<div class="banner-content">
			<h1><?php echo esc_html( get_term_meta( $term->term_id, 'banner_heading', true ) ?: $term->name ); ?></h1>
			<?php if ( $sub = get_term_meta( $term->term_id, 'banner_subheading', true ) ) : ?>
				<h2><?php echo esc_html( $sub ); ?></h2>
			<?php endif; ?>
		</div>
	</section>

<!-- Introducción en dos columnas: Texto e Imagen Lateral -->
<section class="servicio-intro">
	<div class="wrapper-contenido">

		<div class="intro-layout">

			<!-- Columna texto -->
			<div class="intro-text">
				<?php echo wpautop( $desc_global ); ?>
			</div>

			<!-- Columna imagen lateral -->
			<div class="intro-image">
				<?php
				if ( $side = get_term_meta( $term->term_id, 'side_image', true ) ) {
					$side_id = attachment_url_to_postid( $side );
					echo wp_get_attachment_image(
						$side_id,
						'medium_large',       
						false,
						[ 'alt' => $term->name . ' – Reformas' ]
					);
				}
				?>
			</div>

		</div><!-- .intro-layout -->

	</div><!-- .wrapper-contenido -->
</section>


	<?php echo do_shortcode('[cta_presupuesto]'); ?>
	<?php echo do_shortcode('[service_projects]'); ?>
	<?php echo do_shortcode('[seo_cta]'); ?>
	<?php echo do_shortcode('[contact_section]'); ?>


</main>

<?php
$schema = [
	'@context'    => 'https://schema.org',
	'@type'       => 'Service',
	'name'        => $term->name . ' – Reformas',
	'description' => $desc_global,
	'areaServed'  => 'Barcelona',
	'provider'    => [
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url(),
	],
];

echo '<script type="application/ld+json">' .
     wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
     '</script>';


get_footer(); ?>
