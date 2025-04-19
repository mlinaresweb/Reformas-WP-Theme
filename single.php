<?php
// single.php — Plantilla de entrada individual
get_header(); ?>

<main class="single-post">
  <div class="wrapper-contenido">

    <?php
    if ( have_posts() ) {
      while ( have_posts() ) {
        the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

          <!-- Título -->
          <header class="post-header">
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-meta">
              <span class="meta-date"><?php echo get_the_date(); ?></span>
              <span class="meta-author">por <?php the_author_posts_link(); ?></span>
              <span class="meta-cats">
                en <?php the_category(', '); ?>
              </span>
            </div>
          </header>

          <!-- Imagen Destacada -->
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-thumb">
              <?php the_post_thumbnail('large'); ?>
            </div>
          <?php endif; ?>

          <!-- Contenido -->
          <div class="post-content">
            <?php
              the_content();
              wp_link_pages(array(
                'before' => '<nav class="page-links">Páginas:',
                'after'  => '</nav>',
              ));
            ?>
          </div>

          <!-- Paginación anterior/siguiente -->
          <nav class="post-navigation">
            <div class="nav-prev"><?php previous_post_link('« %link'); ?></div>
            <div class="nav-next"><?php next_post_link('%link »'); ?></div>
          </nav>

          <!-- Comentarios (si los usas) -->
          <?php
            if ( comments_open() || get_comments_number() ) {
              comments_template();
            }
          ?>

        </article>

      <?php }
    }
    ?>

  </div>
</main>

<?php get_footer(); ?>
