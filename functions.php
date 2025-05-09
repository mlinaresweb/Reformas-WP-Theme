<?php
/* ================================================================
 *  Reformas Theme –  functions.php
 *  ---------------------------------------------------------------
 *  00 · Constantes y cargadores
 *  01 · Setup del tema
 *  02 · Estilos  /  Scripts  /  Fuentes
 *  03 · Mejoras de rendimiento          (resource‑hints)
 *  04 · Menú – inyección submenú y clases “active”
 *  05 · Scripts de administración       (term image picker)
 *  06 · Librerías front‑end externas    (baguetteBox)
 *  07 · Widgets UI (WhatsApp flotante)
 *  08 · ACF Campos locales
 *  09 · Tamaños de imagen adicionales
 * ===============================================================*/

/* =================================================================
 * 00 · CONSTANTES + AUTO‑INCLUDES
 * =================================================================*/

 $secret_file = get_template_directory() . '/inc/credentials/local-secrets.php';

if ( file_exists( $secret_file ) ) {
	$secrets = require $secret_file;        

	foreach ( $secrets as $key => $value ) {
		if ( ! defined( $key ) ) {
			define( $key, $value );
		}
	}
}

define( 'REF_DIR', get_template_directory() );
define( 'REF_URI', get_stylesheet_directory_uri() );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/* -- Carga de módulos (CPT, tax, componentes, etc.) -------------- */
foreach ( [
	'/inc/cpt/cpt-proyectos.php',
	'/inc/taxonomia/tax-servicios.php',
	'/inc/forms/contact-form.php',
	'/inc/components/service-projects.php',
	'/inc/components/cta-presupuesto.php',
	'/inc/components/seo-cta.php',
	'/inc/components/contact-section.php',
	'/inc/components/page‑banner.php',
] as $inc ) {
	require_once REF_DIR . $inc;
}


/* =================================================================
 * 01 · SETUP DEL TEMA
 * =================================================================*/
add_action( 'after_setup_theme', function () {

  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'wp-block-styles' );
  add_theme_support( 'custom-logo' );
  add_theme_support( 'align-wide' );

	register_nav_menus( [
		'menu_principal' => __( 'Menú Principal', 'reformas-theme' ),
		'footer_menu'    => __( 'Menú Footer',    'reformas-theme' ),
	] );
} );


/* =================================================================
 * 02 · ASSETS  (CSS / JS / FUENTES)
 * =================================================================*/

/** 2‑a · CSS + JS del front */
add_action( 'wp_enqueue_scripts', function () {

	/* —— hoja base (imprescindible para dependencias) */
	wp_enqueue_style( 'ref-base', get_stylesheet_uri(), [], '1.0' );

	/* —— colecciones de estilos */
	$styles = [
	'NavMenu', 
    'templates/Home', 
    'components/ContactSection',
	'components/SeoCta', 
    'templates/Servicios', 
    'components/pageBanner',
	'templates/ServicioIndividual', 
    'components/ProyectosServicio',
	'components/CtaPresupuesto', 
    'templates/Proyectos',
	'templates/Contacto', 
    'templates/Blog',
	'templates/ProyectoIndividual', 
    'Footer',
	];
	foreach ( $styles as $file ) {
		wp_enqueue_style(
			"ref-$file",
			REF_URI . "/css/$file.css",
			[ 'ref-base' ],
			'1.0'
		);
	}

	/* —— JS propio */
	wp_enqueue_script(
		'ref-scroll',
		REF_URI . '/js/scroll.js',
		[],
		'1.0',
		true
	);
} );

/** 2‑b · Estilos para el editor */
add_action( 'admin_init', function () {
	add_editor_style( 'editor-style.css' );
} );

/** 2‑c · Google Fonts */
add_action( 'wp_enqueue_scripts', function () {
	wp_register_style(
		'ref-roboto',
		'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
		[],
		null
	);
	wp_enqueue_style( 'ref-roboto' );
}, 5);


/* =================================================================
 * 03 · RESOURCE HINTS
 * =================================================================*/
add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => true ];
	}
	return $urls;
}, 10, 2 );


/* =================================================================
 * 04 · MENÚ  (sub‑menú dinámico + clases active)
 * =================================================================*/

/* 4‑a · Inyectar sub‑links “Proyectos de [servicio]” ------------- */
add_filter( 'wp_nav_menu_objects', 'ref_add_project_children', 10, 2 );
function ref_add_project_children( $items, $args ) {

	if ( empty( $args->theme_location ) || $args->theme_location !== 'menu_principal' ) {
		return $items;
	}

	foreach ( $items as $item ) {
		if ( 'Proyectos' !== trim( $item->title ) ) continue;

		$terms = get_terms( [ 'taxonomy' => 'servicio', 'hide_empty' => false ] );
		if ( is_wp_error( $terms ) ) return $items;

		$service_pages = [
			'reformas-albanileria'  => site_url( '/proyectos-albanileria/'  ),
			'reformas-carpinteria'  => site_url( '/proyectos-carpinteria/'  ),
			'reformas-fontaneria'   => site_url( '/proyectos-fontaneria/'   ),
			'reformas-electricista' => site_url( '/proyectos-electricista/' ),
			'reformas-pintor'       => site_url( '/proyectos-pintor/'       ),
		];

		$order = $item->menu_order + 0.1;
		foreach ( $terms as $t ) {
			$clone                   = clone $item;
			$clone->ID               = 100000 + $t->term_id;
			$clone->db_id            = $clone->ID;
			$clone->menu_item_parent = $item->ID;
			$clone->menu_order       = $order;
			$clone->title            = 'Proyectos de ' . $t->name;
			$clone->url              = $service_pages[ $t->slug ] ?? get_term_link( $t );
			$items[]                 = $clone;
			$order += 0.1;
		}
		break;
	}
	return $items;
}

/* 4‑b · Señalar enlaces activos correctamente ------------------- */
add_filter( 'nav_menu_css_class', 'ref_projects_current_classes', 10, 4 );
function ref_projects_current_classes( $classes, $item, $args ) {

	if ( empty( $args->theme_location ) || $args->theme_location !== 'menu_principal' ) {
		return $classes;
	}

	global $wp;
	$current = '/' . untrailingslashit( $wp->request );
	$itemURI = untrailingslashit( parse_url( $item->url, PHP_URL_PATH ) );

	/* — página /proyectos */
	if ( '/proyectos' === $current ) {
		if ( 'Proyectos' === trim( $item->title ) || '/proyectos' === $itemURI ) {
			$classes[] = 'current-menu-item';
		} else {
			$classes = array_diff( $classes, [ 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ] );
		}
		return $classes;
	}

	/* — páginas /proyectos‑xxx/ */
	if ( preg_match( '#^/proyectos-([^/]+)$#', $current, $m ) ) {
		$expected = '/proyectos-' . $m[1];
		if ( 'Proyectos' === trim( $item->title ) || '/proyectos' === $itemURI || $itemURI === $expected ) {
			$classes[] = 'current-menu-item';
		}
	}

	return $classes;
}


/* =================================================================
 * 05 · SCRIPTS BACK‑OFFICE
 * =================================================================*/
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( in_array( $hook, [ 'term.php', 'edit-tags.php' ], true ) ) {
		wp_enqueue_media();
		wp_enqueue_script(
			'ref-servicio-admin',
			REF_URI . '/js/servicio-admin.js',
			[ 'jquery' ],
			'1.0',
			true
		);
	}
} );


/* =================================================================
 * 06 · BAGUETTEBOX (galerías)
 * =================================================================*/
add_action( 'wp_enqueue_scripts', function () {

	wp_enqueue_style(
		'baguettebox',
		'https://cdn.jsdelivr.net/npm/baguettebox.js@1.11.1/dist/baguetteBox.min.css',
		[],
		'1.11.1'
	);

	wp_enqueue_script(
		'baguettebox',
		'https://cdn.jsdelivr.net/npm/baguettebox.js@1.11.1/dist/baguetteBox.min.js',
		[],
		'1.11.1',
		true
	);

	wp_add_inline_script(
		'baguettebox',
		"document.addEventListener('DOMContentLoaded',()=>baguetteBox.run('.baguette-gallery',{captions:true,buttons:'auto'}));"
	);
} );


/* =================================================================
 * 07 · BOTÓN FLOTANTE DE WHATSAPP
 * =================================================================*/
add_action( 'wp_footer', 'reformas_whatsapp_floating_button' );
function reformas_whatsapp_floating_button() {

	/* --- Ajusta aquí tu número y el mensaje -------- */
	$phone   = '34666666666';                              // sin + ni espacios
	$message = rawurlencode( '¡Hola! Quisiera más información.' );
	$link    = "https://wa.me/$phone?text=$message";

	?>
	<!-- Botón flotante WhatsApp -->
	<a href="<?php echo esc_url( $link ); ?>"
	   class="whatsapp-float"
	   target="_blank" rel="noopener"
	   aria-label="Chatear por WhatsApp">
		<!-- Ícono (SVG) -->
		<svg viewBox="0 0 32 32" width="22" height="22" aria-hidden="true">
	<path fill="#fff" d="M16 0.002c-8.836 0-16 7.164-16 16 0 2.827 0.741 5.605 2.145 8.058l-2.145 7.94 8.145-2.14c2.376 1.302 5.063 1.988 7.855 1.988 8.836 0 16-7.164 16-16s-7.164-16-16-16zM16 29.035c-2.528 0-5.011-0.678-7.173-1.962l-0.514-0.301-4.843 1.274 1.287-4.77-0.31-0.519c-1.306-2.185-1.996-4.68-1.996-7.258 0-7.557 6.152-13.708 13.708-13.708s13.708 6.152 13.708 13.708c0 7.556-6.152 13.708-13.708 13.708zM23.249 19.208c-0.353-0.176-2.084-1.028-2.407-1.144-0.323-0.118-0.557-0.176-0.79 0.176-0.235 0.353-0.904 1.144-1.107 1.379-0.204 0.235-0.408 0.264-0.761 0.088-0.353-0.176-1.489-0.548-2.838-1.746-1.048-0.937-1.756-2.094-1.961-2.447-0.204-0.353-0.022-0.544 0.154-0.72 0.158-0.157 0.353-0.411 0.529-0.617 0.176-0.205 0.235-0.353 0.353-0.587 0.118-0.235 0.059-0.441-0.029-0.617-0.088-0.176-0.88-2.113-1.21-2.892-0.319-0.769-0.642-0.666-0.88-0.676l-0.751-0.012c-0.235 0-0.617 0.089-0.938 0.441-0.323 0.353-1.247 1.247-1.247 3.048s1.277 3.536 1.456 3.777c0.176 0.235 2.509 3.822 6.082 5.345 0.851 0.368 1.515 0.588 2.044 0.752 0.852 0.276 1.624 0.235 2.24 0.147 0.679-0.105 2.107-0.862 2.393-1.693 0.282-0.83 0.282-1.521 0.196-1.693-0.088-0.172-0.264-0.264-0.557-0.411z"/>
</svg>

	</a>
	<?php
}


/* =================================================================
 * 08 · CAMPOS ACF (Banner por página)
 * =================================================================*/
add_action( 'acf/init', function () {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

	acf_add_local_field_group( [
		'key'    => 'group_page_banner',
		'title'  => 'Banner de página',
		'fields' => [
			[
				'key'          => 'field_banner_image',
				'label'        => 'Imagen del banner',
				'name'         => 'banner_image',
				'type'         => 'image',
				'return_format'=> 'array',
				'preview_size' => 'medium',
				'library'      => 'all',
			],
		],
		'location' => [
			[ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ] ],
		],
	] );
} );


/* =================================================================
 * 09 · TAMAÑOS DE IMAGEN EXTRA
 * =================================================================*/
add_action( 'after_setup_theme', function () {
	add_image_size( 'servicio-card', 640, 360, true ); // 16 : 9 hard‑crop
} );

