<h2>Cervezas Aleatorias</h2>
<?php
	$cervezas = get_posts( array(
		'post_type' => 'beer',
		'posts_per_page'=> -1,
		'orderby' => 'rand'
	) );

	foreach ( $cervezas as $cerveza ) {
		$image = wp_get_attachment_url( get_post_thumbnail_id($cerveza->ID) );
?>
	<a href="<?php echo get_permalink($cerveza->ID);?>"><img src="<?php echo $image;?>" /></a>

<?php } ?>

