<?php
/**
 * Created by ra on 2/13/2015.
 */



/**
 * The theme's module api, usable via the td_global_after hook
 * Class td_api_module static module api
 */
class td_api_module extends td_api_base {

    /**
     * This method to register a new module
     *
     * @param $id string The module id. It must be unique
     * @param $params_array array The module_parameter array
     *
     *      $params_array = array (
     *          'file' => '',                               - [string] the path to the module class
     *          'text' => '',                               - [string] module name text used in the theme panel
     *          'img' => '',                                - [string] the path to the image icon
     *          'used_on_blocks' => array(),                - [array of strings] block names where this module is used or leave blank if it's used internally (ex. it's not used on any category)
     *          'excerpt_title' => '',                      - [int] leave empty '' if you don't want a setting in the panel -> excerpts for this module
     *          'excerpt_content' => '',                    - [int] leave empty ''  ----||----
     *          'enabled_on_more_articles_box' => ,         - [boolean] show the module in the more articles box in panel -> post settings -> more articles box
     *          'enabled_on_loops' => ,                     - [boolean] show the module in panel on loops
     *          'uses_columns' => ,                         - [boolean] if the module uses columns on the page template + loop (if the modules has columns, enable this)
     *          'category_label' =>                         - [boolean] show the module in panel -> block_settings -> category label ?
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($module_id, $params_array = '') {
        parent::add_component(__CLASS__, $module_id, $params_array);
    }

	static function update($module_id, $params_array = '') {
		parent::update_component(__CLASS__, $module_id, $params_array);
	}


    /**
     * This method gets the value for the ('td_api_module') key in the main settings array of the theme.
     *
     * @return mixed array The value set for the 'td_api_module' in the main settings array of the theme
     */
    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }


    /**
     * This method is an internal helper function used to check 'excerpt_title' property of a module
     *
     * @internal
     * @param $module_id string Unique module id
     * @return bool True if the 'excerpt_title' property is set, false otherwise
     */
    static function _check_excerpt_title($module_id) {
        $module_settings = self::get_by_id($module_id);

        if (isset($module_settings) and !empty($module_settings['excerpt_title'])) {
            return true;
        }
        return false;
    }



    /**
     * This method is an internal helper function used to check 'excerpt_content' property of a module
     *
     * @internal
     * @param $module_id string Unique module id
     * @return bool True if the 'excerpt_content' property is set, false otherwise
     */

    static function _check_excerpt_content($module_id) {
        $module_settings = self::get_by_id($module_id);

        if (isset($module_settings) and !empty($module_settings['excerpt_content'])) {
            return true;
        }
        return false;
    }
}
