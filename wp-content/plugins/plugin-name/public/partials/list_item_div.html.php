<div class="col-xs-12 col-sm-6 col-md-3">
<div class="">
	<div class="">
		<?php if( is_user_logged_in() ) { ?>
			<div class="nwm-beerlist-califica" onclick="window.open('<?php echo site_url().'/index.php/review-sample?beer_id='.$cerveza->ID;?>', '_self');">Califica</div>
		<?php } else { ?>
			<div class="nwm-beerlist-califica" data-toggle="modal" data-target="#myModal">Califica</div>
		<?php }?>
		<?php if ( is_user_logged_in() ) { ?>
		<?php
				$results = $wpdb->get_results('SELECT status,review,rate,favorite,forlater FROM nwm_loc_hielera WHERE beer='.$cerveza->ID.' AND user='.get_current_user_id());
				if( empty($results) ){
					$clase = 'nwm-beer-not-in-hielera nwm-beer-no-review nwm-beer-no-rate';
					$favorite = 'nwm-beer-no-favorite';
					$forlater = 'nwm-beer-no-forlater';
					$favorite_img = 'inactive';
					$forlater_img = 'inactive';
				} else{
					$clase = 'nwm-beer-'.strtolower($results[0]->status);
					//$clase.= ($results[0]->review > 0)?' nwm-beer-review':' nwm-beer-no-review';
					$clase.= ($results[0]->rate > 0)?' nwm-beer-rate':' nwm-beer-no-rate';
					$favorite = ($results[0]->favorite > 0)?'nwm-beer-favorite':'nwm-beer-no-favorite';
					$forlater = ($results[0]->forlater > 0)?'nwm-beer-forlater':'nwm-beer-no-forlater';
					$favorite_img = ($results[0]->favorite > 0)?'active':'inactive';
					$forlater_img = ($results[0]->forlater > 0)?'active':'inactive';
				}
		?>
			<span id="<?php echo $cerveza->ID;?>-fav" class="nwm-beerlist-favorite-quick <?php echo $clase.' '.$favorite.' '.$favorite_img;?>">
				<a href="#" onclick="
					var aux_class_pre = jQuery('#<?php echo $cerveza->ID;?>-fav').attr('class');
					if(aux_class_pre.indexOf('inactive') > -1) {
						jQuery('#<?php echo $cerveza->ID;?>-fav').attr('class', aux_class_pre.replace('inactive', 'active'));
					} else {
						jQuery('#<?php echo $cerveza->ID;?>-fav').attr('class', aux_class_pre.replace('active', 'inactive'));
					}
					jQuery.ajax({
    				url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=my_beer_cooler';?>',
    				data: 'beer_id=<?php echo $cerveza->ID;?>&status=Favorite&previous=<?php echo $favorite;?>',
    			});">
					<img class="pulse <?php echo $favorite.' '.$favorite_img;?>" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ).'img/corazon.svg';?>" style="height:46px;width:46px" />
				</a>
			</span>
			<span id="<?php echo $cerveza->ID;?>-for" class="nwm-beerlist-for-later-quick <?php echo $clase.' '.$forlater.' '.$forlater_img;?>">
				<a href="#" onclick="
					var aux_class_pre = jQuery('#<?php echo $cerveza->ID;?>-for').attr('class');
					if(aux_class_pre.indexOf('inactive') > -1) {
						jQuery('#<?php echo $cerveza->ID;?>-for').attr('class', aux_class_pre.replace('inactive', 'active'));
					} else {
						jQuery('#<?php echo $cerveza->ID;?>-for').attr('class', aux_class_pre.replace('active', 'inactive'));
					}
					jQuery.ajax({
    				url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=my_beer_cooler';?>',
    				data: 'beer_id=<?php echo $cerveza->ID;?>&status=ForLater&previous=<?php echo $forlater;?>',
    			});">
					<img class="tada <?php echo $forlater.' '.$forlater_img;?>" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ).'img/mas.svg';?>"/ style="height:50px;width:50px"/>
				</a>
			</span>
			<center>
	      <a href="<?php echo site_url().'/index.php/review-sample?beer_id='.$cerveza->ID;?>">
	        <img src="<?php echo $image;?>" />
	      </a>
	    </center>
		<?php } else { ?>
	    <img src="<?php echo $image;?>" />
		<?php } ?>
	</div>

	<div class="">
	<?php
	if( is_user_logged_in() ) {
		$user_reviews = get_posts( array(
			'post_type' => 'review',
			'posts_per_page'=> -1,
			'order_by' => 'post_date',
			'post_status'    => 'private',
			'author' => get_current_user_id(),
			'meta_key' => 'cerveza_id',
			'meta_value' => $cerveza->ID
		) );
		if(empty($user_reviews)) {
			$rating = 0;
		} else {
			$rating = get_post_meta( $user_reviews[0]->ID, 'rating', true );
		}
	?>
		<div class="" style="display:inline">
			<input id="<?php echo $cerveza->ID.'-1';?>" type="radio" style="display:inline" <?php echo ($rating>=1)?'checked="checked"':'';?> />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-1';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

			<input id="<?php echo $cerveza->ID.'-2';?>" type="radio" style="display:inline" <?php echo ($rating>=2)?'checked="checked"':'';?> />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-2';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-3';?>" type="radio" style="display:inline" <?php echo ($rating>=3)?'checked="checked"':'';?> />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-3';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-4';?>" type="radio" style="display:inline" <?php echo ($rating>=4)?'checked="checked"':'';?> />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-4';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-5';?>" type="radio" style="display:inline" <?php echo ($rating>=5)?'checked="checked"':'';?> />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-5';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input type="hidden" class="my-cheve-id" value="<?php echo $cerveza->ID;?>" />
		</div>
	<?php } else { ?>
		<div class="">
			<input id="<?php echo $cerveza->ID.'-1';?>" type="radio" style="display:inline" data-toggle="modal" data-target="#myModal" />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-1';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

			<input id="<?php echo $cerveza->ID.'-2';?>" type="radio" style="display:inline" data-toggle="modal" data-target="#myModal" />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-2';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-3';?>" type="radio" style="display:inline" data-toggle="modal" data-target="#myModal" />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-3';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-4';?>" type="radio" style="display:inline" data-toggle="modal" data-target="#myModal" />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-4';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input id="<?php echo $cerveza->ID.'-5';?>" type="radio" style="display:inline" data-toggle="modal" data-target="#myModal" />
			<label style="display:inline" for="<?php echo $cerveza->ID.'-5';?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

			<input type="hidden" class="my-cheve-id" value="<?php echo $cerveza->ID;?>" />
		</div>
	<?php } ?>
</div>
		<div class="">
				<?php $productor = get_post_meta($cerveza->ID, 'beer_productor', true);?>
				<a href="<?php echo get_permalink(get_page_by_title($productor,'object','brewery'));?>">
					<?php echo (isset($productor))?$productor:'-';?>
				</a>
		</div>
		<div class="">
			<?php $aux_type_of_beer = wp_get_post_terms( $cerveza->ID, 'type_of_beer' );?>
			<?php echo (empty($aux_type_of_beer))?'-':$aux_type_of_beer[0]->name;?>
		</div>
		<div class="">
			<?php echo get_post_meta($cerveza->ID, 'beer_alcohol', true);?> Alcohol
		</div>
		<div class="">
			<h3>VEREDICTO</h3>
		</div>
		<div class="">
			<?php $maestre_rating = get_post_meta($cerveza->ID, 'beer_rating', true);?>
			<div class="">
				<input id="maestre-<?php echo $cerveza->ID;?>-1" type="radio" class="1" disabled readonly <?php echo ($maestre_rating>=1)?'checked="checked"':'';?> />
				<label for="maestre-<?php echo $cerveza->ID.'-1';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="maestre-<?php echo $cerveza->ID;?>-2" type="radio" class="2" disabled readonly <?php echo ($maestre_rating>=2)?'checked="checked"':'';?> />
		    <label for="maestre-<?php echo $cerveza->ID.'-2';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="maestre-<?php echo $cerveza->ID;?>-3" type="radio" class="3" disabled readonly <?php echo ($maestre_rating>=3)?'checked="checked"':'';?> />
		    <label for="maestre-<?php echo $cerveza->ID.'-3';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="maestre-<?php echo $cerveza->ID;?>-4" type="radio" class="4" disabled readonly <?php echo ($maestre_rating>=4)?'checked="checked"':'';?> />
		    <label for="maestre-<?php echo $cerveza->ID.'-4';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="maestre-<?php echo $cerveza->ID;?>-5" type="radio" class="5" disabled readonly <?php echo ($maestre_rating>=5)?'checked="checked"':'';?> />
		    <label for="maestre-<?php echo $cerveza->ID.'-5';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
			</div>
			<div class="">Gran Maestre</div>
		</div>

		<div class="nwm-beerlist-row-separator"></div>
		<div class="">
			<?php
				global $wpdb;
				$sql = "SELECT AVG(a.meta_value) as average FROM wp_postmeta AS a, wp_postmeta AS b ";
				$sql.= "WHERE a.post_id = b.post_id AND a.meta_key = 'rating' ";
				$sql.= "AND b.meta_key = 'cerveza_id' AND b.meta_value = ".$cerveza->ID;
				$community_rating = $wpdb->get_row($sql)->average;
			?>
			<div class="">
				<input id="community-<?php echo $cerveza->ID;?>-1" type="radio" class="1" disabled readonly <?php echo ($community_rating>=1)?'checked="checked"':'';?> />
				<label for="community-<?php echo $cerveza->ID.'-1';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="community-<?php echo $cerveza->ID;?>-2" type="radio" class="2" disabled readonly <?php echo ($community_rating>=2)?'checked="checked"':'';?> />
		    <label for="community-<?php echo $cerveza->ID.'-2';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="community-<?php echo $cerveza->ID;?>-3" type="radio" class="3" disabled readonly <?php echo ($community_rating>=3)?'checked="checked"':'';?> />
		    <label for="community-<?php echo $cerveza->ID.'-3';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="community-<?php echo $cerveza->ID;?>-4" type="radio" class="4" disabled readonly <?php echo ($community_rating>=4)?'checked="checked"':'';?> />
		    <label for="community-<?php echo $cerveza->ID.'-4';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>

		    <input id="community-<?php echo $cerveza->ID;?>-5" type="radio" class="5" disabled readonly <?php echo ($community_rating>=5)?'checked="checked"':'';?> />
		    <label for="community-<?php echo $cerveza->ID.'-5';?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
			</div>
			<div class="">Comunidad LOC</div>
		</div>
	</div>
</div>
