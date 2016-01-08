<?php
define('AZEXO_THEME_NAME', 'AZEXO');
define('AZEXO_THEME_VERSION', '1.0');
define('AZEXO_THEME_DIR', get_template_directory());
define('AZEXO_THEME_URI', get_template_directory_uri());

if (!get_option('azexo_post_types', false)) {
    add_option('azexo_post_types', array('page', 'post'));
}

add_action('wp_logout', 'nwm_loc_logout');
function nwm_loc_logout() {
  wp_redirect(home_url());
  exit();
}

add_action('after_setup_theme', 'azexo_after_setup_theme');

function azexo_after_setup_theme() {
    load_theme_textdomain(AZEXO_THEME_NAME, get_template_directory() . '/lang');
    add_theme_support('post-formats', array('gallery', 'video'));
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
}

$options = get_option(AZEXO_THEME_NAME);

function azexo_scripts() {
    wp_register_script('azexo', get_template_directory_uri() . '/js/azexo.js', array('jquery'), AZEXO_THEME_VERSION, true);
    wp_enqueue_script('azexo');

    wp_register_script('jquery.sticky-kit', get_template_directory_uri() . '/js/jquery.sticky-kit.min.js', array('jquery'), AZEXO_THEME_VERSION, true);
    wp_enqueue_script('jquery.sticky-kit');

    wp_register_script('imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array('jquery'), AZEXO_THEME_VERSION, true);
    wp_enqueue_script('imagesloaded');

    wp_register_script('background-check', get_template_directory_uri() . '/js/background-check.min.js', array(), AZEXO_THEME_VERSION, true);
    wp_enqueue_script('background-check');

    wp_register_script('owl.carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'));
    wp_register_style('owl.carousel', get_template_directory_uri() . '/css/owl.carousel.css');
    //move styles to header for HTML5 validation

    wp_enqueue_style('owl.carousel');
    wp_enqueue_style('js_composer_front');
    wp_enqueue_style('flexslider');
    wp_enqueue_style('yarppRelatedCss');

    wp_register_script('packery', get_template_directory_uri() . '/js/packery.pkgd.min.js', array('jquery'));

    if (is_singular() && comments_open() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');
    //wp_enqueue_script( 'carlitos-pinterest', get_template_directory_uri() . '/js/carlitos-pinterest.js', array(), false, true);
}

add_action('wp_enqueue_scripts', 'azexo_scripts');

function azexo_get_dir_files($src) {
    $files = array();
    $dir = opendir($src);
    if (is_resource($dir))
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $files[$file] = realpath($src . DIRECTORY_SEPARATOR . $file);
            }
        }
    closedir($dir);
    return $files;
}

function azexo_get_skins() {
    $skins = array();
    $files = azexo_get_dir_files(get_template_directory() . '/less');
    foreach ($files as $name => $path) {
        if (is_dir($path)) {
            $skin_files = azexo_get_dir_files($path);
            if (isset($skin_files['azexo.less'])) {
                $skins[] = $name;
            }
        }
    }
    return $skins;
}

function azexo_get_skin() {
    $options = get_option(AZEXO_THEME_NAME);
    $skin = '';
    if (isset($options['skin'])) {
        $skin = $options['skin'];
    } else {
        $skins = azexo_get_skins();
        $skin = reset($skins);
    }
    return $skin;
}

add_action('init', 'azexo_load_default_skin_options');

function azexo_load_default_skin_options() {
    $options = get_option(AZEXO_THEME_NAME);
    if (!isset($options['skin'])) {
        $skins = azexo_get_skins();
        $skin = reset($skins);
        $file = AZEXO_THEME_DIR . '/azexo/options/' . $skin . '.json';
        if (file_exists($file)) {
            $file_contents = file_get_contents($file);
            $options = json_decode($file_contents, true);
            $redux = get_redux_instance(AZEXO_THEME_NAME);
            $redux->set_options($options);
        }
    }
}

require_once('wp-less/bootstrap-for-theme.php');
require_once('azexo/less-variables.php');

function azexo_styles() {
    wp_register_style('animate-css', get_template_directory_uri() . '/css/animate.css/animate.min.css');
    wp_enqueue_style('animate-css');

    wp_register_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('font-awesome');

    wp_register_style('themify-icons', get_template_directory_uri() . '/css/themify-icons.css');
    wp_enqueue_style('themify-icons');


    $less = WPLessPlugin::getInstance();
    $less->dispatch();
    wp_enqueue_style('azexo', get_template_directory_uri() . '/less/' . azexo_get_skin() . '/azexo.less');

    wp_register_style('nwm', get_template_directory_uri() . '/css/nwm.css');
    wp_enqueue_style('nwm');
}

add_action('wp_enqueue_scripts', 'azexo_styles');

add_action('wp_head', 'azexo_dynamic_css');

if (!function_exists('azexo_dynamic_css')) {

    function azexo_dynamic_css() {
        echo '<!--CUSTOM STYLE--><style type="text/css">';

        $post_categories = get_categories();
        global $azexo_category_fields;

        if (!empty($post_categories)) {
            foreach ($post_categories as $cat) {
                $cat_color = $azexo_category_fields->get_category_meta($cat->cat_ID, 'color');
                echo $cat_color ? 'a.' . esc_attr($cat->slug) . '[rel="category tag"], a.' . esc_attr($cat->slug) . '[rel="category"] { background-color:' . esc_attr($cat_color) . ' !important;}' : '';
            }
        }

        echo '</style><!--/CUSTOM STYLE-->';
    }

}


add_image_size('large-crop', 640, 480, true);
add_filter('image_size_names_choose', 'azexo_custom_sizes');

function azexo_custom_sizes($sizes) {
    return array_merge($sizes, array(
        'large-crop' => __('Large with crop', AZEXO_THEME_NAME),
    ));
}

//Se comentO porquE provoca que el <title> se duplique
//add_filter('wp_title', 'azexo_wp_title', 10, 2);

function azexo_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', AZEXO_THEME_NAME), max($paged, $page));
    return $title;
}

add_filter('embed_defaults', 'azexo_embed_defaults');

function azexo_embed_defaults() {
    return array('width' => 1000, 'height' => 500);
}

add_filter('the_excerpt', 'azexo_the_excerpt', 11);

function azexo_the_excerpt($content) {
    $excerpt = wp_trim_words(wp_strip_all_tags($content), isset($options['excerpt_length']) ? $options['excerpt_length'] : 15);
    return $excerpt;
}

if (!isset($content_width))
    $content_width = 1;

class Azexo_Walker_Comment extends Walker_Comment {

    protected function comment($comment, $depth, $args) {
        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag; ?> <?php comment_class($this->has_children ? 'parent' : '' ); ?> id="comment-<?php comment_ID(); ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <?php endif; ?>
            <div class="comment-author">
                <?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size']); ?>
            </div>
            <div class="comment-data">
                <?php printf(__('<cite class="fn">%s</cite>', AZEXO_THEME_NAME), get_comment_author_link()); ?>
                <?php if ('0' == $comment->comment_approved) : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', AZEXO_THEME_NAME) ?></em>
                    <br />
                <?php endif; ?>
                <div class="comment-meta commentmetadata"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID, $args)); ?>">
                        <?php
                        /* translators: 1: date, 2: time */
                        printf(__('%1$s at %2$s', AZEXO_THEME_NAME), get_comment_date(), get_comment_time());
                        ?></a><?php edit_comment_link(__('(Edit)', AZEXO_THEME_NAME), '&nbsp;&nbsp;', '');
                        ?>
                </div>
                <?php comment_text(get_comment_id(), array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', AZEXO_THEME_NAME), 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
            </div>
            <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }

}

if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'primary' => __('Top primary menu', AZEXO_THEME_NAME),
        'secondary' => __('Secondary menu', AZEXO_THEME_NAME),
    ));
}

function azexo_get_post_wpb_css($id) {
    $shortcodes_custom_css = get_post_meta($id, '_wpb_shortcodes_custom_css', true);
    if (!empty($shortcodes_custom_css)) {
        return '<style type="text/css" data-type="vc_shortcodes-custom-css" scoped>' . $shortcodes_custom_css . '</style>';
    }
    return '';
}

function azexo_get_post_content($id) {
    global $post;
    $original = $post;
    $post = get_post($id);
    setup_postdata($post);
    $content = get_the_content('');
    $matches = array();
    preg_match_all('/tab\_id\=\"([^\"]+)\"/', $content, $matches);
    foreach ($matches[0] as $match) {
        $content = str_replace($match, 'tab_id="azexo-' . rand(0, 99999999) . '"', $content);
    }
    $content = '<div class="scoped-style">' . azexo_get_post_wpb_css($id) . apply_filters('the_content', $content) . '</div>';
    wp_reset_postdata();
    $post = $original;

    return $content;
}

add_filter('nav_menu_link_attributes', 'azexo_nav_menu_link_attributes', 10, 4);

function azexo_nav_menu_link_attributes($atts, $item, $args, $depth) {
    if ($atts['title'] == 'mega') {
        $atts['title'] = '';
        $atts['href'] = '#';
    }
    $atts['class'] = 'menu-link';
    return $atts;
}

add_filter('nav_menu_css_class', 'azexo_nav_menu_css_class', 10, 4);

function azexo_nav_menu_css_class($classes, $item, $args, $depth) {
    if ($item->attr_title == 'mega' && $depth == 0) {
        $classes[] = 'mega';
    }
    return $classes;
}

class Azexo_Walker_Nav_Menu extends Walker_Nav_Menu {

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        if ($item->attr_title == 'mega' && $depth == 0) {
            $output .= '<div class="page">' . azexo_get_post_content($item->object_id) . '</div>';
        }
        $output .= "</li>\n";
    }

}

require_once('azexo/class.category-custom-fields.php' );
require_once('redux-framework/ReduxCore/framework.php');
require_once('azexo/options-init.php');
require_once('post-like-system/post-like.php');
require_once('azexo/vc_extend.php');
require_once('widgets/widgets.php');
require_once('tgm/class-tgm-plugin-activation.php');
require_once('azexo/tgm-init.php');

add_action('widgets_init', 'azexo_widgets_init');

function azexo_widgets_init() {
    if (function_exists('register_sidebar')) {
        register_sidebar(array('name' => 'Right sidebar', 'id' => "sidebar", 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<div class="widget-title"><h3>', 'after_title' => '</h3></div>'));
        register_sidebar(array('name' => 'Footer sidebar', 'id' => "footer_sidebar", 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<div class="widget-title"><h3>', 'after_title' => '</h3></div>'));
        register_sidebar(array('name' => 'Header sidebar', 'id' => "header_sidebar", 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<div class="widget-title"><h3>', 'after_title' => '</h3></div>'));
        register_sidebar(array('name' => 'Middle sidebar', 'id' => "middle_sidebar", 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<div class="widget-title"><h3>', 'after_title' => '</h3></div>'));
    }
}

function removeDemoModeLink() { // Be sure to rename this function to something more unique
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
    }
}

add_action('init', 'removeDemoModeLink');

add_filter('use_default_gallery_style', '__return_false');

if (is_admin()) {
    require_once('azexo/exporter/export.php');
    require_once('azexo/importer/import.php');
}


if (!function_exists('azexo_paging_nav')) :

    function azexo_paging_nav() {
        global $wp_query, $wp_rewrite;

        // Don't print empty markup if there's only one page.
        if ($wp_query->max_num_pages < 2) {
            return;
        }

        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);

        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $wp_query->max_num_pages,
            'current' => $paged,
            'mid_size' => 1,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => '<i class="prev"></i>',
            'next_text' => '<i class="next"></i>',
        ));

        if ($links) :
            ?>
            <nav class="navigation paging-navigation">
                <div class="pagination loop-pagination">
                    <?php echo $links; ?>
                </div><!-- .pagination -->
            </nav><!-- .navigation -->
            <?php
        endif;
    }

endif;


if (!function_exists('azexo_post_nav')) :

    function azexo_post_nav() {
        global $post;

        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);
        $options = get_option(AZEXO_THEME_NAME);
        if (!$next && !$previous)
            return;
        ?>
        <nav class="navigation post-navigation clearfix">
            <div class="nav-links">

                <?php previous_post_link('%link', '<i class="prev"></i><div class="prev-post"><span class="helper">' . (isset($options['post_navigation_previous']) ? $options['post_navigation_previous'] : '') . '</span><span class="title">%title</span></div>'); ?>
                <?php next_post_link('%link', '<i class="next"></i><div class="next-post"><span class="helper">' . (isset($options['post_navigation_next']) ? $options['post_navigation_next'] : '') . '</span><span class="title">%title</span></div>'); ?>

            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }

endif;

function azexo_get_the_category_list($separator = '', $parents = '', $post_id = false) {
    global $wp_rewrite;
    if (!is_object_in_taxonomy(get_post_type($post_id), 'category')) {
        /** This filter is documented in wp-includes/category-template.php */
        return apply_filters('the_category', '', $separator, $parents);
    }

    $categories = get_the_category($post_id);
    if (empty($categories)) {
        /** This filter is documented in wp-includes/category-template.php */
        return apply_filters('the_category', __('Uncategorized', AZEXO_THEME_NAME), $separator, $parents);
    }

    $rel = ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

    $thelist = '';
    if ('' == $separator) {
        $thelist .= '<ul class="post-categories">';
        foreach ($categories as $category) {
            $thelist .= "\n\t<li>";
            switch (strtolower($parents)) {
                case 'multiple':
                    if ($category->parent)
                        $thelist .= get_category_parents($category->parent, true, $separator);
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . '>' . $category->name . '</a></li>';
                    break;
                case 'single':
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '"  ' . $rel . '>';
                    if ($category->parent)
                        $thelist .= get_category_parents($category->parent, false, $separator);
                    $thelist .= $category->name . '</a></li>';
                    break;
                case '':
                default:
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . '>' . $category->name . '</a></li>';
            }
        }
        $thelist .= '</ul>';
    } else {
        $i = 0;
        foreach ($categories as $category) {
            if (0 < $i)
                $thelist .= $separator;
            switch (strtolower($parents)) {
                case 'multiple':
                    if ($category->parent)
                        $thelist .= get_category_parents($category->parent, true, $separator);
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . '>' . $category->name . '</a>';
                    break;
                case 'single':
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . '>';
                    if ($category->parent)
                        $thelist .= get_category_parents($category->parent, false, $separator);
                    $thelist .= "$category->name</a>";
                    break;
                case '':
                default:
                    $thelist .= '<a class="' . $category->slug . '" href="' . esc_url(get_category_link($category->term_id)) . '" ' . $rel . '>' . $category->name . '</a>';
            }
            ++$i;
        }
    }

    /**
     * Filter the category or list of categories.
     *
     * @since 1.2.0
     *
     * @param array  $thelist   List of categories for the current post.
     * @param string $separator Separator used between the categories.
     * @param string $parents   How to display the category parents. Accepts 'multiple',
     *                          'single', or empty.
     */
    return apply_filters('the_category', $thelist, $separator, $parents);
}

if (!function_exists('azexo_entry_field')) :

    function azexo_entry_field($template_name = 'post', $name) {
        $options = get_option(AZEXO_THEME_NAME);

        $output = apply_filters('azexo_entry_field', false, $template_name, $name);
        if ($output)
            return $output;

        switch ($name) {
            case 'post_sticky':
                if (is_sticky() && is_home() && !is_paged())
                    return '<span class="featured-post">' . __('Sticky', AZEXO_THEME_NAME) . '</span>';
                break;
            case 'post_splitted_date':
                return azexo_entry_splitted_date(false);
                break;
            case 'post_date':
                return azexo_entry_date(false);
                break;
            case 'post_category':
                $categories_list = azexo_get_the_category_list(__(', ', AZEXO_THEME_NAME));
                if ($categories_list) {
                    return '<span class="categories-links">' . (isset($options[$template_name . '_category_prefix']) ? esc_html($options[$template_name . '_category_prefix']) : '') . $categories_list . '</span>';
                }
                break;
            case 'post_tags':
                $tag_list = get_the_tag_list('', __(', ', AZEXO_THEME_NAME));
                if ($tag_list) {
                    return '<span class="tags-links">' . (isset($options[$template_name . '_tags_prefix']) ? esc_html($options[$template_name . '_tags_prefix']) : '') . $tag_list . '</span>';
                }
                break;
            case 'post_author':
                if ('post' == get_post_type()) {
                    return sprintf('<span class="author vcard">' . (isset($options[$template_name . '_author_prefix']) ? esc_html($options[$template_name . '_author_prefix']) : '') . '<a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', AZEXO_THEME_NAME), get_the_author())), get_the_author());
                }
                break;
            case 'post_author_avatar':
                if ('post' == get_post_type()) {
                    return '<span class="avatar">' . get_avatar(get_the_author_meta('ID')) . '</span>';
                }
                break;
            case 'post_like':
                return '<span class="like">' . getPostLikeLink(get_the_ID()) . '</span>';
                break;
            case 'post_comments_count':
                if (!is_single() && !is_search()) {
                    $comment_count = get_comment_count(get_the_ID());
                    $comments = '<a href="' . get_comments_link() . '"><span class="count">' . $comment_count['total_comments'] . '</span><span class="label">' . __('comments', AZEXO_THEME_NAME) . '</span></a>';
                    return '<span class="comments">' . $comments . '</span>';
                }
                break;
            default:
                return '';
                break;
        }
        return '';
    }

endif;

if (!function_exists('azexo_entry_meta')) :

    function azexo_entry_meta($template_name = 'post', $place = 'meta') {
        $options = get_option(AZEXO_THEME_NAME);
        $meta = '';
        if (isset($options[$template_name . '_' . $place]) && is_array($options[$template_name . '_' . $place])) {
            foreach ($options[$template_name . '_' . $place] as $field) {
                $meta .= azexo_entry_field($template_name, $field);
            }
        }
        return $meta;
    }

endif;

if (!function_exists('azexo_entry_share')) :

    function azexo_entry_share() {
        global $post;
        echo '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode(esc_url(apply_filters('the_permalink', get_permalink()))) . '"><span class="share-box"><i class="fa fa-facebook"></i></span></a>';
        echo '<a target="_blank" href="https://twitter.com/home?status=' . rawurlencode('Check out this article: ') . rawurlencode(get_the_title()) . '%20-%20' . rawurlencode(esc_url(apply_filters('the_permalink', get_permalink()))) . '"><span class="share-box"><i class="fa fa-twitter"></i></span></a>';
        if (is_object($post)) {
            $pin_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
            if (!empty($pin_image))
                echo '<a target="_blank" href="https://pinterest.com/pin/create/button/?url=' . rawurlencode(esc_url(apply_filters('the_permalink', get_permalink()))) . '&amp;media=' . rawurlencode($pin_image) . '&amp;description=' . rawurlencode(get_the_title()) . '"><span class="share-box"><i class="fa fa-pinterest"></i></span></a>';
        }
        echo '<a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . rawurlencode(esc_url(apply_filters('the_permalink', get_permalink()))) . '&amp;title=' . rawurlencode(get_the_title()) . '&amp;source=LinkedIn"><span class="share-box"><i class="fa fa-linkedin"></i></span></a>';
        echo '<a target="_blank" href="https://plus.google.com/share?url=' . rawurlencode(esc_url(apply_filters('the_permalink', get_permalink()))) . '"><span class="share-box"><i class="fa fa-google-plus"></i></span></a>';
        if (comments_open() && !is_single() && !is_page()) {
            $comments = '<span class="share-box"><i class="fa fa-comment-o"></i></span>';
            comments_popup_link($comments, $comments, $comments, '', '');
        }
    }

endif;


if (!function_exists('azexo_entry_splitted_date')) :

    function azexo_entry_splitted_date($echo = true) {

        $date = '<div class="date"><div class="day">' . get_the_date('d') . '</div><div class="month">' . get_the_date('F') . '</div><div class="year">' . get_the_date('Y') . '</div></div>';

        if ($echo)
            echo $date;

        return $date;
    }

endif;

if (!function_exists('azexo_entry_date')) :

    function azexo_entry_date($echo = true, $post = null) {
        if (has_post_format(array('chat', 'status'), $post))
            $format_prefix = _x('%1$s on %2$s', '1: post format name. 2: date', AZEXO_THEME_NAME);
        else
            $format_prefix = '%2$s';

        $options = get_option(AZEXO_THEME_NAME);
        $date = sprintf('<span class="date">' . (isset($options['post_date_prefix']) ? esc_html($options['post_date_prefix']) : '') . '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url(get_permalink($post)), esc_attr(sprintf(__('Permalink to %s', AZEXO_THEME_NAME), the_title_attribute(array('echo' => false, 'post' => $post)))), esc_attr(get_the_date('c', $post)), esc_html(sprintf($format_prefix, get_post_format_string(get_post_format($post)), get_the_date('', $post)))
        );

        if ($echo)
            echo $date;

        return $date;
    }

endif;

if (!function_exists('azexo_the_attached_image')) :

    function azexo_the_attached_image() {
        $attachment_size = apply_filters('azexo_attachment_size', array(724, 724));
        $next_attachment_url = wp_get_attachment_url();
        $post = get_post();

        $attachment_ids = get_posts(array(
            'post_parent' => $post->post_parent,
            'fields' => 'ids',
            'numberposts' => -1,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'orderby' => 'menu_order ID'
        ));

        // If there is more than 1 attachment in a gallery...
        if (count($attachment_ids) > 1) {
            foreach ($attachment_ids as $attachment_id) {
                if ($attachment_id == $post->ID) {
                    $next_id = current($attachment_ids);
                    break;
                }
            }

            // get the URL of the next image attachment...
            if ($next_id)
                $next_attachment_url = get_attachment_link($next_id);

            // or get the URL of the first image attachment.
            else
                $next_attachment_url = get_attachment_link(array_shift($attachment_ids));
        }

        printf('<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url($next_attachment_url), the_title_attribute(array('echo' => false)), wp_get_attachment_image($post->ID, $attachment_size)
        );
    }

endif;

function azexo_breadcrumbs() {
    /* === OPTIONS === */
    $text['home'] = __('Home', AZEXO_THEME_NAME); // text for the 'Home' link
    $text['category'] = __('Archive by Category "%s"', AZEXO_THEME_NAME); // text for a category page
    $text['tax'] = __('Archive for "%s"', AZEXO_THEME_NAME); // text for a taxonomy page
    $text['search'] = __('Search Results for "%s" Query', AZEXO_THEME_NAME); // text for a search results page
    $text['tag'] = __('Posts Tagged "%s"', AZEXO_THEME_NAME); // text for a tag page
    $text['author'] = __('Articles Posted by %s', AZEXO_THEME_NAME); // text for an author page
    $text['404'] = __('Error 404', AZEXO_THEME_NAME); // text for the 404 page

    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = ' &raquo; '; // delimiter between crumbs
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    /* === END OF OPTIONS === */

    global $post;
    $homeLink = home_url() . '/';
    $linkBefore = '<span typeof="v:Breadcrumb">';
    $linkAfter = '</span>';
    $linkAttr = ' rel="v:url" property="v:title"';
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

    if (is_home() || is_front_page()) {

        if ($showOnHome == 1)
            echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
    } else {

        echo '<div id="crumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;


        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
        } elseif (is_tax()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            echo $before . sprintf($text['tax'], single_cat_title('', false)) . $after;
        } elseif (is_search()) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;
        } elseif (is_day()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($showCurrent == 1)
                    echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($showCurrent == 0)
                    $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
                if ($showCurrent == 1)
                    echo $before . get_the_title() . $after;
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($showCurrent == 1)
                echo $delimiter . $before . get_the_title() . $after;
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1)
                echo $before . get_the_title() . $after;
        } elseif (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs) - 1)
                    echo $delimiter;
            }
            if ($showCurrent == 1)
                echo $delimiter . $before . get_the_title() . $after;
        } elseif (is_tag()) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;
        } elseif (is_404()) {
            echo $before . $text['404'] . $after;
        }

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ' (';
            echo __('Page', AZEXO_THEME_NAME) . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                echo ')';
        }

        echo '</div>';
    }
}

function azexo_get_link_url() {
    $content = get_the_content();
    $has_url = get_url_in_content($content);
    return ( $has_url ) ? $has_url : apply_filters('the_permalink', get_permalink());
}

function get_image_sizes($size = '') {

    global $_wp_additional_image_sizes;

    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();

    // Create the full array with sizes and crop info
    foreach ($get_intermediate_image_sizes as $_size) {

        if (in_array($_size, array('thumbnail', 'medium', 'large'))) {

            $sizes[$_size]['width'] = get_option($_size . '_size_w');
            $sizes[$_size]['height'] = get_option($_size . '_size_h');
            $sizes[$_size]['crop'] = (bool) get_option($_size . '_crop');
        } elseif (isset($_wp_additional_image_sizes[$_size])) {

            $sizes[$_size] = array(
                'width' => $_wp_additional_image_sizes[$_size]['width'],
                'height' => $_wp_additional_image_sizes[$_size]['height'],
                'crop' => $_wp_additional_image_sizes[$_size]['crop']
            );
        }
    }

    // Get only 1 size if found
    if ($size) {

        if (isset($sizes[$size])) {
            return $sizes[$size];
        } else {
            return false;
        }
    }

    return $sizes;
}

function azexo_add_image_size($size) {
    if (!has_image_size($size) && !in_array($size, array('thumb', 'thumbnail', 'medium', 'large', 'post-thumbnail'))) {
        $size_array = explode('x', $size);
        add_image_size($size, $size_array[0], $size_array[1], true);
    }
}

function azexo_get_attachment_thumbnail($attachment_id, $size, $url = false) {
    $metadata = wp_get_attachment_metadata($attachment_id);
    if (!isset($metadata['sizes'][$size])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/post.php');
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id)));
    }
    if ($url)
        return wp_get_attachment_image_src($attachment_id, $size);
    else
        return wp_get_attachment_image($post_id, $size);
}

function azexo_get_the_post_thumbnail($post_id, $size, $url = false) {
    azexo_add_image_size($size);
    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    if (empty($post_thumbnail_id)) {
        if ($url) {

        } else {

        }
    }
    return azexo_get_attachment_thumbnail($post_thumbnail_id, $size, $url);
}

function azexo_get_attachment_image_src($attachment_id, $size) {
    azexo_add_image_size($size);

    $metadata = wp_get_attachment_metadata($attachment_id);
    if (!isset($metadata['sizes'][$size])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/post.php');
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id)));
    }

    return wp_get_attachment_image_src($attachment_id, $size);
}

function strip_first_shortcode($content, $first_shortcode) {
    preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
    if (!empty($matches)) {
        foreach ($matches as $shortcode) {
            if ($first_shortcode === $shortcode[2]) {
                $pos = strpos($content, $shortcode[0]);
                if ($pos !== false)
                    return substr_replace($content, '', $pos, strlen($shortcode[0]));
            }
        }
    }
    return $content;
}

function get_first_shortcode($content, $first_shortcode) {
    preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
    if (!empty($matches)) {
        foreach ($matches as $shortcode) {
            if ($first_shortcode === $shortcode[2]) {
                $pos = strpos($content, $shortcode[0]);
                if ($pos !== false)
                    return $shortcode[0];
            }
        }
    }
    return false;
}

function azexo_get_search_form($echo = true) {
    $result = '<div class="search-wrapper">';
    $result .= get_search_form(false);
    $result .= '<i class="fa fa-search"></i></div>';
    if ($echo)
        echo $result;
    else
        return $result;
}

function azexo_loop($template_name = 'post', $query = array('showposts' => 5, 'nopaging' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => 1)) {
    $loop = new WP_Query($query);
    if ($loop->have_posts()) {
        global $post;
        $original = $post;
        while ($loop->have_posts()) {
            $loop->the_post();
            include(locate_template('content.php'));
        }
        wp_reset_postdata();
        $post = $original;
    }
}

function azexo_most_likes_posts($template_name = 'post') {
    $args = array(
        'post_type' => array('post'),
        'meta_query' => array(
            array(
                'key' => '_post_like_count',
                'value' => '0',
                'compare' => '>'
            )
        ),
        'meta_key' => '_post_like_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'no_found_rows' => 1,
        'ignore_sticky_posts' => 1,
    );
    azexo_loop($template_name, $args);
}

add_filter('embed_oembed_html', 'azexo_embed_oembed_html', 10, 4);

function azexo_embed_oembed_html($html, $url, $attr, $post_ID) {
    return str_replace(array('frameborder="0"', 'webkitallowfullscreen', 'mozallowfullscreen', 'allowfullscreen'), '', $html);
}
