<?php
//custom post type Proyectos
function register_proyecto_post_type() {
  $labels = array(
      'name'                  => _x( 'Proyectos', 'Post Type General Name', 'reformas-theme' ),
      'singular_name'         => _x( 'Proyecto', 'Post Type Singular Name', 'reformas-theme' ),
      'menu_name'             => __( 'Proyectos', 'reformas-theme' ),
      'name_admin_bar'        => __( 'Proyecto', 'reformas-theme' ),
      'add_new'               => __( 'Añadir Proyecto', 'reformas-theme' ),
      'add_new_item'          => __( 'Crear Nuevo Proyecto', 'reformas-theme' ),
      'edit_item'             => __( 'Editar Proyecto', 'reformas-theme' ),
      'new_item'              => __( 'Nuevo Proyecto', 'reformas-theme' ),
      'view_item'             => __( 'Ver Proyecto', 'reformas-theme' ),
      'search_items'          => __( 'Buscar Proyectos', 'reformas-theme' ),
  );

  $args = array(
      'label'                 => __( 'Proyecto', 'reformas-theme' ),
      'description'           => __( 'Proyectos de la empresa de reformas', 'reformas-theme' ),
      'labels'                => $labels,
      'supports'              => array( 'title', 'thumbnail' ), 
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-portfolio',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'post',
      'show_in_rest'          => false,
  );
  register_post_type( 'proyecto', $args );
}
add_action( 'init', 'register_proyecto_post_type', 0 );

function proyecto_add_meta_boxes() {
  add_meta_box(
      'proyecto_detalles',                  // ID del meta box
      __('Detalles del Proyecto', 'reformas-theme'), // Título del meta box
      'proyecto_detalles_callback',         // Función de callback que muestra el contenido
      'proyecto',                           // Post type donde se mostrará
      'normal', 
      'high'
  );
}
add_action('add_meta_boxes', 'proyecto_add_meta_boxes');

function proyecto_detalles_callback( $post ) {
    // Verificación de seguridad
    wp_nonce_field( 'proyecto_detalles_nonce', 'proyecto_detalles_nonce' );

    // Recuperar valores actuales
    $descripcion   = get_post_meta( $post->ID, '_proyecto_descripcion', true );
    $galeria       = get_post_meta( $post->ID, '_proyecto_galeria', true ); // IDs separados por comas
    $fecha         = get_post_meta( $post->ID, '_proyecto_fecha', true );
    $ubicacion     = get_post_meta( $post->ID, '_proyecto_ubicacion', true );
    $destacado     = get_post_meta( $post->ID, '_proyecto_destacado', true );
    $trabajos      = get_post_meta( $post->ID, '_proyecto_trabajos', true );
    $materiales    = get_post_meta( $post->ID, '_proyecto_materiales', true );

    // Campo: Descripción (textarea)
    echo '<p><label for="proyecto_descripcion">' . __('Descripción del Proyecto', 'reformas-theme') . '</label></p>';
    echo '<textarea id="proyecto_descripcion" name="proyecto_descripcion" rows="5" style="width:100%;">' . esc_textarea( $descripcion ) . '</textarea>';

    // Campo: Galería de Imágenes (input de texto para IDs)
    echo '<p><label for="proyecto_galeria">' . __('Galería de Imágenes (IDs separados por comas)', 'reformas-theme') . '</label></p>';
    echo '<input type="text" id="proyecto_galeria" name="proyecto_galeria" value="' . esc_attr( $galeria ) . '" style="width:80%;" />';
    echo ' <input type="button" id="proyecto_galeria_btn" class="button" value="' . __('Seleccionar Imágenes', 'reformas-theme') . '" />';

    // Campo: Fecha (input tipo date)
    echo '<p><label for="proyecto_fecha">' . __('Fecha del Proyecto', 'reformas-theme') . '</label></p>';
    echo '<input type="date" id="proyecto_fecha" name="proyecto_fecha" value="' . esc_attr( $fecha ) . '" style="width:200px;" />';

    // Campo: Ubicación (input de texto)
    echo '<p><label for="proyecto_ubicacion">' . __('Ubicación del Proyecto', 'reformas-theme') . '</label></p>';
    echo '<input type="text" id="proyecto_ubicacion" name="proyecto_ubicacion" value="' . esc_attr( $ubicacion ) . '" style="width:100%;" />';

        // NUEVO CAMPO: Trabajos Realizados en la Reforma (editor)
        echo '<p><label for="proyecto_trabajos">' . __('Trabajos realizados en la reforma', 'reformas-theme') . '</label></p>';
        wp_editor( $trabajos, 'proyecto_trabajos', array(
            'textarea_name' => 'proyecto_trabajos',
            'media_buttons' => false,
            'teeny'         => true,
            'textarea_rows' => 7,
        ));
    
        // NUEVO CAMPO: Materiales Empleados (editor)
        echo '<p><label for="proyecto_materiales">' . __('Materiales empleados', 'reformas-theme') . '</label></p>';
        wp_editor( $materiales, 'proyecto_materiales', array(
            'textarea_name' => 'proyecto_materiales',
            'media_buttons' => false,
            'teeny'         => true,
            'textarea_rows' => 7,
        ));

    // Campo: Checkbox Destacado
    echo '<p>';
    echo '<label for="proyecto_destacado">' . __('Destacar en Home', 'reformas-theme') . '</label> ';
    echo '<input type="checkbox" id="proyecto_destacado" name="proyecto_destacado" value="yes" ' . checked( $destacado, 'yes', false ) . ' />';
    echo '</p>';
}


function proyecto_save_meta( $post_id ) {
  // Verificar nonce
  if ( ! isset($_POST['proyecto_detalles_nonce']) || ! wp_verify_nonce($_POST['proyecto_detalles_nonce'], 'proyecto_detalles_nonce') ) {
      return;
  }
  // Evitar autosave
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
      return;
  }
  // Comprobar permisos
  if ( isset($_POST['post_type']) && 'proyecto' == $_POST['post_type'] ) {
      if ( ! current_user_can('edit_post', $post_id) ) {
          return;
      }
  }

  // Guardar descripción
  if ( isset($_POST['proyecto_descripcion']) ) {
      update_post_meta( $post_id, '_proyecto_descripcion', sanitize_textarea_field( $_POST['proyecto_descripcion'] ) );
  }
  // Guardar galería
  if ( isset($_POST['proyecto_galeria']) ) {
      update_post_meta( $post_id, '_proyecto_galeria', sanitize_text_field( $_POST['proyecto_galeria'] ) );
  }
  // Guardar fecha
  if ( isset($_POST['proyecto_fecha']) ) {
      update_post_meta( $post_id, '_proyecto_fecha', sanitize_text_field( $_POST['proyecto_fecha'] ) );
  }
  // Guardar ubicación
  if ( isset($_POST['proyecto_ubicacion']) ) {
      update_post_meta( $post_id, '_proyecto_ubicacion', sanitize_text_field( $_POST['proyecto_ubicacion'] ) );
  }
     // Guardar Trabajos Realizados (permitiendo HTML seguro)
     if ( isset($_POST['proyecto_trabajos']) ) {
        update_post_meta( $post_id, '_proyecto_trabajos', wp_kses_post($_POST['proyecto_trabajos']) );
    }
    // Guardar Materiales Empleados (permitiendo HTML seguro)
    if ( isset($_POST['proyecto_materiales']) ) {
        update_post_meta( $post_id, '_proyecto_materiales', wp_kses_post($_POST['proyecto_materiales']) );
    }
  // Guardar el checkbox: si está marcado, guardamos "yes", si no, lo dejamos vacío
  if ( isset($_POST['proyecto_destacado']) && $_POST['proyecto_destacado'] == 'yes' ) {
    update_post_meta( $post_id, '_proyecto_destacado', 'yes' );
} else {
    update_post_meta( $post_id, '_proyecto_destacado', '' );
}
}
add_action( 'save_post', 'proyecto_save_meta' );

function proyecto_enqueue_admin_scripts($hook) {
  global $post;
  if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
      if ( isset($post) && 'proyecto' === $post->post_type ) {
          wp_enqueue_media();
          wp_enqueue_script( 'proyecto-admin-js', get_stylesheet_directory_uri() . '/js/proyecto-admin.js', array('jquery'), '1.0', true );
      }
  }
}
add_action('admin_enqueue_scripts', 'proyecto_enqueue_admin_scripts');
