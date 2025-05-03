<?php

/*
Template Name: Página de Servicios
*/

$canonical = get_permalink();      

add_action( 'wp_head', function() use ( $canonical ) {
	echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">';
}, 9 );



get_header();

?>

<main class="servicios-overall">

  <!-- Sección Banner -->
  <?php echo do_shortcode('[page_banner]'); ?>

  <div class="wrapper-contenido">

     <!-- Encabezado SEO -->
  <div class="proyectos-header">

	<h2 class="page-title-seo">Servicios de Reformas Integrales en Barcelona y Alrededores</h2>

	<p class="seo-intro">
		Especialistas en <strong>albañilería, carpintería, fontanería, electricidad y pintura</strong> 
		en Barcelona y Alrededores. Descubre cómo transformamos hogares y negocios combinando materiales de primera calidad,
		mano de obra experta y precios transparentes.
	</p>

</div>

    <div class="servicios-cards">
  <?php
    $servicios = get_terms([
      'taxonomy'   => 'servicio',
      'hide_empty' => false,
    ]);
    if ( ! empty( $servicios ) && ! is_wp_error( $servicios ) ) :
      foreach ( $servicios as $servicio ) :
        $term_link = get_term_link( $servicio );
        // Elegimos imagen según slug
        switch ( $servicio->slug ) {
          case 'reformas-albanileria':
            $img_url = site_url('/wp-content/uploads/albañil1.webp');
            break;
          case 'reformas-carpinteria':
            $img_url = site_url('/wp-content/uploads/carpintero1.webp');
            break;
          case 'reformas-fontaneria':
            $img_url = site_url('/wp-content/uploads/fontanero1.webp');
            break;
          case 'reformas-electricista':
            $img_url = site_url('/wp-content/uploads/electricista1.webp');
            break;
          case 'reformas-pintor':
            $img_url = site_url('/wp-content/uploads/pintor1.webp');
            break;
          default:
            $img_url = site_url('/wp-content/uploads/default-card.webp');
        }
  ?>
    <div class="servicio-card">
      <a href="<?php echo esc_url( $term_link ); ?>" class="servicio-card-link">
        <!-- Imagen 16:9 -->
        <div class="servicio-card-image">
        <?php
$img_id = attachment_url_to_postid( $img_url );

if ( $img_id ) {
	echo wp_get_attachment_image(
		$img_id,
		'servicio-card',           // 640×360 hard‑crop
		false,
		[
			'class'   => 'card-img',                
			'loading' => 'lazy',
			'alt'     => 'Servicio de ' . strtolower( $servicio->name ),
		]
	);
} else {
	printf(
		'<img loading="lazy" width="640" height="360" class="card-img" src="%s" alt="%s">',
		esc_url( $img_url ),
		esc_attr( 'Servicio de ' . strtolower( $servicio->name ) )
	);
}
?>
        </div>
        <!-- Cuerpo flexible -->
        <div class="servicio-card-body">
          <h2 class="servicio-card-title"><?php echo esc_html( $servicio->name ); ?></h2>
          <p class="servicio-card-desc">
            <?php echo esc_html( $servicio->description ?: 'Descubre más sobre este servicio.' ); ?>
          </p>
          <span class="servicio-card-spacer"></span>
          <button class="btn-servicio-card">Ver servicio</button>
        </div>
      </a>
    </div>
  <?php
      endforeach;
    else:
      echo '<p>No se han configurado servicios aún.</p>';
    endif;
  ?>
</div>

  </div><!-- .wrapper-contenido -->

 <!-- Sección SEO antes del formulario -->
 <?php echo do_shortcode('[seo_cta]'); ?>

<!-- Sección: Contáctanos -->
<?php echo do_shortcode('[contact_section]'); ?>

</main>


<?php 

// ========= JSON‑LD ItemList para los 5 servicios =========
if ( ! empty( $servicios ) && ! is_wp_error( $servicios ) ) {
	$items = [];
	$pos   = 1;
	foreach ( $servicios as $s ) {
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => $s->name,
			'url'      => get_term_link( $s ),
		];
	}
	$schema = [
		'@context'         => 'https://schema.org',
		'@type'            => 'ItemList',
		'name'             => 'Servicios de Reformas',
		'itemListElement'  => $items,
	];
	echo '<script type="application/ld+json">' .
	     wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
	     '</script>';
}

get_footer(); 

?>
