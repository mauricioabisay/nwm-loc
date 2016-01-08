<div class="col-xs-12 col-sm-6 col-md-3">
<div class="">
	<div class="">
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
			<center>
	      <a href="<?php echo site_url().'/index.php/review-sample?beer_id='.$cerveza->ID;?>" target="_parent">
	        <img src="<?php echo $image;?>" />
	      </a>
	    </center>
		<?php } else { ?>
	    <img src="<?php echo $image;?>" />
		<?php } ?>
	</div>

	</div>
</div>
