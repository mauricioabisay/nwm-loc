<form action="" id="primaryPostForm" method="POST">
 
    <fieldset>
        <label for="postTitle"><?php _e('Post Title:', 'framework') ?></label>
 
        <input type="text" name="postTitle" id="postTitle" class="required" />
    </fieldset>
 
    <fieldset>
        <label for="postContent"><?php _e('Post Content:', 'framework') ?></label>
 
        <textarea name="postContent" id="postContent" rows="8" cols="30" class="required"></textarea>
    </fieldset>
 
    <fieldset>
        <input type="hidden" name="submitted" id="submitted" value="true" />
 
        <button type="submit"><?php _e('Add Post', 'framework') ?></button>
    </fieldset>
 
</form>