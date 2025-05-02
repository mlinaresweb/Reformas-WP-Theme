<?php
function reformas_theme_setup() {

//cpt proyectos
require_once get_template_directory() . '/inc/cpt/cpt-proyectos.php';

// taxonomia servicios
require_once get_template_directory() . '/inc/taxonomia/tax-servicios.php';

// componente formulario
require_once get_template_directory() . '/inc/forms/contact-form.php';

// componente proyectos del servicio
require_once get_template_directory() . '/inc/components/service-projects.php';

// componente CTA Presupuesto
require_once get_template_directory() . '/inc/components/cta-presupuesto.php';

// componente SEO + CTA
require_once get_template_directory() . '/inc/components/seo-cta.php';

// componente Sección de Contacto
require_once get_template_directory() . '/inc/components/contact-section.php';


  // Soporte para título dinámico en la cabecera
  add_theme_support('title-tag');

  // Soporte para imágenes destacadas
  add_theme_support('post-thumbnails');

  // Soporte de estilos de bloques (Gutenberg)
  add_theme_support('wp-block-styles');

  // Soporte de logo
  add_theme_support('custom-logo');

  // Permitir alineación ancha y completa en bloques
  add_theme_support('align-wide');

  // Registrar un menú principal
  register_nav_menus(array(
    'menu_principal' => __('Menú Principal', 'reformas-theme'),
    'footer_menu'    => __('Menú Footer', 'mi-tema'),
  ));
}
add_action('after_setup_theme', 'reformas_theme_setup');

/**
 * Encolar (cargar) nuestros estilos y scripts
 */

function reformas_add_editor_styles() {
    add_editor_style('editor-style.css');
  }
  add_action('admin_init', 'reformas_add_editor_styles');
  

  function mi_tema_scripts() {

  // style.css principal del tema
  wp_enqueue_style('reformas-theme-style', 
  get_stylesheet_uri(), 
  array(), 
  '1.0', 
  'all'
);

 // Nav
 wp_enqueue_style(
  'reformas-theme-style-nav',
  get_stylesheet_directory_uri() . './css/NavMenu.css',
  array('reformas-theme-style'), // esto indica que se cargue después de style.css
  '1.0',
  'all'
);
    wp_enqueue_script('mi-tema-scroll', get_stylesheet_directory_uri() . './js/scroll.js', array(), '1.0', true);
  }
  add_action('wp_enqueue_scripts', 'mi_tema_scripts');
  
  function mytheme_enqueue_fonts() {
    // 1. Registrar la hoja de estilo de Google Fonts
    wp_register_style(
        'mytheme-roboto',
        'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap',
        [],          // sin dependencias
        null         // deja que WP gestione la versión con la URL
    );

    // 2. Encolarla (enqueue)
    wp_enqueue_style('mytheme-roboto');
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_fonts');


/**
 * Añade resource hints (<link rel="preconnect">) para mejorar el rendimiento.
 */
function mytheme_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = 'https://fonts.googleapis.com';
        // with crossorigin attribute
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        ];
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'mytheme_resource_hints', 10, 2 );

  add_filter('wp_nav_menu_objects', 'add_projects_submenu_items', 10, 2);
  function add_projects_submenu_items($sorted_menu_items, $args) {
      // Limita el filtro al menú principal (ajusta 'menu_principal' si es necesario)
      if ( isset($args->theme_location) && $args->theme_location == 'menu_principal' ) {
          foreach ($sorted_menu_items as $menu_item) {
              // Detecta el elemento cuyo título sea "Proyectos"
              if ( trim($menu_item->title) === 'Proyectos' ) {
                  // Obtén los términos de la taxonomía "servicio"
                  $terms = get_terms(array(
                      'taxonomy'   => 'servicio',
                      'hide_empty' => false,
                  ));
                  if (!is_wp_error($terms) && !empty($terms)) {
                      // Define un mapeo de slugs de servicio a la URL de la página personalizada
                      $service_pages = array(
                          'reformas-albanileria' => site_url('/proyectos-albanileria/'),
                          'reformas-carpinteria' => site_url('/proyectos-carpinteria/'),
                          'reformas-fontaneria'  => site_url('/proyectos-fontaneria/'),
                          'reformas-electricista'=> site_url('/proyectos-electricista/'),
                          'reformas-pintor'       => site_url('/proyectos-pintor/'),
                      );
                      $submenu_items = array();
                      // Empezamos el orden justo después del padre
                      $order = $menu_item->menu_order + 0.1;
                      foreach( $terms as $term ) {
                          $item = clone $menu_item;
                          // Define el título: "Proyectos de [Servicio]"
                          $item->title = 'Proyectos de ' . $term->name;
                          // Asigna un ID único
                          $item->ID = 100000 + $term->term_id;
                          $item->db_id = $item->ID;
                          // Establece el padre para formar el submenú
                          $item->menu_item_parent = $menu_item->ID;
                          // Si en el mapeo existe el slug, usar esa URL; en caso contrario, usar el enlace por defecto del término
                          $slug = strtolower( $term->slug );
                          if ( isset($service_pages[$slug]) ) {
                              $item->url = esc_url( $service_pages[$slug] );
                          } else {
                              $item->url = esc_url(get_term_link($term));
                          }
                          $item->menu_order = $order;
                          $order++;
                          $submenu_items[] = $item;
                      }
                      // Inserta los nuevos subítems justo después del ítem "Proyectos"
                      $index = array_search($menu_item, $sorted_menu_items);
                      array_splice($sorted_menu_items, $index + 1, 0, $submenu_items);
                  }
              }
          }
      }
      return $sorted_menu_items;
  }
  
/**
 * Pinta los enlaces activos del bloque “Proyectos”.
 * – En /proyectos solo se resalta el enlace “Proyectos”.
 * – En /proyectos‑xxx/ se resalta “Proyectos” y el sub‑enlace que
 *   coincide con /proyectos‑xxx/.
 */
add_filter( 'nav_menu_css_class', 'reformas_set_projects_active', 10, 4 );
function reformas_set_projects_active( $classes, $item, $args, $depth ) {

    /* Solo queremos afectar al menú principal ---------------------------- */
    if ( empty( $args->theme_location ) || $args->theme_location !== 'menu_principal' ) {
        return $classes;
    }

    /* Ruta actual sin dominio ni barra final ---------------------------- */
    global $wp;
    $current_path = '/' . untrailingslashit( $wp->request );     // ej:  /proyectos
    $item_path    = untrailingslashit( parse_url( $item->url, PHP_URL_PATH ) ); // ej: /proyectos-albanileria

    /* 1. Página global de proyectos (/proyectos) ------------------------ */
    if ( $current_path === '/proyectos' ) {

        // Si es el enlace padre “Proyectos” => lo marcamos
        if ( trim( $item->title ) === 'Proyectos' || $item_path === '/proyectos' ) {
            $classes[] = 'current-menu-item';
        }
        // En cualquier otro caso aseguramos que NO quede marcado
        else {
            $classes = array_diff( $classes, [ 'current-menu-item', 'current_page_item',
                                               'current-menu-parent', 'current-menu-ancestor' ] );
        }
        return $classes;
    }

    /* 2. Páginas /proyectos-xxx/  -------------------------------------- */
    if ( preg_match( '#^/proyectos-([^/]+)$#', $current_path, $m ) ) {

        $service_slug = $m[1];                     // ej: albanileria
        $expected     = '/proyectos-' . $service_slug;

        // Padre “Proyectos”
        if ( trim( $item->title ) === 'Proyectos' || $item_path === '/proyectos' ) {
            $classes[] = 'current-menu-item';
        }

        // Sub‑enlace exacto
        if ( $item_path === $expected ) {
            $classes[] = 'current-menu-item';
        }
        return $classes;
    }

    /* 3. Cualquier otra URL – no tocamos nada --------------------------- */
    return $classes;
}

  
  function servicio_admin_enqueue_scripts($hook) {
    // Cargar solo en las páginas de edición de términos
    if ( $hook === 'term.php' || $hook === 'edit-tags.php' ) {
        wp_enqueue_media();
        wp_enqueue_script( 'servicio-admin-js', get_template_directory_uri() . '/js/servicio-admin.js', array('jquery'), '1.0', true );
    }
}
add_action( 'admin_enqueue_scripts', 'servicio_admin_enqueue_scripts' );


// Manejar el envío del formulario de contacto de la página Contacto
function handle_contact_page_form() {
    if ( ! isset($_POST['contact_page_nonce']) || ! wp_verify_nonce($_POST['contact_page_nonce'], 'contact_page_form') ) {
      wp_die('Error de seguridad, inténtalo de nuevo.');
    }
    $name    = sanitize_text_field( $_POST['contact_name'] );
    $email   = sanitize_email( $_POST['contact_email'] );
    $phone   = sanitize_text_field( $_POST['contact_phone'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );
  
    $to = 'info@tudominio.com'; // tu email
    $subject = 'Mensaje de contacto: ' . $name;
    $headers = [
      'Content-Type: text/html; charset=UTF-8',
      'Reply-To: ' . $name . ' <' . $email . '>',
    ];
    $body  = "<p><strong>Nombre:</strong> {$name}</p>";
    $body .= "<p><strong>Email:</strong> {$email}</p>";
    if ( $phone ) {
      $body .= "<p><strong>Teléfono:</strong> {$phone}</p>";
    }
    $body .= "<p><strong>Mensaje:</strong><br>" . nl2br($message) . "</p>";
  
    wp_mail( $to, $subject, $body, $headers );
  
    // Redirigir con flag de éxito
    $redirect = add_query_arg( 'contacto', 'ok', wp_get_referer() );
    wp_redirect( $redirect );
    exit;
  }
  add_action( 'admin_post_nopriv_handle_contact_page_form', 'handle_contact_page_form' );
  add_action( 'admin_post_handle_contact_page_form',        'handle_contact_page_form' );
  

  function reformas_enqueue_lightbox() {
    // CSS de baguetteBox
    wp_enqueue_style(
      'baguettebox-css',
      'https://cdn.jsdelivr.net/npm/baguettebox.js@1.11.1/dist/baguetteBox.min.css',
      array(),
      '1.11.1'
    );
    // JS de baguetteBox
    wp_enqueue_script(
      'baguettebox-js',
      'https://cdn.jsdelivr.net/npm/baguettebox.js@1.11.1/dist/baguetteBox.min.js',
      array(),
      '1.11.1',
      true
    );
    // Inicialización al cargar
    wp_add_inline_script(
      'baguettebox-js',
      "document.addEventListener('DOMContentLoaded', function() {
         baguetteBox.run('.baguette-gallery', {
           captions: true,
           buttons: 'auto',
         });
       });"
    );
  }
  add_action('wp_enqueue_scripts', 'reformas_enqueue_lightbox');
  
  