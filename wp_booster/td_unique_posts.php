<?php


class td_unique_posts {


    static $rendered_posts_ids = array(); //here we hold all the rendered posts id's. It's used by the data source to remove them in the following queries
    static $keep_rendered_posts_ids = false; //if this is true, the data source removes the posts from $rendered_posts_ids in each query (except search?)

    static $unique_articles_enabled = false;  //if true, the datasource will filter the rendered posts id

    static function td_init() {
        //add_filter('the_content', array(__CLASS__, 'hook_the_content'), 5, 2);
        //add_filter('td_part_page_home_slider', array(__CLASS__, 'hook_the_content'), 5, 2); //also hook parts/page-homepage-slider.php - the slider is called before the content

        add_filter('td_wp_booster_after_header', array(__CLASS__, 'hook_td_wp_booster_after_header'), 10);

        add_filter('td_wp_boost_new_module', array(__CLASS__, 'hook_td_wp_boost_new_module'), 5, 2);




    }


    //we hook td_module constructor if  `unique articles` or `ajax post view count` are enabled
    static function hook_td_wp_boost_new_module($post) {
        if (self::$keep_rendered_posts_ids == true) {
            self::$rendered_posts_ids[] = $post->ID;
        }
    }


    static function hook_td_wp_booster_after_header() {

        $page_id = get_queried_object_id();

        //if we are on a page, read the page meta and see if td_unique_articles is set
        if (is_page()) {
            $td_unique_articles = get_post_meta($page_id, 'td_unique_articles', true);

            if (!empty($td_unique_articles['td_unique_articles'])) {
                self::$keep_rendered_posts_ids = true; //for new module hook
                self::$unique_articles_enabled = true; //for datasource
            }
        }


        if (td_util::get_option('tds_ajax_post_view_count') == 'enabled') {
            self::$keep_rendered_posts_ids = true;
        }
    }


    function todo() {
        //do we enable the unique posts filter?
        $td_unique_articles = get_post_meta($post->ID, 'td_unique_articles', true);
        if (!empty($td_unique_articles['td_unique_articles'])) {
            td_global::$td_unique_articles = true;
        }
    }
}


td_unique_posts::td_init();