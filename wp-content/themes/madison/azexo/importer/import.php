<?php
if (!defined('ABSPATH'))
    exit;

class azexoImport {

    public $base_urls = array(
        'default' => 'http://www.azexo.com/fractal',
    );

    function __construct() {
        add_action('admin_menu', array(&$this, 'init'));
    }

    function init() {
        add_theme_page(
                'Azexo Import Configuration', 'Azexo Import', 'edit_theme_options', 'azexo_import', array(&$this, 'import')
        );

        wp_enqueue_style('azexo.import', AZEXO_THEME_URI . '/azexo/importer/import.css', false, time(), 'all');
        wp_enqueue_script('azexo.import', AZEXO_THEME_URI . '/azexo/importer/import.js', false, time(), true);
    }

    function array_filter_recursive($input, $callback = null) {
        if (is_array($input)) {
            foreach ($input as &$value) {
                $value = $this->array_filter_recursive($value, $callback);
            }
            return $input;
        } else {
            return $callback($input);
        }
    }

    function import_content($file = 'content.xml') {
        $xml = AZEXO_THEME_DIR . '/azexo/importer/data/' . $file;
        if (file_exists($xml)) {
            $import = new WP_Import();
            $import->fetch_attachments = ( $_POST && key_exists('attachments', $_POST) && $_POST['attachments'] ) ? true : false;

            ob_start();
            $import->import($xml);
            ob_end_clean();

            // set home & blog page
            $home = get_page_by_title('Home');
            $blog = get_page_by_title('Journal');
            if ($home->ID && $blog->ID) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $home->ID); // Front Page
                update_option('page_for_posts', $blog->ID); // Blog Page
            }
        }
    }

    public function import_menus($file = 'menus.txt') {
        $file_path = AZEXO_THEME_URI . '/azexo/importer/data/' . $file;
        $file_data = wp_remote_get($file_path);
        if (!is_wp_error($file_data)) {
            global $wpdb;
            $data = unserialize(base64_decode($file_data['body']));
            $menu_array = array();
            foreach ($data as $registered_menu => $menu_slug) {
                $term_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}terms where slug=%s", $menu_slug), ARRAY_A);
                if (isset($term_rows[0]['term_id'])) {
                    $term_id_by_slug = $term_rows[0]['term_id'];
                } else {
                    $term_id_by_slug = null;
                }
                $menu_array[$registered_menu] = $term_id_by_slug;
            }
            set_theme_mod('nav_menu_locations', array_map('absint', $menu_array));
        }
    }

    function import_menu_location($file = 'menus.txt') {
        $file_path = AZEXO_THEME_URI . '/azexo/importer/data/' . $file;
        $file_data = wp_remote_get($file_path);
        if (!is_wp_error($file_data)) {
            $data = unserialize(base64_decode($file_data['body']));
            $menus = wp_get_nav_menus();

            foreach ($data as $key => $val) {
                foreach ($menus as $menu) {
                    if ($menu->slug == $val) {
                        $data[$key] = absint($menu->term_id);
                    }
                }
            }

            set_theme_mod('nav_menu_locations', $data);
        }
    }

    function import_options($file = 'options.txt', $url = false) {
        $file_path = AZEXO_THEME_URI . '/azexo/importer/data/' . $file;
        $file_data = wp_remote_get($file_path);
        if (!is_wp_error($file_data)) {
            $data = unserialize(base64_decode($file_data['body']));
            if (is_array($data)) {
                if ($url) {
                    $replace = home_url('/');
                    foreach ($data as $name => $option) {
                        if (is_array($option)) {
                            foreach ($option as $key => $op) {
                                if (is_string($op)) {
                                    $data[$name][$key] = str_replace($url, $replace, $op);
                                }
                            }
                        }
                    }
                }
                foreach ($data as $name => $option) {
                    update_option($name, $option);
                }
            }
        }
    }

    function import_widgets($file = 'widget_data.json') {
        $file_path = AZEXO_THEME_URI . '/azexo/importer/data/' . $file;
        $file_data = wp_remote_get($file_path);
        if (!is_wp_error($file_data)) {
            $data = $file_data['body'];
            $this->import_widgets_data($data);
        }
    }

    function import_grids($folder = '') {
        require_once( AZEXO_THEME_DIR . '/azexo/importer/ess_import.php');
        essential_grid_importer(AZEXO_THEME_URI . '/azexo/importer/data/' . $folder);
    }

    function import() {

        if (key_exists('azexo_import_nonce', $_POST)) {
            if (wp_verify_nonce($_POST['azexo_import_nonce'], basename(__FILE__))) {

                // Importer classes
                if (!defined('WP_LOAD_IMPORTERS'))
                    define('WP_LOAD_IMPORTERS', true);

                if (!class_exists('WP_Importer')) {
                    require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                }

                if (!class_exists('WP_Import')) {
                    require_once AZEXO_THEME_DIR . '/azexo/importer/wordpress-importer.php';
                }

                if (class_exists('WP_Importer') && class_exists('WP_Import')) {

                    switch ($_POST['import']) {

                        case 'all':
                            // Full Demo Data ---------------------------------
                            $this->import_content();
                            $this->import_menus();
                            $this->import_options();
                            $this->import_widgets();
                            //$this->import_grids();
                            break;

                        case 'demo':
                            // Single Demo Data ---------------------------------
                            $_POST['demo'] = htmlspecialchars(stripslashes($_POST['demo']));

                            $file = 'demo/' . $_POST['demo'] . '/content.xml';
                            $this->import_content($file);

                            $file = 'demo/' . $_POST['demo'] . '/menus.txt';
                            $this->import_menus($file);

                            $file = 'demo/' . $_POST['demo'] . '/options.txt';
                            $this->import_options($file, $this->urls[$_POST['demo']]);

                            $file = 'demo/' . $_POST['demo'] . '/widget_data.json';
                            $this->import_widgets($file);

                            $folder = 'demo/' . $_POST['demo'];
                            $this->import_grids($folder);

                            break;

                        case 'content':
                            if ($_POST['content']) {
                                $_POST['content'] = htmlspecialchars(stripslashes($_POST['content']));
                                $file = 'content/' . $_POST['content'] . '.xml.gz';
                                $this->import_content($file);
                            } else {
                                $this->import_content();
                            }
                            break;

                        case 'options':
                            // Theme Options ----------------------------------
                            $this->import_options();
                            break;

                        case 'widgets':
                            // Widgets ----------------------------------------
                            $this->import_widgets();
                            break;
                        case 'grids':
                            // Grids ----------------------------------------
                            $this->import_grids();
                            break;

                        default:
                            // Empty select.import
                            $this->error = __('Please select data to import.', AZEXO_THEME_NAME);
                            break;
                    }

                    // message box
                    if (isset($this->error)) {
                        echo '<div class="error settings-error">';
                        echo '<p><strong>' . $this->error . '</strong></p>';
                        echo '</div>';
                    } else {
                        echo '<div class="updated settings-error">';
                        echo '<p><strong>' . __('All done. Have fun!', AZEXO_THEME_NAME) . '</strong></p>';
                        echo '</div>';
                    }
                }
            }
        }
        ?>
        <div id="azexo-wrapper" class="azexo-import wrap">

            <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

            <form action="" method="post">

                <input type="hidden" name="azexo_import_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />

                <table class="form-table">

                    <tr class="row-import">
                        <th scope="row">
                            <label for="import">Import</label>
                        </th>
                        <td>
                            <select name="import" class="import">
                                <option value="">-- Select --</option>
                                <option value="all">All</option>
                                <!--<option value="demo">Demo</option>-->
                                <option value="content">Demo content</option>
                                <option value="options">Options</option>
                                <option value="widgets">Widgets</option>
                                <!--<option value="grids">Grids</option>-->
                            </select>
                        </td>
                    </tr>

                    <tr class="row-demo hide">
                        <th scope="row">
                            <label for="demo">Demo</label>
                        </th>
                        <td>
                            <select name="demo">
                                <option value="azexo">Azexo</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="row-content hide">
                        <th scope="row">
                            <label for="content">Demo content</label>
                        </th>
                        <td>
                            <select name="content">
                                <option value="">-- All --</option>
                                <option value="pages">Pages</option>
                                <option value="posts">Posts</option>
                                <option value="portfolio">Portfolio</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="row-attachments hide">
                        <th scope="row">Attachments</th>
                        <td>
                            <fieldset>
                                <label for="attachments"><input type="checkbox" value="1" id="attachments" name="attachments">Import attachments</label>
                                <p class="description">Download all attachments from the demo may take a while. Please be patient.</p>
                            </fieldset>
                        </td>
                    </tr>

                </table>

                <input type="submit" name="submit" class="button button-primary" value="Import data" />

            </form>

        </div>	
        <?php
    }

    /** ---------------------------------------------------------------------------
     * Parse JSON import file
     * http://wordpress.org/plugins/widget-settings-importexport/
     * ---------------------------------------------------------------------------- */
    function import_widgets_data($json_data) {

        $json_data = json_decode($json_data, true);

        $sidebar_data = $json_data[0];
        $widget_data = $json_data[1];

        // prepare widgets table
        $widgets = array();
        foreach ($widget_data as $k_w => $widget_type) {
            if ($k_w) {
                $widgets[$k_w] = array();
                foreach ($widget_type as $k_wt => $widget) {
                    if (is_int($k_wt))
                        $widgets[$k_w][$k_wt] = 1;
                }
            }
        }

        // sidebars
        foreach ($sidebar_data as $title => $sidebar) {
            $count = count($sidebar);
            for ($i = 0; $i < $count; $i++) {
                $widget = array();
                $widget['type'] = trim(substr($sidebar[$i], 0, strrpos($sidebar[$i], '-')));
                $widget['type-index'] = trim(substr($sidebar[$i], strrpos($sidebar[$i], '-') + 1));
                if (!isset($widgets[$widget['type']][$widget['type-index']])) {
                    unset($sidebar_data[$title][$i]);
                }
            }
            $sidebar_data[$title] = array_values($sidebar_data[$title]);
        }

        // widgets
        foreach ($widgets as $widget_title => $widget_value) {
            foreach ($widget_value as $widget_key => $widget_value) {
                $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
            }
        }

        $sidebar_data = array(array_filter($sidebar_data), $widgets);
        $this->parse_import_data($sidebar_data);
    }

    /** ---------------------------------------------------------------------------
     * Import widgets
     * http://wordpress.org/plugins/widget-settings-importexport/
     * ---------------------------------------------------------------------------- */
    function parse_import_data($import_array) {
        $sidebars_data = $import_array[0];
        $widget_data = $import_array[1];

        $current_sidebars = get_option('sidebars_widgets');
        $new_widgets = array();

        foreach ($sidebars_data as $import_sidebar => $import_widgets) :

            foreach ($import_widgets as $import_widget) :

                // if NOT the sidebar exists
                if (!isset($current_sidebars[$import_sidebar])) {
                    $current_sidebars[$import_sidebar] = array();
                }

                $title = trim(substr($import_widget, 0, strrpos($import_widget, '-')));
                $index = trim(substr($import_widget, strrpos($import_widget, '-') + 1));
                $current_widget_data = get_option('widget_' . $title);
                $new_widget_name = $this->get_new_widget_name($title, $index);
                $new_index = trim(substr($new_widget_name, strrpos($new_widget_name, '-') + 1));

                if (!empty($new_widgets[$title]) && is_array($new_widgets[$title])) {
                    while (array_key_exists($new_index, $new_widgets[$title])) {
                        $new_index++;
                    }
                }
                $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
                if (array_key_exists($title, $new_widgets)) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];

                    // notice fix
                    if (!key_exists('_multiwidget', $new_widgets[$title]))
                        $new_widgets[$title]['_multiwidget'] = '';

                    $multiwidget = $new_widgets[$title]['_multiwidget'];
                    unset($new_widgets[$title]['_multiwidget']);
                    $new_widgets[$title]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];

                    // notice fix
                    if (!key_exists('_multiwidget', $current_widget_data))
                        $current_widget_data['_multiwidget'] = '';

                    $current_multiwidget = $current_widget_data['_multiwidget'];
                    $new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
                    $multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
                    unset($current_widget_data['_multiwidget']);
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[$title] = $current_widget_data;
                }

            endforeach;
        endforeach;

        if (isset($new_widgets) && isset($current_sidebars)) {
            update_option('sidebars_widgets', $current_sidebars);

            foreach ($new_widgets as $title => $content)
                update_option('widget_' . $title, $content);

            return true;
        }

        return false;
    }

    /** ---------------------------------------------------------------------------
     * Get new widget name
     * http://wordpress.org/plugins/widget-settings-importexport/
     * ---------------------------------------------------------------------------- */
    function get_new_widget_name($widget_name, $widget_index) {
        $current_sidebars = get_option('sidebars_widgets');
        $all_widget_array = array();
        foreach ($current_sidebars as $sidebar => $widgets) {
            if (!empty($widgets) && is_array($widgets) && $sidebar != 'wp_inactive_widgets') {
                foreach ($widgets as $widget) {
                    $all_widget_array[] = $widget;
                }
            }
        }
        while (in_array($widget_name . '-' . $widget_index, $all_widget_array)) {
            $widget_index++;
        }
        $new_widget_name = $widget_name . '-' . $widget_index;
        return $new_widget_name;
    }

}

$azexo_import = new azexoImport;
?>
