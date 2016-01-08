<?php
$options = get_option(AZEXO_THEME_NAME);
if (!isset($show_sidebar)) {
    $show_sidebar = true;
}
get_header();

?>

<div class="container active-sidebar">
    <div id="primary" class="content-area">
        
    <header class="page-header">
        <h1 class="page-title"></h1>
    </header>
    <div id="content" class="site-content" role="main">                                   
        <article class="post post-10 review type-review status-publish hentry">
        <div class="entry-data">
            <div class="entry-header">
                    <h2 class="entry-title"><?php echo the_author_meta( 'display_name', $post->post_author );?> | <?php echo $post->post_title;?></h2>
            </div>
            <div class="entry-content">
                <?php $meta = get_post_meta( $post->ID );?>
                <div class="">
                    <?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id( $meta['cerveza_id'][0] ) ); ?>
                    <img src="<?php echo $feat_image;?>" />
                </div>
                <div class="">
                    <div>Espuma: <?php echo $meta['espuma'][0];?></div>
                    <div>Alcohol: <?php echo $meta['alcohol'][0];?></div>
                    <div>Cuerpo: <?php echo $meta['cuerpo'][0];?></div>
                    <div>Final: <?php echo $meta['final'][0];?></div>
                    <div>Amargor: <?php echo $meta['amargor'][0];?></div>
                </div>
            </div><!-- .entry-content -->
                <div class="entry-footer">
                    <span class="date">
                        <a href="http://localhost/cheve/review/auto-draft/" title="Permalink to Auto Draft" rel="bookmark">
                            <time class="entry-date" datetime="2015-09-04T18:34:39+00:00">September 4, 2015</time>
                        </a>
                    </span>
                    <span class="like">
                        <a href="#" class="jm-post-like" data-post_id="10" title="Like">
                            <i class="icon-unlike"></i>&nbsp;Like</a>
                    </span>
                </div>

            <div class="entry-share">
                <div class="helper">
                    SHARE OR SAVE THIS POST FOR LATER USAGE                </div>
                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%2Fcheve%2Freview%2Fauto-draft%2F">
                    <span class="share-box">
                        <i class="fa fa-facebook"></i>
                    </span>
                </a>
                <a target="_blank" href="https://twitter.com/home?status=Check%20out%20this%20article%3A%20Auto%20Draft%20-%20http%3A%2F%2Flocalhost%2Fcheve%2Freview%2Fauto-draft%2F">
                    <span class="share-box">
                        <i class="fa fa-twitter"></i>
                    </span>
                </a>
                <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http%3A%2F%2Flocalhost%2Fcheve%2Freview%2Fauto-draft%2F&amp;title=Auto%20Draft&amp;source=LinkedIn">
                    <span class="share-box">
                        <i class="fa fa-linkedin"></i>
                    </span>
                </a>
                <a target="_blank" href="https://plus.google.com/share?url=http%3A%2F%2Flocalhost%2Fcheve%2Freview%2Fauto-draft%2F">
                    <span class="share-box">
                        <i class="fa fa-google-plus"></i>
                    </span>
                </a>
            </div>
        </div>    
</article><!-- #post -->
                
                                                
<div id="comments" class="comments-area">
    
                            <div id="respond" class="comment-respond">
                <h3 id="reply-title" class="comment-reply-title">Leave a Reply <small><a rel="nofollow" id="cancel-comment-reply-link" href="/cheve/review/auto-draft/#respond" style="display:none;">Cancel Reply</a></small></h3>
                                    <form action="http://localhost/cheve/wp-comments-post.php" method="post" id="commentform" class="comment-form">
                                                                            <p class="logged-in-as">Logged in as <a href="http://localhost/cheve/wp-admin/profile.php">admin</a>. <a href="http://localhost/cheve/wp-login.php?action=logout&amp;redirect_to=http%3A%2F%2Flocalhost%2Fcheve%2Freview%2Fauto-draft%2F&amp;_wpnonce=6f792e66ad" title="Log out of this account">Log out?</a></p>                                                                          <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="message"></textarea>                     
                        <p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="submit"> <input type="hidden" name="comment_post_ID" value="10" id="comment_post_ID">
<input type="hidden" name="comment_parent" id="comment_parent" value="0">
</p><input type="hidden" id="_wp_unfiltered_html_comment_disabled" name="_wp_unfiltered_html_comment" value="647e1c1a94"><script>(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();</script>
                    </form>
                            </div><!-- #respond -->
            </div><!-- #comments -->
            
        </div><!-- #content -->
    </div><!-- #primary -->
<!--
        <div id="tertiary" class="sidebar-container" role="complementary">
        <div class="sidebar-inner">
            <div class="widget-area clearfix">
                <div id="search-2" class="widget widget_search"><form role="search" method="get" class="searchform" action="http://localhost/cheve/">
    <div class="searchform-wrapper">
        <label class="screen-reader-text">Search for:</label>
        <input type="text" value="" name="s" placeholder="Search">
        <div class="submit"><input type="submit" value="Search"></div>
    </div>
</form></div>       <div id="recent-posts-2" class="widget widget_recent_entries">      <div class="widget-title"><h3>Recent Posts</h3></div>       <ul>
                    <li>
                <a href="http://localhost/cheve/2015/09/04/hello-world/">Hello world!</a>
                        </li>
                </ul>
        </div><div id="recent-comments-2" class="widget widget_recent_comments"><div class="widget-title"><h3>Recent Comments</h3></div><ul id="recentcomments"><li class="recentcomments"><span class="comment-author-link"><a href="https://wordpress.org/" rel="external nofollow" class="url">Mr WordPress</a></span> on <a href="http://localhost/cheve/2015/09/04/hello-world/#comment-1">Hello world!</a></li></ul></div><div id="archives-2" class="widget widget_archive"><div class="widget-title"><h3>Archives</h3></div>        <ul>
    <li><a href="http://localhost/cheve/2015/09/">September 2015</a></li>
        </ul>
</div><div id="categories-2" class="widget widget_categories"><div class="widget-title"><h3>Categories</h3></div>       <ul>
    <li class="cat-item cat-item-1"><a href="http://localhost/cheve/category/uncategorized/">Uncategorized</a>
</li>
        </ul>
</div><div id="meta-2" class="widget widget_meta"><div class="widget-title"><h3>Meta</h3></div>         <ul>
            <li><a href="http://localhost/cheve/wp-admin/">Site Admin</a></li>          <li><a href="http://localhost/cheve/wp-login.php?action=logout&amp;_wpnonce=6f792e66ad">Log out</a></li>
            <li><a href="http://localhost/cheve/feed/">Entries <abbr title="Really Simple Syndication">RSS</abbr></a></li>
            <li><a href="http://localhost/cheve/comments/feed/">Comments <abbr title="Really Simple Syndication">RSS</abbr></a></li>
<li><a href="https://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress.org</a></li>           </ul>
</div>            </div><!-- .widget-area
        </div><!-- .sidebar-inner
    </div><!-- #tertiary
</div>-->

    <?php
    if ($show_sidebar)
        get_sidebar();
    ?>
</div>
<?php get_footer(); ?>