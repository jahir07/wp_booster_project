<?php
/**
 * Created by ra on 7/20/2015.
 */

class tdx_util {
    private static $options_cache = array();


    /**
     * reads an option for a specific plugin BUT first it looks in the cache
     * @param $plugin_id
     * @param $option_id
     * @return string
     */
    public static function get_option($plugin_id, $option_id) {
        if (!isset(self::$options_cache[$plugin_id])) {
            // the option cache is not set for this plugin id, fetch it form db
            self::$options_cache[$plugin_id] = get_option($plugin_id);
        }

        if (!empty(self::$options_cache[$plugin_id][$option_id])) {
            return self::$options_cache[$plugin_id][$option_id];
        } else {
            return '';
        }
    }

    /**
     * updates an option in the cache. YOU MUST FLUSH THE CACHE TO THE DATABASE TO SAVE IT!
     * @param $plugin_id
     * @param $option_id
     * @param $option_value
     */
    public static function update_option_in_cache($plugin_id, $option_id, $option_value) {
        if (!isset(self::$options_cache[$plugin_id])) {
            // the option cache is not set for this plugin id, fetch it form db
            self::$options_cache[$plugin_id] = get_option($plugin_id);
        }

        // update the option cache
        self::$options_cache[$plugin_id][$option_id] = $option_value;
    }

    /**
     * saves the cache to the database
     * @param $plugin_id
     */
    public static function flush_options($plugin_id) {
        foreach (self::$options_cache as $plugin_id => $option_cache) {
            update_option($plugin_id, $option_cache);
        }
    }




}