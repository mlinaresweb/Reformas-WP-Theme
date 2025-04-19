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
  