<?php
/**
 * Created by ra on 5/15/2015.
 */
class td_demo_installer {

    /**
     * here we define what fields to import from the import file
     * @var array
     */
    public $td_array_import_settings_from_file = array( //it's public only for testing
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
        add_action( 'wp_ajax_nopriv_td_ajax_demo_install', array($this, 'ajax_stacks_controller'));
        add_action( 'wp_ajax_td_ajax_demo_install', array($this, 'ajax_stacks_controller'));
    }


    function ajax_stacks_controller() {
        $td_demo_action = td_util::get_http_post_val('td_demo_action');
        $td_demo_id = td_util::get_http_post_val('td_demo_id');
        $td_demo_view = td_util::get_http_post_val('td_demo_view');



        /*  ----------------------------------------------------------------------------
            Uninstall button - do uninstall with content
         */
        if ($td_demo_action == 'uninstall_demo') {
            // remove our content
            td_demo_media::remove();
            td_demo_content::remove();
            td_demo_category::remove();
            td_demo_menus::remove();
            td_demo_widgets::remove();

            // restore all settings to the state before a demo was loaded
            $td_demo_history = new td_demo_history();
            $td_demo_history->restore_all();

            // update our state
            td_demo_state::update_state($td_demo_id, '');
        }




        /*  ----------------------------------------------------------------------------
            remove content before stack install
        */

        // step 1
        else if ($td_demo_action == 'remove_content_before_install') {

            // save the history - this class will save the history only when going from user settings -> stack
            $td_demo_history = new td_demo_history();
            $td_demo_history->save_all();



            // clean the user settings
            td_demo_media::remove();
            td_demo_content::remove();
            td_demo_category::remove();
            td_demo_menus::remove();
            td_demo_widgets::remove();


            // remove panel settings and recompile the css as empty
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
        }



        /*  ----------------------------------------------------------------------------
            Install content only
        */
        else if ($td_demo_action == 'install_no_content_demo') {
            td_demo_state::update_state($td_demo_id, 'no_content');
            // load panel settings - this will also recompile the css
            $this->import_panel_settings(td_global::$demo_list[$td_demo_id]['folder'] . 'td_panel_settings.txt');
        }


        /*  ----------------------------------------------------------------------------
            install Full
        */
        else if ($td_demo_action == 'td_media_1') {
            // change our state
            td_demo_state::update_state($td_demo_id, 'full');
            // load panel settings
            $this->import_panel_settings(td_global::$demo_list[$td_demo_id]['folder'] . 'td_panel_settings.txt');
            // load the media import script
            require_once(td_global::$demo_list[$td_demo_id]['folder'] . 'td_media_1.php');
        }


        else if ($td_demo_action == 'td_import')  {
            require_once(td_global::$demo_list[$td_demo_id]['folder'] . 'td_import.php');

        }


    }


    public function import_panel_settings($file_path) { //it's public only for testing


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

new td_demo_installer();