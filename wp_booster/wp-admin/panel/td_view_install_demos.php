<?php

require_once "td_view_header.php";



//$img_id = td_stacks_media::add_image_to_media_gallery('http://demo.tagdiv.com/newsmag/wp-content/uploads/2014/08/photo4.jpg', '');

/*
$cate_id = td_stacks_category::add_category('axra1');
td_stacks_category::add_category('axra2', $cate_id);
$cate_id2 = td_stacks_category::add_category('axra3', $cate_id);
td_stacks_category::add_category('axra4', $cate_id2);

td_stacks_category::remove();

die;

td_stacks_widgets::add_sidebar('td_demo_main');

td_stacks_widgets::add_widget_to_sidebar('td_demo_main', 'td_block_9_widget',
    array (
        'sort' => 'featured',
        'custom_title' => 'EDITOR PICKS',
        'limit' => '4',
        'header_color' => '#f24b4b'
    )
);



td_stacks_widgets::remove();
td_stacks_content::add_post(array(
    'title' => 'my_post',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_post(array(
    'title' => 'my_post2',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));

td_stacks_content::add_post(array(
    'title' => 'my_post3',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));

td_stacks_content::remove();



td_stacks_content::add_page(array(
    'title' => 'test page xx1',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => '',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_page(array(
    'title' => 'test page xx2',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => 'page-pagebuilder-title.php',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_page(array(
    'title' => 'test page xx3',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => '',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));
td_stacks_content::remove();
die;

/*
td_stacks_menus::remove();

$td_stack_top_menu_id = td_stacks_menus::create_menu('td_stack_top', 'top-menu');

    $last_menu_item_id = td_stacks_menus::add_link($td_stack_top_menu_id, 'testing', '#');
    td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 1', '#', $last_menu_item_id);
        $last_menu_item_id_2 = td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 2', '#', $last_menu_item_id);
        td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 3', '#', $last_menu_item_id_2);

*/



/**
 * this is the import theme style / stacks view
 */

$td_import_fonts_show_update_msg = false;

/**
 * holds the imported settings
 */
class td_style_imported_settings {




}

/*
if(!empty($_REQUEST['td_option'])) {


    switch ($_REQUEST['td_option']) {

        case 'factory_restore':
            $new_blank_options = array(
                'sidebars' => '',
                'td_ad_spots' => '',
                'firstInstall' => 'yes',
                'envato_key' => td_util::get_option('envato_key'),
                'td_cake_status_time' => td_util::get_option('td_cake_status_time'),
                'td_cake_status' => td_util::get_option('td_cake_status')
            );
            update_option(TD_THEME_OPTIONS_NAME, $new_blank_options);
            $td_import_fonts_show_update_msg = true;
            break;

        case 'default_style':
            foreach (td_style_imported_settings::$td_array_import_settings_from_file as $import_setting_from_file) {
                td_global::$td_options[$import_setting_from_file] = '';
            }

            //typography settings
            td_global::$td_options['td_fonts'] = '';

            //css font files (google) buffer
            td_global::$td_options['td_fonts_css_files'] = '';

            //compile user css if any
            td_global::$td_options['tds_user_compile_css'] = td_css_generator();

            update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);




            $td_import_fonts_show_update_msg = true;
            break;

        default:
            if (array_key_exists($_REQUEST['td_option'], td_global::$stacks_list)) {
                $td_import_fonts_show_update_msg = td_import_demo_style_fonts($_REQUEST['td_option']);
            }
            break;
    }

}
*/
//import demo style fonts only


?>
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
                            <li class="td-welcome-menu">
                                <a data-td-is-back="yes" class="td-panel-menu-active" href="?page=td_theme_panel">
                                    <span class="td-sp-nav-icon td-ico-welcome"></span>
                                    PREDEFINED STYLES
                                    <span class="td-no-arrow"></span>
                                </a>
                            </li>

                            <li>
                                <a data-td-is-back="yes" href="?page=td_theme_panel">
                                    <span class="td-sp-nav-icon td-ico-back"></span>
                                    Back
                                    <span class="td-no-arrow"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="td-col-rigth" class="td-panel-content">

                        <!-- Export theme settings -->
                        <div id="td-panel-welcome" class="td-panel-active td-panel">

                            <?php echo td_panel_generator::box_start('Import predefined styles'); ?>



                            <div class="td-box-row">
                                <div class="td-box-description td-box-full">
                                    <span class="td-box-title">More information:</span>
                                    <p>The footer uses sidebars to show information. Here you can customize the number of sidebars and the layout. To add content to the footer head go to the widgets section and drag widget to the Footer 1, Footer 2 and Footer 3 sidebars.</p>
                                </div>
                                <div class="td-box-row-margin-bottom"></div>
                            </div>









                            <?php foreach (td_global::$stacks_list as $stack_id => $stack_params) { ?>
                                <hr>



                                <div class="<?php echo $stack_id ?> td-wp-admin-stack">

                                    <!-- Import content -->
                                    <div class="td-box-row">
                                        <img style="width:220px" src="<?php echo td_global::$stacks_list[$stack_id]['img'] ?>"/>
                                        <div class="td-box-description">
                                            <span class="td-box-title">Import demo content</span>
                                            <p>Show or hide the footer</p>
                                        </div>
                                        <div class="td-box-control-full">
                                            <?php
                                            echo td_panel_generator::checkbox(array(
                                                'ds' => 'td_import_theme_styles',
                                                'option_id' => 'td_import_menus',
                                                'true_value' => '',
                                                'false_value' => 'no'
                                            ));
                                            ?>
                                        </div>
                                    </div>

                                    <div class="td-box-row">
                                        <div class="td-box-control-full">
                                            <a class="td-big-button" data-stack-id="<?php echo $stack_id ?>" style="width: 150px; text-align: center"><?php echo $stack_params['text'] ?></a>
                                        </div>
                                        <div class="td-box-row-margin-bottom"></div>
                                    </div>


                                    <div class="td-progress-bar-wrap"><div class="td-progress-bar td-progress-bar-<?php echo $stack_id ?>"></div></div>
                                </div>
                            <?php } ?>




                            <div class="td-box-row">
                                <div class="td-box-description td-box-full">
                                    <span class="td-box-title"></span>
                                    <p>This option will delete all settings except license key</p>
                                </div>
                                <div class="td-box-control-full">
                                    <a onclick="return confirm('Are you sure? This will reset all the theme settings to default!')" href="?page=td_theme_panel&td_page=td_view_import_theme_styles&td_option=factory_restore" class="td-big-button">Factory restore !!! ( RESET ALL SETTINGS )</a>
                                </div>
                                <div class="td-box-row-margin-bottom"></div>
                            </div>



                            <?php echo td_panel_generator::box_end();?>
                        </div>


                    </div>
                </div>
            </div>

            <div class="td-clear"></div>

        </div>

        <div class="td-clear"></div>

    </div>
<?php if($td_import_fonts_show_update_msg == 1){?><script type="text/javascript">alert('Import is done!');</script><?php }?>