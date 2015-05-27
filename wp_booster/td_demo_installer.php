<?php
/**
 * Created by ra on 5/15/2015.
 */
class td_demo_installer {

    function __construct() {
        //AJAX VIEW PANEL LOADING
        add_action( 'wp_ajax_nopriv_td_ajax_demo_install', array($this, 'ajax_stacks_controller'));
        add_action( 'wp_ajax_td_ajax_demo_install', array($this, 'ajax_stacks_controller'));
    }


    function ajax_stacks_controller() {
        $td_demo_action = td_util::get_http_post_val('td_demo_action');
        $td_demo_id = td_util::get_http_post_val('td_demo_id');



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
            foreach (td_global::$td_options as $option_id => $option_value) {
                td_global::$td_options[$option_id] = '';
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
        td_global::$td_options = $file_settings;
        //compile user css if any
        td_global::$td_options['tds_user_compile_css'] = td_css_generator();
        //write the changes to the database
        update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);
    }

}

new td_demo_installer();