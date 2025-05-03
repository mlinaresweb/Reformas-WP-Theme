<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
  // 1) Obtenemos los términos de la taxonomía para los submenús móviles
  $terms = get_terms([
    'taxonomy'   => 'servicio',
    'hide_empty' => false,
  ]);

  // 2) Detectamos término actual de servicio
  if ( is_tax('servicio') ) {
    $current_term = get_queried_object();
  } elseif ( is_singular('proyecto') ) {
    $proj_terms   = wp_get_post_terms( get_the_ID(), 'servicio' );
    $current_term = ! empty($proj_terms) ? $proj_terms[0] : null;
  } else {
    $current_term = null;
  }

  // 3) Detectamos slug de la página /proyectos-xxx/ si aplica
  $page_slug = get_post_field( 'post_name', get_queried_object_id() );
?>

<header class="site-header">
  <div class="wrapper-contenido nav-container">
    <!-- Logo -->
    <div class="logo">
      <a href="<?php echo home_url(); ?>">
        <?php if ( has_custom_logo() ) the_custom_logo(); else bloginfo('name'); ?>
      </a>
    </div>

    <!-- Botón hamburguesa (mobile) -->
    <button class="nav-toggle" aria-label="Abrir menú">
      <span></span><span></span><span></span>
    </button>

    <!-- Menú Desktop -->
    <nav class="main-navigation desktop-menu">
      <?php wp_nav_menu([
        'theme_location'=>'menu_principal',
        'container'     => false,
        'menu_class'    => 'nav-menu'
      ]); ?>
    </nav>
  </div>

  <!-- Overlay menú Mobile -->
  <div class="mobile-menu-overlay">
    <div class="mobile-menu-header">
      <div class="logo">
        <a href="<?php echo home_url(); ?>">
          <?php if ( has_custom_logo() ) the_custom_logo(); else bloginfo('name'); ?>
        </a>
      </div>
      <button class="nav-close" aria-label="Cerrar menú">&times;</button>
    </div>

    <!-- Nivel 0: enlaces principales -->
    <nav class="main-navigation mobile-menu menu-level-0">
      <ul class="nav-menu">
        <?php
        wp_nav_menu([
          'theme_location'=>'menu_principal',
          'container'     => false,
          'items_wrap'    => '%3$s',
          'depth'         => 1,
        ]);
        ?>
      </ul>
    </nav>

<!-- Nivel 1 : Sub‑menú SERVICIOS -->
<nav class="main-navigation mobile-menu menu-level-1 servicios-submenu" style="display:none">
  <div class="mobile-submenu-header">
    <span class="mobile-submenu-title">Servicios</span>
  </div>
  <ul class="nav-menu">
    <?php foreach( $terms as $t ):
        $is_active = ( $current_term && $current_term->slug === $t->slug )
                     ? 'current-menu-item' : '';

        $url = get_term_link( $t );         
    ?>
      <li class="<?php echo esc_attr( $is_active ); ?>">
        <a href="<?php echo esc_url( $url ); ?>">
          <?php echo esc_html( $t->name ); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <button class="submenu-back">&larr; Volver</button>
</nav>


    <!-- Nivel 1: Submenu Proyectos -->
    <nav class="main-navigation mobile-menu menu-level-1 proyectos-submenu" style="display:none">
      <div class="mobile-submenu-header">       
        <span class="mobile-submenu-title">Proyectos</span>
      </div>
      <ul class="nav-menu">
        <?php
        $expected_slug = 'reformas-' . str_replace('proyectos-','',$page_slug);
        foreach( $terms as $t ):
          $is_active = ( 
            ( $current_term && $current_term->slug === $t->slug ) ||
            ( $expected_slug === $t->slug )
          ) ? 'current-menu-item' : '';
          $url = site_url('/proyectos-'. str_replace('reformas-','',$t->slug) .'/');
        ?>
          <li class="<?php echo esc_attr( $is_active ); ?>">
            <a href="<?php echo esc_url($url); ?>">
              Proyectos de <?php echo esc_html( $t->name ); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <button class="submenu-back">&larr; Volver</button>
    </nav>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const overlay       = document.querySelector('.mobile-menu-overlay');
  const toggle        = document.querySelector('.nav-toggle');
  const closeBtn      = document.querySelector('.nav-close');
  const lvl0          = overlay.querySelector('.menu-level-0');
  const serviciosView = overlay.querySelector('.servicios-submenu');
  const proyectosView = overlay.querySelector('.proyectos-submenu');
  const backButtons   = overlay.querySelectorAll('.submenu-back');

  function showLevel0(){
    lvl0.style.display         = 'block';
    serviciosView.style.display = 'none';
    proyectosView.style.display  = 'none';
  }
  function showServicios(){
    lvl0.style.display         = 'none';
    serviciosView.style.display = 'flex';
    proyectosView.style.display  = 'none';
  }
  function showProyectos(){
    lvl0.style.display         = 'none';
    serviciosView.style.display = 'none';
    proyectosView.style.display  = 'flex';
  }

  // Añadimos el toggle (►) solo al abrir el menú
  function markHasChildren() {
    const arrowSvg = '<svg viewBox="0 0 20 20" width="20" height="20" class="menu-arrow-svg arrow-tipos"><path d="M5 8l5 5 5-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    lvl0.querySelectorAll('li > a').forEach(a => {
      const txt = a.textContent.trim();
      if ( txt === 'Servicios' || txt === 'Proyectos' ) {
        const li = a.parentElement;
        if ( ! li.classList.contains('menu-item-has-children') ) {
          li.classList.add('menu-item-has-children');
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'submenu-toggle';
          btn.setAttribute('aria-label','Abrir submenú '+txt);
          btn.innerHTML = arrowSvg;
          a.insertAdjacentElement('afterend', btn);
          btn.addEventListener('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            txt === 'Servicios' ? showServicios() : showProyectos();
          });
        }
      }
    });
  }

  toggle.addEventListener('click', () => {
    overlay.classList.add('active');
    showLevel0();
    markHasChildren();
  });
  closeBtn.addEventListener('click', () => {
    overlay.classList.remove('active');
    showLevel0();
  });
  overlay.addEventListener('click', e => {
    if ( e.target === overlay ) {
      overlay.classList.remove('active');
      showLevel0();
    }
  });
  backButtons.forEach(btn => btn.addEventListener('click', showLevel0) );

  // Inicializar
  showLevel0();
});
</script>
