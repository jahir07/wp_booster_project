<?php
/*  ----------------------------------------------------------------------------
    background support
 */


class td_background {
    function __construct() {
        add_action('wp_head', array($this, 'wp_head_hook'), 1);
    }



    function wp_head_hook() {
        global $post, $paged;

        $render_background_flag = true;


        $background_params = array (
            'is_boxed_layout' => false,
            'is_stretched_bg' => false,
            'theme_bg_image' => td_util::get_option('tds_site_background_image'),
            'theme_bg_repeat' => td_util::get_option('tds_site_background_repeat'),
            'theme_bg_position' => td_util::get_option('tds_site_background_position_x'),
            'theme_bg_attachment' => td_util::get_option('tds_site_background_attachment'),
            'theme_bg_color' => td_util::get_option('tds_site_background_color'),

            //the background ad support was merged with this from td_ads.php
            'td_ad_background_click_link' => stripslashes(td_util::get_option('tds_background_click_url')),
            'td_ad_background_click_target' => td_util::get_option('tds_background_click_target')
        );



        /*  --------------------------------------------------------------------------
            Read the background settings
         */

        // is stretch background?
        if (td_util::get_option('tds_stretch_background') == 'yes') {
            $background_params['is_stretched_bg'] = true;
        }

        // activate the boxed layout - if we have an image or color
        if ($background_params['theme_bg_image'] != '' or  $background_params['theme_bg_color'] != '') {
            $background_params['is_boxed_layout'] = true;
        }





        /*  --------------------------------------------------------------------------
            we are on a category
        */
        elseif (is_category()) {
            // try to read the category settings
            $post_primary_category_id = intval(get_query_var('cat')); //we are on a category, get the id @todo verify this, get_query_var('cat') may not work with permalinks
            $background_params = $this->get_category_bg_settings($post_primary_category_id, $background_params);
        }


        /*  --------------------------------------------------------------------------
            we are on a page
        */
        if (is_page()) {
            $td_page = (get_query_var('page')) ? get_query_var('page') : 1; //rewrite the global var
            $td_paged = (get_query_var('paged')) ? get_query_var('paged') : 1; //rewrite the global var
            if ($td_paged > $td_page) {
                $paged = $td_paged;
            } else {
                $paged = $td_page;
            }
            if (
                !empty($post->post_content)
                and strpos($post->post_content, 'td_block_homepage_full_1') !== false
                and (empty($paged) or $paged < 2)) {
                // deactivate the background only on td_block_homepage_full_1 + page 1.
                // on the second page, load it with the normal site wide background
                $render_background_flag = false;
            }
        }


        /*  --------------------------------------------------------------------------
            we are on a single post
        */
        elseif (is_singular('post')) {   //is_single runs on all the posts types, that's why we need is_singular
            $post_meta_values = get_post_meta($post->ID, 'td_post_theme_settings', true);
            if(!empty($post_meta_values['td_post_template'])) {

                if (td_api_single_template::get_key($post_meta_values['td_post_template'], 'disable_background') === true) {
                    $render_background_flag = false;
                }

                if (td_api_single_template::get_key($post_meta_values['td_post_template'], 'use_featured_image_as_background') === true) {
                    $background_params['theme_bg_image'] = td_util::get_featured_image_src($post->ID, 'full');
                    $background_params['is_stretched_bg'] = true;
                }

                /*
                if (td_api_single_template::get_key($post_meta_values['td_post_template'], 'disable_boxed_layout') === true) {
                    $background_params['is_boxed_layout'] = false;
                }
                */
            }

            // try to read the background settings for the parent category of this post
            $post_primary_category_id = intval(td_global::get_primary_category_id());  // we are on single post - get the primary category id
            $background_params = $this->get_category_bg_settings($post_primary_category_id, $background_params);
        }



        if ($render_background_flag === true) {
            new td_background_render($background_params);
        }
    }


    /**
     * This function, reads the category background settings and patches the $background_params with the cat settings
     * @param $category_id - the category id, used to read the settings
     * @param $background_params - the current background settings
     * @return array - the patched background settings
     */
    function get_category_bg_settings($category_id, $background_params) {
        // read the background settings from the category if needed
        if (!empty($category_id)) {
            //get the category bg image
            $tdc_image = td_util::get_category_option($category_id, 'tdc_image');
            if (!empty($tdc_image)) {
                $background_params['theme_bg_image'] = $tdc_image;
            }

            //get the category bg color
            $tdc_bg_color = td_util::get_category_option($category_id, 'tdc_bg_color');
            if (!empty($tdc_bg_color)) {
                $background_params['theme_bg_image'] = $tdc_bg_color;
            }

            //get the bg style - from category specific
            $tdc_bg_repeat = td_util::get_category_option($category_id, 'tdc_bg_repeat');
            switch  ($tdc_bg_repeat) {
                case '':
                    //do nothing - the background is already stretched if needed from the top of this function
                    break;

                case 'stretch':
                    $background_params['is_stretched_bg'] = true;
                    break;

                case 'tile':
                    $background_params['is_stretched_bg'] = false;
                    $background_params['theme_bg_repeat'] = 'repeat';
                    break;
            }
        }
        return $background_params;
    }
}


new td_background();



