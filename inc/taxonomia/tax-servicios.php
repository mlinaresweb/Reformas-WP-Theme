<?php


//Taxonomia Servicios
function register_servicio_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Servicios', 'Taxonomy General Name', 'reformas-theme' ),
        'singular_name'              => _x( 'Servicio', 'Taxonomy Singular Name', 'reformas-theme' ),
        'menu_name'                  => __( 'Servicios', 'reformas-theme' ),
        'all_items'                  => __( 'Todos los Servicios', 'reformas-theme' ),
        'parent_item'                => __( 'Servicio Padre', 'reformas-theme' ),
        'parent_item_colon'          => __( 'Servicio Padre:', 'reformas-theme' ),
        'new_item_name'              => __( 'Nuevo Servicio', 'reformas-theme' ),
        'add_new_item'               => __( 'Añadir Nuevo Servicio', 'reformas-theme' ),
        'edit_item'                  => __( 'Editar Servicio', 'reformas-theme' ),
        'update_item'                => __( 'Actualizar Servicio', 'reformas-theme' ),
        'view_item'                  => __( 'Ver Servicio', 'reformas-theme' ),
        'separate_items_with_commas' => __( 'Separar servicios con comas', 'reformas-theme' ),
        'add_or_remove_items'        => __( 'Añadir o quitar servicios', 'reformas-theme' ),
        'choose_from_most_used'      => __( 'Elegir entre los más usados', 'reformas-theme' ),
        'popular_items'              => __( 'Servicios Populares', 'reformas-theme' ),
        'search_items'               => __( 'Buscar Servicios', 'reformas-theme' ),
        'not_found'                  => __( 'No se encontraron servicios', 'reformas-theme' ),
        'no_terms'                   => __( 'No hay servicios', 'reformas-theme' ),
        'items_list'                 => __( 'Lista de servicios', 'reformas-theme' ),
        'items_list_navigation'      => __( 'Navegación de lista de servicios', 'reformas-theme' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true, // Se comporta como categorías
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true, // Para Gutenberg
    );
    register_taxonomy( 'servicio', array( 'proyecto' ), $args );
}
add_action( 'init', 'register_servicio_taxonomy', 0 );

/**
 * Registra campos meta para el término.
 */
function register_servicio_term_meta() {
    register_term_meta( 'servicio', 'banner_image', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ) );
    register_term_meta( 'servicio', 'banner_heading', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ) );
    register_term_meta( 'servicio', 'banner_subheading', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ) );
    register_term_meta( 'servicio', 'intro_text', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ) );
    register_term_meta( 'servicio', 'side_image', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'register_servicio_term_meta' );

// Agregar campos en el formulario "añadir nuevo" para el término "servicio"
function add_servicio_meta_fields() {
    ?>
    <div class="form-field term-banner-image-wrap">
        <label for="banner-image"><?php _e( 'Imagen de Banner', 'reformas-theme' ); ?></label>
        <input type="text" name="banner_image" id="banner-image" value="" placeholder="URL de la imagen del banner">
        <p class="description"><?php _e( 'Ingresa o selecciona la imagen que se usará como banner para este servicio.', 'reformas-theme' ); ?></p>
        <input type="button" id="banner-image_btn" class="button" value="<?php _e('Seleccionar Imagen', 'reformas-theme'); ?>">
    </div>
    <div class="form-field term-banner-heading-wrap">
        <label for="banner-heading"><?php _e( 'Título del Banner', 'reformas-theme' ); ?></label>
        <input type="text" name="banner_heading" id="banner-heading" value="" placeholder="Título del banner">
        <p class="description"><?php _e( 'Ingresa el título principal para el banner.', 'reformas-theme' ); ?></p>
    </div>
    <div class="form-field term-banner-subheading-wrap">
        <label for="banner-subheading"><?php _e( 'Subtítulo del Banner', 'reformas-theme' ); ?></label>
        <input type="text" name="banner_subheading" id="banner-subheading" value="" placeholder="Subtítulo del banner">
        <p class="description"><?php _e( 'Ingresa un subtítulo para el banner.', 'reformas-theme' ); ?></p>
    </div>
    <div class="form-field term-intro-text-wrap">
        <label for="intro-text"><?php _e( 'Texto Introductorio', 'reformas-theme' ); ?></label>
        <?php 
         wp_editor( '', 'intro-text', array(
                'textarea_name' => 'intro_text',
                'media_buttons' => false,
                'teeny'         => true,
                'textarea_rows' => 10,
         ) ); 
        ?>
        <p class="description"><?php _e( 'Este texto se mostrará en la página del servicio para describirlo en detalle.', 'reformas-theme' ); ?></p>
    </div>
    <div class="form-field term-side-image-wrap">
        <label for="side-image"><?php _e( 'Imagen Lateral', 'reformas-theme' ); ?></label>
        <input type="text" name="side_image" id="side-image" value="" placeholder="URL de la imagen lateral">
        <p class="description"><?php _e( 'Ingresa o selecciona la imagen que se mostrará junto al texto introductorio.', 'reformas-theme' ); ?></p>
        <input type="button" id="side-image_btn" class="button" value="<?php _e('Seleccionar Imagen', 'reformas-theme'); ?>">
    </div>
    <?php
}
add_action( 'servicio_add_form_fields', 'add_servicio_meta_fields' );



function edit_servicio_meta_fields($term) {
    $banner_image = get_term_meta( $term->term_id, 'banner_image', true );
    $banner_heading = get_term_meta( $term->term_id, 'banner_heading', true );
    $banner_subheading = get_term_meta( $term->term_id, 'banner_subheading', true );
    $intro_text = get_term_meta( $term->term_id, 'intro_text', true );
    $side_image = get_term_meta( $term->term_id, 'side_image', true );
    ?>
    <tr class="form-field term-banner-image-wrap">
      <th scope="row"><label for="banner-image"><?php _e( 'Imagen de Banner', 'reformas-theme' ); ?></label></th>
      <td>
        <input type="text" name="banner_image" id="banner-image" value="<?php echo esc_attr($banner_image); ?>" placeholder="URL de la imagen del banner">
        <p class="description"><?php _e( 'Ingresa o selecciona la imagen que se usará como banner para este servicio.', 'reformas-theme' ); ?></p>
        <input type="button" id="banner-image_btn" class="button" value="<?php _e('Seleccionar Imagen', 'reformas-theme'); ?>">
      </td>
    </tr>
    <tr class="form-field term-banner-heading-wrap">
      <th scope="row"><label for="banner-heading"><?php _e( 'Título del Banner', 'reformas-theme' ); ?></label></th>
      <td>
        <input type="text" name="banner_heading" id="banner-heading" value="<?php echo esc_attr($banner_heading); ?>" placeholder="Título del banner">
        <p class="description"><?php _e( 'Ingresa el título principal para el banner.', 'reformas-theme' ); ?></p>
      </td>
    </tr>
    <tr class="form-field term-banner-subheading-wrap">
      <th scope="row"><label for="banner-subheading"><?php _e( 'Subtítulo del Banner', 'reformas-theme' ); ?></label></th>
      <td>
        <input type="text" name="banner_subheading" id="banner-subheading" value="<?php echo esc_attr($banner_subheading); ?>" placeholder="Subtítulo del banner">
        <p class="description"><?php _e( 'Ingresa un subtítulo para el banner.', 'reformas-theme' ); ?></p>
      </td>
    </tr>
    <tr class="form-field term-intro-text-wrap">
      <th scope="row"><label for="intro-text"><?php _e( 'Texto Introductorio', 'reformas-theme' ); ?></label></th>
      <td>
        <?php 
            wp_editor( $intro_text, 'intro-text', array(
                'textarea_name' => 'intro_text',
                'media_buttons' => false,
                'teeny'         => true,
                'textarea_rows' => 10,
            ) );
        ?>
        <p class="description"><?php _e( 'Este texto se mostrará en la página del servicio para describirlo en detalle.', 'reformas-theme' ); ?></p>
      </td>
    </tr>
    <tr class="form-field term-side-image-wrap">
      <th scope="row"><label for="side-image"><?php _e( 'Imagen Lateral', 'reformas-theme' ); ?></label></th>
      <td>
        <input type="text" name="side_image" id="side-image" value="<?php echo esc_attr($side_image); ?>" placeholder="URL de la imagen lateral">
        <p class="description"><?php _e( 'Ingresa o selecciona la imagen que se mostrará junto al texto introductorio.', 'reformas-theme' ); ?></p>
        <input type="button" id="side-image_btn" class="button" value="<?php _e('Seleccionar Imagen', 'reformas-theme'); ?>">
      </td>
    </tr>
    <?php
}
add_action( 'servicio_edit_form_fields', 'edit_servicio_meta_fields' );




function save_servicio_meta_fields($term_id) {
    if ( isset($_POST['banner_image']) )
        update_term_meta( $term_id, 'banner_image', sanitize_text_field($_POST['banner_image']) );
    if ( isset($_POST['banner_heading']) )
        update_term_meta( $term_id, 'banner_heading', sanitize_text_field($_POST['banner_heading']) );
    if ( isset($_POST['banner_subheading']) )
        update_term_meta( $term_id, 'banner_subheading', sanitize_text_field($_POST['banner_subheading']) );
    if ( isset($_POST['intro_text']) )
        update_term_meta( $term_id, 'intro_text', wp_kses_post($_POST['intro_text']) );  // Permite HTML seguro
    if ( isset($_POST['side_image']) )
        update_term_meta( $term_id, 'side_image', sanitize_text_field($_POST['side_image']) );
}
add_action( 'created_servicio', 'save_servicio_meta_fields' );
add_action( 'edited_servicio', 'save_servicio_meta_fields' );

