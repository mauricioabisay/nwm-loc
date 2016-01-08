<div id="taxonomy-<?php echo $taxonomy; ?>">
    <!-- Display taxonomy terms -->
    <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
    	<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
    	<?php foreach($terms as $term) { ?>
    		<li id="<?php echo $id;?>"><label class='selectit'>
            <input type="checkbox" id="in-<?php echo $taxonomy.'-'.$term->term_id;?>" name="tax_input[<?php echo $taxonomy;?>][]" <?php echo checked($current,$term->term_id,false);?> value="<?php echo $term->term_id;?>" />
            <?php echo $term->name;?><br/>
            </label></li>
		<?php } ?>
		</ul>
    </div>
</div>