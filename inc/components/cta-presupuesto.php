<?php
/**
 * Componente: CTA Presupuesto
 * Muestra un bloque con un texto SEO-friendly (1-2 frases) y un botón "Pide Presupuesto" 
 * que enlaza a la página de contacto.
 */
function render_cta_presupuesto() {
    ob_start();
    // Puedes definir el texto directamente aquí o recuperarlo de una opción.
    $cta_text = '¿Quieres transformar tu hogar con reformas integrales en Barcelona? Descubre nuestras soluciones personalizadas y de alta calidad.';
    // Obtén la URL de la página de contacto. 
    // Ajusta 'contacto' por el slug correcto de tu página de contacto.
    $contact_page = get_page_by_path('contacto'); 
    $contact_url = $contact_page ? get_permalink($contact_page->ID) : '#';
    ?>
    <section class="cta-presupuesto">
      <div class="wrapper-contenido">
         <p class="cta-text"><?php echo esc_html($cta_text); ?></p>
         <a href="<?php echo esc_url($contact_url); ?>" class="btn-cta-presupuesto">Pide Presupuesto</a>
      </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('cta_presupuesto', 'render_cta_presupuesto');
