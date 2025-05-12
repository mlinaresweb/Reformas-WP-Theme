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
	<form id="contact-form" class="custom-contact-form" novalidate
	      action="<?php echo esc_url( admin_url('admin-post.php') ); ?>"
	      method="post">

		<?php wp_nonce_field( 'contact_form_action', 'contact_form_nonce' ); ?>
		<input type="hidden" name="action" value="reformas_handle_contact_ajax">
		<input type="text" name="website" class="hp-field contact-hidden" tabindex="-1">

		<p><label>Nombre
			<input type="text" name="contact_name" required minlength="2"
			       maxlength="80" pattern="[A-Za-zÀ-ÿ '\-]+"></label>
			<span class="error-msg" aria-live="polite"></span>
		</p>

		<p><label>Email
			<input type="email" name="contact_email" required maxlength="80"></label>
			<span class="error-msg" aria-live="polite"></span>
		</p>

		<p>
  <label>Teléfono
  <input type="tel"
           name="contact_phone"
           pattern="[0-9]{9}"   
           minlength="9"
           maxlength="9"
		   style="height: 40px; border: 1px solid #dddddd";
>
  </label>
  <span class="error-msg" aria-live="polite"></span>
</p>

		<p><label>Asunto
			<input type="text" name="contact_subject" required
			       minlength="3" maxlength="120"></label>
			<span class="error-msg" aria-live="polite"></span>
		</p>

		<p><label>Mensaje
			<textarea name="contact_message" rows="5" required
			          minlength="10" maxlength="1200"></textarea></label>
			<span class="error-msg" aria-live="polite"></span>
		</p>

		<!-- reCAPTCHA v3  -->
		<!-- <input type="hidden" name="recaptcha_token" id="recaptchaToken"> -->

		<!-- Aceptación de la política de privacidad -->
<p class="checkbox-privacy">
  <label style="display: flex; justify-content: flex-start; align-items: flex-start; flex-direction: row;flex-wrap: wrap;">
    <input type="checkbox"
           name="privacy_accept"
           required
           aria-required="true"
           value="1" style="width: max-content;margin-right: 10px;">
    He leído y acepto la 
     <a href="/politica-privacidad/"
       target="_blank"
       rel="noopener noreferrer"
	   class="enlace-form">
        Política&nbsp;de&nbsp;Privacidad</a>.
  </label>
  <span class="error-msg" aria-live="polite"></span>
</p>

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
function reformas_handle_contact_ajax() {

	/* 1 ▸ Seguridad */
	if ( empty($_POST['contact_form_nonce']) ||
	     ! wp_verify_nonce($_POST['contact_form_nonce'],'contact_form_action') ||
	     ! empty($_POST['website']) ) {
		wp_send_json_error( ['message'=>'Petición no válida.'], 400 );
	}

	/* 2 ▸ Rate-limit 90 s/IP */
	$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if ( get_transient("contact_lock_$ip") ) {
	wp_send_json_error(
		[
			'message'      => 'Por favor, espera 90 segundos antes de volver a enviar.',
			'retry_after'  => 90            
		],
		429
	);
}
set_transient("contact_lock_$ip", '1', 90);

	/* 3 ▸ Sanitizar + validar */
	$name  = trim( wp_unslash($_POST['contact_name']  ?? '') );
	$email = trim( wp_unslash($_POST['contact_email'] ?? '') );
	$phone = trim( wp_unslash($_POST['contact_phone'] ?? '') );
	$subj  = trim( wp_unslash($_POST['contact_subject'] ?? '') );
	$msg   = trim( wp_unslash($_POST['contact_message'] ?? '') );
	$privacy = isset($_POST['privacy_accept']) ? '1' : '0';

	$errors = [];
	if ( ! preg_match('/^[A-Za-zÀ-ÿ \'\-]{2,80}$/', $name) )   $errors[] = 'Nombre';
	if ( ! is_email($email) )                                  $errors[] = 'Email';
	if ( $phone && ! preg_match('/^[0-9+\s().-]{6,30}$/',$phone) ) $errors[]='Teléfono';
	if ( strlen($subj) < 3 )                                   $errors[] = 'Asunto';
	if ( strlen($msg)  < 10 )                                  $errors[] = 'Mensaje';
	if ( $privacy !== '1' )                                    $errors[] = 'Política de privacidad';

	if ( $errors ) {
		wp_send_json_error(
			['message'=>'Error en: '.implode(', ', $errors)],
			422
		);
	}

	/* 4 ▸ E-mail */
	$to      = defined('CONTACT_TO_EMAIL') ? CONTACT_TO_EMAIL : get_option('admin_email');
	$headers = [
		'Content-Type: text/html; charset=UTF-8',
		"Reply-To: $name <$email>",
	];
	$body = "<strong>Nombre:</strong> ".esc_html($name)."<br>
	         <strong>Email:</strong> ".esc_html($email)."<br>
	         <strong>Teléfono:</strong> ".esc_html($phone)."<br>
	         <strong>Mensaje:</strong><br>".nl2br(esc_html($msg))."<br>";

	wp_mail( $to, $subj, $body, $headers );

	/* 5 ▸ Google Sheets (opcionalmente añade 'Sí' al final) */
	reformas_append_to_sheet([
		current_time('Y-m-d H:i'),
		$name, $email, $phone, $subj, $msg
	]);

	/* 6 ▸ Respuesta JSON OK */
	wp_send_json_success( ['message'=>'¡Mensaje enviado correctamente!'] );
}

add_action( 'wp_ajax_nopriv_reformas_handle_contact_ajax',
            'reformas_handle_contact_ajax' );
add_action( 'wp_ajax_reformas_handle_contact_ajax',
            'reformas_handle_contact_ajax' );


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
	$client->setAuthConfig( $credPath );                   
	$client->setScopes([
		Google_Service_Sheets::SPREADSHEETS,
		Google_Service_Sheets::DRIVE        
	]);

	$service = new Google_Service_Sheets( $client );

	$spreadsheetId = $sheetId;                             
	$range         = $sheetRange;                            
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
$ContactMail  = defined('CONTACT_TO_EMAIL')    ? CONTACT_TO_EMAIL    : '';
add_filter( 'wp_mail_from',      fn() => $ContactMail );
add_filter( 'wp_mail_from_name', fn() => get_bloginfo( 'name' ) );
