<?php
/**
 * Shortcode  [custom_contact_form]
 * y manejo del envío con registro en Google Sheets
 */

/*---------------------------------------------------
  FORMULARIO
----------------------------------------------------*/
function reformas_render_contact_form() {
	ob_start(); ?>

<div id="contact-form-wrap">
	<form method="post"
	      action="<?php echo esc_url( admin_url('admin-post.php') ); ?>"
	      class="custom-contact-form">

		<?php wp_nonce_field( 'contact_form_action', 'contact_form_nonce' ); ?>
		<input type="hidden" name="action"  value="reformas_handle_contact">
		<input type="text"    name="website" style="display:none"><!-- honeypot -->

		<p><label>Nombre
			<input type="text" name="contact_name" required maxlength="80"></label></p>

		<p><label>Email
			<input type="email" name="contact_email" required maxlength="80"></label></p>

		<p><label>Teléfono
			<input type="tel" name="contact_phone" maxlength="30"></label></p>

		<p><label>Asunto
			<input type="text" name="contact_subject" required maxlength="120"></label></p>

		<p><label>Mensaje
			<textarea name="contact_message" rows="5" required maxlength="1200"></textarea></label></p>

		<p><button type="submit" class="btn-contact-form">Enviar</button></p>
		<?php
            /* Aviso justo debajo del botón ------------------------- */
            if ( isset($_GET['sent']) && $_GET['sent']==='ok' ) {
                echo '<p class="contact-success" id="flash-msg">¡Mensaje enviado correctamente!</p>';
            }
            ?>
	</form>
	</div><!-- /#contact-form-wrap -->
	<?php
	return ob_get_clean();
}
add_shortcode( 'custom_contact_form', 'reformas_render_contact_form' );

/*---------------------------------------------------
  HANDLER
----------------------------------------------------*/
function reformas_handle_contact_form() {

	/* 1 Seguridad */
	if ( empty($_POST['contact_form_nonce']) ||
	     ! wp_verify_nonce($_POST['contact_form_nonce'], 'contact_form_action') ||
	     ! empty($_POST['website']) ) {
		wp_die( 'Petición no válida.' );
	}

	/* 2 Datos limpios */
	$name    = sanitize_text_field( $_POST['contact_name']    ?? '' );
	$email   = sanitize_email     ( $_POST['contact_email']   ?? '' );
	$phone   = sanitize_text_field( $_POST['contact_phone']   ?? '' );
	$subject = sanitize_text_field( $_POST['contact_subject'] ?? '' );
	$message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

	/* 3 E-mail */
	$to      = 'm.s.rapbarcelona@gmail.com';       // ← tu email
	$headers = [
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: '.$name.' <'.$email.'>',
	];
	$body = "
		<strong>Nombre:</strong> {$name}<br>
		<strong>Email:</strong> {$email}<br>
		<strong>Teléfono:</strong> {$phone}<br>
		<strong>Mensaje:</strong><br>".nl2br($message);

	wp_mail( $to, $subject, $body, $headers );

	/* 4 Añadir fila en Google Sheets */
	reformas_append_to_sheet( [
		current_time( 'Y-m-d H:i' ),
		$name, $email, $phone, $subject, $message
	] );

/* 5 · Redirect flash + ancla */
$ref = wp_get_referer();
$ref = remove_query_arg( 'sent', $ref );
$ref = add_query_arg   ( 'sent', 'ok', $ref );
$ref .= '#contact-form-wrap';             // ancla

wp_safe_redirect( esc_url_raw( $ref ) );
  exit;
}
add_action( 'admin_post_nopriv_reformas_handle_contact',
            'reformas_handle_contact_form' );
add_action( 'admin_post_reformas_handle_contact',
            'reformas_handle_contact_form' );

/*---------------------------------------------------
  SHEETS
----------------------------------------------------*/
function reformas_append_to_sheet( array $values ){

	$vendor = dirname( __DIR__, 2 ) . '/vendor/autoload.php';
	$credPath = defined('GOOGLE_CREDENTIALS') ? GOOGLE_CREDENTIALS : '';
	$sheetId  = defined('GOOGLE_SHEET_ID')    ? GOOGLE_SHEET_ID    : '';
	$sheetRange  = defined('GOOGLE_SHEET_RANGE')    ? GOOGLE_SHEET_RANGE    : '';
	$ContactMail  = defined('CONTACT_TO_EMAIL')    ? CONTACT_TO_EMAIL    : '';

	if ( ! file_exists( $vendor ) || ! file_exists( $credPath ) ) {
		error_log('[Sheets] vendor o credenciales no encontrados');
		return;
	}
	require_once $vendor;

	$client = new Google_Client();
	$client->setAuthConfig( $credPath );                     // ① JSON de service-account
	$client->setScopes([
		Google_Service_Sheets::SPREADSHEETS,
		Google_Service_Sheets::DRIVE         // ② scopes de escritura
	]);

	$service = new Google_Service_Sheets( $client );

	$spreadsheetId = $sheetId;                               // ③ ID de la hoja de cálculo
	$range         = $sheetRange;                             // ④ rango de la hoja
	error_log('[Sheets] ID.....: '.$spreadsheetId);
	error_log('[Sheets] Range..: '.$range);
	error_log('[Sheets] Values.: '.wp_json_encode($values));

	$body = new Google_Service_Sheets_ValueRange([
		'values' => [$values],
	]);

	try {
		$service->spreadsheets_values->append(
			$spreadsheetId,
			$range,
			$body,
			['valueInputOption' => 'USER_ENTERED']
		);
		error_log('[Sheets] ✔ Fila añadida correctamente');
	} catch (Exception $e) {
		error_log('[Sheets] ERROR: '.$e->getMessage());
	}
}
/*---------------------------------------------------
  Cabeceras “From”
----------------------------------------------------*/
add_filter( 'wp_mail_from',      fn() => $ContactMail );
add_filter( 'wp_mail_from_name', fn() => get_bloginfo( 'name' ) );
