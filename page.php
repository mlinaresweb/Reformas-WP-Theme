<?php get_header(); ?>

<main id="site-content">
  <?php
    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post();
        ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
          <h1 class="page-title"><?php the_title(); ?></h1>
          <div class="page-content">
            <?php the_content(); // El contenido de bloques ?>
          </div>
        </article>
        <?php
      }
    }
  ?>
</main>

<?php get_footer(); ?>
