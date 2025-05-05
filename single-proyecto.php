<?php
/**
 * single-proyecto.php — Plantilla para mostrar un Proyecto individual
 */

/* ----------  SEO dinámico para cada Proyecto  ---------- */
add_filter( 'document_title_parts', function ( $parts ) {
	if ( is_singular( 'proyecto' ) ) {
		$parts['title'] = get_the_title() . ' | Reformas en Barcelona';
	}
	return $parts;
} );

add_action( 'wp_head', function () {
	if ( ! is_singular( 'proyecto' ) ) {
		return;
	}

	$post_id = get_the_ID();

	/* --- Meta‑description (150‑160 car.) --- */
	$descripcion = get_post_meta( $post_id, '_proyecto_descripcion', true );
	if ( empty( $descripcion ) ) {
		$descripcion = wp_trim_words( strip_shortcodes( get_the_content() ), 28 );
	}
	echo '<meta name="description" content="' . esc_attr( $descripcion ) . '">' . "\n";

	/* --- Canonical --- */
	echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />' . "\n";

	/* --- JSON‑LD “Project” + Breadcrumb --- */
	$imgs   = [];
	$feat   = get_the_post_thumbnail_url( $post_id, 'full' );
	if ( $feat ) { $imgs[] = $feat; }

	$gallery = get_post_meta( $post_id, '_proyecto_galeria', true );
	if ( $gallery ) {
		foreach ( array_map( 'trim', explode( ',', $gallery ) ) as $id ) {
			if ( $url = wp_get_attachment_url( $id ) ) { $imgs[] = $url; }
		}
	}

	$breadcrumb = [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => [
			[
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => 'Inicio',
				'item'     => home_url(),
			],
			[
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => 'Proyectos',
				'item'     => get_post_type_archive_link( 'proyecto' ),
			],
			[
				'@type'    => 'ListItem',
				'position' => 3,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			],
		],
	];

	$schema_project = [
		'@context'        => 'https://schema.org',
		'@type'           => 'Project',            // también se admite CreativeWork
		'name'            => get_the_title(),
		'description'     => $descripcion,
		'image'           => $imgs,
		'datePublished'   => get_the_date( 'c' ),
		'url'             => get_permalink(),
		'locationCreated' => get_post_meta( $post_id, '_proyecto_ubicacion', true ),
		'provider'        => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url(),
		],
	];

	echo '<script type="application/ld+json">'
	     . wp_json_encode( [ $breadcrumb, $schema_project ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	     . '</script>';
}, 9 );
/* ------------------------------------------------------- */

get_header();
?>


<main class="single-proyectos">
  <div class="wrapper-contenido">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <!-- Título del proyecto -->
      <h1 class="proyecto-title"><?php the_title(); ?></h1>

      <?php
  $fecha     = get_post_meta( get_the_ID(), '_proyecto_fecha', true );
  $ubicacion = get_post_meta( get_the_ID(), '_proyecto_ubicacion', true );
  if ( $fecha ) {
    $fecha_fmt = date_i18n( get_option('date_format'), strtotime( $fecha ) );
  }
  if ( $fecha || $ubicacion ) : 
?>
  <div class="proyecto-meta">
    <?php if ( $fecha ) : ?>
      <span class="proyecto-fecha" aria-label="Fecha de realización">
        <i class="icon-calendar"></i>
        <?php echo esc_html( $fecha_fmt ); ?>
      </span>
    <?php endif; ?>
    <?php if ( $ubicacion ) : ?>
      <span class="proyecto-ubicacion" aria-label="Ubicación">
        <i class="icon-location"></i>
        <?php echo esc_html( $ubicacion ); ?>
      </span>
    <?php endif; ?>
  </div>
<?php endif; ?>

      <!-- Imagen destacada -->
      <?php if ( has_post_thumbnail() ) : ?>
	<div class="proyecto-featured-image">
		<?php the_post_thumbnail( 'large', [
			'alt' => get_the_title() . ' – Reforma',
			'loading' => 'lazy'
		] ); ?>
	</div>
<?php endif; ?>


      <!-- Descripción del proyecto -->
      <section class="proyecto-descripcion">
        <?php
          $descripcion = get_post_meta( get_the_ID(), '_proyecto_descripcion', true );
          echo wpautop( wp_kses_post( $descripcion ) );
        ?>
      </section>

      <!-- Galería de imágenes -->
<section class="proyecto-galeria">
	<div class="galeria-grid baguette-gallery">
	<?php

		$gallery = get_post_meta( get_the_ID(), '_proyecto_galeria', true );

		if ( $gallery ) :

			$ids = array_filter( array_map( 'trim', explode( ',', $gallery ) ) );

			foreach ( $ids as $img_id ) :
				$full = wp_get_attachment_url( $img_id );      
				if ( ! $full ) continue;                      

				echo '<div class="galeria-item galeria-item-proyecto">
						<a href="' . esc_url( $full ) . '" data-caption="' . esc_attr( get_the_title() ) . '">'
							. wp_get_attachment_image(
									$img_id,
									'medium',          
									false,
									[
										'alt'     => get_the_title() . ' – Reforma',
										'loading' => 'lazy'
									]
								)
						. '</a>
					  </div>';
			endforeach;

		endif;
	?>
	</div>
</section>



<div class="wrapper-listas-proyectos">
      <!-- Trabajos realizados en la reforma -->
      <?php
        $trabajos = get_post_meta( get_the_ID(), '_proyecto_trabajos', true );
        if ( $trabajos ) : ?>
          <section class="proyecto-trabajos">
            <h2>Trabajos realizados en la reforma</h2>
            <div class="trabajos-content">
              <?php echo wp_kses_post( $trabajos ); ?>
            </div>
          </section>
      <?php endif; ?>

      <!-- Materiales empleados -->
      <?php
        $materiales = get_post_meta( get_the_ID(), '_proyecto_materiales', true );
        if ( $materiales ) : ?>
          <section class="proyecto-materiales">
            <h2>Materiales empleados</h2>
            <div class="materiales-content">
              <?php echo wp_kses_post( $materiales ); ?>
            </div>
          </section>
      <?php endif; ?>
      </div>
    <?php endwhile; endif; ?>

  </div><!-- .wrapper-contenido -->

  <!-- Sección SEO antes del formulario -->
  <?php echo do_shortcode('[seo_cta]'); ?>

<!-- Sección: Contáctanos -->
<?php echo do_shortcode('[contact_section]'); ?>

</main>

<?php get_footer(); ?>
