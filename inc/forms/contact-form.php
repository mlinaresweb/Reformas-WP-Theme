<?php
/**
 * Archivo: inc/forms/contact-form.php
 * Descripción: Componente de formulario de contacto reutilizable.
 */

// Función que genera el formulario y lo retorna como output buffering.
function render_custom_contact_form() {
    ob_start();
    // Mostrar un mensaje de éxito si se envió el formulario.
    if ( isset($_GET['contact_status']) && $_GET['contact_status'] === 'sent' ) {
        echo '<p class="contact-success">¡Tu mensaje ha sido enviado con éxito!</p>';
    }
    ?>
    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="custom-contact-form">
        <?php wp_nonce_field( 'custom_contact_form', 'custom_contact_nonce' ); ?>
        <input type="hidden" name="action" value="handle_custom_contact_form">
        <p>
            <label for="contact_name">Nombre</label>
            <input type="text" id="contact_name" name="contact_name" required>
        </p>
        <p>
            <label for="contact_email">Correo Electrónico</label>
            <input type="email" id="contact_email" name="contact_email" required>
        </p>
        <p>
            <label for="contact_subject">Asunto</label>
            <input type="text" id="contact_subject" name="contact_subject" required>
        </p>
        <p>
            <label for="contact_message">Mensaje</label>
            <textarea id="contact_message" name="contact_message" rows="5" required></textarea>
        </p>
        <p>
            <input type="submit" value="Enviar" class="btn-contact-form">
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'custom_contact_form', 'render_custom_contact_form' );


// Función para procesar el envío del formulario.
function handle_custom_contact_form() {
    // Verificar el nonce para seguridad
    if ( ! isset( $_POST['custom_contact_nonce'] ) || ! wp_verify_nonce( $_POST['custom_contact_nonce'], 'custom_contact_form' ) ) {
        wp_die('Error de seguridad. Por favor, inténtalo de nuevo.');
    }
    
    // Sanitizar y obtener los datos
    $name    = sanitize_text_field( $_POST['contact_name'] );
    $email   = sanitize_email( $_POST['contact_email'] );
    $subject = sanitize_text_field( $_POST['contact_subject'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );
    
    // Configurar el destinatario del correo (modifica esta dirección)
    $to = 'ghaedesigns@gmail.com'; 
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );
    
    // Cuerpo del correo
    $body  = '<p><strong>Nombre:</strong> ' . $name . '</p>';
    $body .= '<p><strong>Email:</strong> ' . $email . '</p>';
    $body .= '<p><strong>Mensaje:</strong><br>' . nl2br($message) . '</p>';
    
    // Enviar correo
    wp_mail( $to, $subject, $body, $headers );
    
    // Redirigir a la misma página con un parámetro que indique éxito
    $redirect_url = add_query_arg( 'contact_status', 'sent', wp_get_referer() );
    wp_redirect( $redirect_url );
    exit;
}
add_action( 'admin_post_nopriv_handle_custom_contact_form', 'handle_custom_contact_form' );
add_action( 'admin_post_handle_custom_contact_form', 'handle_custom_contact_form' );
