<?php
$options = get_option(AZEXO_THEME_NAME);
if (!isset($template_name))
    $template_name = 'post';
$default_post_template = isset($options['default_post_template']) ? $options['default_post_template'] : 'post';

if ($template_name == 'masonry_post') {
    wp_enqueue_script('masonry');
}
?>

<article <?php post_class(array(str_replace('_', '-', $template_name), 'no-results', 'not-found')); ?>>
    <div class="entry-data">
        <div class="entry-content">
            <?php if (is_home() && current_user_can('publish_posts')) : ?>

                <p><?php printf(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', AZEXO_THEME_NAME), esc_url(admin_url('post-new.php'))); ?></p>

            <?php elseif (is_search()) : ?>

                <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', AZEXO_THEME_NAME); ?></p>

            <?php else : ?>

                <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', AZEXO_THEME_NAME); ?></p>

            <?php endif; ?>
        </div><!-- .entry-content -->
    </div>    
</article><!-- #post -->
