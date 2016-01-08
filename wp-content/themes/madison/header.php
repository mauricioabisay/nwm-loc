<?php
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <?php $options = get_option(AZEXO_THEME_NAME); ?>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width">
        <!--Shortcut icon-->
        <?php
        if (isset($options['favicon']['url']) && !empty($options['favicon']['url'])) {
            ?>
            <link rel="shortcut icon" href="<?php echo esc_url($options['favicon']['url']); ?>" />
        <?php } ?>
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div id="preloader"><div id="status"></div></div>
        <div id="page" class="hfeed site">

            <header id="masthead" class="site-header clearfix">
              <?php
              if (!is_page_template('page-templates/fullscreen.php')) {
                get_sidebar('header');
              }
              ?>
              <?php if(!is_page('Review Sample')) { ?>
              <div class="header-main clearfix <?php print (is_page_template('page-templates/fullscreen.php') ? 'fs' : ''); ?>">

                <div class="container">

                    <?php if (isset($options['logo']['url']) && !empty($options['logo']['url'])) { ?>
                      <a class="site-title" href="<?php echo (is_page('Gabinete')) ? get_permalink(get_page_by_title('Beer List')) : esc_url(home_url('/')); ?>" rel="home">
                        <div class="nwm-title-img-container">
                          <img src="<?php echo esc_url($options['logo']['url']); ?>" alt="logo">
                        </div>
                      </a>
                    <?php } ?>
                    <div class="mobile-menu-button"><span><i class="fa fa-bars"></i></span></div>
                    <?php
                      $options = get_option(AZEXO_THEME_NAME);
                      if (isset($options['show_search']) && $options['show_search']) {
                        azexo_get_search_form();
                      }
                      if (isset($options['header'])) {
                    ?>
                        <div class="custom-header"><?php print $options['header'];?></div>
                    <?php
                      }
                    ?>
                      <nav class="site-navigation mobile-menu">
                        <?php
                          if(is_page('Beer List')||is_page('Beer List News')||is_page('Beer List Suggestions')||is_page('Gabinete')||is_page('Review Sample')) {
                            wp_nav_menu(array(
                              'theme_location' => 'primary',
                              'menu_class' => 'nav-menu',
                              'menu' => 'Review',
                              'walker' => new Azexo_Walker_Nav_Menu(),
                            ));
                          } else {
                            if (has_nav_menu('primary') && !is_page('Review Sample')) {
                              wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_class' => 'nav-menu',
                                'menu_id' => 'primary-menu-mobile',
                                'walker' => new Azexo_Walker_Nav_Menu(),
                              ));
                            }
                          }
                        ?>
                      </nav>
                      <nav class="site-navigation primary-navigation">
                      <?php
                        if(is_page('Beer List')||is_page('Beer List News')||is_page('Beer List Suggestions')||is_page('Gabinete')) {
                          wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'nav-menu',
                            'menu' => 'Review',
                            'walker' => new Azexo_Walker_Nav_Menu(),
                          ));
                        } else {
                          if (has_nav_menu('primary') && !is_page('Review Sample')) {
                            wp_nav_menu(array(
                              'theme_location' => 'primary',
                              'menu_class' => 'nav-menu',
                              'menu_id' => 'primary-menu',
                              'walker' => new Azexo_Walker_Nav_Menu(),
                            ));
                          }
                        }
                      ?>
                      </nav>

                </div><!--END OF CONTAINER-->

              </div><!--END OF HEADER-->
              <?php } ?>
                <?php
                if (!is_page_template('page-templates/fullscreen.php')) {
                    get_sidebar('middle');
                }
                ?>
            </header><!-- #masthead -->

            <div id="main" class="site-main">
