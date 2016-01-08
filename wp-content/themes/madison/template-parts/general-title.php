<?php
$options = get_option(AZEXO_THEME_NAME);
?>

<?php if (is_404()) : ?>
    <header class="page-header">
        <h1 class="page-title"><?php _e('Not Found', AZEXO_THEME_NAME); ?></h1>
    </header>
<?php elseif (is_category()): ?>
    <header class="archive-header">
        <h1 class="archive-title"><?php echo single_cat_title('', false); ?></h1>
        <div class="archive-subtitle"><?php echo __('Category', AZEXO_THEME_NAME); ?></div>
        <?php if (category_description()) : // Show an optional category description ?>
            <div class="archive-meta"><?php echo category_description(); ?></div>
        <?php endif; ?>
    </header><!-- .archive-header -->
<?php elseif (is_tag()): ?>
    <header class="archive-header">
        <h1 class="archive-title"><?php echo single_tag_title('', false); ?></h1>
        <div class="archive-subtitle"><?php echo __('Tag', AZEXO_THEME_NAME); ?></div>
        <?php if (tag_description()) : // Show an optional tag description  ?>
            <div class="archive-meta"><?php echo tag_description(); ?></div>
        <?php endif; ?>
    </header><!-- .archive-header -->
<?php elseif (is_archive()): ?>
    <header class="archive-header">
        <h1 class="archive-title">
            <?php
            if (is_day()) :
                echo get_the_date();
            elseif (is_month()) :
                echo get_the_date(_x('F Y', 'monthly archives date format', AZEXO_THEME_NAME));
            elseif (is_year()) :
                echo get_the_date(_x('Y', 'yearly archives date format', AZEXO_THEME_NAME));
            else :
                _e('Archives', AZEXO_THEME_NAME);
            endif;
            ?>
        </h1>
        <?php if (is_day() || is_month() || is_year()) : ?>
            <div class="archive-subtitle">
                <?php
                if (is_day()) :
                    _e('Daily Archives', AZEXO_THEME_NAME);
                elseif (is_month()) :
                    _e('Monthly Archives', AZEXO_THEME_NAME);
                elseif (is_year()) :
                    _e('Yearly Archives', AZEXO_THEME_NAME);
                endif;
                ?>
            </div>
        <?php endif; ?>
    </header><!-- .archive-header -->
<?php elseif (is_search()): ?>
    <header class="page-header">
        <h1 class="page-title"><?php echo __('Search Results for', AZEXO_THEME_NAME); ?></h1>
        <div class="page-subtitle"><?php echo get_search_query(); ?></div>
    </header>
<?php elseif (is_page()): ?>
    <header class="page-header">
        <h1 class="page-title">
            <?php
            $post = get_post();
            if ($post) {
                print $post->post_title;
            }
            ?>
        </h1>
        <?php if (isset($options['show_breadcrumbs']) && $options['show_breadcrumbs']): ?>
            <div class="page-subtitle">
                <?php azexo_breadcrumbs(); ?>
            </div>        
        <?php endif; ?>
    </header>
<?php elseif (is_single()): ?>
    <header class="page-header">
        <h1 class="page-title">
            <?php
            if (isset($options['post_page_title'])) {
                print azexo_entry_field('post', $options['post_page_title']);
            }
            ?>
        </h1>
    </header>
<?php else: ?>
    <header class="page-header">
        <h1 class="page-title">
            <?php
            print isset($options['default_title']) ? $options['default_title'] : '';
            ?>
        </h1>
    </header>
<?php endif; ?>
