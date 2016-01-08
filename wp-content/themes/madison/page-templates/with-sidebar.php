<?php
/*
  Template Name: With sidebar
 */
?>

<?php get_header(); ?>

<div class="container active-sidebar">
    <div id="primary" class="content-area">
        <?php
        $options = get_option(AZEXO_THEME_NAME);
        if ($options['show_page_title']) {
            get_template_part('template-parts/general', 'title');
        }
        ?>
        <div id="content" class="site-content" role="main">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', AZEXO_THEME_NAME) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
                    </div><!-- .entry-content -->
                    <footer class="entry-meta">
                    </footer><!-- .entry-meta -->
                </article><!-- #post -->
                <?php
                if (comments_open())
                    comments_template();
                ?>
            <?php endwhile; ?>
        </div><!-- #content -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>