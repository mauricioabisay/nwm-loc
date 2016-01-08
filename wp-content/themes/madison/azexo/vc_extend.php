<?php

if (function_exists('vc_remove_param')) {
    vc_remove_param('vc_row', 'full_width');
}

if (class_exists('WPBakeryShortCode') && function_exists('vc_map')) {

    class WPBakeryShortCode_azexo_search_form extends WPBakeryShortCode {
        
    }

    vc_map(array(
        "name" => "AZEXO - Search Form",
        "base" => "azexo_search_form",
        "content_element" => true,
        "controls" => "full",
        "show_settings_on_create" => false
    ));

    class WPBakeryShortCode_azexo_post extends WPBakeryShortCode {
        
    }

    global $azexo_templates;
    vc_map(array(
        "name" => "AZEXO - Post",
        "base" => "azexo_post",
        "content_element" => true,
        "controls" => "full",
        "show_settings_on_create" => true,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Post ID', AZEXO_THEME_NAME),
                'param_name' => 'post_id',
                'description' => __('Post ID', AZEXO_THEME_NAME),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Post template', AZEXO_THEME_NAME),
                'param_name' => 'template',
                'value' => array_flip($azexo_templates),
                'description' => __('Post template.', AZEXO_THEME_NAME)
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Extra class name', AZEXO_THEME_NAME),
                'param_name' => 'el_class',
                'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', AZEXO_THEME_NAME),
            ),
            array(
                'type' => 'css_editor',
                'heading' => __('Css', AZEXO_THEME_NAME),
                'param_name' => 'css',
                'group' => __('Design options', AZEXO_THEME_NAME),
            ),
        )
    ));

    class WPBakeryShortCode_azexo_posts_list extends WPBakeryShortCode {
        
    }

    vc_map(array(
        "name" => "AZEXO - Posts List",
        "base" => "azexo_posts_list",
        "content_element" => true,
        "controls" => "full",
        "show_settings_on_create" => true,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('List title', AZEXO_THEME_NAME),
                'param_name' => 'title',
                'description' => __('Enter text which will be used as title. Leave blank if no title is needed.', AZEXO_THEME_NAME)
            ),
            array(
                'type' => 'loop',
                'heading' => __('Grids content', AZEXO_THEME_NAME),
                'param_name' => 'loop',
                'settings' => array(
                    'size' => array('hidden' => false, 'value' => 10),
                    'order_by' => array('value' => 'date'),
                ),
                'description' => __('Create WordPress loop, to populate content from your site.', AZEXO_THEME_NAME)
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Posts clauses filter function name', AZEXO_THEME_NAME),
                'param_name' => 'posts_clauses',
                'description' => __('Function which can alter WP_Query object.', AZEXO_THEME_NAME)
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Only content?', AZEXO_THEME_NAME),
                'param_name' => 'only_content',
                'value' => array(__('Yes, please', AZEXO_THEME_NAME) => 'yes')
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Post template', AZEXO_THEME_NAME),
                'param_name' => 'template',
                'value' => array_merge(array(__('Default') => 'post'), array_flip($azexo_templates)),
                'description' => __('Post template.', AZEXO_THEME_NAME),
                'dependency' => array(
                    'element' => 'only_content',
                    'is_empty' => true,
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Show as carousel?', AZEXO_THEME_NAME),
                'param_name' => 'carousel',
                'value' => array(__('Yes, please', AZEXO_THEME_NAME) => 'yes')
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Item margin', AZEXO_THEME_NAME),
                'param_name' => 'item_margin',
                'value' => '0',
                'dependency' => array(
                    'element' => 'carousel',
                    'not_empty' => true,
                )),
            array(
                'type' => 'textfield',
                'heading' => __('Posts per carousel item', AZEXO_THEME_NAME),
                'param_name' => 'posts_per_item',
                'value' => '1',
                'dependency' => array(
                    'element' => 'carousel',
                    'not_empty' => true,
                )),
            array(
                'type' => 'textfield',
                'heading' => __('Extra class name', AZEXO_THEME_NAME),
                'param_name' => 'el_class',
                'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', AZEXO_THEME_NAME),
            ),
            array(
                'type' => 'css_editor',
                'heading' => __('Css', AZEXO_THEME_NAME),
                'param_name' => 'css',
                'group' => __('Design options', AZEXO_THEME_NAME),
            ),
        )
    ));
}