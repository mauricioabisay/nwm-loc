<?php


function starter_plugin_register_required_plugins() {

    $plugins = array(
        array(
            'name' => 'WPBakery Visual Composer',
            'slug' => 'js_composer',
            'source' => AZEXO_THEME_DIR . '/plugins/js_composer.zip',
            'required' => true,
        ),
        array(
            'name' => 'Visual Composer Widgets',
            'slug' => 'vc_widgets',
            'source' => AZEXO_THEME_DIR . '/plugins/vc_widgets.zip',
            'required' => true,
        ),        
        array(
            'name' => 'JP Widget Visibility',
            'slug' => 'jetpack-widget-visibility',
            'source' => AZEXO_THEME_DIR . '/plugins/jetpack-widget-visibility.zip',
            'required' => false,
        ),
        array(
            'name' => 'Widget - Flickr Badge Widget',
            'slug' => 'flickr-badges-widget',
            'source' => AZEXO_THEME_DIR . '/plugins/flickr-badges-widget.zip',
            'required' => false,
        ),
        array(
            'name' => 'WP Instagram Widget',
            'slug' => 'wp-instagram-widget',
            'source' => AZEXO_THEME_DIR . '/plugins/wp-instagram-widget.zip',
            'required' => false,
        ),
    );    
    tgmpa($plugins, array());
}

add_action('tgmpa_register', 'starter_plugin_register_required_plugins');
