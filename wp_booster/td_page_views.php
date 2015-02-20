<?php
class td_page_views {

    static $post_view_counter_key = 'post_views_count';

    //the name of the field where 7 days counter are kept(in a serialized array) for the given post
    static $post_view_counter_7_day_array = 'post_views_count_7_day_arr';

    //the name of the field for the total of 7 days
    static $post_view_counter_7_day_total = 'post_views_count_7_day_total';

    static $post_view_7days_last_day = 'post_view_7days_last_day';


    //used only in single.php to update the views
    static function update_page_views($postID) {

        if (td_util::get_option('tds_p_show_views') == 'hide') {
            return;
        }

        global $page;


        //$page == 1 - fix for yoast
        if (is_single() and (empty($page) or $page == 1)) {  //do not update the counter only on single posts that are on the first page of the post
            //debug





            //use general single page count only when `ajax_post_view_count` is disabled
            if(td_util::get_option('tds_ajax_post_view_count') != 'enabled') {
                //used for general count
                $count = get_post_meta($postID, self::$post_view_counter_key, true);
                if ($count == ''){
                    update_post_meta($postID, self::$post_view_counter_key, 1);
                } else {
                    $count++;
                    update_post_meta($postID, self::$post_view_counter_key, $count);
                }
            }

            //stop here if
            if (td_util::get_option('tds_p_enable_7_days_count') != 'enabled') {
                return;
            }

            //used for 7 day count array

            $current_day = date("N") - 1;  //get the current day
            $count_7_day_array = get_post_meta($postID, self::$post_view_counter_7_day_array, true);  // get the array with day of week -> count


            if (is_array($count_7_day_array)) {


                if (isset($count_7_day_array[$current_day])) { // check to see if the current day is defined - if it's not defined it's not ok.

                    if (get_post_meta($postID, self::$post_view_7days_last_day, true) == $current_day) {
                        // the day was not changed since the last update
                        $count_7_day_array[$current_day]++;
                    } else {
                        // the day was changed since the last update - reset the current day
                        $count_7_day_array[$current_day] = 1;

                        //update last day with the current day
                        update_post_meta($postID, self::$post_view_7days_last_day, $current_day);
                    }

                    // update the array
                    update_post_meta($postID, self::$post_view_counter_7_day_array, $count_7_day_array);

                    // update the 7days sum
                    update_post_meta($postID, self::$post_view_counter_7_day_total, array_sum($count_7_day_array));
                }

            } else {
                // the array is not initialized
                $count_7_day_array = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
                $count_7_day_array[$current_day] = 1; // add one view on the current day

                // update the array
                update_post_meta($postID, self::$post_view_counter_7_day_array, $count_7_day_array);

                // update last day with the current day
                update_post_meta($postID, self::$post_view_7days_last_day, $current_day);

                // update the 7 days total - 1 view :)
                update_post_meta($postID, self::$post_view_counter_7_day_total, 1);
            }


            /*
            $count_7_day_array = get_post_meta($postID, self::$post_view_counter_7_day_array, true);
            $count_7_day_total = get_post_meta($postID, self::$post_view_counter_7_day_total, true);
            $count_7_day_total_all = get_post_meta($postID, self::$post_view_counter_key, true);

            $count_7_day_lastday = get_post_meta($postID, self::$post_view_7days_last_day, true);

            echo '<br>';
            print_r($count_7_day_array);
            echo "<br>total week: " . $count_7_day_total;
            echo "<br>total all time: " . $count_7_day_total_all;
            echo '<br>last day: ' . $count_7_day_lastday;
            */

        }
    }

    static function get_page_views($postID) {
        $count = get_post_meta($postID, self::$post_view_counter_key, true);

        if ($count == '') {
            delete_post_meta($postID, self::$post_view_counter_key);
            add_post_meta($postID, self::$post_view_counter_key, '0');
            return "0";
        }
        return $count;
    }



    static function hook_manage_posts_columns($defaults) {
        $defaults['td_post_views'] = 'Views';
        return $defaults;
    }

    static function hook_manage_posts_custom_column($column_name, $id) {
        if($column_name === 'td_post_views'){
            echo self::get_page_views(get_the_ID());
        }
    }

    static function hook_wp_admin() {
        add_filter('manage_posts_columns', array(__CLASS__, 'hook_manage_posts_columns'));
        add_action('manage_posts_custom_column', array(__CLASS__, 'hook_manage_posts_custom_column'), 5, 2);
    }
}


if (td_util::get_option('tds_p_show_views') != 'hide') {
    td_page_views::hook_wp_admin(); //do the hook shake
}

