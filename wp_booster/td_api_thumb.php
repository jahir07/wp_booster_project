<?php
/**
 * Created by ra on 2/13/2015.
 */

class td_api_thumb extends td_api_base {

    /**
     * This method to register a new thumb
     *
     * @param $id string The single template id. It must be unique
     * @param $params_array array The single_template_parameter array
     *
     *      $params_array = array (
     *          'name' => 'td_0x420',                       - [string] the thumb name
     *          'width' => ,                                - [int] the thumb width
     *          'height' => ,                               - [int] the thumb height
     *          'crop' => array('center', 'top'),           - [array of string] what crop to use (center, top, etc)
     *          'post_format_icon_size' => '',              - [string] what play icon to load (small or normal)
     *          'used_on' => array('')                      - [array of string] description where the thumb is used
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($thumb_id, $params_array = '') {
        parent::add_component(__CLASS__, $thumb_id, $params_array);
    }

	static function update($thumb_id, $params_array = '') {
		parent::update_component(__CLASS__, $thumb_id, $params_array);
	}

    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }
}



