<?php
/**
 * Componente: SEO + CTA
 * Shortcode [seo_cta]
 */

function render_seo_cta_section( $atts ) {
  // atributos por si quieres personalizar en el futuro
  $atts = shortcode_atts( array(
    'button_text' => 'Pide Presupuesto',
    'button_url'  => site_url('/contacto/'),
  ), $atts, 'seo_cta' );

  ob_start();
  ?>
  <section class="seo-text-section">
    <div class="wrapper-contenido">
      <p class="seo-text">
        ¡Transforma tu hogar con nuestros servicios de reformas en Barcelona y alrededores!
      </p>
      <p class="seo-text">
        ¿Estás pensando en renovar tu hogar? <span class="span-marron"> ¡Nosotros te ayudamos! </span>
      </p>
      <p class="seo-text seo-text-last">
        En El Amrani Khalid Reformas somos expertos en reformas integrales y servicios especializados en albañilería, carpintería, fontanería, electricidad y pintura. Nos encargamos de cada detalle de tu proyecto para ofrecerte soluciones personalizadas, profesionales y de alta calidad. Trabajamos en toda el área de Barcelona y alrededores, adaptándonos a tus necesidades y presupuesto.
      </p>
      <a href="<?php echo esc_url( $atts['button_url'] ); ?>" class="btn-cta-presupuesto">
        <?php echo esc_html( $atts['button_text'] ); ?>
      </a>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
add_shortcode( 'seo_cta', 'render_seo_cta_section' );
