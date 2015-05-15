<?php
class td_panel_stacks {

    /**
     * here we define what fields to import from the import file
     * @var array
     */
    private $td_array_import_settings_from_file = array(
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


    function __construct() {
        //AJAX VIEW PANEL LOADING
        add_action( 'wp_ajax_nopriv_td_ajax_view_panel_loading', array($this, 'ajax_stacks_controller'));
        add_action( 'wp_ajax_td_ajax_view_panel_loading', array($this, 'ajax_stacks_controller'));
    }


    function ajax_stacks_controller() {
        $td_stack = td_util::get_http_post_val('td_stack');
        $td_view = td_util::get_http_post_val('td_view');


        if ($td_stack == 'uninstall_all') {
            foreach ($this->td_array_import_settings_from_file as $import_setting_from_file) {
                td_global::$td_options[$import_setting_from_file] = '';
            }

            //typography settings
            td_global::$td_options['td_fonts'] = '';

            //css font files (google) buffer
            td_global::$td_options['td_fonts_css_files'] = '';

            //compile user css if any
            td_global::$td_options['tds_user_compile_css'] = td_css_generator();

            update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);

            return;
        }

        switch ($td_view) {
            case 'td_media';
                // load panel settings
                $this->import_panel_settings(td_global::$stacks_list[$td_stack]['folder'] . 'td_panel_settings.txt');
                // load the media import script
                require_once(td_global::$stacks_list[$td_stack]['folder'] . 'td_media.php');
                break;

            case 'td_import';
                require_once(td_global::$stacks_list[$td_stack]['folder'] . 'td_import.php');
                break;
        }
    }


    private function import_panel_settings($file_path) {


        //read the settings file
        $file_settings = unserialize(base64_decode(file_get_contents($file_path, true)));

        foreach ($this->td_array_import_settings_from_file as $import_setting_from_file) {
            /**
             *  we import a setting from the file only if it's not empty
             *  OR
             *  if the setting is td_body_classes we import it even as empty if needed ///but why???
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

        //write the changes to the database
        update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);
    }

}

new td_panel_stacks();

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


                                    <div class="td-progress-bar-wrap"><div class="td-progress-bar"></div></div>
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