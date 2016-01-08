<?php
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class Azexo_Export {

    function Azexo_Export() {
        add_action('admin_menu', array(&$this, 'azexo_admin_export'));
    }

    function init_azexo_export() {
        if (isset($_REQUEST['export_option'])) {
            $export_option = $_REQUEST['export_option'];
            if ($export_option == 'widgets') {
                $this->export_widgets_sidebars();
            } elseif ($export_option == 'azexo_options') {
                $this->export_options();
            } elseif ($export_option == 'azexo_menus') {
                $this->export_azexo_menus();
            }
        }
    }

    public function export_options() {
        $output = array(
            AZEXO_THEME_NAME => get_option(AZEXO_THEME_NAME),
        );
        $output = base64_encode(serialize($output));
        $this->save_as_txt_file("options.txt", $output);
    }

    public function export_widgets_sidebars() {
        $data = array();
        $data['sidebars'] = $this->export_sidebars();
        $data['widgets'] = $this->export_widgets();
        $output = base64_encode(serialize($data));
        $this->save_as_txt_file("widgets.txt", $output);
    }

    public function export_widgets() {

        global $wp_registered_widgets;
        $all_azexo_widgets = array();

        foreach ($wp_registered_widgets as $azexo_widget_id => $widget_params)
            $all_azexo_widgets[] = $widget_params['callback'][0]->id_base;

        foreach ($all_azexo_widgets as $azexo_widget_id) {
            $azexo_widget_data = get_option('widget_' . $azexo_widget_id);
            if (!empty($azexo_widget_data))
                $widget_datas[$azexo_widget_id] = $azexo_widget_data;
        }
        unset($all_azexo_widgets);
        return $widget_datas;
    }

    public function export_sidebars() {
        $azexo_sidebars = get_option("sidebars_widgets");
        $azexo_sidebars = $this->exclude_sidebar_keys($azexo_sidebars);
        return $azexo_sidebars;
    }

    private function exclude_sidebar_keys($keys = array()) {
        if (!is_array($keys))
            return $keys;

        unset($keys['wp_inactive_widgets']);
        unset($keys['array_version']);
        return $keys;
    }

    public function export_azexo_menus() {
        global $wpdb;

        $data = array();
        $locations = get_nav_menu_locations();

        foreach ((array) $locations as $location => $menu_id) {
            $menu_slug = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}terms where term_id=%d", $menu_id), ARRAY_A);
            $data[$location] = $menu_slug[0]['slug'];
        }
        $output = base64_encode(serialize($data));
        $this->save_as_txt_file("menus.txt", $output);
    }

    function save_as_txt_file($file_name, $output) {
        header("Content-type: application/text", true, 200);
        header("Content-Disposition: attachment; filename=$file_name");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $output;
        exit;
    }

    function azexo_admin_export() {
        if (isset($_REQUEST['export'])) {
            $this->init_azexo_export();
        }
        //Add the Azexo options page to the Themes' menu
        add_menu_page('Azexo Theme', esc_html__('Azexo Export', AZEXO_THEME_NAME), 'manage_options', 'azexo_options_export_page', array(&$this, 'azexo_generate_export_page'));
    }

    function azexo_generate_export_page() {
        ?>
        <div class="wrapper">
            <div class="content">
                <table class="form-table">
                    <tbody>
                        <tr><td scope="row" width="150"><h2><?php esc_html_e('Export', AZEXO_THEME_NAME); ?></h2></td></tr>
                        <tr valign="middle">

                            <td>
                                <!--                                <form method="post" action="">
                                                                    <input type="hidden" name="export_option" value="widgets" />
                                                                    <input type="submit" value="Export Widgets" name="export" />
                                                                </form>
                                                                <br />-->
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="azexo_options" />
                                    <input type="submit" value="Export Options" name="export" />
                                </form>
                                <br />
                                <form method="post" action="">
                                    <input type="hidden" name="export_option" value="azexo_menus" />
                                    <input type="submit" value="Export Menus" name="export" />
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }

}

$my_Azexo_Export = new Azexo_Export();


