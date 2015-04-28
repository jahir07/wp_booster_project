<?php


/*  ----------------------------------------------------------------------------
    background support
 */


$background_params = array (
    'is_boxed_layout' => false,
    'is_stretched_bg' => true,
    'theme_bg_image' => 'http://0div.com:69/wp_011/wp-content/uploads/2015/03/4ec0a389-d9a8-4f87-b84a-6daccdbab12d.jpg',
    'theme_bg_repeat' => '',
    'theme_bg_position' => '',
    'theme_bg_attachment' => '',
    'theme_bg_color' => '',
);
//$td_background_render = new td_background_render($background_params);



class td_background_render {

    // here we store the background parameters
    private $background_parameters = array();


    /**
     * all the data transfer happens from this constructor. The class will make sure to apply the settings on WP
     * @param $background_parameters
     *        array (
     *            'is_boxed_layout' => false,
     *            'is_stretched_bg' => true,
     *            'theme_bg_image' => '',
     *            'theme_bg_repeat' => '',
     *            'theme_bg_position' => '',
     *            'theme_bg_attachment' => '',
     *            'theme_bg_color' => '',
     *        );
     *
     */
    function __construct($background_parameters) {
        $this->background_parameters = $background_parameters;

        // all the needed js and css is generated via our own filters that register on wp_head
        // @legacy we have to hook our filters to wp_head action - for them to work in theme customizer.
        // The theme does not use the theme customizer now but we may switch back
        add_action('wp_head', array($this, 'wp_head_hook'), 10);

        // here we manipulate the body_class-es, we remove the WordPress ones and add our own + boxed version class
        add_filter('body_class', array($this,'add_slug_to_body_class'));
    }



    function wp_head_hook() {
        if ($this->background_parameters['theme_bg_image'] != '' or  $this->background_parameters['theme_bg_color'] != '') {
            add_filter( 'td_css_buffer_render', array($this, 'add_css_custom_background'));
        }

        if ($this->background_parameters['is_stretched_bg'] == true) {
            add_filter( 'td_js_buffer_footer_render', array($this, 'add_js_hook'));
        }
    }


    /**
     * we emulate the WordPress background function using our own setting
     * @param $css string - the existing css rendered by wp booster
     * @return string - the new css
     */
    function add_css_custom_background($css) {
        $css .= "\n" . "body {";

        //color handling
        if (!empty($this->background_parameters['theme_bg_color'])) {
            $css.= "\n" . 'background-color:' . $this->background_parameters['theme_bg_color'] . ';';
        }

        //image handling; if there is no image stretching
        if(!empty($this->background_parameters['theme_bg_image']) and $this->background_parameters['is_stretched_bg'] == false) {

            //add the image
            $css.= "\n" . "background-image:url('" . $this->background_parameters['theme_bg_image'] . "');";

            //repeat image option
            switch ($this->background_parameters['theme_bg_repeat']) {
                case '':
                    $css.= "\n" . 'background-repeat:no-repeat;';
                    break;

                case 'repeat':
                    //$css.= "\n" . 'background-repeat:repeat;';//default value `background-repeat`
                    break;

                case 'repeat-x':
                    $css.= "\n" . 'background-repeat:repeat-x;';
                    break;

                case 'repeat-y':
                    $css.= "\n" . 'background-repeat:repeat-y;';
                    break;
            }//end switch


            //position image option
            switch ($this->background_parameters['theme_bg_position']) {
                case '':
                    //$css.= "\n" . 'background-position:left top;';//default value `background-position`
                    break;

                case 'center':
                    $css.= "\n" . 'background-position:center top;';
                    break;

                case 'right':
                    $css.= "\n" . 'background-position:right top;';
                    break;
            }//end switch


            //background attachment options
            switch ($this->background_parameters['theme_bg_attachment']) {
                case '':
                    //$css.= "\n" . 'background-attachment:scroll;';//default value `background-attachment`
                    break;

                case 'fixed':
                    $css.= "\n" . 'background-attachment:fixed;';
                    break;
            }//end switch
        }

        return $css . "
                    }";
    }



    //custom background js
    function add_js_hook($js) {
        if (!empty($this->background_parameters['theme_bg_image']) and $this->background_parameters['is_stretched_bg'] == true) {
            ob_start();
            // @todo chestia asta ar trebuii trecuta pe flag sau ceva in td_config ?
            ?>

            <script>

                jQuery(window).ready(function() {

                    // if the theme has td_backstr support, it means this already uses it
                    if (typeof window.td_backstr !== 'undefined') {

                        (function(){

                            // the site background td-backstretch jquery object is dynamically added in DOM, and after any translation effects are applied over td-backstretch
                            var wrapper_image_jquery_obj = jQuery('<div class=\'backstretch\'></div>');
                            var image_jquery_obj = jQuery('<img class=\'td-backstretch\' src=\'<?php echo $this->background_parameters['theme_bg_image']; ?>\'>');

                            wrapper_image_jquery_obj.append(image_jquery_obj);

                            jQuery('body').prepend(wrapper_image_jquery_obj);

                            var td_backstr_item = new td_backstr.item();

                            td_backstr_item.wrapper_image_jquery_obj = wrapper_image_jquery_obj;
                            td_backstr_item.image_jquery_obj = image_jquery_obj;

                            td_backstr.add_item(td_backstr_item);

                        })();

                    } else {

                        // - this is the old backstretch jquery plugin call
                        // - td_backstretch.js is in wp_booster, so it is still used by the themes that don't use new td_backstr.js
                        jQuery.backstretch('<?php echo $this->background_parameters['theme_bg_image']; ?>', {fade:1200, centeredY:false});
                    }
                });

            </script>
            <?php
            $buffer = ob_get_clean();
            $js .= "\n". td_util::remove_script_tag($buffer);
        } //end if

        return $js;
    }



    /**
     * Adds the boxed layout or full layout classes
     * @param $classes
     * @return array
     */
    function add_slug_to_body_class($classes) {
        if ($this->background_parameters['is_boxed_layout']) {
            $classes[] = 'td-boxed-layout';
        } else {
            $classes[] = 'td-full-layout';
        }
        return $classes;
    }

}




class td_background {


    /**
     * is true if it's a the box version of the theme (we have background color or a background image)
     * @var bool
     */
    public $is_boxed_layout = false;


    /**
     * if true, the theme will use the stretched background. Newsmag and Newspaper use different versions of the backstratch now.
     * @var bool
     */
    public $is_stretched_bg = false;

    //the background image of the theme; this will be overwritten by category settings if on category or post page
    public $theme_bg_image = '';

    //background image options
    public $theme_bg_repeat = '';
    public $theme_bg_position = '';
    public $theme_bg_attachment = '';


    //the background color of the theme; this will be overwritten by category settings if on category or post page
    var $theme_bg_color = '';


    function __construct() {



        add_action('wp_head', array($this, 'wp_head_hook'), 10);

        //clean up the wp custom-background class if needed
        add_filter('body_class', array($this,'add_slug_to_body_class'));
    }


    //we have to hook our filters to wp_head action - for them to work in theme customizer
    function wp_head_hook() {

        //if not empty, the theme will load the settings from this category id
        $category_id = 0;




        //background settings for category and post page (post that are in a category)
        if (is_category() or is_single()) {

            //read the category id
            if (is_category()) {
                $category_id = intval(get_query_var('cat'));
            }

            //read the post - category id
            if (is_single()) {
                $category_id = intval(td_global::get_primary_category_id());
            }


            //echo $use_settings_from_category_id;
            if ($category_id > 0) {

                //get the category bg
                $tdc_image = td_util::get_category_option($category_id, 'tdc_image');
                if (!empty($tdc_image)) {
                    $this->is_boxed_layout = true;
                    $this->theme_bg_image = $tdc_image;
                }

                //get the category bg color
                $tdc_bg_color = td_util::get_category_option($category_id, 'tdc_bg_color');
                if (!empty($tdc_bg_color)) {
                    $this->is_boxed_layout = true;
                    $this->theme_bg_color = $tdc_bg_color;
                }

                //get the bg style - from category specific
                $tdc_bg_repeat = td_util::get_category_option($category_id, 'tdc_bg_repeat');

                switch  ($tdc_bg_repeat) {
                    case '':
                        //do nothing - the background is already stretched if needed from the top of this function
                        break;


                    case 'stretch':
                        $this->is_stretched_bg = true;
                        break;


                    case 'tile':
                        $this->is_stretched_bg = false;
                        $this->theme_bg_repeat = 'repeat';
                        break;
                }

            }

        }

        //setting variable : is_boxed_layout
        if ($this->theme_bg_image != '' or  $this->theme_bg_color != '') {
            $this->is_boxed_layout = true;

            //make the site boxed
            add_filter( 'td_css_buffer_render', array($this, 'add_css_custom_background'));
        }


        if ($this->is_stretched_bg == true) {
            //add the js filter for our custom bg
            add_filter( 'td_js_buffer_footer_render', array($this, 'add_js_hook'));
        }


    }



    //called with a category custom bg (emulates the wp function)
    function add_css_custom_background($css) {
         $css .= "\n" . "body {";

         //color handling
         if (!empty($this->theme_bg_color)) {
             $css.= "\n" . 'background-color:' . $this->theme_bg_color . ';';
         }

         //image handling; if there is no image stretching
         if(!empty($this->theme_bg_image) and $this->is_stretched_bg == false) {

              //add the image
              $css.= "\n" . "background-image:url('" . $this->theme_bg_image . "');";


              //repeat image option
              switch ($this->theme_bg_repeat) {
                    case '':
                        $css.= "\n" . 'background-repeat:no-repeat;';
                        break;

                    case 'repeat':
                        //$css.= "\n" . 'background-repeat:repeat;';//default value `background-repeat`
                        break;

                    case 'repeat-x':
                         $css.= "\n" . 'background-repeat:repeat-x;';
                         break;

                    case 'repeat-y':
                         $css.= "\n" . 'background-repeat:repeat-y;';
                         break;
              }//end switch


              //position image option
              switch ($this->theme_bg_position) {
                   case '':
                       //$css.= "\n" . 'background-position:left top;';//default value `background-position`
                       break;

                   case 'center':
                       $css.= "\n" . 'background-position:center top;';
                       break;

                   case 'right':
                       $css.= "\n" . 'background-position:right top;';
                       break;
              }//end switch


              //background attachment options
              switch ($this->theme_bg_attachment) {
                    case '':
                        //$css.= "\n" . 'background-attachment:scroll;';//default value `background-attachment`
                        break;

                    case 'fixed':
                        $css.= "\n" . 'background-attachment:fixed;';
                        break;
              }//end switch
         }

        return $css . "
                    }";
    }




	//custom background js
    function add_js_hook($js) {

	    $buffer = '';

	    if (!empty($this->theme_bg_image) and $this->is_stretched_bg == true) {

		    ob_start();


            // @todo chestia asta ar trebuii trecuta pe flag sau ceva in td_config ?

		    ?>

		    <script>

			    jQuery(window).ready(function() {

				    // if the theme has td_backstr support, it means this already uses it
				    if (typeof window.td_backstr !== 'undefined') {

					    (function(){

						    // the site background td-backstretch jquery object is dynamically added in DOM, and after any translation effects are applied over td-backstretch
						    var wrapper_image_jquery_obj = jQuery('<div class=\'backstretch\'></div>');
						    var image_jquery_obj = jQuery('<img class=\'td-backstretch\' src=\'<?php echo $this->theme_bg_image; ?>\'>');

						    wrapper_image_jquery_obj.append(image_jquery_obj);

						    jQuery('body').prepend(wrapper_image_jquery_obj);

						    var td_backstr_item = new td_backstr.item();

						    td_backstr_item.wrapper_image_jquery_obj = wrapper_image_jquery_obj;
						    td_backstr_item.image_jquery_obj = image_jquery_obj;

						    td_backstr.add_item(td_backstr_item);

					    })();

				    } else {

					    // - this is the old backstretch jquery plugin call
					    // - td_backstretch.js is in wp_booster, so it is still used by the themes that don't use new td_backstr.js
					    jQuery.backstretch('<?php echo $this->theme_bg_image; ?>', {fade:1200, centeredY:false});
				    }
			    });

		    </script>

		    <?php

		    $buffer = ob_get_clean();
	    }
	    $js .= "\n". td_util::remove_script_tag($buffer);

	    return $js;
    }



    /**
     * Checks to see if we need boxed layout or full layout
     *
     * @param $classes
     * @return array
     */
    function add_slug_to_body_class($classes) {
        //custom stretching background
        if ($this->is_stretched_bg or $this->theme_bg_image != '') {
            //remove the default word press class if it's stretched or if it's coming from a category bg
            $i = 0;
            foreach ($classes as $key => $value) {
                if ($value == 'custom-background') {
                    unset($classes[$i]);
                }
                $i++;
            }
        }

        if ($this->is_boxed_layout) {
            $classes[] = 'td-boxed-layout';
        } else {
            $classes[] = 'td-full-layout';
        }

        return $classes;
    }

}//end class


/*
 * Checks the page template to load or not the background, when we have a homepage with the homepage_full_1 shortcode, we have to remove the bg
*/
function td_check_template_before_header() {
    global $post, $paged, $wp_query;

    $td_page = (get_query_var('page')) ? get_query_var('page') : 1; //rewrite the global var
    $td_paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //rewrite the global var

    $flag_initialize_background = true;


    if(isset($post) and $post->ID > 0) {

        //paged works on single pages, page - works on homepage
        if ($td_paged > $td_page) {
            $paged = $td_paged;
        } else {
            $paged = $td_page;
        }

        //this check is used (instead of is_page($post->ID) wordpress function), because not all of is_page() properties are set this early in wordpress workflow(when wp hook is run) and theme pagination was not working
        if (
            !empty($wp_query->posts[0]->post_type)
            and $wp_query->posts[0]->post_type == 'page'
            and !empty($wp_query->posts[0]->ID)
            and $wp_query->posts[0]->ID == $post->ID
        ) {
            $page_content = get_post($post->ID);//get the page content; usually this are js_Visual_Composer made shortcodes

            //print_r($page_content);
            if (!empty($page_content->post_content) and strpos($page_content->post_content, 'td_block_homepage_full_1') !== false and (empty($paged) or $paged < 2)) {
                $flag_initialize_background = false;
            }
        }

        //check if we are single post page
        if (is_single()) {
            $post_meta_values = get_post_meta($post->ID, 'td_post_theme_settings', true);

            //print_r($post_meta_values);
            if(!empty($post_meta_values['td_post_template']) and $post_meta_values['td_post_template'] == 'single_template_6') {
                $flag_initialize_background = false;
            }
        }

    }

    //run the background class if necessary
    if($flag_initialize_background){
        $td_background  = new td_background();


        //background settings (color and images) for pages (except categories and post that are in a category)
        $td_background->theme_bg_image = td_util::get_option('tds_site_background_image');
        $td_background->theme_bg_color = td_util::get_option('tds_site_background_color');

        //setting variable : is_stretched_bg
        if (td_util::get_option('tds_stretch_background') == 'yes') {
            $td_background->is_stretched_bg = true;
        }

        // WordPress normal background positions settings (used when the background is not stretched)
        $td_background->theme_bg_repeat = td_util::get_option('tds_site_background_repeat');
        $td_background->theme_bg_position = td_util::get_option('tds_site_background_position_x');
        $td_background->theme_bg_attachment = td_util::get_option('tds_site_background_attachment');


    }

}


/*
 * http://codex.wordpress.org/Plugin_API/Action_Reference/wp
 *
 * This action hook runs immediately after the global WP class object is set up.
 * The $wp object is passed to the hooked function as a reference (no return is necessary).
 * This hook is one effective place to perform any high-level filtering or validation,
 * following queries, but before WordPress does any routing, processing, or handling
*/
//add_action('wp', 'td_check_template_before_header');


