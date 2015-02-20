<div class="my_meta_control td-not-portfolio td-not-home">


    <p class="td_help_section td-help-select">
        <span class="td_custom_label">Is product review? :</span>

        <?php $mb->the_field('has_review'); ?>

        <div class="td-select-style-overwrite td-inline-block-wrap">
            <select id="reviewSelector" name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                <option value="">No</option>
                <option value="rate_stars"<?php $mb->the_select_state('rate_stars'); ?>>Stars</option>
                <option value="rate_percent"<?php $mb->the_select_state('rate_percent'); ?>>Percentages</option>
                <option value="rate_point"<?php $mb->the_select_state('rate_point'); ?>>Points</option>
            </select>
        </div>
    </p>


    <div class="rating_type rate_Stars">
        <div><strong>Add star ratings for this product:</strong></div>
        <div class="my_meta_control td-not-home">
            <?php while($mb->have_fields_and_multi('p_review_stars')): ?>
            <?php $mb->the_group_open(); ?>

                <?php $mb->the_field('desc'); ?>
                <span class="td_custom_label">Feature name:</span>
                <input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                <?php $mb->the_field('rate'); ?>

                <select name="<?php $mb->the_name(); ?>">
                    <option value="">Select rating</option>
                    <option value="5"<?php $mb->the_select_state('5'); ?>>5 stars</option>
                    <option value="4.5"<?php $mb->the_select_state('4.5'); ?>>4.5 stars</option>
                    <option value="4"<?php $mb->the_select_state('4'); ?>>4 stars</option>
                    <option value="3.5"<?php $mb->the_select_state('3.5'); ?>>3.5 stars</option>
                    <option value="3"<?php $mb->the_select_state('3'); ?>>3 stars</option>
                    <option value="2.5"<?php $mb->the_select_state('2.5'); ?>>2.5 stars</option>
                    <option value="2"<?php $mb->the_select_state('2'); ?>>2 stars</option>
                    <option value="1.5"<?php $mb->the_select_state('1.5'); ?>>1.5 stars</option>
                    <option value="1"<?php $mb->the_select_state('1'); ?>>1 stars</option>
                    <option value="0.5"<?php $mb->the_select_state('0.5'); ?>>0.5 stars</option>
                    <option value="0"<?php $mb->the_select_state('0'); ?>>0 stars</option>
                </select>


                        <a href="#" class="dodelete button">Delete</a>

            <?php $mb->the_group_close(); ?>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_stars button">Add rating category</a></p>
        </div>
    </div>


    <div class="rating_type rate_Percentages">
        <div><strong>Add percent ratings for this product:</strong></div>
        <div class="my_meta_control td-not-home">
            <?php while($mb->have_fields_and_multi('p_review_percents')): ?>
                <?php $mb->the_group_open(); ?>

                <?php $mb->the_field('desc'); ?>
                <span class="td_custom_label">Feature name: </span><input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                <?php $mb->the_field('rate'); ?>
                - Percent:
                <input style="width: 100px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>


                <a href="#" class="dodelete button">Delete</a>

                <?php $mb->the_group_close(); ?>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_percents button">Add rating category</a></p>
        </div>
    </div>


    <div class="rating_type rate_Points">
        <div><strong>Add points ratings for this product:</strong></div>
        <div class="my_meta_control td-not-home">
            <?php while($mb->have_fields_and_multi('p_review_points')): ?>
                <?php $mb->the_group_open(); ?>

                <?php $mb->the_field('desc'); ?>
                <span class="td_custom_label">Feature name: </span><input style="width: 200px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

                <?php $mb->the_field('rate'); ?>
                - Points:
                <input style="width: 100px;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>


                <a href="#" class="dodelete button">Delete</a>

                <?php $mb->the_group_close(); ?>
            <?php endwhile; ?>

            <p><a href="#" class="docopy-p_review_points button">Add rating category</a></p>
        </div>
    </div>

    <div class="review_desc">
        <div><strong>Review description:</strong></div>
        <p class="td_help_section">
            <?php $mb->the_field('review'); ?>

            <textarea class="td-textarea-subtitle" " type="text" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea>
        </p>
    </div>

</div>


<script>
    jQuery().ready(function() {
        td_updateMetaboxes();

        jQuery('#reviewSelector').change(function() {
            td_updateMetaboxes();
        });

        function td_updateMetaboxes() {
            var cur_selection = jQuery('#reviewSelector option:selected').text();

            if(cur_selection.indexOf("No") !== -1) {
                //alert('ra');
                jQuery('.rating_type').hide();
                jQuery('.review_desc').hide();

            } else {
                jQuery('.rating_type').hide();
                jQuery('.rate_' + cur_selection).show();
                jQuery('.review_desc').show();
                //alert(cur_selection);
            }



        }
    }); //end on load
</script>