<?php
// home.php — Página principal del blog
get_header(); 
$banner_field = get_field('banner_image'); // ACF devuelve array si es tipo imagen
if ( $banner_field && isset($banner_field['url']) ) {
  $banner_url = $banner_field['url'];
} else {
  // fallback a imagen por defecto
  $banner_url = site_url('/wp-content/uploads/carpinteria.webp');
}


?>

<main class="blog-grid">
<section class="proyecto-banner" style="background-image: url('<?php echo esc_url($banner_url); ?>');">
  <div class="banner-content">
    <h1>Blog</h1>
  </div>
</section>
  <div class="wrapper-contenido">

    <?php
    // Paginación
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    // Query de posts: 6 por página
    $args = array(
      'post_type'      => 'post',
      'posts_per_page' => 6,
      'paged'          => $paged,
    );
    $blog_query = new WP_Query($args);

    if ( $blog_query->have_posts() ) :
    ?>

      <div class="blog-grid-container">
        <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
          <article <?php post_class('blog-card'); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php the_permalink(); ?>" class="card-image">
                <?php the_post_thumbnail('medium_large'); ?>
              </a>
            <?php endif; ?>

            <div class="card-content">
              <h2 class="card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>

              <div class="card-excerpt">
                <?php 
                  // 20 palabras + "..."
                  echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20, '...' ) );
                ?>
              </div>

              <a href="<?php the_permalink(); ?>" class="read-more">Ver Más</a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <nav class="pagination-blog">
        <?php
          echo paginate_links( array(
            'total'   => $blog_query->max_num_pages,
            'current' => $paged,
            'format'  => '?paged=%#%',
            'prev_text' => '« Anterior',
            'next_text' => 'Siguiente »',
            'type'    => 'list',
          ) );
        ?>
      </nav>

    <?php else: ?>
      <p>No hay entradas de blog todavía.</p>
    <?php endif;

    wp_reset_postdata();
    ?>

  </div>
</main>

<?php get_footer(); ?>
