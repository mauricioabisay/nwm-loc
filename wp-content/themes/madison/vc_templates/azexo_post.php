<?php

$output = $template = $post_id = $el_class = $css = '';
extract(shortcode_atts(array(
    'post_id' => '',
    'template' => 'post',
    'el_class' => '',
    'css' => '',
                ), $atts));

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . vc_shortcode_custom_css_class($css, ' '), $this->settings['base'], $atts);

$template_name = $template;

if (is_numeric($post_id)) {
    global $post;
    $original = $post;
    $post = get_post($post_id);
    setup_postdata($post);
    print '<div class="' . esc_attr($css_class) . '">';
    include(locate_template('content.php'));
    print '</div>';
    wp_reset_postdata();
    $post = $original;
}
