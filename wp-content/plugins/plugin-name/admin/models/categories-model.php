<?php
/**
 * 
 */
class Categories_Model {

	public function create($data) {
		global $wpdb;
		$wpdb->insert($wpdb->prefix.'nwm_catalog_categories', $data);
		return $wpdb->insert_id;	
	}
	
	public function get() {
		global $wpdb;
		return $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'nwm_catalog_categories');
	}
}