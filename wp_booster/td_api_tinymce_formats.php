<?php
/**
 * Created by ra on 2/13/2015.
 */



class td_api_tinymce_formats extends td_api_base {

    private static $tiny_mce_format_list = array ();


    static function add($tinymce_format_id, $params_array = '') {
        parent::add_component(__CLASS__, $tinymce_format_id, $params_array);
    }


    static function update($tinymce_format_id, $params_array = '') {
        parent::update_component(__CLASS__, $tinymce_format_id, $params_array);
    }


    static function get_all() {
        return parent::get_all_components_metadata(__CLASS__);
    }


	/**
	 * sets @see td_global::$tiny_mce_style_formats with settings from the api
	 */
    static function _helper_get_tinymce_format() {
	    self::$tiny_mce_format_list = self::get_all();

        array_walk(self::$tiny_mce_format_list, array('td_api_tinymce_formats', '_connect_to_parent'));

	    td_global::$tiny_mce_style_formats = array_filter(self::$tiny_mce_format_list, array('td_api_tinymce_formats', '_get_root_elements'));
    }


	/**
	 * callback for array_walk @see td_api_tinymce_formats::_helper_get_tinymce_format
	 * @param $value
	 * @param $key
	 */
	static function _connect_to_parent($value, $key) {

		if (!empty($value['parent_id'])
		    && isset(self::$tiny_mce_format_list[$value['parent_id']])
		    && isset(self::$tiny_mce_format_list[$key])) {

			self::$tiny_mce_format_list[$value['parent_id']]['items'][] = &self::$tiny_mce_format_list[$key];
		}
	}


	/**
	 * callback for array_filter @see td_api_tinymce_formats::_helper_get_tinymce_format
	 * @param $value
	 * @return bool
	 */
	static function _get_root_elements($value) {
		return empty($value['parent_id']);
	}

}



