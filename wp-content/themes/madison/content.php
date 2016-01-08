<?php
$options = get_option(AZEXO_THEME_NAME);
$more_link_text = sprintf(__('Read more<span class="meta-nav"> &rsaquo;</span>', AZEXO_THEME_NAME));
if (!isset($template_name))
    $template_name = 'post';
$default_post_template = isset($options['default_post_template']) ? $options['default_post_template'] : 'post';
$thumbnail_size = isset($options[$template_name . '_thumbnail_size']) && !empty($options[$template_name . '_thumbnail_size']) ? $options[$template_name . '_thumbnail_size'] : 'large';
$image_thumbnail = isset($options[$template_name . '_image_thumbnail']) ? $options[$template_name . '_image_thumbnail'] : false;

if ($template_name == 'masonry_post') {
    wp_enqueue_script('masonry');
}
?>

<article <?php post_class(str_replace('_', '-', $template_name)); ?>>
    <?php if (isset($options[$template_name . '_show_thumbnail']) && $options[$template_name . '_show_thumbnail']) : ?>
        <?php if (!post_password_required() && !is_attachment()) : ?>
            <?php if (has_post_format('gallery') && !$image_thumbnail) : ?>
                <div class="entry-gallery <?php print (isset($options[$template_name . '_gallery_slider_thumbnails']) && esc_attr($options[$template_name . '_gallery_slider_thumbnails']) ? 'thumbnails' : ''); ?>">
                    <?php
                    wp_enqueue_style('flexslider');
                    wp_enqueue_script('flexslider');
                    print get_post_gallery();
                    ?>
                    <?php print azexo_entry_meta($template_name, 'thumbnail'); ?>
                    <?php if (isset($options[$template_name . '_share']) && ($options[$template_name . '_share'] == 'thumbnail')): ?>
                        <div class="entry-share">
                            <?php azexo_entry_share(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif (has_post_format('video') && !$image_thumbnail) : ?>
                <div class="entry-video">
                    <?php
                    $embed = get_first_shortcode(get_the_content(''), 'embed');
                    if ($embed) {
                        global $wp_embed;
                        print $wp_embed->run_shortcode($embed);
                    }
                    ?>
                    <?php print azexo_entry_meta($template_name, 'thumbnail'); ?>
                    <?php if (isset($options[$template_name . '_share']) && ($options[$template_name . '_share'] == 'thumbnail')): ?>
                        <div class="entry-share">
                            <?php azexo_entry_share(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-thumbnail">
                        <?php
                        $url = azexo_get_the_post_thumbnail(get_the_ID(), $thumbnail_size, true);
                        $size = get_image_sizes($thumbnail_size);
                        ?>                        
                        <div class="image" style="height: <?php print esc_attr($size['height']); ?>px;" data-width="<?php print esc_attr($size['width']); ?>" data-height="<?php print esc_attr($size['height']); ?>">
                            <a href="<?php echo esc_url(get_permalink());?>"><img src="<?php print esc_url($url[0]); ?>" /></a>
                        </div>
                        <div class="mi_categoria <?php echo get_post_class()[7];?>"></div>

                        <?php print azexo_entry_meta($template_name, 'thumbnail'); ?>
                        <?php if (isset($options[$template_name . '_share']) && ($options[$template_name . '_share'] == 'thumbnail')): ?>
                            <div class="entry-share">
                                <?php azexo_entry_share(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    <div class="entry-data">
        <div class="entry-header">
            <?php
            $extra = azexo_entry_meta($template_name, 'extra');
            ?>
            <?php if (!empty($extra)) : ?>
                <div class="entry-extra">
                    <?php print $extra; ?>
                </div>
            <?php endif; ?>
            <?php
            if (is_single() && $template_name == $default_post_template) :
                the_title('<h2 class="entry-title">', '</h2>');
            else :
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;
            ?>
            <?php
            $meta = azexo_entry_meta($template_name, 'meta');
            ?>
            <?php if (!empty($meta)) : ?>
                <div class="entry-meta">
                    <?php print $meta; ?>
                </div>
            <?php endif; ?>
            <?php print azexo_entry_meta($template_name, 'header'); ?>
        </div>


        <?php if (isset($options[$template_name . '_show_content']) && $options[$template_name . '_show_content'] != 'hidden'): ?>
            <?php if (is_search() || $options[$template_name . '_show_content'] == 'excerpt') : // Only display Excerpts for Search  ?>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->
            <?php else : ?>
                <div class="entry-content">
                    <?php
                    if (!get_post_format() || has_post_format('gallery') || has_post_format('video')) {
                        $content = '';
                        if (has_post_format('gallery')) {
                            if (isset($options[$template_name . '_more_inside_content']) && $options[$template_name . '_more_inside_content'])
                                $content = strip_first_shortcode(get_the_content($more_link_text), 'gallery');
                            else
                                $content = strip_first_shortcode(get_the_content(''), 'gallery');
                        } elseif (has_post_format('video')) {
                            if (isset($options[$template_name . '_more_inside_content']) && $options[$template_name . '_more_inside_content'])
                                $content = strip_first_shortcode(get_the_content($more_link_text), 'embed');
                            else
                                $content = strip_first_shortcode(get_the_content(''), 'embed');
                        } else {
                            if (isset($options[$template_name . '_more_inside_content']) && $options[$template_name . '_more_inside_content'])
                                $content = get_the_content($more_link_text);
                            else
                                $content = get_the_content('');
                        }
                        $content = str_replace(']]>', ']]&gt;', apply_filters('the_content', $content));
                        print $content;
                    } else {
                        if (isset($options[$template_name . '_more_inside_content']) && $options[$template_name . '_more_inside_content'])
                            the_content($more_link_text);
                        else
                            the_content('');
                    }
                    ?>
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', AZEXO_THEME_NAME) . '</span>',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                    ));
                    ?>
                </div><!-- .entry-content -->
            <?php endif; ?>        

            <?php if ((!is_single() && !is_search() || $template_name != $default_post_template) && (!isset($options[$template_name . '_more_inside_content']) || (isset($options[$template_name . '_more_inside_content']) && !$options[$template_name . '_more_inside_content']))): ?>
                <div class="entry-more">
                    <?php
                    print apply_filters('the_content_more_link', ' <a href="' . get_permalink() . "#more-{" . get_the_ID() . "}\" class=\"more-link\">" . esc_html($more_link_text) . "</a>", $more_link_text);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <?php print azexo_entry_meta($template_name, 'data'); ?>

        <div class="entry-footer">
            <?php print azexo_entry_meta($template_name, 'footer'); ?>
        </div>

        <?php if (isset($options[$template_name . '_share']) && ($options[$template_name . '_share'] == 'data')): ?>
            <div class="entry-share">
                <div class="helper">
                    <?php print (isset($options[$template_name . '_share_prefix']) ? esc_html($options[$template_name . '_share_prefix']) : ''); ?>
                </div>
                <?php azexo_entry_share(); ?>
            </div>
        <?php endif; ?>
    </div>    
</article><!-- #post -->
