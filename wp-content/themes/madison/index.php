<?php
$options = get_option(AZEXO_THEME_NAME);
if (!isset($show_sidebar))
    $show_sidebar = true;
if (!isset($template_name))
    $template_name = isset($options['default_post_template']) ? $options['default_post_template'] : 'post';
get_header();
$have_posts = false;
?>

<div class="container <?php print (is_active_sidebar('sidebar') && $show_sidebar ? 'active-sidebar' : ''); ?>">
    <div id="primary" class="content-area">
        <?php
        $options = get_option(AZEXO_THEME_NAME);
        if ($options['show_page_title']) {
            get_template_part('template-parts/general', 'title');
        }
        ?>
        <div id="content" class="site-content <?php print str_replace('_', '-', $template_name); ?>" role="main">
            <?php
            if (is_page()) {
                $paged = get_query_var('paged');
                if (empty($paged))
                    $paged = get_query_var('page');
                query_posts('post_type=post&post_status=publish&posts_per_page=' . get_option('posts_per_page') . '&paged=' . $paged);
            }
            ?>
            <?php if (have_posts()) : $have_posts = true; ?>                
                <?php while (have_posts()) : the_post(); ?>
                    <?php include(locate_template('content.php')); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <?php include(locate_template('content-none.php')); ?>
            <?php endif; ?>

        </div><!-- #content -->
        <?php
        if ($have_posts)
            azexo_paging_nav();
        ?>
    </div><!-- #primary -->

    <?php
    if ($show_sidebar)
        get_sidebar();
    ?>
</div>
<?php get_footer(); ?>