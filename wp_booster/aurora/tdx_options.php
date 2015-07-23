<?php
/**
 * Created by ra on 7/23/2015.
 */


class tdx_options {
    private static $options_cache = array();

    /**
     * @var array - here we store all the registered data sources
     */
    private static $registered_data_sources = array();



    /**
     * reads an option for a specific plugin BUT first it looks in the cache
     * @param $datasource
     * @param $option_id
     * @return string
     */
    static function get_option($datasource, $option_id) {
        // check if the data source is registered
        if (!in_array($datasource, self::$registered_data_sources)) {
            tdx_util::error(__FILE__, 'get_option on a unregistered data source');
            return '';
        }

        if (!isset(self::$options_cache[$datasource])) {
            // the option cache is not set for this plugin id, fetch it form db
            self::$options_cache[$datasource] = get_option($datasource);
        }

        if (!empty(self::$options_cache[$datasource][$option_id])) {
            return self::$options_cache[$datasource][$option_id];
        } else {
            return '';
        }
    }

    /**
     * updates an option in the cache. YOU MUST FLUSH THE CACHE TO THE DATABASE TO SAVE IT!
     * @param $datasource
     * @param $option_id
     * @param $option_value
     */
    static function update_option_in_cache($datasource, $option_id, $option_value) {
        // check if the data source is registered
        if (!in_array($datasource, self::$registered_data_sources)) {
            tdx_util::error(__FILE__, 'get_option on a unregistered data source');
            return;
        }

        if (!isset(self::$options_cache[$datasource])) {
            // the option cache is not set for this plugin id, fetch it form db
            self::$options_cache[$datasource] = get_option($datasource);
        }

        // update the option cache
        self::$options_cache[$datasource][$option_id] = $option_value;
    }

    /**
     * saves the cache to the database
     * @param $datasource
     */
    static function flush_options($datasource) {
        foreach (self::$options_cache as $datasource => $option_cache) {
            update_option($datasource, $option_cache);
        }
    }



    /**
     * Allows plugins to register new datasources
     * @param $data_source_id - the id of the datasource
     */
    static function register_data_source($data_source_id) {
        self::$registered_data_sources[] = $data_source_id;
    }


    /**
     * saves a bundle of options @see td_panel_data_source::update
     * IT ALSO FLUSHES THE CACHE
     * @param $datasource - the data source
     * @param $options_array  - the options array to update
     */
    static function set_data_to_datasource($datasource, $options_array) {
        foreach ($options_array as $option_id => $option_value) {
            self::update_option_in_cache($datasource, $option_id, $option_value);
        }
        self::flush_options($datasource);
    }




}