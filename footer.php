<footer class="site-footer">
  <div class="wrapper-contenido footer-columns">
    <!-- Columna 1: Logo + contacto + redes -->
    <div class="footer-column">
      <div class="footer-logo">
        <?php
          if ( has_custom_logo() ) {
            the_custom_logo();
          } else {
            echo '<a href="' . esc_url( home_url() ) . '">' . get_bloginfo('name') . '</a>';
          }
        ?>
      </div>
      <div class="footer-contact">
        <a href="tel:+34666666666">+34 666 666 666</a><br>
        <a href="mailto:correo@tudominio.com">correo@tudominio.com</a>
      </div>
      <div class="footer-social">
        <a href="https://wa.me/34666666666" target="_blank" rel="noopener">
          <img src="<?php echo site_url('/wp-content/uploads/whatsapp.png'); ?>" alt="WhatsApp">
        </a>
        <a href="https://facebook.com/tuPagina" target="_blank" rel="noopener">
          <img src="<?php echo site_url('/wp-content/uploads/facebook.png'); ?>" alt="Facebook">
        </a>
        <a href="https://instagram.com/tuPagina" target="_blank" rel="noopener">
          <img src="<?php echo site_url('/wp-content/uploads/instagram.png'); ?>" alt="Instagram">
        </a>
      </div>
    </div>

    <!-- Columna 2: Servicios -->
    <div class="footer-column">
      <h4>Servicios</h4>
      <ul class="footer-list">
        <?php
          $terms = get_terms([
            'taxonomy'   => 'servicio',
            'hide_empty' => false,
          ]);
          foreach ($terms as $term) {
            $link = get_term_link($term);
            echo '<li><a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a></li>';
          }
        ?>
      </ul>
    </div>

    <!-- Columna 3: Proyectos -->
    <div class="footer-column">
      <h4>Proyectos</h4>
      <ul class="footer-list">
        <?php
          foreach ($terms as $t) {
            $slug_clean = str_replace('reformas-', '', $t->slug);
            $url = site_url('/proyectos-' . $slug_clean . '/');
            echo '<li><a href="' . esc_url($url) . '">Proyectos de ' . esc_html($t->name) . '</a></li>';
          }
        ?>
      </ul>
    </div>

    <!-- Columna 4: Páginas + CTA -->
    <div class="footer-column footer-last-column">
      <h4>Páginas</h4>
      <ul class="footer-list">
        <li><a href="<?php echo esc_url(home_url('/contacto/')); ?>">Contacto</a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a></li>
      </ul>
      <p><a class="btn-footer-cta" href="<?php echo esc_url(home_url('/contacto/')); ?>">Pedir presupuesto</a></p>
    </div>


  </div>

  <div class="site-info">
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.</p>
    <p class="footer-legal">
      <a href="<?php echo esc_url(home_url('/aviso-legal/')); ?>">Aviso Legal</a> |
      <a href="<?php echo esc_url(home_url('/politica-privacidad/')); ?>">Política de Privacidad</a> |
      <a href="<?php echo esc_url(home_url('/politica-cookies/')); ?>">Política de Cookies</a>
    </p>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    /* scroll suave si hay hash — por si otros scripts lo impiden */
    if (location.hash === '#contact-form-wrap') {
        const el = document.querySelector(location.hash);
        if (el) el.scrollIntoView({behavior:'smooth'});
    }

    /* ocultar mensaje flash tras 10 s */
    const msg = document.getElementById('flash-msg');
    if (msg) setTimeout(() => msg.style.display = 'none', 10000);
});
</script>


<?php wp_footer(); ?>
</body>
</html>
<?php
add_action( 'wp_footer', function () {
	if ( file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
		$lines = array_slice( file( WP_CONTENT_DIR . '/debug.log' ), -20 );
		echo '<script>console.group("WP Debug");';
		foreach ( $lines as $l ) {
			echo 'console.log('.json_encode(trim($l)).');';
		}
		echo 'console.groupEnd();</script>';
	}
} );
?>