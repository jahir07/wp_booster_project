<form id="td_panel_big_form" action="?page=td_theme_panel" method="post">
<input type="hidden" name="action" value="td_ajax_update_panel">
<div class="td_displaying_saving"></div>
<div class="td_wrapper_saving_gifs">
    <img class="td_displaying_saving_gif" src="<?php echo get_template_directory_uri();?>/includes/wp_booster/wp-admin/images/panel/loading.gif">
    <img class="td_displaying_ok_gif" src="">
</div>


<div class="wrap">

<div class="td-container-wrap">

<div class="td-panel-main-header">
    <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/panel-wrap/panel-logo.png'?>" alt=""/>
    <span><?php echo sprintf('%s - Theme panel', strtoupper(TD_THEME_NAME)) ?></span>
</div>


<div id="td-container-left">
    <div id="td-container-right">
        <div id="td-col-left">
            <ul class="td-panel-menu">

                <?php
                //show the panel tabs on the left
                $td_is_first_panel = true;
                $td_first_menu_item_class = 'td-panel-menu-active'; //to show the class only on the first loop
                $td_first_menu_welcome_menu = 'td-welcome-menu'; //we are using this class to fix some rendering issues with the first menu

                foreach (td_global::$theme_panels_list as $panel_id => $panel_array) {
                    if (is_array($panel_array)) {
                        // it's a normal menu
                        ?>
                        <li class="<?php echo $td_first_menu_welcome_menu?>">
                            <a data-panel="<?php echo $panel_id ?>" data-bg="<?php echo esc_attr(get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/bg/1.jpg')?>" class="<?php echo $td_first_menu_item_class; ?>" href="#">
                                <span class="td-sp-nav-icon <?php echo $panel_array['ico_class'] ?>"></span>
                                <?php echo $panel_array['text'] ?>
                                <span class="td-arrow"></span>
                            </a>
                        </li>

                        <?php
                    } else {
                        //it's a group
                        ?>
                        <li class="td-panel-menu-sep"><?php echo $panel_array ?></li>
                        <?php
                    }
                    $td_first_menu_item_class = ''; // do not show any more td-panel-menu-active
                    $td_first_menu_welcome_menu = '';
                }
                ?>

            </ul>
        </div>
        <div id="td-col-rigth" class="td-panel-content">

            <?php
            // show the panel views
            $td_panel_active = 'td-panel-active'; //to show the class only on the first loop
            foreach (td_global::$theme_panels_list as $panel_id => $panel_array) {
                if (is_array($panel_array)) {
                    ?>
                    <div id="<?php echo $panel_id ?>" class="<?php echo $td_panel_active ?> td-panel">
                        <?php
                        /**
                         * 1. try to locate the template in 'includes/panel/views/' (also checks in the child theme)
                         * 2. include the default panel from wp_booster if none is found
                         */
                        $td_template_found_in_theme_or_child = locate_template('includes/panel/views/' . $panel_array['file_id'] . '.php', true);
                        if (empty($td_template_found_in_theme_or_child)) {
                            require_once('views/' . $panel_array['file_id'] . '.php'); // the panel is loaded from our hardcoded global panel list - there should be no security issues
                        }
                        ?>
                    </div>
                    <?php
                    $td_panel_active = '';
                }
            }
            ?>

        </div>
    </div>
</div>

<div class="td-clear"></div>

<div class="td-panel-main-footer">
    <input type="button" id="td_button_save_panel" class="td-panel-save-button" value="SAVE SETTINGS">
</div>

</div>

<div class="td-clear"></div>
</form>
</div>
