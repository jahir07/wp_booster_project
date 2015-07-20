<?php
/**
 * Created by ra on 7/20/2015.
 */


class tdx_api_panel {

    /**
     * @var array - here we store all the registered data sources
     */
    private static $registered_data_sources = array();


    /**
     * Allows plugins to register new datasources
     * @param $data_source_id - the id of the datasource
     */
    static function add_data_source($data_source_id) {
        self::$registered_data_sources[] = $data_source_id;
    }


    /**
     * Allows the panel to save data to the datasources registered by plugins
     * @param $post_data_source - the data source
     * @param $post_value       - post value received by the panel for this specific datasource
     */
    static function set_data_to_datasource($post_data_source, $post_value) {
        if (in_array($post_data_source, self::$registered_data_sources)) {

            foreach ($post_value as $option_id => $option_value) {
                tdx_util::update_option_in_cache($post_data_source, $option_id, $option_value);
            }

            tdx_util::flush_options($post_data_source);
        }

    }

    /**
     * allows the panel to read data for a specific datasource that was registered by a plugin and option id
     * @param $data_source - what data source to use, this will be checked by the list of registered data sources
     * @param $option_id   - the option id
     * @return string      - returns the option value
     */
    static function get_data_from_datasource($data_source, $option_id) {
        if (in_array($data_source, self::$registered_data_sources)) {
            return tdx_util::get_option($data_source, $option_id);
        } else {
            return '';
        }
    }

    /**
     * adds a new panel to a specific panel spot. After each panel is added, the panel spot can be rendered from @see td_panel_core::render_panel
     * @param $panel_spot_id
     * @param $params_array
     */
    static function add($panel_spot_id, $params_array) {
        if (isset(td_global::$all_theme_panels_list[$panel_spot_id])) {
            td_global::$all_theme_panels_list[$panel_spot_id] = array_merge(td_global::$all_theme_panels_list[$panel_spot_id], $params_array);
        } else {
            td_global::$all_theme_panels_list[$panel_spot_id] = $params_array;
        }
    }
}