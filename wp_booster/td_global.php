<?php



/**
 * td_global_blocks.php
 * Here we store the global state of the theme. All globals are here (in theory)
 *  - no td_util loaded, no access to settings
 */

class td_global {


    static $td_options; //here we store all the options of the theme will be used in td_first_install.php

    static $current_template = ''; //used by page-homepage-loop, 404

    static $current_author_obj; //set by the author page template, used by widgets

    static $cur_url_page_id; //the id of the main page (if we have loop in loop, it will return the id of the page that has the uri)

    static $load_sidebar_from_template; //used by some templates for custom sidebars (setted by page-homepage-loop.php etc)

    static $load_featured_img_from_template; //used by single.php to instruct td_module_single to load the full with thumb when necessary (ex. no sidebars)

    static $cur_single_template_sidebar_pos = ''; // set in single.php - used by the gallery short code to show appropriate images

    static $cur_single_template = ''; /** @var string set here: @see  */


    static $is_woocommerce_installed = false; // at the end of this file we check if woo commerce is installed

    static $current_category_obj; /**  used on category pages, it's set on pre_get_posts hook @see td_modify_main_query_for_category_page */

    //this is used to check for if we are in loop
    //also used for quotes in blocks - check isf the module is displayed on blocks or not
    static $is_wordpress_loop = '';

    static $custom_no_posts_message = '';  /** used to set a custom post message for the template. If this is set to false, the default message will not show @see td_page_generator::no_posts */


    /**
     * @var string used to store texts for: includes/wp_booster/wp-admin/content-metaboxes/td_set_video_meta.php
     * is set in td_config @see td_wp_booster_config::td_global_after
     */
    static $td_wp_admin_text_list = array();


    static $http_or_https = 'http'; //is set below with either http or https string  @see EOF


	//@todo refactor all code to use TEMPLATEPATH instead
    static $get_template_directory = '';  // here we store the value from get_template_directory(); - it looks like the wp function does a lot of stuff each time is called

	//@todo refactor all code to use STYLESHEETPATH instead
    static $get_template_directory_uri = ''; // here we store the value from get_template_directory_uri(); - it looks like the wp function does a lot of stuff each time is called


	static $td_viewport_intervals = array(); // the td_viewport intervals are stored



    /**
     * the js files that the theme uses on the front end (file_id - filename) @see td_wp_booster_config
     * @see td_wp_booster_hooks
     * @var array
     */
    static $js_files = array ();

    static $theme_plugins_list = array();


	static $td_animation_stack_effects = array();


    /**
     * the js files that are used in wp-admin
     * @var array
     */
    static $js_files_for_wp_admin = array(
        'td_wp_admin' => '/includes/wp_booster/wp-admin/js/td_wp_admin.js',
        'td_wp_admin_color_picker' => '/includes/wp_booster/wp-admin/js/td_wp_admin_color_picker.js',
        'td_wp_admin_panel' => '/includes/wp_booster/wp-admin/js/td_wp_admin_panel.js',
        'td_edit_page' => '/includes/wp_booster/wp-admin/js/td_edit_page.js',
        'td_wp_admin_demos' => '/includes/wp_booster/wp-admin/js/td_wp_admin_demos.js',
        'td_page_options' => '/includes/wp_booster/wp-admin/js/td_page_options.js',
        'td_tooltip' => '/includes/wp_booster/wp-admin/js/tooltip.js'

    );


    /**
     * @var array the tinyMCE style formats
     */
    static $tiny_mce_style_formats = array();


    /**
     * @var array
     *
     *  'td_full_width' => array(           - id used in the drop down in tinyMCE
     *      'text' => 'Full width',         - the text that will appear in the dropdown in tinyMCE
     *      'class' => 'td-post-image-full' - the class tha this image style will add to the image
     *  )
     *
     */
    static $tiny_mce_image_style_list = array();


    /**
     * here we store the fields form td-panel -> custom css
     * @var array
     */
    static $theme_panel_custom_css_fields_list = array();


    /**
     * the big grid styles used by the theme. This styles will show up in the panel @see td_panel_categories.php and on each big grid block
     */
    static $big_grid_styles_list = array();


      /**
     * the list of panels - NOTE that the system will not load from other paths outside of theme as of now (ex. cannot be used in plugins YET)
     * 1. try to locate the template in 'includes/panel/views/' (also checks in the child theme)
     * 2. include the default panel from wp_booster if none is found
     * @var array
     */
    static $theme_panels_list = array (
        'td-panel-header' => array(
            'text' => 'HEADER',
            'ico_class' => 'td-ico-header',
            'file_id' => 'td_panel_header'
        ),
        'td-panel-footer' => array(
            'text' => 'FOOTER',
            'ico_class' => 'td-ico-footer',
            'file_id' => 'td_panel_footer'
        ),
        'td-panel-ads' => array(
            'text' => 'ADS',
            'ico_class' => 'td-ico-ads',
            'file_id' => 'td_panel_ads'
        ),


        'td-panel-separator-1' => 'LAYOUT SETTINGS',   //separator
        'td-panel-template-settings' => array(
            'text' => 'TEMPLATE SETTINGS',
            'ico_class' => 'td-ico-template',
            'file_id' => 'td_panel_template_settings'
        ),

        'td-panel-categories' => array(
            'text' => 'CATEGORIES',
            'ico_class' => 'td-ico-categories',
            'file_id' => 'td_panel_categories'
        ),
        'td-panel-post-settings' => array(
            'text' => 'POST SETTINGS',
            'ico_class' => 'td-ico-post',
            'file_id' => 'td_panel_post_settings'
        ),


        'td-panel-separator-2' => 'MISC',   //separator
        'td-panel-block-style' => array(
            'text' => 'BLOCK SETTINGS',
            'ico_class' => 'td-ico-block',
            'file_id' => 'td_panel_block_settings',
        ),
        'td-panel-background' => array(
            'text' => 'BACKGROUND',
            'ico_class' => 'td-ico-background',
            'file_id' => 'td_panel_background'
        ),
        'td-panel-excerpts' => array(
            'text' => 'EXCERPTS',
            'ico_class' => 'td-ico-excerpts',
            'file_id' => 'td_panel_excerpts'
        ),
        'td-panel-translates' => array(
            'text' => 'TRANSLATIONS',
            'ico_class' => 'td-ico-translation',
            'file_id' => 'td_panel_translations'
        ),
        'td-panel-theme-colors' => array(
            'text' => 'THEME COLORS',
            'ico_class' => 'td-ico-color',
            'file_id' => 'td_panel_theme_colors'
        ),

        'td-panel-theme-fonts' => array(
            'text' => 'THEME FONTS',
            'ico_class' => 'td-ico-typography',
            'file_id' => 'td_panel_theme_fonts'
        ),
        'td-panel-custom-css' => array(
            'text' => 'CUSTOM CSS',
            'ico_class' => 'td-ico-css',
            'file_id' => 'td_panel_custom_css'
        ),
        'td-panel-custom-javascript' => array(
            'text' => 'CUSTOM JAVASCRIPT',
            'ico_class' => 'td-ico-js',
            'file_id' => 'td_panel_custom_javascript'
        ),
        'td-panel-analytics' => array(
            'text' => 'ANALYTICS',
            'ico_class' => 'td-ico-analytics',
            'file_id' => 'td_panel_analytics'
        ),
        'td-panel-social-networks' => array(
            'text' => 'SOCIAL NETWORKS',
            'ico_class' => 'td-ico-social',
            'file_id' => 'td_panel_social_networks'
        ),
        'td-panel-cpt-taxonomy' => array(
            'text' => 'CPT &amp; TAXONOMY',
            'ico_class' => 'td-ico-social',
            'file_id' => 'td_panel_cpt_taxonomy'
        )

    );



    static $translate_languages_list = array(
        'en' => 'English (default)',
        'af' => 'Afrikaans',
        'sq' => 'Albanian',
        'ar' => 'Arabic',
        'hy' => 'Armenian',
        'az' => 'Azerbaijani',
        'eu' => 'Basque',
        'be' => 'Belarusian',
        'bn' => 'Bengali',
        'bs' => 'Bosnian',
        'bg' => 'Bulgarian',
        'ca' => 'Catalan',
        'ceb' => 'Cebuano',
        'ny' => 'Chichewa',
        'zh' => 'Chinese (Simplified)',
        'zh-TW' => 'Chinese (Traditional)',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'nl' => 'Dutch',
        'eo' => 'Esperanto',
        'et' => 'Estonian',
        'tl' => 'Filipino',
        'fi' => 'Finnish',
        'fr' => 'French',
        'gl' => 'Galician',
        'ka' => 'Georgian',
        'de' => 'German',
        'el' => 'Greek',
        'gu' => 'Gujarati',
        'ht' => 'Haitian Creole',
        'ha' => 'Hausa',
        'iw' => 'Hebrew',
        'hi' => 'Hindi',
        'hmn' => 'Hmong',
        'hu' => 'Hungarian',
        'is' => 'Icelandic',
        'ig' => 'Igbo',
        'id' => 'Indonesian',
        'ga' => 'Irish',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'jw' => 'Javanese',
        'kn' => 'Kannada',
        'kk' => 'Kazakh',
        'km' => 'Khmer',
        'ko' => 'Korean',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'lt' => 'Lithuanian',
        'mk' => 'Macedonian',
        'mg' => 'Malagasy',
        'ms' => 'Malay',
        'ml' => 'Malayalam',
        'mt' => 'Maltese',
        'mi' => 'Maori',
        'mr' => 'Marathi',
        'mn' => 'Mongolian',
        'my' => 'Myanmar (Burmese)',
        'ne' => 'Nepali',
        'no' => 'Norwegian',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'pa' => 'Punjabi',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sr' => 'Serbian',
        'st' => 'Sesotho',
        'si' => 'Sinhala',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'so' => 'Somali',
        'es' => 'Spanish',
        'su' => 'Sundanese',
        'sw' => 'Swahili',
        'sv' => 'Swedish',
        'tg' => 'Tajik',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        'vi' => 'Vietnamese',
        'cy' => 'Welsh',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'zu' => 'Zulu'
    );



    /**
     * stack_filename => stack_name
     * @var array
     */
    public static $demo_list = array ();


    /**
     * the list of fonts used by the theme by default
     * @var array
     */
    public static $default_google_fonts_list = array();


    /**
     * @var string here we keep the typography settings from the THEME FONTS panel.
     * this is also used by the css compiler
     */
    public static $typography_settings_list = array ();




    // @todo clean this up
    private static $post = '';
    private static $primary_category = '';


    static function load_single_post($post) {

            self::$post = $post;


        /*  ----------------------------------------------------------------------------
            update the primary category Only on single posts :0
         */
        if (is_single()) {
            //read the post setting
            $td_post_theme_settings = get_post_meta(self::$post->ID, 'td_post_theme_settings', true);
            if (!empty($td_post_theme_settings['td_primary_cat'])) {
                self::$primary_category = $td_post_theme_settings['td_primary_cat'];
                return;
            }

            $categories = get_the_category(self::$post->ID);
            foreach($categories as $category) {
                if ($category->name != TD_FEATURED_CAT) { //ignore the featured category name
                    self::$primary_category = $category->cat_ID;
                    break;
                }
            }
        }
    }


    //used on single posts
    static function get_primary_category_id() {
        if (empty(self::$post->ID)) {
            return get_queried_object_id();
        }
        return self::$primary_category;
    }


    //generate unique_ids
    private static $td_unique_counter = 0;

    static function td_generate_unique_id() {
        self::$td_unique_counter++;
        return 'td_uid_' . self::$td_unique_counter . '_' . uniqid();
    }



}


if (is_ssl()) {
    td_global::$http_or_https = 'https';
}

if (is_admin()) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('woocommerce/woocommerce.php')) {
        td_global::$is_woocommerce_installed = true;
    }
}


td_global::$get_template_directory = get_template_directory();

td_global::$get_template_directory_uri = get_template_directory_uri();