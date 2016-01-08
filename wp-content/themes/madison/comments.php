<?php
if (post_password_required())
    return;
?>
<?php if(!is_page('Beer List') && !is_page('Beer List News') && !is_page('Beer List Suggestions') && !is_page('Gabinete')) {?>
<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php comments_number(__('No Comments'), __('One Comment'), __('% Comments'));
            ?>
        </h3>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'walker' => new Azexo_Walker_Comment(),
                'avatar_size' => 60,
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php
        // Are there comments to navigate through?
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h1 class="screen-reader-text section-heading"><?php _e('Comment navigation'); ?></h1>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;')); ?></div>
            </nav><!-- .comment-navigation -->
        <?php endif; // Check for comment navigation   ?>

        <?php if (!comments_open() && get_comments_number()) : ?>
            <p class="no-comments"><?php _e('Comments are closed.'); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments()   ?>

    <?php
    if (comments_open()) :
        $args = array(
            'id_form' => 'commentform',
            'id_submit' => 'submit',
            'title_reply' => __('Leave a Reply'),
            'title_reply_to' => __('Leave a Reply to %s'),
            'cancel_reply_link' => __('Cancel Reply'),
            'label_submit' => __('submit'),
            'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __('message') . '"></textarea>',
            'must_log_in' => '<p class="must-log-in">' .
            sprintf(
                    __('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink()))
            ) . '</p>',
            'logged_in_as' => '<p class="logged-in-as">' .
            sprintf(
                    __('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))
            ) . '</p>',
            'comment_notes_before' => '',
            'comment_notes_after' => '',
            'fields' => apply_filters('comment_form_default_fields', array(
                'author' =>
                '<div><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" placeholder="' . __('name') . '"/>',
                'email' =>
                '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" placeholder="' . __('email') . '" /></div>',
                'url' =>
                '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" placeholder="' . __('website') . '" />'
                    )
            ),
        );
        if(is_page('Review Sample')) {
          $args['title_reply'] = 'Comenta esta cerveza';
        }
        comment_form($args);
    endif;
    ?>
</div><!-- #comments -->
<?php }?>
