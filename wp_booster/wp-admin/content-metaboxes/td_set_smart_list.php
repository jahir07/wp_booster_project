<div class="my_meta_control td-not-portfolio td-not-home">


    <p class="td_help_section td-inline-block-wrap">
        <span class="td_custom_label">Use a smart list? :</span>

        <div class="td-inline-block-wrap">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_smart_list',
                'item_id' => '',
                'option_id' => 'smart_list_template',
                'values' => td_api_smart_list::_helper_td_smart_list_api_to_panel_values(),
                'selected_value' => $mb->get_the_value('smart_list_template')
            ));
            ?>
        </div>
    </p>




    <p class="td_help_section td-help-select">
        <span class="td_custom_label">Title tags:</span>
        <?php $mb->the_field('td_smart_list_h'); ?>
        <div class="td-select-style-overwrite td-inline-block-wrap">
            <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                <option value="h1"<?php $mb->the_select_state('h1'); ?>>Heading 1 ( H1 tag )</option>
                <option value="h2"<?php $mb->the_select_state('h2'); ?>>Heading 2 ( H2 tag )</option>
                <option value="" <?php $mb->the_select_state(''); ?>>Heading 3 ( H3 tag )</option>
                <option value="h4"<?php $mb->the_select_state('h4'); ?>>Heading 4 ( H4 tag )</option>
                <option value="h5"<?php $mb->the_select_state('h5'); ?>>Heading 5 ( H5 tag )</option>
                <option value="h6"<?php $mb->the_select_state('h6'); ?>>Heading 6 ( H6 tag )</option>
            </select>
        </div>
    <span class="td_info_inline td-help-info-inline"> - The tags that wrap the title of each smartlist item.</span>
    </p>



    <p class="td_help_section td-help-select">
        <span class="td_custom_label">Smart list numbering:</span>
        <?php $mb->the_field('td_smart_list_order'); ?>
        <div class="td-select-style-overwrite td-inline-block-wrap">
            <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                <option value=""<?php $mb->the_select_state(''); ?>>Descending (ex: 3, 2, 1)</option>
                <option value="asc_1" <?php $mb->the_select_state('asc_1'); ?>>Ascending (ex: 1, 2, 3)</option>
            </select>
        </div>
        <span class="td_info_inline td-help-info-inline"> - The smart lists put a number on each item.</span>
    </p>
</div>
