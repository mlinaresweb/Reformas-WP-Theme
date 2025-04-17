<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
  

  <!-- Menú principal -->
  <nav class="main-navigation">
    <div class="nav-container wrapper-contenido">
      <div class="logo">
        <!-- Tu logo o nombre del sitio -->
        <a href="<?php echo home_url(); ?>">
          <?php
            if ( has_custom_logo() ) {
              the_custom_logo();
            } else {
              bloginfo('name');
            }
          ?>
        </a>
      </div>

      <div class="menu-principal">
        <?php
          wp_nav_menu(array(
            'theme_location'  => 'menu_principal',
            'container'       => false,
            'menu_class'      => 'nav-menu',
          ));
        ?>
      </div>
    </div>
  </nav>
</header>
