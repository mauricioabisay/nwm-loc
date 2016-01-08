<?php

$output = $template = $title = $loop = $posts_clauses = $only_content = $carousel = $item_margin = $posts_per_item = $el_class = $css = '';
extract(shortcode_atts(array(
    'title' => '',
    'loop' => '',
    'posts_clauses' => '',
    'template' => 'post',
    'only_content' => false,
    'carousel' => false,
    'item_margin' => 0,
    'posts_per_item' => 1,
    'el_class' => '',
    'css' => '',
                ), $atts));

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);

global $vc_posts_grid_exclude_id;
$vc_posts_grid_exclude_id[] = get_the_ID();
require_once vc_path_dir('PARAMS_DIR', 'loop/loop.php');
list( $loop_args, $query ) = vc_build_loop_query($loop, $vc_posts_grid_exclude_id);

$loop_args['ignore_sticky_posts'] = true;

if (!empty($posts_clauses) && function_exists('posts_clauses'))
    add_filter('posts_clauses', $posts_clauses);

$query = new WP_Query($loop_args);

if (!empty($posts_clauses) && function_exists('posts_clauses'))
    remove_filter('posts_clauses', $posts_clauses);

if ($carousel) {
    wp_enqueue_script('owl.carousel');
    wp_enqueue_style('owl.carousel');
}

if ($query->have_posts()) {
    $options = get_option(AZEXO_THEME_NAME);
    if ($only_content) {
        $size = array('width' => '', 'height' => '');
    } else {
        $thumbnail_size = isset($options[$template . '_thumbnail_size']) && !empty($options[$template . '_thumbnail_size']) ? $options[$template . '_thumbnail_size'] : 'large';
        azexo_add_image_size($thumbnail_size);
        $size = get_image_sizes($thumbnail_size);
    }

    print '<div class="posts-list-wrapper">';
    if (!empty($title)) {
        print '<div class="list-title"><h3>' . $title . '</h3></div>';
    }
    print '<div class="posts-list ' . ($only_content ? '' : str_replace('_', '-', $template)) . ' ' . ($carousel ? 'owl-carousel' : '') . ' ' . (($posts_per_item == 1) ? 'item-as-post' : '') . esc_attr($css_class) . '" data-width="' . $size['width'] . '" data-height="' . $size['height'] . '" data-margin="' . $item_margin . '">';
    $number = 0;
    global $post;
    $original = $post;
    while ($query->have_posts()) {
        $query->the_post();

        if ($carousel && $number == 0) {
            print '<div class="item">';
        }
        if ($only_content) {
            print azexo_get_post_content($post->ID);
        } else {
            $template_name = $template;
            $azwoo_base_tag = 'div';
            include(locate_template(apply_filters('azexo_posts_list_template_path', 'content.php', $template)));
        }
        $number++;
        if ($carousel && $number == $posts_per_item) {
            print '</div>';
            $number = 0;
        }
    }
    wp_reset_postdata();
    $post = $original;
    print '</div></div>';
}
