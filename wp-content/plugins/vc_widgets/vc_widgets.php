<?php
/*
  Plugin Name: Visual Composer widgets
  Plugin URI: http://azexo.com
  Description: Visual Composer widgets
  Author: azexo
  Author URI: http://azexo.com
  Version: 1.0
  Text Domain: vc_widgets
 */

add_action('widgets_init', 'vc_widgets_register_widgets');

if(function_exists('vc_default_editor_post_types')) {
    $pt_array = ( $pt_array = get_option('wpb_js_content_types') ) ? ( $pt_array ) : vc_default_editor_post_types();
} else {
    $pt_array = ( $pt_array = get_option('wpb_js_content_types') ) ? ( $pt_array ) : array();
}
$pt_array[] = 'vc_widget';
update_option('wpb_js_content_types', $pt_array);

function vc_widgets_register_widgets() {
    register_widget('VC_Widget');
}

class VC_Widget extends WP_Widget {

    function VC_Widget() {
        parent::__construct('vc_widget', AZEXO_THEME_NAME . ' - VC Widget');
    }

    function widget($args, $instance) {
        print '<div class="widget vc-widget">';
        if (!empty($instance['title']))
            print '<div class="widget-title"><h3>' . $instance['title'] . '</h3></div>';
        if (!empty($instance['post'])) {
            $wpautop = false;
            if (has_filter('the_content', 'wpautop')) {
                remove_filter('the_content', 'wpautop');
                $wpautop = true;
            }
            print azexo_get_post_content($instance['post']);
            if ($wpautop) {
                add_filter('the_content', 'wpautop');
            }
        }
        print '</div>';
    }

    function form($instance) {
        $defaults = array('post' => '', 'title' => '');
        $instance = wp_parse_args((array) $instance, $defaults);


        $vc_widgets = array();
        $loop = new WP_Query(array(
            'post_type' => 'vc_widget',
            'post_status' => 'publish',
            'showposts' => 100,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
        if ($loop->have_posts()) {
            global $post;
            $original = $post;
            while ($loop->have_posts()) {
                $loop->the_post();
                $vc_widgets[] = $post;
            }
            wp_reset_postdata();
            $post = $original;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('VC Widget:', AZEXO_THEME_NAME); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>">
                <?php
                foreach ($vc_widgets as $vc_widget) :
                    ?>
                    <option value="<?php echo $vc_widget->ID ?>" <?php selected($vc_widget->ID, $instance['post']) ?>><?php echo $vc_widget->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>        
        <?php
    }

}

add_action('init', 'vc_widgets_register');

function vc_widgets_register() {
    register_post_type('vc_widget', array(
        'labels' => array(
            'name' => __('VC Widget', AZEXO_THEME_NAME),
            'singular_name' => __('VC Widget', AZEXO_THEME_NAME),
            'add_new' => _x('Add VC Widget', AZEXO_THEME_NAME),
            'add_new_item' => _x('Add New VC Widget', AZEXO_THEME_NAME),
            'edit_item' => _x('Edit VC Widget', AZEXO_THEME_NAME),
            'new_item' => _x('New VC Widget', AZEXO_THEME_NAME),
            'view_item' => _x('View VC Widget', AZEXO_THEME_NAME),
            'search_items' => _x('Search VC Widgets', AZEXO_THEME_NAME),
            'not_found' => _x('No VC Widget found', AZEXO_THEME_NAME),
            'not_found_in_trash' => _x('No VC Widget found in Trash', AZEXO_THEME_NAME),
            'parent_item_colon' => _x('Parent VC Widget:', AZEXO_THEME_NAME),
            'menu_name' => _x('VC Widgets', AZEXO_THEME_NAME),
        ),
        'query_var' => false,
        'rewrite' => true,
        'hierarchical' => true,
        'supports' => array('title', 'editor', 'revisions'),
        'public' => true,
            )
    );
}
