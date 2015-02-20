<div class="my_meta_control">

    <p><strong>Note:</strong> The settings from this box only work if you do not use visual composer on this template. The template detects if visual composer is used and it removes the title and sidebars if that's the case. </p>


    <p class="td_help_section td-inline-block-wrap td-post-settings-post-template">
        <span class="td_custom_label">Sidebar position:</span>



        <div class="td-inline-block-wrap">
            <?php
            echo td_panel_generator::visual_select_o(array(
                'ds' => 'td_page',
                'item_id' => '',
                'option_id' => 'td_sidebar_position',
                'values' => array(
                    array('text' => '', 'title' => '', 'val' => '', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-default.png'),
                    array('text' => '', 'title' => '', 'val' => 'sidebar_left', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-left.png'),
                    array('text' => '', 'title' => '', 'val' => 'no_sidebar', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-full.png'),
                    array('text' => '', 'title' => '', 'val' => 'sidebar_right', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/sidebar-right.png')
                ),
                'selected_value' => $mb->get_the_value('td_sidebar_position')
            ));
            ?>
        </div>
    </p>



    <p class="td_help_section td-custom-sidebar-label">
        <span class="td_custom_label">Custom sidebar:</span>

        <div class="td-display-inline-block td_sidebars_pulldown_align">
            <?php
            echo td_panel_generator::sidebar_pulldown(array(
                'ds' => 'td_page',
                'item_id' => '',
                'option_id' => 'td_sidebar',
                'selected_value' => $mb->get_the_value('td_sidebar')
            ));
            ?>
            <div class="td-panel-control-comment td-text-align-right">Create or select an existing sidebar</div>
        </div>

    </p>
</div>


