<div id="taxonomy-<?php echo $taxonomy; ?>">
    <!-- Display taxonomy terms -->
    <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
        <select id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy;?> categorychecklist form-no-clear" name="tax_input[<?php echo $taxonomy;?>][]">
            <?php foreach($terms as $term) { ?>
            <option id="<?php echo 'in-'.$taxonomy.'-'.$term->term_id;?>" value="<?php echo $term->term_id;?>" name="tax_input[<?php echo $taxonomy;?>][]" <?php selected( $term->term_id, $current, true);?>>
                <?php echo $term->name; selected($current, $term->term_id, false);?></option>
            <?php } ?>
        </select>
    </div>
</div>
