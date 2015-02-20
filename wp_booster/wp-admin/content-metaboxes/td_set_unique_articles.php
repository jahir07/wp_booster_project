<div class="my_meta_control td-page-module-loop-wrap">
    <p class="td_help_section td-help-select">
        <span class="td_custom_label">Unique articles:</span>
        <?php $mb->the_field('td_unique_articles'); ?>
        <div class="td-select-style-overwrite td-inline-block-wrap">
            <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                <option value=""> - Disabled - </option>
                <option value="enabled"<?php $mb->the_select_state('enabled'); ?>>Enabled</option>
            </select>
        </div>
    </p>


</div>