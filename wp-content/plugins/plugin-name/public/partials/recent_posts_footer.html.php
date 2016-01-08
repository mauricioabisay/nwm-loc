<div class="row">
<header class="page-header">
  <h1 class="page-title">Publicaciones recientes.</h1>
</header>
<?php foreach($posts as $post) {
    $categories = wp_get_post_categories( $post->ID );

    $image_id = get_post_thumbnail_id($post->ID);
    $image = wp_get_attachment_url( $image_id );
    $categories_css = '';
?>
<article class="recent-post-footer col-sm-6 col-md-3" style="position:relative">
  <div>
    <a href="<?php echo get_permalink($post->ID);?>">
      <img src="<?php echo $image;?>" style="width:100%">
    </a>
  </div>
  <div>
    <div style="top: -5em;position: relative;">
      <span class="categories-links">
        <?php foreach($categories as $category) {
                $c = get_category($category);
                $categories_css.= 'category-'.$c->slug.' ';
        ?>
          <a class="<?php echo $c->slug;?>" href="" rel="category tag" style="color: #fff !important;font-weight: 300;padding: 5px 6px;line-height: 2;">
            <?php echo $c->name;?>
          </a>
        <?php }?>

      </span>
    </div>
    <h2 style="font-size: 18px;top: -3em;position:relative">
      <a style="color:#fff !important" href="<?php echo get_permalink($post->ID);?>" rel="bookmark">
        <?php echo $post->post_title;?>
      </a>
    </h2>
  </div>
  <div class="mi_categoria <?php echo $categories_css;?>"></div>
</article>
<?php $flag=false;} ?>
</div>
<div class="row"></div>
