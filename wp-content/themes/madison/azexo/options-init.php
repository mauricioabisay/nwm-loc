<?php
/*
  ReduxFramework Config File
 */

if (!class_exists('AZEXO_Redux_Framework_config')) {

    class AZEXO_Redux_Framework_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }
            if (true == Redux_Helpers::isTheme(__FILE__)) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {
            $this->theme = wp_get_theme();
            $this->setArguments();
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            add_action('redux/loaded', array($this, 'remove_demo'));
            add_filter('redux/options/' . $this->args['opt_name'] . '/args', array($this, 'change_arguments'));
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        function change_arguments($args) {
            $args['dev_mode'] = false;

            return $args;
        }

        function remove_demo() {
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {
            
            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get('Name');
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', AZEXO_THEME_NAME), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
                <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                    <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
            <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', AZEXO_THEME_NAME), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', AZEXO_THEME_NAME), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', AZEXO_THEME_NAME) . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
                    <?php
                    if ($this->theme->parent()) {
                        printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', AZEXO_THEME_NAME), $this->theme->parent()->display('Name'));
                    }
                    ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            global $azexo_templates;
            if (!isset($azexo_templates))
                $azexo_templates = array();
            $azexo_templates = array_merge($azexo_templates, array(
                'post' => __('Post', AZEXO_THEME_NAME),
                'half_image_post' => __('Half image post', AZEXO_THEME_NAME),
                'bg_image_post' => __('Background image post', AZEXO_THEME_NAME),
                'masonry_post' => __('Masonry post', AZEXO_THEME_NAME),
                'related_post' => __('Related post', AZEXO_THEME_NAME),
                'carousel_post' => __('Carousel post', AZEXO_THEME_NAME),
                'widget_post' => __('Widget post', AZEXO_THEME_NAME),
                'title_post' => __('Title-post', AZEXO_THEME_NAME),
                'thumb_title_post' => __('Thumb/title-post', AZEXO_THEME_NAME),
                'big_thumb_title_post' => __('Big-thumb/title-post', AZEXO_THEME_NAME),
                'avatar_title_post' => __('Avatar/title-post', AZEXO_THEME_NAME),
            ));
            global $post_field_names;
            if (!isset($post_field_names))
                $post_field_names = array();
            $post_field_names = array_merge($post_field_names, array(
                'post_sticky' => __('Post sticky', AZEXO_THEME_NAME),
                'post_date' => __('Post date', AZEXO_THEME_NAME),
                'post_splitted_date' => __('Post splitted date', AZEXO_THEME_NAME),
                'post_author' => __('Post author', AZEXO_THEME_NAME),
                'post_author_avatar' => __('Post author avatar', AZEXO_THEME_NAME),
                'post_category' => __('Post category', AZEXO_THEME_NAME),
                'post_tags' => __('Post tags', AZEXO_THEME_NAME),
                'post_like' => __('Post like', AZEXO_THEME_NAME),
                'post_comments_count' => __('Post comments count', AZEXO_THEME_NAME),
            ));

            $general_settings_fields = array(
                array(
                    'id' => 'brand-color',
                    'type' => 'color',
                    'title' => __('Brand color', AZEXO_THEME_NAME),
                    'validate' => 'color',
                    'default' => '#000',
                ),
                array(
                    'id' => 'accent-1-color',
                    'type' => 'color',
                    'title' => __('Accent 1 color', AZEXO_THEME_NAME),
                    'validate' => 'color',
                    'default' => '#000',
                ),
                array(
                    'id' => 'accent-2-color',
                    'type' => 'color',
                    'title' => __('Accent 2 color', AZEXO_THEME_NAME),
                    'validate' => 'color',
                    'default' => '#000',
                ),
                array(
                    'id' => 'default_post_template',
                    'type' => 'select',
                    'title' => __('Default post template', AZEXO_THEME_NAME),
                    'options' => $azexo_templates,
                    'default' => 'post',
                ),
                array(
                    'id' => 'favicon',
                    'type' => 'media',
                    'title' => __('Favicon', AZEXO_THEME_NAME),
                    'subtitle' => __('Upload any media using the WordPress native uploader', AZEXO_THEME_NAME),
                ),
                array(
                    'id' => 'header',
                    'type' => 'ace_editor',
                    'title' => __('Header HTML', AZEXO_THEME_NAME),
                    'subtitle' => __('Paste your HTML code here.', AZEXO_THEME_NAME),
                    'mode' => 'html',
                    'theme' => 'monokai',
                    'default' => "<ul>\n<li><a href=\"#\"><i class='fa fa-facebook'></i></a></li>\n<li><a href=\"#\"><i class='fa fa-twitter'></i></a></li>\n<li><a href=\"#\"><i class='fa fa-pinterest'></i></a></li>\n</ul>\n"
                ),
                array(
                    'id' => 'footer',
                    'type' => 'ace_editor',
                    'title' => __('Footer HTML', AZEXO_THEME_NAME),
                    'subtitle' => __('Paste your HTML code here.', AZEXO_THEME_NAME),
                    'mode' => 'html',
                    'theme' => 'monokai',
                    'default' => "<ul class=\"social-icons\">\n<li><a href=\"#\"><i class='fa fa-facebook'></i></a></li>\n<li><a href=\"#\"><i class='fa fa-twitter'></i></a></li>\n<li><a href=\"#\"><i class='fa fa-pinterest'></i></a></li>\n</ul>\n<p>Copyright © 2015 AZEXO</p>"
                ),
//                array(
//                    'id' => 'smooth_page_scroll',
//                    'type' => 'checkbox',
//                    'title' => __('Smooth page scroll', AZEXO_THEME_NAME),
//                ),
                array(
                    'id' => 'custom-css',
                    'type' => 'ace_editor',
                    'title' => __('CSS Code', AZEXO_THEME_NAME),
                    'subtitle' => __('Paste your CSS code here.', AZEXO_THEME_NAME),
                    'mode' => 'css',
                    'theme' => 'monokai',
                    'default' => "#header{\nmargin: 0 auto;\n}"
                ),
                array(
                    'id' => 'custom-js',
                    'type' => 'ace_editor',
                    'title' => __('JS Code', AZEXO_THEME_NAME),
                    'subtitle' => __('Paste your JS code here.', AZEXO_THEME_NAME),
                    'mode' => 'javascript',
                    'theme' => 'chrome',
                    'default' => "jQuery(document).ready(function(){\n\n});"
                ),
            );

            $options = get_option(AZEXO_THEME_NAME);
            if (isset($options['show_logo']) && $options['show_logo']) {
                array_unshift($general_settings_fields, array(
                    'id' => 'logo',
                    'type' => 'media',
                    'title' => __('Logo', AZEXO_THEME_NAME),
                    'subtitle' => __('Upload any media using the WordPress native uploader', AZEXO_THEME_NAME),
                ));
            }

            $skins = azexo_get_skins();
            array_unshift($general_settings_fields, array(
                'id' => 'skin',
                'type' => 'select',
                'title' => __('Select skin', AZEXO_THEME_NAME),
                'options' => array_combine($skins, $skins),
                'default' => reset($skins),
            ));

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => __('General settings', AZEXO_THEME_NAME),
                'fields' => $general_settings_fields
            );


            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => __('Templates configuration', AZEXO_THEME_NAME),
                'fields' => array(
                    array(
                        'id' => 'header_sidebar_fullwidth',
                        'type' => 'checkbox',
                        'title' => __('Header sidebar fullwidth', AZEXO_THEME_NAME),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'middle_sidebar_fullwidth',
                        'type' => 'checkbox',
                        'title' => __('Middle sidebar fullwidth', AZEXO_THEME_NAME),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'footer_sidebar_fullwidth',
                        'type' => 'checkbox',
                        'title' => __('Footer sidebar fullwidth', AZEXO_THEME_NAME),
                        'default' => '0'
                    ),
                    array(
                        'id' => 'show_page_title',
                        'type' => 'checkbox',
                        'title' => __('Show page title in templates', AZEXO_THEME_NAME),
                        'default' => '1'
                    ),
                    array(
                        'id' => 'show_logo',
                        'type' => 'checkbox',
                        'title' => __('Show logo in header', AZEXO_THEME_NAME),
                        'default' => '0'
                    ),
                    array(
                        'id' => 'show_search',
                        'type' => 'checkbox',
                        'title' => __('Show search form in header', AZEXO_THEME_NAME),
                        'default' => '0'
                    ),
                    array(
                        'id' => 'show_breadcrumbs',
                        'type' => 'checkbox',
                        'title' => __('Show breadcrumbs in title', AZEXO_THEME_NAME),
                        'default' => '0'
                    ),
                    array(
                        'id' => 'post_navigation',
                        'type' => 'select',
                        'title' => __('Post navigation place', AZEXO_THEME_NAME),
                        'options' => array(
                            'hidden' => __('Hidden', AZEXO_THEME_NAME),
                            'before' => __('Before content', AZEXO_THEME_NAME),
                            'after' => __('After content', AZEXO_THEME_NAME),
                        ),
                        'default' => 'hidden',
                    ),
                    array(
                        'id' => 'post_navigation_previous',
                        'type' => 'text',
                        'title' => __('Post navigation previous text', AZEXO_THEME_NAME),
                        'default' => '',
                    ),
                    array(
                        'id' => 'post_navigation_next',
                        'type' => 'text',
                        'title' => __('Post navigation next text', AZEXO_THEME_NAME),
                        'default' => '',
                    ),
                    array(
                        'id' => 'default_title',
                        'type' => 'text',
                        'title' => __('Default page title', AZEXO_THEME_NAME),
                        'default' => 'Latest posts',
                    ),
                    array(
                        'id' => 'post_page_title',
                        'type' => 'select',
                        'title' => __('Post page title', AZEXO_THEME_NAME),
                        'options' => $post_field_names,
                        'default' => '',
                    ),
                    array(
                        'id' => 'excerpt_length',
                        'type' => 'text',
                        'title' => __('Excerpt length', AZEXO_THEME_NAME),
                        'default' => '15',
                    ),
                )
            );


            foreach ($azexo_templates as $template_slug => $template_name) {


                $places = array(
                    $template_slug . '_thumbnail' => $template_name . ' ' . __('thumbnail', AZEXO_THEME_NAME),
                    $template_slug . '_extra' => $template_name . ' ' . __('extra', AZEXO_THEME_NAME),
                    $template_slug . '_meta' => $template_name . ' ' . __('meta', AZEXO_THEME_NAME),
                    $template_slug . '_header' => $template_name . ' ' . __('header', AZEXO_THEME_NAME),
                    $template_slug . '_data' => $template_name . ' ' . __('data', AZEXO_THEME_NAME),
                    $template_slug . '_footer' => $template_name . ' ' . __('footer', AZEXO_THEME_NAME),
                );
                $post_fields = array();
                foreach ($places as $id => $name) {
                    $post_fields[] = array(
                        'id' => $id,
                        'type' => 'select',
                        'multi' => true,
                        'sortable' => true,
                        'title' => $name,
                        'options' => $post_field_names
                    );
                }

                $this->sections[] = array(
                    'icon' => 'el-icon-cogs',
                    'title' => $template_name . ' ' . __('template configuration', AZEXO_THEME_NAME),
                    'subsection' => true,
                    'fields' => array_merge(array(
                        array(
                            'id' => $template_slug . '_show_thumbnail',
                            'type' => 'checkbox',
                            'title' => __('Show thumbnail', AZEXO_THEME_NAME),
                            'default' => '1'
                        ),
                        array(
                            'id' => $template_slug . '_image_thumbnail',
                            'type' => 'checkbox',
                            'title' => __('Only image thumbnail', AZEXO_THEME_NAME),
                            'default' => '0'
                        ),
                        array(
                            'id' => $template_slug . '_gallery_slider_thumbnails',
                            'type' => 'checkbox',
                            'title' => __('Show gallery slider thumbnails', AZEXO_THEME_NAME),
                            'default' => '0'
                        ),
                        array(
                            'id' => $template_slug . '_show_content',
                            'type' => 'select',
                            'title' => __('Show content/excerpt', AZEXO_THEME_NAME),
                            'options' => array(
                                'hidden' => __('Hidden', AZEXO_THEME_NAME),
                                'content' => __('Show content', AZEXO_THEME_NAME),
                                'excerpt' => __('Show excerpt', AZEXO_THEME_NAME),
                            ),
                            'default' => 'content',
                        ),
                        array(
                            'id' => $template_slug . '_more_inside_content',
                            'type' => 'checkbox',
                            'title' => __('Show more link inside content', AZEXO_THEME_NAME),
                            'default' => '1'
                        ),
                        array(
                            'id' => $template_slug . '_date_prefix',
                            'type' => 'text',
                            'title' => __('Date prefix', AZEXO_THEME_NAME),
                            'default' => '',
                        ),
                        array(
                            'id' => $template_slug . '_author_prefix',
                            'type' => 'text',
                            'title' => __('Author prefix', AZEXO_THEME_NAME),
                            'default' => '',
                        ),
                        array(
                            'id' => $template_slug . '_category_prefix',
                            'type' => 'text',
                            'title' => __('Category prefix', AZEXO_THEME_NAME),
                            'default' => '',
                        ),
                        array(
                            'id' => $template_slug . '_tags_prefix',
                            'type' => 'text',
                            'title' => __('Tags prefix', AZEXO_THEME_NAME),
                            'default' => '',
                        ),
                        array(
                            'id' => $template_slug . '_share_prefix',
                            'type' => 'text',
                            'title' => __('Share prefix', AZEXO_THEME_NAME),
                            'default' => '',
                        ),
                        array(
                            'id' => $template_slug . '_share',
                            'type' => 'select',
                            'title' => __('Share place', AZEXO_THEME_NAME),
                            'options' => array(
                                'hidden' => __('Hidden', AZEXO_THEME_NAME),
                                'data' => __('Inside post data', AZEXO_THEME_NAME),
                                'thumbnail' => __('Inside post thumbnail', AZEXO_THEME_NAME),
                            ),
                            'default' => 'data',
                        ),
                        array(
                            'id' => $template_slug . '_thumbnail_size',
                            'type' => 'text',
                            'title' => __('Thumbnail size', AZEXO_THEME_NAME),
                            'default' => 'large',
                        ),
                            ), $post_fields)
                );
            }

            $theme_info = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', AZEXO_THEME_NAME) . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', AZEXO_THEME_NAME) . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', AZEXO_THEME_NAME) . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', AZEXO_THEME_NAME) . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon' => 'el-icon-list-alt',
                    'title' => __('Documentation', AZEXO_THEME_NAME),
                    'fields' => array(
                        array(
                            'id' => '17',
                            'type' => 'raw',
                            'markdown' => true,
                            'content' => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }

            $this->sections[] = array(
                'title' => __('Import / Export', AZEXO_THEME_NAME),
                'desc' => __('Import and Export your Redux Framework settings from file, text or URL.', AZEXO_THEME_NAME),
                'icon' => 'el-icon-refresh',
                'fields' => array(
                    array(
                        'id' => 'import-export',
                        'type' => 'import_export',
                        'title' => 'Import Export',
                        'subtitle' => 'Save and restore your Redux options',
                        'full_width' => false,
                    ),
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon' => 'el-icon-info-sign',
                'title' => __('Theme Information', AZEXO_THEME_NAME),
                'fields' => array(
                    array(
                        'id' => 'raw-info',
                        'type' => 'raw',
                        'content' => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon' => 'el-icon-book',
                    'title' => __('Documentation', AZEXO_THEME_NAME),
                    'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setArguments() {

            $theme = wp_get_theme();

            $this->args = array(
                'opt_name' => AZEXO_THEME_NAME,
                'page_slug' => '_options',
                'page_title' => 'Azexo Options',
                'update_notice' => true,
                'admin_bar' => false,
                'menu_type' => 'menu',
                'menu_title' => 'Azexo Options',
                'allow_sub_menu' => true,
                'page_parent_post_type' => 'your_post_type',
                'customizer' => true,
                'default_mark' => '*',
                'hints' =>
                array(
                    'icon' => 'el-icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color' => 'lightgray',
                    'icon_size' => 'normal',
                    'tip_style' =>
                    array(
                        'color' => 'light',
                    ),
                    'tip_position' =>
                    array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' =>
                    array(
                        'show' =>
                        array(
                            'duration' => '500',
                            'event' => 'mouseover',
                        ),
                        'hide' =>
                        array(
                            'duration' => '500',
                            'event' => 'mouseleave unfocus',
                        ),
                    ),
                ),
                'output' => true,
                'output_tag' => true,
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => '3600',
                'network_sites' => true,
            );

            $theme = wp_get_theme();
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");
        }

    }

    global $reduxConfig;
    $reduxConfig = new AZEXO_Redux_Framework_config();
}