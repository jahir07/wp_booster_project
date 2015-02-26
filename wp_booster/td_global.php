<?php



/**
 * td_global_blocks.php
 * Here we store the global state of the theme. All globals are here (in theory)
 *  - no td_util loaded, no access to settings
 */

class td_global {

    /**
     * @deprecated - used by the old old social counter
     * @var array
     */
    static $blocks_map_list = array();

    static $td_options; //here we store all the options of the theme will be used in td_first_install.php

    static $current_template = ''; //used by page-homepage-loop, 404

    static $current_author_obj; //set by the author page template, used by widgets

    static $cur_url_page_id; //the id of the main page (if we have loop in loop, it will return the id of the page that has the uri)

    static $load_sidebar_from_template; //used by some templates for custom sidebars (setted by page-homepage-loop.php etc)

    static $load_featured_img_from_template; //used by single.php to instruct td_module_single to load the full with thumb when necessary (ex. no sidebars)

    static $cur_single_template_sidebar_pos = ''; // set in single.php - used by the gallery short code to show appropriate images

    static $cur_single_template = ''; /** @var string set here: @see  */


    static $current_category_obj; /**  used on category pages, it's set on pre_get_posts hook @see td_modify_main_query_for_category_page */

    //this is used to check for if we are in loop
    //also used for quotes in blocks - check isf the module is displayed on blocks or not
    static $is_wordpress_loop = '';

    static $custom_no_posts_message = '';  /** used to set a custom post message for the template. If this is set to false, the default message will not show @see td_page_generator::no_posts */



    static $http_or_https = 'http'; //is set below with either http or https string  @see EOF


    static $get_template_directory = '';

    static $get_template_directory_uri = '';



    /**
     * the js files that the theme uses on the front end (file_id - filename) @see td_wp_booster_config
     * @see td_wp_booster_hooks
     * @var array
     */
    static $js_files = array ();


    /**
     * the js files that are used in wp-admin
     * @var array
     */
    static $js_files_for_wp_admin = array(
        'td_wp_admin' => '/includes/wp_booster/wp-admin/js/td_wp_admin.js',
        'td_wp_admin_color_picker' => '/includes/wp_booster/wp-admin/js/td_wp_admin_color_picker.js',
        'td_wp_admin_panel' => '/includes/wp_booster/wp-admin/js/td_wp_admin_panel.js',
        'td_edit_page' => '/includes/wp_booster/wp-admin/js/td_edit_page.js'
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
     * the list of panels - NOTE that the system will not load from other paths outside of theme as of now (ex. cannot be used in plugins YET)
     * 1. try to locate the template in 'includes/panel/views/' (also checks in the child theme)
     * 2. include the default panel from wp_booster if none is found
     * @var array
     */
    static $theme_panels_list = array (
        'td-panel-welcome' => array(
            'text' => 'WELCOME',
            'ico_class' => 'td-ico-welcome',
            'file_id' => 'td_panel_welcome'
        ),
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
    public static $stacks_list = array ();






    // @todo clean up this
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


    /**
     * the filter array (used by blocks and by the loop filters)
     * @return array
     */
    static function get_map_filter_array () {
        return array(
            array(
                "param_name" => "category_id",
                "type" => "dropdown",
                "value" => td_util::get_category2id_array(),
                "heading" => 'Category filter:',
                "description" => "A single category filter. If you want to filter multiple categories, use the 'Multiple categories filter' and leave this to default",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "category_ids",
                "type" => "textfield",
                "value" => '',
                "heading" => 'Multiple categories filter:',
                "description" => "Filter multiple categories by ID. Enter here the category IDs separated by commas (ex: 13,23,18). To exclude categories from this block add them with '-' (ex: -9, -10)",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "tag_slug",
                "type" => "textfield",
                "value" => '',
                "heading" => 'Filter by tag slug:',
                "description" => "To filter multiple tag slugs, enter here the tag slugs separated by commas (ex: tag1,tag2,tag3)",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "autors_id",
                "type" => "dropdown",
                "value" => td_util::create_array_authors(),
                "heading" => "Authors Filter:",
                "description" => "Filter by author. Shows only posts made by this author",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "sort",
                "type" => "dropdown",
                "value" => array('- Latest -' => '', 'Random posts Today' => 'random_today' , 'Random posts from last 7 Day' => 'random_7_day' , 'Alphabetical A -> Z' => 'alphabetical_order', 'Popular (all time)' => 'popular', 'Popular (last 7 days; enable first from ' . TD_THEME_NAME . ' Panel -> Block settings -> 7 days post sorting)' => 'popular7' , 'Featured' => 'featured', 'Highest rated (reviews)' => 'review_high', 'Random Posts' => 'random_posts', 'Most Commented' => 'comment_count'),
                "heading" => 'Sort order:',
                "description" => "How to sort the posts.",
                "holder" => "div",
                "class" => ""
            )
            ,
            array(
                "param_name" => "installed_post_types",
                "type" => "textfield",
                "value" =>  '',//td_util::create_array_installed_post_types(),
                "heading" => 'Post Type:',
                "description" => "Filter by post types. Usage: post OR post,events,pages ; write 1 or more post types delimited by commas",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "limit",
                "type" => "textfield",
                "value" => '5',
                "heading" => 'Limit post number:',
                "description" => "If the field is empty the limit post number will be the number from Wordpress settings -> Reading",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "offset",
                "type" => "textfield",
                "value" => '',
                "heading" => 'Offset posts:',
                "description" => "Start the count with an offset. If you have a block that shows 5 posts before this one, you can make this one start from the 6'th post (by using offset 5)",
                "holder" => "div",
                "class" => ""
            )
        );//end generic array
    }//end get_map function


    /**
     * This array is used only by blocks that have loops + title (it is merged with the array from get_map_filter_array)
     * @return array
     */
    static function get_map_block_array() {
        return array(
            // title settings
            array(
                "param_name" => "custom_title",
                "type" => "textfield",
                "value" => "Block title",
                "heading" => 'Custom title for this block:',
                "description" => "Optional - a title for this block, if you leave it blank the block will not have a title",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "custom_url",
                "type" => "textfield",
                "value" => "",
                "heading" => 'Title url:',
                "description" => "Optional - a custom url when the block title is clicked",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => 'Title text color',
                "param_name" => "header_text_color",
                "value" => '', //Default Red color
                "description" => 'Optional - Choose a custom title text color for this block'
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => 'Title background color',
                "param_name" => "header_color",
                "value" => '', //Default Red color
                "description" => 'Optional - Choose a custom title background color for this block'
            ),

            // appearance settings
            array(
                "param_name" => "border_top",
                "type" => "dropdown",
                "value" => array('- With border -' => '', 'no border' => 'no_border_top'),
                "heading" => 'Border top:',
                "description" => "By default all the blocks have a border at the top. You may wish to remove that in some cases (like when the block it's the first in a sidebar)",
                "holder" => "div",
                "class" => ""
            ),

            array(
                "param_name" => "color_preset",
                "type" => "dropdown",
                "value" => array('- Default -' => '', 'Style 1 - Red' => 'td-block-color-style-1', 'Style 2 - Black' => 'td-block-color-style-2', 'Style 3 - Orange' => 'td-block-color-style-3', 'Style 4 - Yellow' => 'td-block-color-style-4', 'Style 5 - Green' => 'td-block-color-style-5', 'Style 6 - Pink' => 'td-block-color-style-6'),
                "heading" => 'Color preset (background style):',
                "description" => "Choose a color preset for this block. You can customize the presets in Theme panel -> Block settings.",
                "holder" => "div",
                "class" => ""
            ),


            //custom filter types
            array(
                "param_name" => "td_ajax_filter_type", //this is used to build the filter list (for example a list of categories from the id-s bellow)
                "type" => "dropdown",
                "value" => array('- No drop down ajax filter -' => '', 'Filter by categories' => 'td_category_ids_filter', 'Filter by authors' => 'td_author_ids_filter', 'Filter by tag slug' => 'td_tag_slug_filter', 'Filter by popularity (Featured | All time popular)' => 'td_popularity_filter_fa'),
                "heading" => 'Ajax dropdown - filter type:',
                "description" => "Show the ajax drop down filter. The ajax filters (except by popularity) require an additional parameter. If no ids are provided in the input below, the filter will show all the available items (ex: all authors, all categories etc..)",
                "holder" => "div",
                "class" => ""
            ),

            //filter by ids
            array(
                "param_name" => "td_ajax_filter_ids", //the ids that we will show in the list
                "type" => "textfield",
                "value" => '',
                "heading" => 'Ajax dropdown - show the following IDs:',
                "description" => "The ajax drop down shows only the (author ids, categories ids OR tag slugs) that you enter here separated by comas",
                "holder" => "div",
                "class" => ""
            ),

            //default pull down text
            array(
                "param_name" => "td_filter_default_txt",
                "type" => "textfield",
                "value" => 'All',
                "heading" => 'Ajax dropdown - Filter default text',
                "description" => "The default text for the first item from the drop down. The first item shows the default block settings",
                "holder" => "div",
                "class" => ""
            ),



            array(
                "param_name" => "ajax_pagination",
                "type" => "dropdown",
                "value" => array('- No pagination -' => '', 'Next Prev ajax' => 'next_prev', 'Load More button' => 'load_more', 'Infinite load' => 'infinite'),
                "heading" => 'Pagination:',
                "description" => "Our blocks support pagination.",
                "holder" => "div",
                "class" => ""
            ),


            array(
                "param_name" => "ajax_pagination_infinite_stop",
                "type" => "textfield",
                "value" => '',
                "heading" => "Infinite load show 'Load more' after x pages:",
                "description" => "ONLY FOR INFINITE LOAD pagination: Shows 'load more' button after x number of pages. Leave this blank to load posts forever when infinite load is set for ajax pagination",
                "holder" => "div",
                "class" => ""
            )




        );//end generic array
    }


    /**
     * This array is used only by blocks that have loops + title (it is merged with the array from get_map_filter_array)
     * @return array
     */
    static function get_map_slide_array() {
        return array(
            array(
                "param_name" => "autoplay",
                "type" => "textfield",
                "value" => '',
                "heading" => 'Autoplay slider (at x seconds)',
                "description" => "Leave empty do disable autoplay",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => 'Header color',
                "param_name" => "header_color",
                "value" => '', //Default Red color
                "description" => 'Choose a custom header color for this block'
            ),
            array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "heading" => 'Header text color',
                "param_name" => "header_text_color",
                "value" => '', //Default Red color
                "description" => 'Choose a custom header color for this block'
            ),
            array(
                "param_name" => "custom_title",
                "type" => "textfield",
                "value" => "",
                "heading" => 'Optional - custom title for this block:',
                "description" => "",
                "holder" => "div",
                "class" => ""
            ),
            array(
                "param_name" => "custom_url",
                "type" => "textfield",
                "value" => "",
                "heading" => 'Optional - custom url for this block (when the module title is clicked):',
                "description" => "",
                "holder" => "div",
                "class" => ""
            )
        );
    }




}


if (is_ssl()) {
    td_global::$http_or_https = 'https';
}


td_global::$get_template_directory = get_template_directory();

td_global::$get_template_directory_uri = get_template_directory_uri();