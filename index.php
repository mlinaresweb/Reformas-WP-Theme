<?php get_header(); ?>

<main id="site-content">
  <?php
    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post();
        the_content(); // Muestra el contenido principal (bloques) 
      }
    }
  ?>
</main>

<?php get_footer(); ?>
