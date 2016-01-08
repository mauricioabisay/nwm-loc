<?php

class My_Ajax {
	public function search_brewery() {
		if( isset($_GET['term']) ) {
			global $wpdb;

			$title = strtoupper($_GET['term']);

			$sql = "SELECT ID FROM $wpdb->posts ";
			$sql.= "WHERE UCASE(post_title) LIKE '%$title%' ";
			$sql.= "AND post_type='brewery' ";
			$sql.= "AND post_status='publish'";

			$ids = $wpdb->get_col($sql);

			if( $ids ) {
				$args = array(
					'post__in'=> $ids,
	        'post_type'=>'brewery',
	        'orderby'=>'title',
	        'order'=>'asc'
	    	);

				$query = new WP_Query($args);

				if( $query->have_posts() ) {
					$response = array();
					while( $query->have_posts() ) {
						$query->the_post();
						$response[] = get_the_title();
					}
					echo json_encode($response);
					wp_reset_postdata();
					exit();
				} else {
					echo json_encode(array('result' => 'No hay coincidencias'));
					wp_reset_postdata();
					exit();
				}
			} else {
				echo json_encode(array('result' => 'No hay coincidencias'));
				wp_reset_postdata();
				exit();
			}
		}
		echo json_encode(array('result' => '...'));
		wp_reset_postdata();
		exit();
	}

	public function search_beer() {
		if( isset($_GET['term']) ) {
			global $wpdb;

			$title = strtoupper($_GET['term']);

			$sql = "SELECT ID FROM $wpdb->posts ";
			$sql.= "WHERE UCASE(post_title) LIKE '%$title%' ";
			$sql.= "AND post_type='beer' ";
			$sql.= "AND post_status='publish'";

			$ids = $wpdb->get_col($sql);

			if( $ids ) {
				$args = array(
	        		'post__in'=> $ids,
	        		'post_type'=>'beer',
	        		'orderby'=>'title',
	        		'order'=>'asc'
	    		);

				$query = new WP_Query($args);

				if( $query->have_posts() ) {
					$response = array();
					while( $query->have_posts() ) {
						$query->the_post();
						$response[] = get_the_title();
					}
					echo json_encode($response);
					wp_reset_postdata();
					exit();
				} else {
					echo json_encode(array('result' => 'No hay coincidencias'));
					wp_reset_postdata();
					exit();
				}
			} else {
				echo json_encode(array('result' => 'No hay coincidencias'));
				wp_reset_postdata();
				exit();
			}
		}
		echo json_encode(array('result' => '...'));
		wp_reset_postdata();
		exit();
	}

	public function my_beer_cooler() {
		if( isset($_GET['beer_id']) ) {
			global $wpdb;
			$update = array(
				'status' => $_GET['status']
			);

			switch($_GET['status']) {
				case 'Favorite': {
					$update['favorite'] = ($_GET['previous'] == 'nwm-beer-no-favorite')? 1 : 0;
					break;
				}
				case 'ForLater': {
					$update['forlater'] = ($_GET['previous'] == 'nwm-beer-no-forlater') ? 1 : 0;
					break;
				}
			}

			$sql = 'SELECT * FROM nwm_loc_hielera ';
			$sql.= 'WHERE beer='.$_GET['beer_id'].' ';
			$sql.= 'AND user='.get_current_user_id();

			if(($wpdb->get_row($sql)) == null) {
				$newCoolerRow = array_merge(
					array(
						'user'			=>	get_current_user_id(),
						'beer'			=>	$_GET['beer_id'],
						'review'		=>	0,
						'rate'			=>	0
					),
					$update
				);
				$wpdb->insert('nwm_loc_hielera', $newCoolerRow);
			} else {
				$updateCoolerRow = array_merge(
					array(
						'status'		=>	$_GET['status']
					),
					$update
				);
				$wpdb->update(
					'nwm_loc_hielera',
					$updateCoolerRow,
					array(
						'user'			=>	get_current_user_id(),
						'beer'			=>	$_GET['beer_id']
					)
				);
			}
			exit();
		}
	}

	public function quick_beer_rate() {
		if( isset($_GET['beer_id']) && isset($_GET['rating'] ) ) {
			global $current_user;
    		get_currentuserinfo();

    		$user_reviews = get_posts( array(
        		'post_type' => 'review',
        		'posts_per_page'=> -1,
        		'order_by' => 'post_date',
        		'post_status'    => 'private',
        		'author' => $current_user->ID,
        		'meta_key' => 'cerveza_id',
        		'meta_value' => $_GET['beer_id']
    		) );

    		if( empty($user_reviews) ) {
    			$cerveza_data = get_post($_GET['beer_id']);
        		$post = array(
            		'post_name'      => $current_user->display_name.'|Experiencia '.$cerveza_data->post_title,
            		'post_title'     => $current_user->display_name.'|Experiencia '.$cerveza_data->post_title,
            		'post_content'   => '',
            		'post_status'    => 'private',
            		'post_type'      => 'review',
            		'post_author'    => $current_user->ID,
            		'post_date'      => date('Y-m-d H:i:s'), // The time post was made.
            		'comment_status' => 'closed'
        		);
        		$my_review_id = wp_insert_post($post, true);

		        update_post_meta( $my_review_id, 'cerveza_id', $cerveza_data->ID );
		        update_post_meta( $my_review_id, 'cerveza_name', $cerveza_data->post_title );
		        update_post_meta( $my_review_id, 'color', 0 );
		        update_post_meta( $my_review_id, 'espuma', 0 );
		        update_post_meta( $my_review_id, 'alcohol', 0 );
		        update_post_meta( $my_review_id, 'cuerpo', 0 );
		        update_post_meta( $my_review_id, 'final', 0 );
		        update_post_meta( $my_review_id, 'amargor', 0 );
		        update_post_meta( $my_review_id, 'rating', $_GET['rating'] );
    		} else {
        		update_post_meta( $user_reviews[0]->ID, 'rating', $_GET['rating'] );
    		}

    		global $wpdb;
				$sql = 'SELECT * FROM nwm_loc_hielera ';
				$sql.= 'WHERE beer='.$_GET['beer_id'].' ';
				$sql.= 'AND user='.get_current_user_id();

				if(($wpdb->get_row($sql)) == null) {
					$wpdb->insert(
						'nwm_loc_hielera',
						array(
							'user'			=>	$current_user->ID,
							'beer'			=>	$_GET['beer_id'],
							'status'		=>	'Rated',
							'review'		=>	0,
							'rate'			=> 	1,
							'favorite'	=> 	0,
							'forlater'	=> 	0
						)
					);
				} else {
					$wpdb->update(
						'nwm_loc_hielera',
						array(
							'status'		=>	'Rated',
							'rate'			=>	1
						),
						array(
							'user'			=>	get_current_user_id(),
							'beer'			=>	$_GET['beer_id']
						)
					);
				}
    		wp_reset_postdata();
			exit();
		}
	}

	public function remove_punto_venta() {
		if( isset($_GET['beer_id']) && isset($_GET['index']) ) {
			$id = $_GET['beer_id'];
			$index = $_GET['index'];

			$total_puntos_venta = get_post_meta( $id, 'beer_punto_venta_total', true );
			if($index != $total_puntos_venta) {
				//Si el elemento es el primero o uno de los intermedios, hay que recorrer el resto
				$aux_array = array();
				for($i = 1; $i <= $total_puntos_venta; $i++) {
					if($i != $index) {
						$aux_array[] = array(
							'nombre' => get_post_meta( $id, 'beer_punto_venta_nombre_'.$i, true ),
							'link'	 => get_post_meta( $id, 'beer_punto_venta_link_'.$i, true )
						);
					}
				}
				delete_post_meta($id, 'beer_punto_venta_nombre_'.$total_puntos_venta);
				delete_post_meta($id, 'beer_punto_venta_link_'.$total_puntos_venta);
				$total_puntos_venta = $total_puntos_venta - 1;
				$total_puntos_venta = ($total_puntos_venta >= 0) ? $total_puntos_venta : 0;
				update_post_meta($id, 'beer_punto_venta_total', $total_puntos_venta);
				$j = 1;
				foreach ($aux_array as $pv) {
					update_post_meta($id, 'beer_punto_venta_nombre_'.$j, $pv['nombre']);
					update_post_meta($id, 'beer_punto_venta_link_'.$j, $pv['link']);
					$j++;
				}
			} else {
				//Si el elemento es el ultimo, solo hay que borrarlo y listo
				delete_post_meta($id, 'beer_punto_venta_nombre_'.$index);
				delete_post_meta($id, 'beer_punto_venta_link_'.$index);

				$total_puntos_venta = $total_puntos_venta - 1;
				$total_puntos_venta = ($total_puntos_venta >= 0) ? $total_puntos_venta : 0;
				update_post_meta($id, 'beer_punto_venta_total', $total_puntos_venta);
			}
			exit();
		}
	}

	public function remove_punto_distribucion() {
		if( isset($_GET['beer_id']) && isset($_GET['index']) ) {
			$id = $_GET['beer_id'];
			$index = $_GET['index'];
			$total = get_post_meta( $id, 'beer_punto_distribucion_total', true );

			if($index != $total) {
				$aux_array = array();
				for($i = 1; $i <= $total; $i++) {
					if($i != $index) {
						$aux_array[] = array(
							'nombre' => get_post_meta( $id, 'beer_punto_distribucion_nombre_'.$i, true ),
							'link'	 => get_post_meta( $id, 'beer_punto_distribucion_link_'.$i, true )
						);
					}
				}
				delete_post_meta($_GET['beer_id'], 'beer_punto_distribucion_nombre_'.$total);
				delete_post_meta($_GET['beer_id'], 'beer_punto_distribucion_link_'.$total);
				$total = get_post_meta( $id, 'beer_punto_distribucion_total', true );
				$total = $total - 1;
				$total = ($total >= 0) ? $total : 0;
				update_post_meta($_GET['beer_id'], 'beer_punto_distribucion_total', $total);
				$j = 1;
				foreach ($aux_array as $pd) {
					update_post_meta($id, 'beer_punto_distribucion_nombre_'.$j, $pd['nombre']);
					update_post_meta($id, 'beer_punto_distribucion_link_'.$j, $pd['link']);
					$j++;
				}
			} else {
				delete_post_meta($_GET['beer_id'], 'beer_punto_distribucion_nombre_'.$_GET['index']);
				delete_post_meta($_GET['beer_id'], 'beer_punto_distribucion_link_'.$_GET['index']);
				$total = get_post_meta( $id, 'beer_punto_distribucion_total', true );
				$total = $total - 1;
				$total = ($total >= 0) ? $total : 0;
				update_post_meta($_GET['beer_id'], 'beer_punto_distribucion_total', $total);
			}
			exit();
		}
	}
}
