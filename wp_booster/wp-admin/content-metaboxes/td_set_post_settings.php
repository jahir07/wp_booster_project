<div class="my_meta_control">

    <p class="td_help_section td-inline-block-wrap td-post-settings-post-template">
        <span class="td_custom_label">Post template:</span>
        <div class="td-inline-block-wrap">
        <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_post_theme_settings',
                'item_id' => '',
                'option_id' => 'td_post_template',
                'values' => td_api_single_template::_helper_td_global_list_to_panel_values(),
                'selected_value' => $mb->get_the_value('td_post_template')
            ));
        ?>
        </div>
    </p>

    <p class="td_help_section td-inline-block-wrap td-post-settings-post-template">
        <span class="td_custom_label">Sidebar position:</span>
        <div class="td-inline-block-wrap">
        <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_post_theme_settings',
                'item_id' => '',
                'option_id' => 'td_sidebar_position',
                'values' => array(
                                    array('text' => '', 'title' => '', 'val' => '', 'class' => 'td-sidebar-position-default', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-default.png'),
                                    array('text' => '', 'title' => '', 'val' => 'sidebar_left', 'class' => 'td-sidebar-position-left', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-left.png'),
                                    array('text' => '', 'title' => '', 'val' => 'no_sidebar', 'class' => 'td-no-sidebar', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-full.png'),
                                    array('text' => '', 'title' => '', 'val' => 'sidebar_right', 'class' => 'td-sidebar-position-right','img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-right.png')
                                ),
                'selected_value' => $mb->get_the_value('td_sidebar_position')
            ));
        ?>
        </div>
    </p>



    <p class="td_help_section td-custom-sidebar-label">
        <span class="td_custom_label ">Custom sidebar:</span>

        <div class="td-display-inline-block td_sidebars_pulldown_align">
            <?php
            echo td_panel_generator::sidebar_pulldown(array(
                'ds' => 'td_post_theme_settings',
                'item_id' => '',
                'option_id' => 'td_sidebar',
                'selected_value' => $mb->get_the_value('td_sidebar')
            ));
            ?>
            <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
        </div>
    </p>


    <p class="td_help_section td-help-select">
        <span class="td_custom_label">Primary category:</span>
        <?php $mb->the_field('td_primary_cat'); ?>
        <div class="td-select-style-overwrite td-inline-block-wrap">
            <select name="<?php $mb->the_name(); ?>" class="td-panel-dropdown">
                <option value="">Auto select a category</option>
                <?php
                $td_current_categories = td_util::get_category2id_array(false);

                //print_r($td_current_categories);
                //die;
                foreach ($td_current_categories as $td_category => $td_category_id) {
                    ?>
                    <option value="<?php echo $td_category_id?>"<?php $mb->the_select_state($td_category_id); ?>><?php echo $td_category?></option>
                <?php
                }
                ?>
            </select>
        </div>

        <span class="td_info_inline td-help-info-inline"> - If the posts has multiple categories, the one selected here will show up in blocks.</span>

    </p>


    <p class="td_help_section">
        <?php $mb->the_field('td_subtitle'); ?>
        <span class="td_custom_label td_text_area_label">Subtitle:</span>
        <textarea name="<?php $mb->the_name(); ?>" class="td-textarea-subtitle"><?php $mb->the_value(); ?></textarea>
        <span class="td_info_inline td_info_inline_textarea">this text will appear under the title</span>
    </p>

    <p class="td_help_section">
        <?php $mb->the_field('td_quote_on_blocks'); ?>
        <span class="td_custom_label">Quote on blocks:</span>
        <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
        <span class="td_info_inline td_info_inline_textarea">show a quote (only when this article shows up in blocks that support quote and only on blocks that are on one row)</span>
    </p>

    <p class="td_help_section">
        <?php $mb->the_field('td_source'); ?>
        <span class="td_custom_label">Source name:</span>
        <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
        <span class="td_info_inline"> - name of the source</span>
    </p>

    <p class="td_help_section">
        <?php $mb->the_field('td_source_url'); ?>
        <span class="td_custom_label">Source url:</span>
        <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
        <span class="td_info_inline"> - url to the source</span>
    </p>

    <p class="td_help_section">
        <?php $mb->the_field('td_via'); ?>
        <span class="td_custom_label">Via name:</span>
        <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

    </p>


    <p class="td_help_section">
        <?php $mb->the_field('td_via_url'); ?>
        <span class="td_custom_label">Via url:</span>
        <input class="td-input-text-post-settings" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
    </p>

</div>


