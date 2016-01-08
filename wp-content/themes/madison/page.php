<?php get_header(); ?>
<div id="primary" class="content-area">
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
<?php get_footer(); ?>