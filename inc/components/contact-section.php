<?php
/**
 * Componente: Sección de Contacto
 * Shortcode [contact_section]
 */

function render_contact_section( $atts ) {
  $atts = shortcode_atts( array(
    // Permite cambiar la imagen desde el shortcode si lo deseas
    'image_url'   => site_url('/wp-content/uploads/reforma-contacto.webp'),
    // Texto del encabezado
    'heading'     => 'Pide Tu Presupuesto',
    // Shortcode del formulario a usar
    'form_shortcode' => '[custom_contact_form]',
  ), $atts, 'contact_section' );

  ob_start();
  ?>
  <section class="seccion-contacto">
    <div class="wrapper-contenido">
      <div class="contact-layout">
        <!-- Columna Izquierda: Imagen -->
        <div class="contact-image" style="background-image: url('<?php echo esc_url( $atts['image_url'] ); ?>');">
          <!-- fondo -->
        </div>
        <!-- Columna Derecha: Formulario -->
        <div class="contact-form">
          <h2><?php echo esc_html( $atts['heading'] ); ?></h2>
          <?php echo do_shortcode( $atts['form_shortcode'] ); ?>
        </div>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
add_shortcode( 'contact_section', 'render_contact_section' );
