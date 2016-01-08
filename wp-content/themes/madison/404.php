<?php get_header(); ?>

<div class="container">
    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php $options = get_option(AZEXO_THEME_NAME); if($options['show_page_title']) get_template_part('template-parts/general', 'title') ?>
            <div class="page-wrapper">
                <div class="page-content">
                    <h2><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', AZEXO_THEME_NAME); ?></h2>
                    <p><?php _e('It looks like nothing was found at this location.', AZEXO_THEME_NAME); ?></p>
                </div><!-- .page-content -->
            </div><!-- .page-wrapper -->

        </div><!-- #content -->
    </div><!-- #primary -->
</div>
<?php get_footer(); ?>