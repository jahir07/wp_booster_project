<?php

/**
 * this is the import theme style / stacks view
 */

$td_import_fonts_show_update_msg = false;

/**
 * holds the imported settings
 */
class td_style_imported_settings {


    /**
     * here we define what fields to import from the import file
     * @var array
     */
    static $td_array_import_settings_from_file = array(
	    // header
        'tds_header_style',
	    'tds_top_menu',
		'tds_data_top_menu',
	    'tds_data_time_format',
	    'tds_login_sign_in_widget',
		'tds_snap_menu',
	    'tds_logo_on_sticky',
	    'td_social_networks_show',

	    // footer
	    'tds_footer',


	    // post settings
	    'td_default_site_post_template',
	    'tds_more_articles_on_post_pages_enable',
	    'tds_more_articles_on_post_pages_display',
	    'tds_more_articles_on_post_pages_display_module',
		'tds_more_articles_on_post_pages_number',


		// template settings
		'tds_home_page_layout',
		'tds_category_page_layout',


	    // theme color
	    'tds_theme_color',
	    'tds_site_background_color',
	    'tds_grid_line_color',
	    'tds_top_menu_color',
	    'tds_top_menu_text_color',
	    'tds_top_menu_text_hover_color',
	    'tds_top_sub_menu_text_color',
	    'tds_top_sub_menu_text_hover_color',
	    'tds_top_social_icons_color',
	    'tds_top_social_icons_hover_color',
	    'tds_menu_color',
	    'tds_menu_text_color',
	    'tds_menu_border_color',
	    'tds_header_wrap_color',
		'tds_footer_color',
	    'tds_footer_bottom_color',
	    'tds_footer_bottom_text_color',


	    // block settings
		'tds_category_module_1',
		'tds_category_module_2',
		'tds_category_module_3',
		'tds_category_module_4',
		'tds_category_module_5',
		'tds_category_module_6',
		'tds_category_module_7',
		'tds_category_module_8',
		'tds_category_module_9',
		'tds_category_module_10',
		'tds_category_module_11',
		'tds_category_module_12',
		'tds_category_module_13',
		'tds_category_module_14',
		'tds_category_module_15',
		'tds_category_module_mx1',
		'tds_category_module_mx2',
		'tds_category_module_mx3',
		'tds_category_module_mx4',
	    'tds_category_module_related',
	    'tds_category_module_mega_menu',
	    'tds_category_module_big_grid',
	    'tds_category_module_slide',


	    // custom css
        'td_body_classes'
    );

}


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

//import demo style fonts only
function td_import_demo_style_fonts($file_style) {
    $explode_nr_file = explode('_', $file_style);

    //read the settings file
    $file_settings = unserialize(base64_decode(file_get_contents(get_template_directory() . '/includes/stacks/' . $file_style . '.txt', true)));


    foreach (td_style_imported_settings::$td_array_import_settings_from_file as $import_setting_from_file) {
        /**
         *  we import a setting from the file only if it's not empty
         *  OR
         *  if the setting is td_body_classes we import it even as empty if needed
         */
        if (isset($file_settings[$import_setting_from_file])) {
            td_global::$td_options[$import_setting_from_file] = $file_settings[$import_setting_from_file];
        } else {
            if($import_setting_from_file == 'td_body_classes') {
                td_global::$td_options['td_body_classes'] = '';
            }
        }
    }


    //import typography settings
    if(!empty($file_settings['td_fonts'])) {
        td_global::$td_options['td_fonts'] = $file_settings['td_fonts'];
    }

    //import css font files (google) buffer
    if(!empty($file_settings['td_fonts_css_files'])) {
        td_global::$td_options['td_fonts_css_files'] = $file_settings['td_fonts_css_files'];
    }


    //compile user css if any
    td_global::$td_options['tds_user_compile_css'] = td_css_generator();


    //print_r($file_settings);


    //write the changes to the database
    update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);

    return true;
}

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
                <span>NEWSMAG - Theme panel</span>
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


                            <?php foreach (td_global::$stacks_list as $stack_file => $stack_name) { ?>

                                <div class="td-box-row">
                                    <div class="td-box-control-full">
                                        <a onclick="return confirm('Are you sure? This will import our predefined settings for the stack (background, template layouts, fonts, colors etc...). Please backup your settings to be sure that you don`t lose them by accident.')" href="?page=td_theme_panel&td_page=td_view_import_theme_styles&td_option=<?php echo $stack_file ?>" class="td-big-button" style="width: 150px; text-align: center"><?php echo $stack_name ?></a>
                                    </div>
                                    <div class="td-box-row-margin-bottom"></div>
                                </div>


                            <?php } ?>


                            <div class="td-box-row">
                                <div class="td-box-control-full">
                                    <a onclick="return confirm('Are you sure? This will import our predefined settings for the stack (background, template layouts, fonts, colors etc...). Please backup your settings to be sure that you don`t lose them by accident.')" href="?page=td_theme_panel&td_page=td_view_import_theme_styles&td_option=default_style" class="td-big-button" style="width: 150px; text-align: center">Default style</a>
                                </div>
                                <div class="td-box-row-margin-bottom"></div>
                            </div>



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