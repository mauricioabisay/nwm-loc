<form role="search" method="get" class="searchform" action="<?php print esc_url(home_url('/')); ?>">
    <div class="searchform-wrapper">
        <label class="screen-reader-text"><?php print _x('Search for:', AZEXO_THEME_NAME); ?></label>
        <input type="text" value="<?php print get_search_query(); ?>" name="s" />
        <div class="submit"><input type="submit" value="<?php print esc_attr_x('Search', AZEXO_THEME_NAME); ?>"></div>
    </div>
</form>