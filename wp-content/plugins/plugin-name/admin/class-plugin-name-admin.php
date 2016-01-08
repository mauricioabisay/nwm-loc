<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Admin Controller
	 */
	public $controller;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		require_once 'admin-controller.php';
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->controller = new Admin_Controller();
	}

	public function admin_bar_menu($wp_admin_bar) {
		$wp_admin_bar->remove_node('site-name');
		$wp_admin_bar->remove_node('wp-logo');
		$wp_admin_bar->remove_node('edit-profile');
		$args = array(
			'id' => 'gabinete-favoritas',
			'title' => 'Gabinete - Favoritas',
			'parent' => 'user-actions',
			'href' => get_permalink(get_page_by_title('Gabinete')).'?status=Favorite'
		);
		$wp_admin_bar->add_node($args);
		$args = array(
			'id' => 'gabinete-por-probar',
			'title' => 'Gabiente - Por Probar',
			'parent' => 'user-actions',
			'href' => get_permalink(get_page_by_title('Gabinete')).'?status=ForLater'
		);
		$wp_admin_bar->add_node($args);
		$args = array(
			'id' => 'gabinete-por-calificar',
			'title' => 'Gabinete - Por Calificar',
			'parent' => 'user-actions',
			'href' => get_permalink(get_page_by_title('Gabinete')).'?status=Rated'
		);
		$wp_admin_bar->add_node($args);
		$args = array(
			'id' => 'gabinete-catas',
			'title' => 'Gabinete - Catas',
			'parent' => 'user-actions',
			'href' => get_permalink(get_page_by_title('Gabinete')).'?status=Reviewed'
		);
		$wp_admin_bar->add_node($args);
		$args = array(
			'id' => 'cerrar-sesion',
			'title' => 'Cerrar Sesión',
			'parent' => 'user-actions',
			'href' => wp_logout_url(home_url('/'))
		);
		$wp_admin_bar->add_node($args);
	}

	/**
	 * Register the menu and submenus for the admin area.
	 */
	public function admin_menu() {

		if(isset($_GET['post_type']) && $_GET['post_type'] == 'beer') {
			remove_meta_box( 'postcustom', 'beer',  'normal' );
		}

		if(isset($_GET['post_type']) && $_GET['post_type'] == 'review') {
			remove_action( 'media_buttons', 'media_buttons' );
			remove_meta_box( 'postcustom', 'review',  'normal' );
		}
		/*add_menu_page(
			'Main',
	        'NWM Catalog',
	        'manage_options',
	        'nwm-catalog',
	        array($this->controller, 'main'),
	        'dashicons-book',
	        '23.56'
        );
        add_submenu_page(
        	'nwm-catalog',
        	'Categories',
        	'Categories CRUD',
        	'manage_options',
        	'nwm-catalog-categories',
        	array($this->controller, 'categories')
        )
;        add_submenu_page(
        	'nwm-catalog',
        	'About',
        	'About',
        	'manage_options',
        	'nwm-catalog-about',
        	array($this->controller, 'about')
        );*/
	}

	public function post_types() {
		$beer_type_labels = array(
			'name' 								=> _x( 'Beer Types', 'types of beer' ),
			'singular_name' 			=> _x( 'Beer Type', 'type of beer' ),
			'search_items' 				=> __( 'Search Beer Types'),
			'all_items' 					=> __( 'All Beer Types' ),
			'parent_item'       	=> __( 'Parent Beer Type' ),
    	'parent_item_colon' 	=> __( 'Parent Beer Type:' ),
    	'edit_item'         	=> __( 'Edit Beer Type' ),
    	'update_item'       	=> __( 'Update Beer Type' ),
    	'add_new_item'      	=> __( 'Add New Beer Type' ),
    	'new_item_name'     	=> __( 'New Beer Type' ),
    	'menu_name'         	=> __( 'Beer Types' ),
		);
		$beer_type_args = array(
			'labels' 				=> $beer_type_labels,
			'hierarchical' 	=> true,
			'meta_box_cb' 	=> array($this, 'to_select_box')
		);
		register_taxonomy( 'type_of_beer', 'beer', $beer_type_args );

		register_post_type(
			'beer',
			array(
	      'labels' => array(
		      'name' 								=> 'Beers',
		      'singular_name' 			=> 'Beer',
		      'add_new' 						=> 'Add New',
		      'add_new_item' 				=> 'Add New Beer',
		      'edit' 								=> 'Edit',
		      'edit_item' 					=> 'Edit Beer',
		      'new_item' 						=> 'New Beer',
		      'view' 								=> 'View',
		      'view_item' 					=> 'View Beer',
		      'search_items' 				=> 'Search Beers',
		      'not_found' 					=> 'No Beers found',
		      'not_found_in_trash' 	=> 'No Beers found in Trash',
		      'parent' 							=> 'Parent Beer'
	      ),
				'public' 				=> true,
	      'menu_position' => 15,
	      'supports' 			=> array( 'title', 'editor', 'comments', 'thumbnail'),
	      'taxonomies' 		=> array( '' ),
	      'menu_icon' 		=> 'dashicons-book',
	      'has_archive' 	=> true
	      //register_meta_box_cb
      )
		);

		register_post_type(
			'brewery',
			array(
	    	'labels' => array(
	                	'name' 								=> 'Breweries',
	                	'singular_name' 			=> 'Brewery',
		                'add_new' 						=> 'Add New',
		                'add_new_item' 				=> 'Add New Brewery',
		                'edit' 								=> 'Edit',
		                'edit_item' 					=> 'Edit Brewery',
		                'new_item'						=> 'New Brewery',
		                'view' 								=> 'View',
		                'view_item' 					=> 'View Brewery',
		                'search_items' 				=> 'Search Breweries',
		                'not_found' 					=> 'No Breweries found',
		                'not_found_in_trash' 	=> 'No Breweries found in Trash',
		                'parent' 							=> 'Parent Brewery'
	      ),
				'public' 				=> true,
	      'menu_position' => 15,
	      'supports' 			=> array( 'title', 'editor', 'comments', 'thumbnail' ),
	      'taxonomies' 		=> array( '' ),
	      'menu_icon' 		=> 'dashicons-book',
	      'has_archive' 	=> true
      )
		);

		$beer_aroma_labels = array(
			'name' 								=> _x( 'Beer Aromas', 'aromas of beer' ),
			'singular_name' 			=> _x( 'Beer Aroma', 'aroma of beer' ),
			'search_items' 				=> __( 'Search Beer Aromas'),
			'all_items' 					=> __( 'All Beer Aromas' ),
			'parent_item'       	=> __( 'Parent Beer Aroma' ),
    	'parent_item_colon' 	=> __( 'Parent Beer Aroma:' ),
    	'edit_item'         	=> __( 'Edit Beer Aroma' ),
    	'update_item'       	=> __( 'Update Beer Aroma' ),
    	'add_new_item'      	=> __( 'Add New Beer Aroma' ),
    	'new_item_name'     	=> __( 'New Beer Aroma' ),
    	'menu_name'         	=> __( 'Beer Aroma' ),
		);
		$beer_aroma_args = array(
			'labels' 				=> $beer_aroma_labels,
			'hierarchical' 	=> true,
			//'meta_box_cb' => array($this, 'aroma_to_cloud')
		);
		register_taxonomy( 'aroma_of_beer', 'review', $beer_aroma_args );

		$beer_flavor_labels = array(
			'name' 							=> _x( 'Beer Flavors', 'flavors of beer' ),
			'singular_name' 		=> _x( 'Beer Flavor', 'Flavor of beer' ),
			'search_items' 			=> __( 'Search Beer Flavors'),
			'all_items' 				=> __( 'All Beer Flavors' ),
			'parent_item'       => __( 'Parent Beer Flavor' ),
    	'parent_item_colon' => __( 'Parent Beer Flavor:' ),
    	'edit_item'         => __( 'Edit Beer Flavor' ),
    	'update_item'       => __( 'Update Beer Flavor' ),
    	'add_new_item'      => __( 'Add New Beer Flavor' ),
    	'new_item_name'     => __( 'New Beer Flavor' ),
    	'menu_name'         => __( 'Beer Flavor' ),
		);
		$beer_flavor_args = array(
			'labels' 				=> $beer_flavor_labels,
			'hierarchical' 	=> true,
			//'meta_box_cb' => array($this, 'flavor_to_cloud')
		);
		register_taxonomy( 'flavor_of_beer', 'review', $beer_flavor_args );

		register_post_type(
			'review',
			array(
	    	'labels' => array(
	                	'name' 								=> 'Reviews',
	                	'singular_name' 			=> 'Review',
	                	'add_new' 						=> 'Add New',
	                	'add_new_item' 				=> 'Add New Review',
	                	'edit' 								=> 'Edit',
		                'edit_item' 					=> 'Edit Review',
		                'new_item' 						=> 'New Review',
		                'view' 								=> 'View',
		                'view_item' 					=> 'View Review',
		                'search_items' 				=> 'Search Reviews',
		                'not_found' 					=> 'No Reviews found',
		                'not_found_in_trash' 	=> 'No Reviews found in Trash',
		                'parent' 							=> 'Parent Review'
	      ),
				'public' 				=> true,
	      'menu_position' => 15,
	      'supports' 			=> array( 'comments', 'thumbnail' ),
	      'taxonomies' 		=> array( '' ),
	      'menu_icon' 		=> 'dashicons-book',
	      'has_archive' 	=> true
      )
		);
	}

	public function admin_init() {
		if ( ! current_user_can( 'manage_options' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			wp_redirect( site_url().'/index.php/beer-list/' );
			exit;
		}
	}

	public function login_redirect() {
		wp_redirect( site_url().'/index.php/beer-list/' );
		exit;
	}

	public function add_meta_box() {
		add_meta_box(
			'beer_review_meta_box',
			'Beer Fields',
			array($this, 'display_beer_fields'),
			'beer',
			'normal',
			'high'
		);

		add_meta_box(
			'review_meta_box',
			'Beer',
			array($this, 'display_beer_select'),
			'review',
			'normal',
			'high'
		);
	}

	public function to_select_box($post){

		$taxonomy = 'type_of_beer';
		//Get all the terms in the custom taxonomy
		$terms = get_terms($taxonomy, array('hide_empty' => false));
		//Get the custom taxonomy terms associated with the current post
    	$postterms = get_the_terms( $post->ID, $taxonomy );
    	//To avoid problems use only one of the terms associated
    	$current = ($postterms ? array_pop($postterms) : false);
    	//Get the selected taxonomy term IDs
    	$current = ($current ? $current->term_id : 0);

    	include_once 'partials/type_of_beer_meta_box.html.php';
	}

	public function flavor_to_cloud($post){

		$taxonomy = 'flavor_of_beer';
		//Get all the terms in the custom taxonomy
		$terms = get_terms($taxonomy, array('hide_empty' => false));
		//Get the custom taxonomy terms associated with the current post
    	$postterms = get_the_terms( $post->ID, $taxonomy );
    	//To avoid problems use only one of the terms associated
    	$current = ($postterms ? array_pop($postterms) : false);
    	//Get the selected taxonomy term IDs
    	$current = ($current ? $current->term_id : 0);

    	include_once 'partials/cloud_flavor_meta_box.html.php';
	}

	public function aroma_to_cloud($post){

		$taxonomy = 'aroma_of_beer';
		//Get all the terms in the custom taxonomy
		$terms = get_terms($taxonomy, array('hide_empty' => false));
		//Get the custom taxonomy terms associated with the current post
    	$postterms = get_the_terms( $post->ID, $taxonomy );
    	//To avoid problems use only one of the terms associated
    	$current = ($postterms ? array_pop($postterms) : false);
    	//Get the selected taxonomy term IDs
    	$current = ($current ? $current->term_id : 0);

    	include_once 'partials/cloud_aroma_meta_box.html.php';
	}

	public function add_beer_review_link($actions, $post) {
		if ( $post->post_type != 'beer' ) {
	        return $actions;
	    }

	    $actions['review'] = '<a href="';
	    $actions['review'].= site_url('wp-admin/post-new.php?post_type=review&beer='.$post->ID.'&beer_title='.$post->post_title, 'admin');
	    $actions['review'].= '" title="Review" rel="permalink">Review</a>';
	    return $actions;
	}

	public function display_beer_fields($beer) {
		if(isset($_GET['post'])) {
		    $id = $_GET['post'];
		    $productor_value = get_post_meta( $id, 'beer_productor', true );
		    $origen_value = get_post_meta( $id, 'beer_origen', true );
		    $origen_estado_value = get_post_meta( $id, 'beer_origen_estado', true );
		    $rating_value = get_post_meta( $id, 'beer_rating', true );

		    $alcohol_value = get_post_meta( $id, 'beer_alcohol', true );
		    $amargor_value = get_post_meta( $id, 'beer_amargor', true );
		    $temperatura_value = get_post_meta( $id, 'beer_temperatura', true );
		    $vaso_value = get_post_meta( $id, 'beer_vaso', true );
		    $maridaje_value = get_post_meta( $id, 'beer_maridaje', true );
		    $color_value = get_post_meta( $id, 'beer_color', true );

				$total_puntos_venta = get_post_meta( $id, 'beer_punto_venta_total', true );
				$punto_venta_array = array();
				for($i = 1; $i <= $total_puntos_venta; $i++) {
					$punto_venta_array[] = array(
						'id'			=> $i,
						'nombre'	=> get_post_meta( $id, 'beer_punto_venta_nombre_'.$i, true ),
						'link'		=> get_post_meta( $id, 'beer_punto_venta_link_'.$i, true )
					);
				}

				$total_puntos_distribucion = get_post_meta( $id, 'beer_punto_distribucion_total', true );
				$punto_distribucion_array = array();
				for($i = 1; $i <= $total_puntos_distribucion; $i++) {
					$punto_distribucion_array[] = array(
						'id'			=> $i,
						'nombre'	=> get_post_meta( $id, 'beer_punto_distribucion_nombre_'.$i, true ),
						'link'		=> get_post_meta( $id, 'beer_punto_distribucion_link_'.$i, true )
					);
				}
		} else {
		    $productor_value = '';
		    $origen_value = 'México';
		    $origen_estado_value = 'Puebla';
		    $rating_value = '';

		    $alcohol_value = '';
		    $amargor_value = '';
		    $temperatura_value = '';
		    $vaso_value = '';
		    $maridaje_value = '';
		    $color_value = '';

		    $punto_venta_array = array();
		    $punto_distribucion_array = array();
		}
		include_once 'partials/meta-box-review.html.php';
	}

	public function display_beer_select($review) {
		include_once 'partials/meta-box-beer-select.html.php';
	}

	public function add_beer_fields($post_id) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		  return;
		}

		if ( !($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			return;
		}

		if ( isset($_POST['post_type']) && ('beer' == $_POST['post_type']) ) {
		  if ( !current_user_can( 'edit_page', $post_id ) ) {
		    return;
		  }
		} else {
		    if ( !current_user_can( 'edit_post', $post_id ) ){
		      return;
		    }
		}

		if ( isset($_POST['post_type']) && ('beer' == $_POST['post_type']) ) {
			$productor = $_POST['beer_productor'];
			$origen = $_POST['beer_origen'];
			$origen_estado = $_POST['beer_origen_estado'];
			$rating = $_POST['beer_rating'];

			$punto_venta_nombre = $_POST['punto_venta_nombre'];
			$punto_venta_link = $_POST['punto_venta_link'];

			$punto_distribucion_nombre = $_POST['punto_distribucion_nombre'];
			$punto_distribucion_link = $_POST['punto_distribucion_link'];

			update_post_meta( $post_id, 'beer_productor', $productor );
			update_post_meta( $post_id, 'beer_origen', $origen );
			update_post_meta( $post_id, 'beer_origen_estado', $origen_estado );
			update_post_meta( $post_id, 'beer_rating', $rating );

			update_post_meta( $post_id, 'beer_alcohol', $_POST['beer_alcohol'] );
    	update_post_meta( $post_id, 'beer_amargor', $_POST['beer_amargor'] );
    	update_post_meta( $post_id, 'beer_temperatura', $_POST['beer_temperatura'] );
    	update_post_meta( $post_id, 'beer_vaso', $_POST['beer_vaso'] );
    	update_post_meta( $post_id, 'beer_maridaje', $_POST['beer_maridaje'] );
    	update_post_meta( $post_id, 'beer_color', $_POST['beer_color'] );

			$len = get_post_meta( $post_id, 'beer_punto_venta_total', true );
			$len = ($len) ? $len : 0;
			$len_new = sizeof($punto_venta_nombre) + $len;
			$index = 0;
			for($i = $len; $i < $len_new; $i++) {
				update_post_meta( $post_id, 'beer_punto_venta_nombre_'.($i+1), $punto_venta_nombre[$index] );
				update_post_meta( $post_id, 'beer_punto_venta_link_'.($i+1), $punto_venta_link[$index] );
				$index++;
			}
			update_post_meta( $post_id, 'beer_punto_venta_total', $len_new);

			$len = get_post_meta( $post_id, 'beer_punto_distribucion_total', true );
			$len = ($len) ? $len : 0;
			$len_new = sizeof($punto_distribucion_nombre) + $len;
			$index = 0;
			for($i = $len; $i < $len_new; $i++) {
				update_post_meta( $post_id, 'beer_punto_distribucion_nombre_'.($i+1), $punto_distribucion_nombre[$index] );
				update_post_meta( $post_id, 'beer_punto_distribucion_link_'.($i+1), $punto_distribucion_link[$index] );
				$index++;
			}
			update_post_meta( $post_id, 'beer_punto_distribucion_total', $len_new);

		}
	}

	public function add_review_fields($post_id) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		  return;
		}

		if ( !($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			return;
		}

		if ( (isset($_POST['post_type'])) && ('review' == $_POST['post_type']) ) {
		  if ( !current_user_can( 'edit_page', $post_id ) ) {
		    return;
		  }
		} else {
		    if ( !current_user_can( 'edit_post', $post_id ) ){
		      return;
		    }
		}

		if ( (isset($_POST['post_type'])) && ('review' == $_POST['post_type']) ) {
			$cerveza_id = $_POST['beer_id'];
			$cerveza_name = $_POST['beer_name'];
			$color = $_POST['color'];
			$espuma = $_POST['espuma'];
			$alcohol = $_POST['alcohol'];
			$cuerpo = $_POST['cuerpo'];
			$final = $_POST['final'];
			$amargor = $_POST['amargor'];

			update_post_meta( $post_id, 'cerveza_id', $cerveza_id );
			update_post_meta( $post_id, 'cerveza_name', $cerveza_name );
			update_post_meta( $post_id, 'color', $color );
			update_post_meta( $post_id, 'espuma', $espuma );
			update_post_meta( $post_id, 'alcohol', $alcohol );
			update_post_meta( $post_id, 'cuerpo', $cuerpo );
			update_post_meta( $post_id, 'final', $final );
			update_post_meta( $post_id, 'amargor', $amargor );
			remove_action( 'save_post', array($this, 'add_review_fields') );
			wp_update_post( array ('ID' => $post_id, 'post_title' => 'Experiencia '.$cerveza_name ) );
			add_action( 'save_post', array($this, 'add_review_fields') );
		}
	}

	/**
	 * Register
	 * @return [type] [description]
	 */
	public function single_template($single_template) {
		global $post;
		if ($post->post_type == 'beer') {
			$single_template = plugin_dir_path( __FILE__ ) . 'template/single-beer.php';
		}
		return $single_template;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
		/*
		global $wp_scripts;
		// get registered script object for jquery-ui
		$ui = $wp_scripts->query('jquery-ui-core');

		$theme_name = 'pepper-grinder';

		// tell WordPress to load the Smoothness theme from Google CDN
		$url = '//ajax.googleapis.com/ajax/libs/jqueryui/' . urlencode($ui->ver) . '/themes/' . urlencode($theme_name) . '/jquery-ui.min.css';
		//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css"
		wp_enqueue_style('jquery-ui-' . $theme_name, $url, false, null);
		*/
		wp_enqueue_style( 'jquery-ui', plugin_dir_url( dirname( __FILE__ ) ) . 'css/jquery-ui.min.css');

	}

	public function ajax_search_brewery() {

	}

		/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'my-autocomplete', 'MyAutocomplete', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brewery-autocomplete.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/jquery.ui.touch-punch.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-button' );
	}

	public function thumbnail_html($content, $post_id) {
		echo '<p><label>URL: </label><input name="thumbnail_url" type="text" /></p>';
	}

	public function PDF_add_thumbnail_url($post_id) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		  return;
		}

		if ( !($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			return;
		}

		if ( 'post' == $_POST['post_type'] ) {
		  if ( !current_user_can( 'edit_page', $post_id ) ) {
		    return;
		  }
		} else {
		    if ( !current_user_can( 'edit_post', $post_id ) ){
		      return;
		    }
		}
		if ( 'post' == $_POST['post_type'] ) {
			$thumbnail_url = $_POST['thumbnail_url'];

			update_post_meta( $post_id, 'thumbnail_url', $thumbnail_url );
		}
	}
}
