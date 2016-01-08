<?php
$section = 'hielera';
include 'list_menu.html.php';
?>
<?php
$sql = 'SELECT beer FROM nwm_loc_hielera ';
$sql.= 'WHERE user = '.get_current_user_id().' ';

$legend = '';
switch($_GET['status']) {
  case 'ForLater': {
    $legend = 'Por Probar';
    $sql.= 'AND forlater = 1';
    break;
  }
  case 'Favorite': {
    $legend = 'Favoritas';
    $sql.= 'AND favorite = 1';
    break;
  }
  case 'Rated': {
    $legend = 'Por Catar';
    $sql.= 'AND rate = 1 AND review = 0';
    break;
  }
  case 'Reviewed': {
    $legend = 'Catadas';
    $sql.= 'AND review = 1';
    break;
  }
}
?>
<h2 class="nwm-hielera-title">Mi Gabinete - <?php echo $legend;?></h2>


<?php
	global $wpdb;

	$gabinete = $wpdb->get_results($sql);

	if( empty($gabinete) ) {
?>
	<h4>Aún no tienes Cervezas <?php echo $legend;?> en tu Gabinete.</h4>
<?php
	} else {
?>
<div class="row nwm-beerlist-table-container">
<?php
	$cervezas = array();

	foreach($gabinete as $c) {
		$cervezas[] = get_post($c->beer);
	}

	$i = 0;
	$items_per_row = 3;
	$flag = false;
	foreach ( $cervezas as $cerveza ) {
		$image_id = get_post_thumbnail_id($cerveza->ID);
		$image = wp_get_attachment_url( $image_id );
        include 'list_item.html.php';
    }
?>
</div>

<script type="text/javascript">
jQuery(function () {
var ratingRadios = document.getElementsByClassName("nwm-rating-radio");

for( var i = 0; i<ratingRadios.length; i++ ) {
	var radios = ratingRadios[i].children;
    for ( var j = 0; j<radios.length; j++ ) {
    	var className = radios[j].className;
    	switch(className) {
    		case '1':{
    			radios[j].onclick = (function() {
    				var padre = this.parentElement;
    				padre.getElementsByClassName("1")[0].checked = true;
    				padre.getElementsByClassName("2")[0].checked = false;
    				padre.getElementsByClassName("3")[0].checked = false;
    				padre.getElementsByClassName("4")[0].checked = false;
    				padre.getElementsByClassName("5")[0].checked = false;
    				var id = padre.getElementsByClassName("my-cheve-id")[0].value;
						document.getElementById("raiting-texto").innerHTML = 1;
						document.getElementById("cata-quick-access").innerHTML = '<a href="<?php echo site_url().'/index.php/review-sample?beer_id=';?>'+ id +'">Catar esta cerveza</a>';
						jQuery('#msg').modal('show');
    				jQuery.ajax({
    					url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=quick_beer_rate';?>',
    					data: 'beer_id=' + id + '&rating=1'
    				});
    			});
    			break;
    		}
    		case '2':{
    			radios[j].onclick = (function() {
    				var padre = this.parentElement;
    				padre.getElementsByClassName("1")[0].checked = true;
    				padre.getElementsByClassName("2")[0].checked = true;
    				padre.getElementsByClassName("3")[0].checked = false;
    				padre.getElementsByClassName("4")[0].checked = false;
    				padre.getElementsByClassName("5")[0].checked = false;
    				var id = padre.getElementsByClassName("my-cheve-id")[0].value;
						document.getElementById("raiting-texto").innerHTML = 2;
						document.getElementById("cata-quick-access").innerHTML = '<a href="<?php echo site_url().'/index.php/review-sample?beer_id=';?>'+ id +'">Catar esta cerveza</a>';
						jQuery('#msg').modal('show');
    				jQuery.ajax({
    					url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=quick_beer_rate';?>',
    					data: 'beer_id=' + id + '&rating=2'
    				});
    			});
    			break;
    		}
    		case '3':{
    			radios[j].onclick = (function() {
    				var padre = this.parentElement;
    				padre.getElementsByClassName("1")[0].checked = true;
    				padre.getElementsByClassName("2")[0].checked = true;
    				padre.getElementsByClassName("3")[0].checked = true;
    				padre.getElementsByClassName("4")[0].checked = false;
    				padre.getElementsByClassName("5")[0].checked = false;
    				var id = padre.getElementsByClassName("my-cheve-id")[0].value;
						document.getElementById("raiting-texto").innerHTML = 3;
						document.getElementById("cata-quick-access").innerHTML = '<a href="<?php echo site_url().'/index.php/review-sample?beer_id=';?>'+ id +'">Catar esta cerveza</a>';
						jQuery('#msg').modal('show');
    				jQuery.ajax({
    					url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=quick_beer_rate';?>',
    					data: 'beer_id=' + id + '&rating=3'
    				});
    			});
    			break;
    		}
    		case '4':{
    			radios[j].onclick = (function() {
    				var padre = this.parentElement;
    				padre.getElementsByClassName("1")[0].checked = true;
    				padre.getElementsByClassName("2")[0].checked = true;
    				padre.getElementsByClassName("3")[0].checked = true;
    				padre.getElementsByClassName("4")[0].checked = true;
    				padre.getElementsByClassName("5")[0].checked = false;
    				var id = padre.getElementsByClassName("my-cheve-id")[0].value;
						document.getElementById("raiting-texto").innerHTML = 4;
						document.getElementById("cata-quick-access").innerHTML = '<a href="<?php echo site_url().'/index.php/review-sample?beer_id=';?>'+ id +'">Catar esta cerveza</a>';
						jQuery('#msg').modal('show');
    				jQuery.ajax({
    					url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=quick_beer_rate';?>',
    					data: 'beer_id=' + id + '&rating=4'
    				});
    			});
    			break;
    		}
    		case '5':{
    			radios[j].onclick = (function() {
    				var padre = this.parentElement;
    				padre.getElementsByClassName("1")[0].checked = true;
    				padre.getElementsByClassName("2")[0].checked = true;
    				padre.getElementsByClassName("3")[0].checked = true;
    				padre.getElementsByClassName("4")[0].checked = true;
    				padre.getElementsByClassName("5")[0].checked = true;
    				var id = padre.getElementsByClassName("my-cheve-id")[0].value;
						document.getElementById("raiting-texto").innerHTML = 5;
						document.getElementById("cata-quick-access").innerHTML = '<a href="<?php echo site_url().'/index.php/review-sample?beer_id=';?>'+ id +'">Catar esta cerveza</a>';
						jQuery('#msg').modal('show');
    				jQuery.ajax({
    					url: '<?php echo site_url('wp-admin').'/admin-ajax.php?action=quick_beer_rate';?>',
    					data: 'beer_id=' + id + '&rating=5'
    				});
    			});
    			break;
    		}
    	}
    }
}
})
</script>
<?php }?>
