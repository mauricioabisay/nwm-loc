<?php
$section = 'suggest';
include 'list_menu.html.php';
?>

<?php
	global $wpdb;

	$gabinete = $wpdb->get_results('SELECT beer FROM nwm_loc_hielera WHERE user='.get_current_user_id());
	$types_of_beer = array();
	$producers = array();
	$origins = array();

	if( empty($gabinete) ) {
?>
	<h4>El Gran Maestre lo sabe todo, pero no es adivino.</h4>
<?php } else {?>
<div class="row nwm-beerlist-table-container">
<?php
	foreach($gabinete as $c) {
		$type_of_beer = wp_get_post_terms( $c->beer, 'type_of_beer' );
	    $productor = get_post_meta( $c->beer, 'beer_productor', true );
	    $origen = get_post_meta( $c->beer, 'beer_origen', true );
	    //$porcentaje_alcohol = get_post_meta( $c->beer, 'beer_alcohol', true );
	    //$temperatura = get_post_meta( $c->beer, 'beer_temperatura', true );
	    //$vaso = get_post_meta( $c->beer, 'beer_vaso', true );
	    //$maridaje = get_post_meta( $c->beer, 'beer_maridaje', true );
	    $types_of_beer[] = $type_of_beer[0]->term_taxonomy_id;
	    $producers[] = $productor;
	    $origins[] = $origen;
	}

	//Obtener cervezas del mismo TIPO
	$where = 'WHERE ';
	$len = sizeof( $types_of_beer );
	for( $i = 0; $i < $len; $i++ ) {
		$where.= 'term_taxonomy_id='.$types_of_beer[$i];
		if( $i+1 < $len ) {
			$where.= ' OR ';
		}
	}

	$sql = 'SELECT object_id as ID FROM '.$wpdb->prefix.'term_relationships ';
	$sql.= $where;

	$sugerencias_tipo = $wpdb->get_results($sql);
	//Obtener cervezas del mismo PRODUCTOR
	$where = 'WHERE (';
	$len = sizeof( $producers );
	for( $i = 0; $i < $len; $i++ ) {
		$where.= 'meta_value="'.$producers[$i].'"';
		if( $i+1 < $len ) {
			$where.= ' OR ';
		}
	}
	$where.=') AND meta_key = "beer_productor"';

	$sql = 'SELECT post_id as ID FROM '.$wpdb->prefix.'postmeta ';
	$sql.= $where;

	$sugerencias_productor = $wpdb->get_results($sql);
	/*
	*Nota de mauricio@nwm.mx al 24 de Septiembre de 2015
	*FUNCIONALIDAD PENDIENTE, HAY UN PROBLEMA CON EL COLLATION DE MYSQL

	Obtener cervezas del mismo PAIS DE ORIGEN
	$where = 'WHERE (';
	$len = sizeof( $origins );
	for( $i = 0; $i < $len; $i++ ) {
		$where.= 'meta_value="'.$origins[$i].'" COLLATE utf8_unicode_ci';
		if( $i+1 < $len ) {
			$where.= ' OR ';
		}
	}
	$where.=') AND meta_key="beer_origen"';

	$sql = 'SELECT post_id as ID FROM '.$wpdb->prefix.'postmeta ';
	$sql.= $where;

	echo $sql.'<br>';//$sugerencias_origen = $wpdb->get_results($sql);
	*/
	//Obteniedo y comparando IDs para evitar duplicados
	$ids = array();
	foreach ($sugerencias_tipo as $s) {
		$flag = true;
		foreach( $ids as $id ) {
			if( $s->ID == $id ) {
				$flag = false;
				break;
			} else {
				$flag = true;
			}
		}
		if( $flag ) {
			$ids[] = $s->ID;
		}
	}

	foreach ($sugerencias_productor as $s) {
		$flag = true;
		foreach( $ids as $id ) {
			if( $s->ID == $id ) {
				$flag = false;
				break;
			} else {
				$flag = true;
			}
		}
		if( $flag ) {
			$ids[] = $s->ID;
		}
	}
	/*
	foreach ($sugerencias_origen as $s) {
		$flag = true;
		foreach( $ids as $id ) {
			if( $s->ID == $id ) {
				$flag = false;
				break;
			} else {
				$flag = true;
			}
		}
		if( $flag ) {
			$ids[] = $s->ID;
		}
	}
	*/
	//Armando query para obtener las cervezas relacionadas con las IDs
	$where = '';
	$len = sizeof($ids);
	for( $i = 0; $i < $len; $i++ ) {
		$where.= 'ID = '.$ids[$i];
		if( $i+1 < $len ) {
			$where.= ' OR ';
		}
	}

	$sql = 'SELECT ID FROM '.$wpdb->prefix.'posts WHERE '.$where;
	$cervezas = $wpdb->get_results($sql);

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
