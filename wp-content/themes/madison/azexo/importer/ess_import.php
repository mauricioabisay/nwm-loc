<?php

require_once( WP_PLUGIN_DIR . '/essential-grid/essential-grid.php');
require_once( WP_PLUGIN_DIR . '/essential-grid/admin/includes/import.class.php');

function essential_grid_importer($folder) {
    $im = new Essential_Grid_Import();

    @$skins_json = file_get_contents($folder . '/ess-skins.json');
    if ($skins_json) {
        $skins = json_decode(str_replace('\\\\\\\\\\\\\\', '\\', $skins_json), true);
        $skins = $skins['skins'];
        $skin_ids = array();
        foreach ($skins as $skin) {
            $skin_ids[] = $skin['id'];
        }
        $im->import_skins($skins, $skin_ids);
    }

    @$navskins_json = file_get_contents($folder . '/ess-navskins.json');
    if ($navskins_json) {
        $navskins = json_decode(str_replace('\\\\\\\\', '\\\\\\', ($navskins_json)), true);
        $navskins = $navskins['navigation-skins'];        
        $navskin_ids = array();
        foreach ($navskins as $navskin) {
            $navskin_ids[] = $navskin['id'];
        }
        $im->import_navigation_skins($navskins, $navskin_ids);
    }

    @$elements_json = file_get_contents($folder . '/ess-elements.json');
    if ($elements_json) {
        $elements = json_decode(str_replace('\\\\\\\\', '\\\\\\', ($elements_json)), true);
        $im->import_elements($elements['elements'], array());
    }

    @$globalcss = file_get_contents($folder . '/ess-global.css');
    if ($globalcss) {
        $im->import_global_styles($globalcss);
    }

    $fonts_array = array();
    //$fonts_array[] = array("url" => "Source+Sans+Pro:200,300,400,600,700,900", "handle" => "sourcesans");
    if (!empty($fonts_array)) {
        $im->import_punch_fonts($fonts_array);
    }

    @$grids = file_get_contents($folder . '/ess-grids.json');
    if ($grids) {
        @$essgrid_images = file_get_contents($folder . '/essgrid-images.json');
        if ($essgrid_images) {
            $essgrid_images = json_decode($essgrid_images, true);
            foreach ($essgrid_images as $essgrid_image_id => $essgrid_image_basename) {
                $attach_id = create_image($essgrid_image_basename);
                $grids = str_replace('"custom-image\\\\\\\\\\\\\\":\\\\\\\\\\\\\\"' . $essgrid_image_id, '"custom-image\\\\\\\\\\\\\\":\\\\\\\\\\\\\\"' . $attach_id, $grids);
                $grids = str_replace('"eg-clients-icon\\\\\\\\\\\\\\":\\\\\\\\\\\\\\"' . $essgrid_image_id, '"eg-clients-icon\\\\\\\\\\\\\\":\\\\\\\\\\\\\\"' . $attach_id, $grids);
            }
        }


        $grids = json_decode($grids, true);
        $new_grids = array();
        $im->import_grids($grids['grids']);
    }

    @$meta_json = file_get_contents($folder . '/ess-meta.json');
    if ($meta_json) {
        $meta = json_decode(str_replace('\\\\\\\\', '\\\\\\', ($meta_json)), true);        
        $im->import_custom_meta($meta['custom-meta']);
    }
}

function create_image($file) {
    if (empty($file))
        return false;
    $image_url = T_THEME . '/assets/images/demo/' . $file;
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if (wp_mkdir_p($upload_dir['path']))
        $file = $upload_dir['path'] . '/' . $filename;
    else
        $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $file);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);
    return $attach_id;
}

?>