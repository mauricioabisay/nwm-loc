<?php
$options = get_option(AZEXO_THEME_NAME);
if (!isset($show_sidebar))
    $show_sidebar = true;
get_header();
?>

<div class="container <?php print (is_active_sidebar('sidebar') && $show_sidebar ? 'active-sidebar' : ''); ?>">
    <div id="primary" class="content-area">
        <?php
        $options = get_option(AZEXO_THEME_NAME);
        if ($options['show_page_title']) {
            get_template_part('template-parts/general', 'title');
        }
        ?>
        <div id="content" class="site-content" role="main">
            <?php while (have_posts()) : the_post(); ?>
                <?php
                $options = get_option(AZEXO_THEME_NAME);
                if (isset($options['post_navigation']) && ($options['post_navigation'] == 'before'))
                    azexo_post_nav();
                ?>
                <?php get_template_part('content', get_post_format()); ?>                
                <?php
                if (isset($options['post_navigation']) && ($options['post_navigation'] == 'after'))
                    azexo_post_nav();
                ?>
                <?php
                if (function_exists('related_posts')) {
                    related_posts(array(
                        'template' => 'yarpp-template-default.php',
                    ));
                }
                ?>
                <?php
                if (comments_open())
                    comments_template();
                ?>

            <?php endwhile; ?>

        </div><!-- #content -->
    </div><!-- #primary -->

    <?php
    if ($show_sidebar)
        get_sidebar();
    ?>
</div>
<?php get_footer(); ?>