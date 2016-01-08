<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function beer_template() {
		global $post;
		if ($post->post_type == 'beer') {
			$single_template = plugin_dir_path( __FILE__ ) . 'template/single-beer.php';
		}
		return $single_template;
	}

	public function post() {
		if ( isset($_POST['review_id']) ) {
			update_post_meta( $_POST['review_id'], 'color', $_POST['color'] );
      update_post_meta( $_POST['review_id'], 'espuma', $_POST['espuma'] );
      update_post_meta( $_POST['review_id'], 'alcohol', $_POST['alcohol'] );
      update_post_meta( $_POST['review_id'], 'cuerpo', $_POST['cuerpo'] );
      update_post_meta( $_POST['review_id'], 'final', $_POST['final'] );
      update_post_meta( $_POST['review_id'], 'amargor', $_POST['amargor'] );
      update_post_meta( $_POST['review_id'], 'rating', $_POST['rating'] );

      $cerveza_id = get_post_meta( $_POST['review_id'], 'cerveza_id', true );
			$flavors = $_POST['tax_input']['flavor_of_beer'];
			$aromas = $_POST['tax_input']['aroma_of_beer'];

			wp_set_post_terms( $_POST['review_id'], $flavors, 'flavor_of_beer' );
			wp_set_post_terms( $_POST['review_id'], $aromas, 'aroma_of_beer' );

			global $wpdb;

			$sql = 'SELECT * FROM nwm_loc_hielera ';
			$sql.= 'WHERE beer='.$cerveza_id.' ';
			$sql.= 'AND user='.get_current_user_id();

			if(($wpdb->get_row($sql)) == null) {
				$newCoolerRow = array(
					'user'			=>	get_current_user_id(),
					'beer'			=>	$cerveza_id,
					'review'		=>	1,
					'rate'			=>	1,
					'forlater'	=>	0,
					'favorite'	=> 	0,
					'status'		=>	'Review'
				);
				$wpdb->insert('nwm_loc_hielera', $newCoolerRow);
			} else {
				$updateCoolerRow = array(
					'status'		=>	'Review',
					'review'		=>	1
				);
				$wpdb->update(
					'nwm_loc_hielera',
					$updateCoolerRow,
					array(
						'user'			=>	get_current_user_id(),
						'beer'			=>	$cerveza_id
					)
				);
			}

    	$wpdb->insert('nwm_loc_hielera', array('user'=>get_current_user_id(),'beer'=>$cerveza_id));

    	$wpdb->update('nwm_loc_hielera',
    		array('status'=>'Reviewed','review'=>1,'rate'=>1),
    		array('user'=>get_current_user_id(),'beer'=>$cerveza_id)
    	);
		}
	}

	public function create_shortcodes() {
		add_shortcode( 'nwm_beer_review_view', array($this, 'review_view') );
		add_shortcode( 'nwm_beer_review_form', array( $this, 'review_form' ) );
		add_shortcode( 'nwm_beer_list_abc', array( $this, 'list_abc' ) );
		add_shortcode( 'nwm_beer_list_random', array( $this, 'list_random' ) );
		add_shortcode( 'nwm_beer_list_news', array( $this, 'list_news' ) );
		add_shortcode( 'nwm_beer_list_suggest', array( $this, 'list_suggest' ) );
		add_shortcode( 'nwm_beer_list_hielera', array( $this, 'list_hielera' ) );
		add_shortcode( 'nwm_recent_post_footer', array( $this, 'recent_post_footer' ) );
	}

	public function review_form() {
		//Verificar si nos han proporcionado el ID de la cerveza a calificar
		if(isset($_GET['beer_id'])) {
			global $current_user;
			global $wpdb;
		  get_currentuserinfo();

		  $cerveza_data = get_post( $_GET['beer_id'] );
			//Obtenemos el review de la CERVEZA del usuario actual
		  $user_reviews = get_posts( array(
		      'post_type' 		 => 'review',
		      'posts_per_page' => -1,
		      'order_by' 			 => 'post_date',
		      'post_status'    => 'private',
		      'author' 				 => $current_user->ID,
		      'meta_key' 			 => 'cerveza_id',
		      'meta_value'		 => $cerveza_data->ID
		  ) );
			//Si no se ha hecho un review creamos el esqueleto del review y obtenemos los valores
			//por default para el review del GRAN MAESTRE
		  if( empty($user_reviews) ) {
				$post = array(
		    	'post_name'      => $current_user->display_name.'|Experiencia '.$cerveza_data->post_title,
					'post_title'     => $current_user->display_name.'|Experiencia '.$cerveza_data->post_title,
					'post_content'   => '',
					'post_status'    => 'private',
					'post_type'      => 'review',
					'post_author'    => $current_user->ID,
					'post_date'      => date('Y-m-d H:i:s'), // The time post was made.
					'comment_status' => 'closed'
					//'page_template'  => [ <string> ] Requires name of template file, eg template.php. Default empty.
		    );

				$my_review_id = wp_insert_post($post, true);
				//Obtenemos el review del Gran Maestre para la CERVEZA
		    $maestre_info = get_user_by('login','admin');
		    $maestre_reviews = get_posts( array(
		      'post_type' 		=> 'review',
		      'posts_per_page'=> -1,
		      'order_by' 			=> 'post_date',
		      'post_status'   => 'private',
		      'author' 				=> $maestre_info->ID,
		      'meta_key' 			=> 'cerveza_id',
		      'meta_value' 		=> $cerveza_data->ID
		    ) );
				if( empty($maestre_reviews) ) {
		      update_post_meta( $my_review_id, 'cerveza_id', $cerveza_data->ID );
		      update_post_meta( $my_review_id, 'cerveza_name', $cerveza_data->post_title );
		      update_post_meta( $my_review_id, 'color', 0 );
		      update_post_meta( $my_review_id, 'espuma', 0 );
		      update_post_meta( $my_review_id, 'alcohol', 0 );
		      update_post_meta( $my_review_id, 'cuerpo', 0 );
		      update_post_meta( $my_review_id, 'final', 0 );
		      update_post_meta( $my_review_id, 'amargor', 0 );
		      update_post_meta( $my_review_id, 'rating', 0 );
		    } else {
					$id = $maestre_reviews[0]->ID;

		      $color = get_post_meta( $id, 'color', true );
		      $espuma = get_post_meta( $id, 'espuma', true );
		      $alcohol = get_post_meta( $id, 'alcohol', true );
		      $cuerpo = get_post_meta( $id, 'cuerpo', true );
		      $final = get_post_meta( $id, 'final', true );
		      $amargor = get_post_meta( $id, 'amargor', true );
		      $rating = get_post_meta( $id, 'rating', true );

		      update_post_meta( $my_review_id, 'cerveza_id', $cerveza_data->ID );
		      update_post_meta( $my_review_id, 'cerveza_name', $cerveza_data->post_title );
		      update_post_meta( $my_review_id, 'color', ($color) ? $color : 0 );
		      update_post_meta( $my_review_id, 'espuma', ($espuma) ? $espuma : 0 );
		      update_post_meta( $my_review_id, 'alcohol', ($alcohol) ? $alcohol : 0 );
		      update_post_meta( $my_review_id, 'cuerpo', ($cuerpo) ? $cuerpo : 0 );
		      update_post_meta( $my_review_id, 'final', ($final) ? $final : 0 );
		      update_post_meta( $my_review_id, 'amargor', ($amargor) ? $amargor : 0 );
		      update_post_meta( $my_review_id, 'rating', ($rating) ? $rating : 0 );
		    }
		  } else {
				$my_review_id = $user_reviews[0]->ID;
		  }

		  $id 						= $my_review_id;
		  $cerveza_id 		= get_post_meta( $id, 'cerveza_id', true );
		  $cerveza_name 	= get_post_meta( $id, 'cerveza_name', true );
		  $color 					= get_post_meta( $id, 'color', true );
		  $espuma 				= get_post_meta( $id, 'espuma', true );
		  $alcohol 				= get_post_meta( $id, 'alcohol', true );
		  $cuerpo 				= get_post_meta( $id, 'cuerpo', true );
		  $final 					= get_post_meta( $id, 'final', true );
		  $amargor 				= get_post_meta( $id, 'amargor', true );
		  $rating 				= get_post_meta( $id, 'rating', true );
		  $review_sabores = wp_get_post_terms( $id, 'flavor_of_beer');
		  $review_aromas 	= wp_get_post_terms( $id, 'aroma_of_beer');

		  $type_of_beer = wp_get_post_terms( $cerveza_id, 'type_of_beer' );

		  $productor 					= get_post_meta( $cerveza_id, 'beer_productor', true );
		  $origen 						= get_post_meta( $cerveza_id, 'beer_origen', true );
		  $origen_estado 			= get_post_meta( $cerveza_id, 'beer_origen_estado', true );
		  $porcentaje_alcohol = get_post_meta( $cerveza_id, 'beer_alcohol', true );
		  $temperatura 				= get_post_meta( $cerveza_id, 'beer_temperatura', true );
		  $vaso 							= get_post_meta( $cerveza_id, 'beer_vaso', true );
		  $maridaje 					= get_post_meta( $cerveza_id, 'beer_maridaje', true );

			$puntos_venta = array();
			$puntos_venta_num		= get_post_meta( $cerveza_id, 'beer_punto_venta_total', true );
			$len = $puntos_venta_num = ($puntos_venta_num) ? $puntos_venta_num : 0;
			for($i = 1; $i <= $len; $i++) {
				$puntos_venta[] = (object) array(
					'nombre' => get_post_meta( $cerveza_id, 'beer_punto_venta_nombre_'.($i), true ),
					'link' => get_post_meta( $cerveza_id, 'beer_punto_venta_link_'.($i), true )
				);
			}

			$puntos_distribucion = array();
			$puntos_distribucion_num		= get_post_meta( $cerveza_id, 'beer_punto_distribucion_total', true );
			$len = $puntos_distribucion_num = ($puntos_distribucion_num) ? $puntos_distribucion_num : 0;
			for($i = 1; $i <= $len; $i++) {
				$puntos_distribucion[] = (object) array(
					'nombre' => get_post_meta( $cerveza_id, 'beer_punto_distribucion_nombre_'.($i), true ),
					'link' => get_post_meta( $cerveza_id, 'beer_punto_distribucion_link_'.($i), true )
				);
			}

		  $image = wp_get_attachment_url( get_post_thumbnail_id($cerveza_id) );

			$aromas 	= array();
			$sabores 	= array();
			if( !empty($maestre_reviews) ) {
				foreach( $maestre_reviews as $r ) {
					foreach( wp_get_post_terms( $r->ID, 'aroma_of_beer') as $a ) {
						$aromas[] = $a->term_taxonomy_id;
					}
					foreach( wp_get_post_terms( $r->ID, 'flavor_of_beer') as $a ) {
						$sabores[] = $a->term_taxonomy_id;
					}
				}
			} else {
				$community_reviews = get_posts( array(
					'post_type' => 'review',
		      'posts_per_page'=> -1,
		      'order_by' => 'post_date',
		    	'post_status'    => 'private',
		      //'author' => get_user_by( 'login', 'admin' )->ID,
		      'meta_key' => 'cerveza_id',
		      'meta_value' => $cerveza_data->ID
				) );

				foreach( $community_reviews as $r ) {
					foreach( wp_get_post_terms( $r->ID, 'aroma_of_beer') as $a ) {
						$aromas[] = $a->term_taxonomy_id;
					}
					foreach( wp_get_post_terms( $r->ID, 'flavor_of_beer') as $a ) {
						$sabores[] = $a->term_taxonomy_id;
					}
				}
			}
			//OBTENIENDO EL RATING DEL GRAN MAESTRE
			$sql = 'SELECT ID FROM wp_posts,wp_postmeta ';
			$sql.= "WHERE (meta_key = 'cerveza_id' AND meta_value = '$cerveza_data->ID') ";
			$sql.= "AND (post_author = 1 AND post_type = 'review') ";
			$sql.= "AND post_id = ID";
			$maestre_review_id = $wpdb->get_row($sql);
			if($maestre_review_id) {
				$maestre_review_id = $maestre_review_id->ID;
				$maestre_rating = get_post_meta($maestre_review_id, 'rating', true);
			} else {
				$maestre_rating = get_post_meta($cerveza->ID, 'beer_rating', true);
			}
			//OBTENIENDO EL RATING DE LA COMUNIDAD
			$sql = "SELECT AVG(a.meta_value) as average FROM wp_postmeta AS a, wp_postmeta AS b ";
			$sql.= "WHERE a.post_id = b.post_id AND a.meta_key = 'rating' ";
			$sql.= "AND b.meta_key = 'cerveza_id' AND b.meta_value = ".$cerveza_data->ID;
			$community_rating = $wpdb->get_row($sql)->average;

			//OBTENIENDO SUGERENCIAS
			if( !empty($aromas) || !empty($sabores) ) {
				$where = '';
				$len = sizeof( $aromas );
				for( $i = 0; $i < $len; $i++ ) {
					$where.= 'term_taxonomy_id='.$aromas[$i];
					if( $i+1 < $len ) {
						$where.= ' OR ';
					}
				}
				$where.= ( empty($sabores)||($where == '') )?' ':' OR ';
				$len = sizeof( $sabores );
				for( $i = 0; $i < $len; $i++ ) {
					$where.= 'term_taxonomy_id='.$sabores[$i];
					if( $i+1 < $len ) {
						$where.= ' OR ';
					}
				}

				$sql = 'SELECT object_id as ID FROM '.$wpdb->prefix.'term_relationships WHERE ';
				$sql.= $where;
				$sql.= ' GROUP BY object_id';
				$sugerencias_cervezas = $wpdb->get_results($sql);
				//Armando query para obtener las cervezas relacionadas con las IDs
				$where = '';
				$len = sizeof($sugerencias_cervezas);
				for( $i = 0; $i < $len; $i++ ) {
					$aux_cerveza_id = get_post_meta( $sugerencias_cervezas[$i]->ID, 'cerveza_id', true );
					if($aux_cerveza_id) {
						if( (1 < $i) ) {
							$where.= ' OR ';
						}
						$where.= 'ID = '.$aux_cerveza_id;
					}
				}

				$sql = 'SELECT ID FROM '.$wpdb->prefix.'posts WHERE '.$where;
				$cervezas = $wpdb->get_results($sql);
			} else {
				$cervezas = [];
			}
		}
		include_once 'partials/review_form.html.php';
	}

	public function list_abc() {
		include_once 'partials/list_abc.html.php';
	}

	public function list_random() {
		include_once 'partials/list_random.html.php';
	}

	public function list_news() {
		include_once 'partials/list_news.html.php';
	}

	public function list_suggest() {
		include_once 'partials/list_suggest.html.php';
	}

	public function list_hielera() {
		include_once 'partials/list_hielera.html.php';
	}

	public function review_view() {
		include_once 'partials/review_view.html.php';
	}

	public function recent_post_footer() {
		$posts = get_posts(array(
			'posts_per_page'   => 4,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'       => '',
			'post_status'      => 'publish',
			'suppress_filters' => true
		));
		include_once 'partials/recent_posts_footer.html.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'nwm-loc-review', plugin_dir_url( dirname( __FILE__ ) ) . 'css/nwm-loc-review.css');
		wp_enqueue_style( 'jquery-ui', plugin_dir_url( dirname( __FILE__ ) ) . 'css/jquery-ui.min.css');
		wp_enqueue_style( 'bootstrap', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap.min.css', array(), false, 'all');
		wp_enqueue_style( 'bootstrap-theme', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-theme.min.css', array(), false, 'all');
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		//wp_localize_script( 'my-autocomplete', 'MyAutocomplete', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brewery-autocomplete.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		//wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-button' );

	}

}
