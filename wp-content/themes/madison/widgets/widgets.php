<?php
add_action('widgets_init', 'azexo_register_widgets');

function azexo_register_widgets() {
    register_widget('AzexoTitle');
    register_widget('AzexoPost');
    register_widget('NWMCarousel');
    //register_widget('AzexoPostCarousel');
    //register_widget('AzexoPostPackery');
}

class AzexoTitle extends WP_Widget {

    function AzexoTitle() {
        parent::__construct('azexo_title', AZEXO_THEME_NAME . ' - Page title');
    }

    function widget($args, $instance) {
        print '<div class="widget page-title">';
        get_template_part('template-parts/general', 'title');
        print '</div>';
    }

}

class AzexoPost extends WP_Widget {

    function AzexoPost() {
        parent::__construct('azexo_post', AZEXO_THEME_NAME . ' - One post');
    }

    function widget($args, $instance) {
        print '<div class="widget azexo-post">';
        if (!empty($instance['title']))
            print '<div class="widget-title"><h3>' . $instance['title'] . '</h3></div>';
        if (!empty($instance['post'])) {
            if ($instance['full'] == 'on') {
                global $post;
                $original = $post;
                $post = get_post($instance['post']);
                setup_postdata($post);
                $template_name = $instance['template'];
                print '<div class="scoped-style">' . azexo_get_post_wpb_css($instance['post']);
                include(locate_template('content.php'));
                print '</div>';
                wp_reset_postdata();
                $post = $original;
            } else {
                $wpautop = false;
                if (has_filter('the_content', 'wpautop')) {
                    remove_filter('the_content', 'wpautop');
                    $wpautop = true;
                }
                print azexo_get_post_content($instance['post']);
                if ($wpautop) {
                    add_filter('the_content', 'wpautop');
                }
            }
        }
        print '</div>';
    }

    function update($new_instance, $old_instance) {
        $instance = parent::update($new_instance, $old_instance);
        $instance['full'] = $new_instance['full'];
        return $instance;
    }

    function form($instance) {
        $defaults = array('post' => '', 'title' => '', 'template' => 'widget_post', 'full' => 'off');
        $instance = wp_parse_args((array) $instance, $defaults);
        global $azexo_templates;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p><p>
            <label for="<?php echo esc_attr($this->get_field_id('post')); ?>"><?php _e('Post ID:', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('post')); ?>" name="<?php echo esc_attr($this->get_field_name('post')); ?>" type="text" value="<?php echo esc_attr($instance['post']); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['full'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('full')); ?>" name="<?php echo esc_attr($this->get_field_name('full')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('full')); ?>"><?php _e('Full post', AZEXO_THEME_NAME); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('template')); ?>"><?php _e('Post template:', AZEXO_THEME_NAME); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('template')); ?>" name="<?php echo esc_attr($this->get_field_name('template')); ?>">
                <?php
                foreach ($azexo_templates as $slug => $name) :
                    ?>
                    <option value="<?php echo esc_attr($slug) ?>" <?php selected($slug, $instance['template']) ?>><?php echo esc_attr($name); ?></option>
        <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

}

class AzexoPostCarousel extends WP_Widget {

    function AzexoPostCarousel() {
        parent::__construct('azexo_carousel', AZEXO_THEME_NAME . ' - Post carousel');
    }

    function widget($args, $instance) {
        global $_wp_additional_image_sizes;

        if (!empty($instance['posts'])) {
            wp_enqueue_script('owl.carousel');
            wp_enqueue_style('owl.carousel');
            $posts = explode(',', $instance['posts']);

            if (!has_image_size($instance['size'])) {
                $size_array = explode('x', $instance['size']);
                add_image_size($instance['size'], $size_array[0], $size_array[1], true);
            }

            print '<div class="owl-carousel widget widget-posts-carousel" data-width="' . $_wp_additional_image_sizes[$instance['size']]['width'] . '" data-height="' . $_wp_additional_image_sizes[$instance['size']]['height'] . '">';
            foreach ($posts as $p) {
                $post = get_post($p);
                print '<div class="item">';
                $image_src = azexo_get_attachment_image_src(get_post_thumbnail_id($p), $instance['size']);
                print '<div class="image" style="background-image: url(\'' . $image_src[0] . '\')"></div>';
                print '<div class="details">';
                print '<div class="title"><a href="' . esc_url(get_permalink($p)) . '" rel="bookmark">' . get_the_title($p) . '</a></div>';
                print '<div class="categories">' . get_the_category_list(__(', ', AZEXO_THEME_NAME), '', $p) . '</div>';
                print '<div class="date">' . azexo_entry_date(false, $post) . '</div>';
                if ($instance['excerpt'] == 'on') {
                    setup_postdata($post);
                    print '<div class="excerpt">' . wp_trim_words(apply_filters('get_the_excerpt', $post->post_excerpt), 20) . '</div>';
                }
                print '</div>';
                print '</div>';
            }
            print '</div>';
        }
    }

    function form($instance) {
        $defaults = array('title' => '', 'posts' => '', 'size' => 'post-thumbnail', 'excerpt' => 'off');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('posts')); ?>"><?php _e('Posts IDs (comma separated):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('posts')); ?>" name="<?php echo esc_attr($this->get_field_name('posts')); ?>" type="text" value="<?php echo esc_attr($instance['posts']); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('size')); ?>"><?php _e('Image size:', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('size')); ?>" name="<?php echo esc_attr($this->get_field_name('size')); ?>" type="text" value="<?php echo esc_attr($instance['size']); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['excerpt'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('excerpt')); ?>"><?php _e('Excerpt', AZEXO_THEME_NAME); ?></label>
        </p>
        <?php
    }

}

class AzexoPostPackery extends WP_Widget {

    function AzexoPostPackery() {
        parent::__construct('azexo_packery', AZEXO_THEME_NAME . ' - Post packery grid');
    }

    function widget($args, $instance) {
        if (!empty($instance['posts'])) {
            $items = array();
            $posts = explode(',', $instance['posts']);
            srand(1);
            foreach ($posts as $p) {
                $post_thumbnail_id = get_post_thumbnail_id($p);
                $metadata = wp_get_attachment_metadata($post_thumbnail_id);
                $ratio = $metadata['width'] / $metadata['height'];
                $item = array('post_id' => $p);
                $min_i = 1;
                $min_j = 1;
                $min_error = 1;
                for ($i = 1; $i <= $instance['span']; $i ++) {
                    for ($j = 1; $j <= $instance['span']; $j ++) {
                        $error = abs($j * $ratio - $i);
                        if ($min_error > $error) {
                            $min_error = $error;
                            $min_i = $i;
                            $min_j = $j;
                        }
                    }
                }
                if ($min_i == $min_j) {
                    $max_size = $min_i;
                    $r = rand(1, $max_size);
                    $size = $r * $instance['size'];
                    $item['size'] = $size . 'x' . $size;
                    $item['width'] = $r;
                    $item['height'] = $r;
                } else {
                    $w = $min_i * $instance['size'];
                    $h = $min_j * $instance['size'];
                    $item['size'] = $w . 'x' . $h;
                    $item['width'] = $min_i;
                    $item['height'] = $min_j;
                }
                $items[] = $item;
            }

            wp_enqueue_script('packery');
            wp_enqueue_script('imagesloaded');
            print '<div class="widget widget-posts-packery" data-size="' . $instance['size'] . '" data-gutter="' . $instance['gutter'] . '" data-columns="' . $instance['columns'] . '">';
            print '<div class="gutter-sizer" style="width: ' . $instance['gutter'] . 'px;"></div>';
            print '<div class="grid-sizer"></div>';
            foreach ($items as $item) {
                $post = get_post($item['post_id']);
                print '<div class="item" data-width="' . $item['width'] . '" data-height="' . $item['height'] . '">';
                $image_src = azexo_get_attachment_image_src(get_post_thumbnail_id($item['post_id']), $item['size']);
                print '<div class="image" style="background-image: url(\'' . $image_src[0] . '\')"></div>';
                print '<div class="details">';
                print '<div class="title"><a href="' . esc_url(get_permalink($item['post_id'])) . '" rel="bookmark">' . get_the_title($item['post_id']) . '</a></div>';
                print '<div class="categories">' . get_the_category_list(__(', ', AZEXO_THEME_NAME), '', $item['post_id']) . '</div>';
                print '<div class="date">' . azexo_entry_date(false, $post) . '</div>';
                print '</div>';
                print '</div>';
            }
            print '</div>';
        }
    }

    function form($instance) {
        $defaults = array('title' => '', 'posts' => '', 'size' => '300', 'span' => '2', 'columns' => '3', 'gutter' => '0');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('posts')); ?>"><?php _e('Posts IDs (comma separated):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('posts')); ?>" name="<?php echo esc_attr($this->get_field_name('posts')); ?>" type="text" value="<?php echo esc_attr($instance['posts']); ?>" />
        </p><p>
            <label for="<?php echo esc_attr($this->get_field_id('size')); ?>"><?php _e('Minimum square image size(px):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('size')); ?>" name="<?php echo esc_attr($this->get_field_name('size')); ?>" type="text" value="<?php echo esc_attr($instance['size']); ?>" />
        </p><p>
            <label for="<?php echo esc_attr($this->get_field_id('span')); ?>"><?php _e('Maximum span(number):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('span')); ?>" name="<?php echo esc_attr($this->get_field_name('span')); ?>" type="text" value="<?php echo esc_attr($instance['span']); ?>" />
        </p><p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php _e('Maximum columns(number):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>" type="text" value="<?php echo esc_attr($instance['columns']); ?>" />
        </p><p>
            <label for="<?php echo esc_attr($this->get_field_id('gutter')); ?>"><?php _e('Gutter(px):', AZEXO_THEME_NAME); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('gutter')); ?>" name="<?php echo esc_attr($this->get_field_name('gutter')); ?>" type="text" value="<?php echo esc_attr($instance['gutter']); ?>" />
        </p>
        <?php
    }

}

class NWMCarousel extends WP_Widget {
  function NWMCarousel() {
    $widget_details = array(
      'classname' => 'NWMCarousel',
      'description' => 'Posts Carousel'
    );
    parent::__construct( 'NWMCarousel', 'NWM Carousel', $widget_details );
  }

  function widget($args, $instance) {
    if(isset($instance['posts'])) {
      $posts_ids = explode(',', $instance['posts']);
    } else {
      $posts_ids = array();
    }

    if(sizeof($posts_ids)==0) {
      $posts = get_posts(array(
        'posts_per_page'   => 5,
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
    } else {
      $posts = get_posts(array(
        'post__in'        => $posts_ids,
        'orderby'          => 'date',
        'order'            => 'DESC'
      ));
    }
    $flag = true;
      ?>
        <div id="myCarousel" class="nwm-carousel carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">

        <?php foreach($posts as $post) {
            $categories = wp_get_post_categories( $post->ID );

            $image_id = get_post_thumbnail_id($post->ID);
            $image = wp_get_attachment_url( $image_id );
            $categories_css = '';
        ?>
        <article class="carousel-post item post type-post status-publish format-standard has-post-thumbnail <?php echo ($flag)?'active':'';?>">
            <div class="entry-thumbnail">
                <a href="<?php echo get_permalink($post->ID);?>">
                    <div class="image nwm-carousel-item-bg-container" style="background-image: url(<?php echo $image;?>);" data-width="500" data-height="600">
                        <img src="<?php echo $image;?>">
                    </div>
                </a>
            </div>
            <div class="entry-data nwm-carousel-item-data carousel-caption">
            <div class="entry-header">
                <div class="entry-extra">
                    <span class="categories-links nwm-carousel-item-categories-list">
                        <?php foreach($categories as $category) {
                            $c = get_category($category);
                            $categories_css.= 'category-'.$c->slug.' ';
                        ?>
                            <a class="<?php echo $c->slug;?>" href="" rel="category tag">
                                <?php echo $c->name;?>
                            </a>
                        <?php }?>

                    </span>
                </div>
                <h2 class="entry-title nwm-carousel-item-title">
                    <a href="<?php echo get_permalink($post->ID);?>" rel="bookmark">
                        <?php echo $post->post_title;?>
                    </a>
                </h2>
            </div>
            <div class="entry-footer">
                <span class="like">
                    <a href="#" class="jm-post-like" data-post_id="<?php echo $post->ID;?>" title="Like">
                        <i class="icon-unlike"></i>&nbsp;5
                    </a>
                </span>
            </div>

            </div>
          <div class="mi_categoria <?php echo $categories_css;?>"></div>
        </article>
        <?php $flag=false;} ?>
    </div>
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span aria-hidden="true">&nbsp;</span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span aria-hidden="true">&nbsp;</span>
        <span class="sr-only">Next</span>
      </a>
  </div>

        <?php
        if (!empty($instance['posts'])) {
            $posts = explode(',', $instance['posts']);

            if ( (isset($instance['size'])) && (!has_image_size($instance['size'])) ) {
                $size_array = explode('x', $instance['size']);
                add_image_size($instance['size'], $size_array[0], $size_array[1], true);
            }
        }
    }

    function form($instance) {
    ?>
        <p>Paciencia por favor, este widget es TBD</p>
    <?php

        $defaults = array('title' => '', 'posts' => '', 'size' => 'post-thumbnail', 'excerpt' => 'off');
        $instance = wp_parse_args((array) $instance, $defaults);
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('posts')); ?>">Escribe los id's de los Posts, separados por ','</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('posts')); ?>" name="<?php echo esc_attr($this->get_field_name('posts')); ?>" type="text" value="<?php echo esc_attr($instance['posts']); ?>" />
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
      return $new_instance;
    }
}
