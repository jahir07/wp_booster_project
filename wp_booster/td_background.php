<?php
/*  ----------------------------------------------------------------------------
    background support
 */


class td_background {
    function __construct() {
        add_action('wp_head', array($this, 'wp_head_hook'), 1);
    }



    function wp_head_hook() {
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

            if (is_page()) {
                $page_content = get_post($post->ID);//get the page content; usually this are js_Visual_Composer made shortcodes
                if (
                    !empty($page_content->post_content)
                    and strpos($page_content->post_content, 'td_block_homepage_full_1') !== false
                    and (empty($paged) or $paged < 2)) {
                    $flag_initialize_background = false;
                }
            }

            //check if we are single post page
            if (is_single()) {
                $post_meta_values = get_post_meta($post->ID, 'td_post_theme_settings', true);
                if(!empty($post_meta_values['td_post_template']) and $post_meta_values['td_post_template'] == 'single_template_6') {
                    $flag_initialize_background = false;
                }
            }

        }

        //run the background class if necessary
        if($flag_initialize_background){


            $is_stretched_bg = false;
            $is_boxed_layout = false;

            $theme_bg_image = td_util::get_option('tds_site_background_image');
            $theme_bg_color = td_util::get_option('tds_site_background_color');
            $theme_bg_repeat = td_util::get_option('tds_site_background_repeat');

            // is stretch background?
            if (td_util::get_option('tds_stretch_background') == 'yes') {
                $is_stretched_bg = true;
            }


            // activate the boxed layout - if we have an image or color
            if ($theme_bg_image != '' or  $theme_bg_color != '') {
                $is_boxed_layout = true;
            }


            //background settings for category and post page (post that are in a category)
            if (is_category() or is_single()) {


                if (is_category()) {
                    $category_id = intval(get_query_var('cat')); //we are on a category, get the id
                } else {
                    $category_id = intval(td_global::get_primary_category_id());  // we are on single post - get the primary category id
                }

                if (!empty($category_id)) {

                    //get the category bg image
                    $tdc_image = td_util::get_category_option($category_id, 'tdc_image');
                    if (!empty($tdc_image)) {
                        $theme_bg_image = $tdc_image;
                    }

                    //get the category bg color
                    $tdc_bg_color = td_util::get_category_option($category_id, 'tdc_bg_color');
                    if (!empty($tdc_bg_color)) {
                        $theme_bg_image = $tdc_bg_color;
                    }

                    //get the bg style - from category specific
                    $tdc_bg_repeat = td_util::get_category_option($category_id, 'tdc_bg_repeat');
                    switch  ($tdc_bg_repeat) {
                        case '':
                            //do nothing - the background is already stretched if needed from the top of this function
                            break;


                        case 'stretch':
                            $is_stretched_bg = true;
                            break;


                        case 'tile':
                            $is_stretched_bg = false;
                            $theme_bg_repeat = 'repeat';
                            break;
                    }

                }

            }

            $background_params = array (
                'is_boxed_layout' => $is_boxed_layout,
                'is_stretched_bg' => $is_stretched_bg,
                'theme_bg_image' => $theme_bg_image,
                'theme_bg_repeat' => $theme_bg_repeat,
                'theme_bg_position' => td_util::get_option('tds_site_background_position_x'),
                'theme_bg_attachment' => td_util::get_option('tds_site_background_attachment'),
                'theme_bg_color' => $theme_bg_color,

                //the background ad support was merged with this from td_ads.php
                'td_ad_background_click_link' => stripslashes(td_util::get_option('tds_background_click_url')),
                'td_ad_background_click_target' => td_util::get_option('tds_background_click_target')
            );
            new td_background_render($background_params);
        }
    }
}


new td_background();



