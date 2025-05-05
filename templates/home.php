<?php
/*
Template Name: Plantilla Home (Reformas)
*/

/* ───────────────────────── SEO DINÁMICO ───────────────────────── */

/* 1) <title> — añadimos palabra clave + branding */
add_filter( 'document_title_parts', function ( $parts ) {
	$parts['title'] = 'Reformas integrales en Barcelona | ' . get_bloginfo( 'name' );
	return $parts;
} );

/* 2) meta‑description + canonical + JSON‑LD */
add_action( 'wp_head', function () {

	/* --- descripción (≈ 160 car.) --- */
	$desc = 'Empresa low‑cost de reformas integrales en Barcelona: albañilería, carpintería, fontanería, electricidad y pintura con plazos rápidos y precios transparentes.';
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";

	/* --- canonical --- */
	echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />' . "\n";

	/* --- JSON‑LD WebPage --- */
	echo '<script type="application/ld+json">' .
		wp_json_encode( [
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'name'     => get_bloginfo( 'name' ),
			'description' => $desc,
			'url'         => get_permalink(),
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) .
	'</script>';
}, 9 );

/* ───────────────────────── FIN SEO ───────────────────────── */

get_header(); 
?>

<main class="home-site main-content">

<?php
$hero_bg   = get_field( 'hero_background_image' );
?>

<!-- HERO ---------------------------------------------------------------- -->
<section class="hero-home" >
	<div class="hero-wrrapper wrapper-contenido">
		<div class="hero-contenedor">

			<header class="hero-content">
				<!-- h1 oculto solo para SEO / accesibilidad -->
				<h1 class="sr-only">Reformas integrales low‑cost en Barcelona – <?php bloginfo( 'name' ); ?></h1>

				<h2><?php the_field( 'titulo_subhero' ); ?></h2>
				<p class="hero-heading"><?php the_field( 'titulo_hero' ); ?></p>
				<p><?php the_field( 'texto_hero' ); ?></p>

				<a href="<?php the_field( 'link_boton_hero' ); ?>" class="btn-hero" aria-label="Solicitar presupuesto de reforma">
					<?php the_field( 'texto_boton_hero' ); ?>
				</a>
			</header>

		</div>
	</div>
</section>

<!-- SERVICIOS ------------------------------------------------------------ -->
<section class="servicios-de-reforma">
	<div class="wrapper-contenido">
		<h2 class="section-title">Nuestros Servicios</h2>

		<div class="servicios-layout">
			<!-- Galería (izquierda) -->
			<div class="servicios-gallery">
				<img id="servicio-img"
					 src="<?php echo esc_url( site_url( '/wp-content/uploads/reformas.webp' ) ); ?>"
					 alt="Servicios de reforma integral"
					 width="640" height="360" loading="lazy">
			</div>

			<!-- Lista de servicios (derecha) -->
			<div class="servicios-list">
			<?php
				$terms = get_terms( [
					'taxonomy'   => 'servicio',
					'hide_empty' => false,
				] );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
					foreach ( $terms as $s ) :

						switch ( $s->slug ) {
							case 'reformas-albanileria':
								$gal = site_url('/wp-content/uploads/albañileria.webp');
								$ico = site_url('/wp-content/uploads/albañil-marron.webp');
								break;
							case 'reformas-carpinteria':
								$gal = site_url('/wp-content/uploads/carpinteria.webp');
								$ico = site_url('/wp-content/uploads/carpintero-marron.webp');
								break;
							case 'reformas-fontaneria':
								$gal = site_url('/wp-content/uploads/fontaneria.webp');
								$ico = site_url('/wp-content/uploads/fontanero-marron.webp');
								break;
							case 'reformas-electricista':
								$gal = site_url('/wp-content/uploads/lampista.webp');
								$ico = site_url('/wp-content/uploads/electricista-marron.webp');
								break;
							case 'reformas-pintor':
								$gal = site_url('/wp-content/uploads/pintura.webp');
								$ico = site_url('/wp-content/uploads/pintor-marron.webp');
								break;
							default:
								$gal = site_url('/wp-content/uploads/reformas.webp');
								$ico = site_url('/wp-content/uploads/albañil-marron.webp');
						}
			?>
				<article class="servicio-item" data-image="<?php echo esc_url( $gal ); ?>">
					<div class="servicio-icon">
						<img src="<?php echo esc_url( $ico ); ?>" alt="<?php echo esc_attr( $s->name ); ?> icono"
							 width="56" height="56" loading="lazy">
					</div>

					<div class="servicio-info">
						<h3 class="servicio-title"><?php echo esc_html( $s->name ); ?></h3>
						<?php if ( $s->description ) : ?>
							<p class="servicio-description"><?php echo esc_html( $s->description ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; endif; ?>
			</div><!-- /.servicios-list -->
		</div><!-- /.servicios-layout -->
	</div>
</section>

<!-- Sección Por qué Elegirnos -->
<section class="porque-elegirnos">
	<div class="wrapper-contenido">

		<!-- ❶ H 2 ya existente -->
		<h2 class="section-title">¿Por qué elegirnos?</h2>

		<!-- ❷ H 3 “fantasma” solo para SEO/AT — oculto con CSS -->
		<h3 class="sr-only">Ventajas competitivas de nuestra reforma low‑cost</h3>

		<div class="cards-container">
		<?php if ( have_rows( 'elegirnos_cards' ) ) : ?>
			<?php while ( have_rows( 'elegirnos_cards' ) ) : the_row(); ?>
				<div class="card" itemscope itemtype="https://schema.org/Service">
					<meta itemprop="provider" content="<?php bloginfo( 'name' ); ?>">
					<?php $icon = get_sub_field( 'icono_card' ); ?>
					<?php if ( $icon ) : ?>
						<div class="card-icon" aria-hidden="true">
							<img loading="lazy"
							     src="<?php echo esc_url( $icon['url'] ); ?>"
							     alt=""
							     width="64" height="64">
						</div>
					<?php endif; ?>

					<h4 class="card-title" itemprop="name"><?php the_sub_field( 'titulo_card' ); ?></h4>
					<p class="card-desc" itemprop="description"><?php the_sub_field( 'descripcion_card' ); ?></p>
				</div>
			<?php endwhile; ?>

		<?php else : /* Fallback */ ?>

			<?php
				$fallback = [
					[
						'title' => 'Mejor relación calidad‑precio',
						'desc'  => 'Máxima calidad sin comprometer tu presupuesto.',
						'icon'  => site_url( '/wp-content/uploads/ahorro-marron.png' ),
					],
					[
						'title' => 'Presupuestos transparentes',
						'desc'  => 'Sin sorpresas: conoces cada coste desde el inicio.',
						'icon'  => site_url( '/wp-content/uploads/presupuesto-marron.png' ),
					],
					[
						'title' => 'Experiencia profesional',
						'desc'  => 'Años de obras nos avalan en Barcelona.',
						'icon'  => site_url( '/wp-content/uploads/experiencia-marron.png' ),
					],
					[
						'title' => 'Calidad y confianza',
						'desc'  => 'Resultados duraderos y clientes satisfechos.',
						'icon'  => site_url( '/wp-content/uploads/confianza-marron.png' ),
					],
				];
				foreach ( $fallback as $i => $c ) :
			?>
				<div class="card" itemscope itemtype="https://schema.org/Service">
					<meta itemprop="provider" content="<?php bloginfo( 'name' ); ?>">
					<div class="card-icon" aria-hidden="true">
						<img loading="lazy"
						     src="<?php echo esc_url( $c['icon'] ); ?>"
						     alt=""
						     width="64" height="64">
					</div>
					<h4 class="card-title" itemprop="name"><?php echo esc_html( $c['title'] ); ?></h4>
					<p class="card-desc" itemprop="description"><?php echo esc_html( $c['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		</div><!-- .cards-container -->
	</div><!-- .wrapper-contenido -->
</section>

<?php
/* ❸ JSON‑LD ItemList con las 4 ventajas por defecto (solo si no existen filas ACF) */
if ( ! have_rows( 'elegirnos_cards' ) ) {
	$list = [];
	foreach ( $fallback as $pos => $c ) {
		$list[] = [
			'@type'    => 'ListItem',
			'position' => $pos + 1,
			'name'     => $c['title'],
			'description' => $c['desc'],
		];
	}
	echo '<script type="application/ld+json">' .
	     wp_json_encode(
		     [
			     '@context'        => 'https://schema.org',
			     '@type'           => 'ItemList',
			     'name'            => 'Ventajas competitivas',
			     'itemListElement' => $list,
		     ],
		     JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	     ) .
	     '</script>';
}
?>



<!-- GALERÍA PROYECTOS ---------------------------------------------------- -->
<section class="galeria-proyectos" aria-labelledby="tit-proy-home">
	<div class="wrapper-contenido">
		<div class="galeria-proyectos-layout">

			<div class="galeria-info">
				<h2 id="tit-proy-home" class="galeria-title">
					Descubre<br>nuestro<br>trabajo
				</h2>
				<p class="galeria-desc">
					Conoce cómo llevamos a cabo cada reforma, combinando experiencia y calidad para transformar espacios.
				</p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>"
				   class="btn-ver-proyectos"
				   aria-label="Ver todos los proyectos de reformas">
					Ver proyectos
				</a>
			</div>

			<div class="galeria-items">
			<?php
				/* Seleccionamos 4 proyectos (destacados o recientes) */
				$args = [
					'post_type'      => 'proyecto',
					'posts_per_page' => 4,
					'orderby'        => 'date',
					'order'          => 'DESC',
				];
				$projects = get_posts( $args );

				foreach ( $projects as $p ) :
					/* Img: galería → destacada → fallback */
					$img = '';
					$ids = get_post_meta( $p->ID, '_proyecto_galeria', true );
					if ( $ids ) {
						$arr = array_filter( array_map( 'trim', explode( ',', $ids ) ) );
						$img = wp_get_attachment_image_url( $arr[0] ?? 0, 'large' );
					}
					if ( ! $img ) { $img = get_the_post_thumbnail_url( $p, 'large' ); }
					if ( ! $img ) { $img = site_url( '/wp-content/uploads/default-project.jpg' ); }
			?>
				<div class="galeria-item">
					<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"
					   aria-label="Proyecto: <?php echo esc_attr( get_the_title( $p ) ); ?>">
						<div class="item-overlay">
							<h3 class="item-title"><?php echo esc_html( get_the_title( $p ) ); ?></h3>
						</div>
						<img src="<?php echo esc_url( $img ); ?>"
							 alt="<?php echo esc_attr( get_the_title( $p ) ); ?>"
							 width="640" height="360" loading="lazy">
					</a>
				</div>
			<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>

<!-- SEO + CTA ------------------------------------------------------------ -->
<?php echo do_shortcode( '[seo_cta]' ); ?>

<!-- CONTACTO ------------------------------------------------------------- -->
<?php echo do_shortcode( '[contact_section]' ); ?>

</main>

<?php get_footer(); ?>

<script>

(function(){
  let initialized = false;
  const MOBILE_MAX = 768;

  function setupReadmore() {
    const isMobile = window.innerWidth <= MOBILE_MAX;
    document.querySelectorAll('.servicio-item').forEach(item => {
      const desc = item.querySelector('.servicio-description');
      if (!desc) return;
      desc.classList.remove('expanded');
      const existingBtn = item.querySelector('.servicio-readmore');
      if (existingBtn) existingBtn.remove();

      if (isMobile) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'servicio-readmore';
        btn.textContent = 'Ver más';
        desc.insertAdjacentElement('afterend', btn);
        btn.addEventListener('click', function(e){
          e.stopPropagation();
          if (desc.classList.contains('expanded')) {
            desc.classList.remove('expanded');
            btn.textContent = 'Ver más';
          } else {
            desc.classList.add('expanded');
            btn.textContent = 'Ver menos';
          }
        });
      }
    });
  }

  document.addEventListener('DOMContentLoaded', setupReadmore);
  let resizeTimer;
  window.addEventListener('resize', function(){
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(setupReadmore, 200);
  });
})();

document.addEventListener("DOMContentLoaded", function() {
  const serviceItems = document.querySelectorAll('.servicio-item');
  const galleryImg = document.getElementById('servicio-img');
  
  galleryImg.style.transition = 'opacity 0.3s ease';

  serviceItems.forEach(function(item) {
    item.addEventListener('click', function() {
      serviceItems.forEach(i => i.classList.remove('active'));
      this.classList.add('active');
      
      const newImage = this.getAttribute('data-image');
      if (newImage) {
        const preloader = new Image();
        preloader.onload = function() {
          galleryImg.style.opacity = 0; 
          setTimeout(function() {
            galleryImg.src = newImage;
          }, 300);
        };
        preloader.src = newImage;
        
        galleryImg.onload = function() {
          galleryImg.style.opacity = 1; 
        };
      }
    });
  });
});

</script>
