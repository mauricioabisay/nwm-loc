<div class="row nwm-beerlist-suggest-table-container">
	<h2 class="tambien">También podrían gustarte</h2>
</div>
<div class="row">
<?php if(!empty($cervezas)) {
	foreach ( $cervezas as $cerveza ) {
		$image_id = get_post_thumbnail_id($cerveza->ID);
		$image = wp_get_attachment_url( $image_id );
		include 'list_item_summary.html.php';
	}
?>
<?php } else { ?>
<p>Lo siento, aún no hemos descubierto cervezas con tus preferencias.</p>
<?php }?>
</div>
