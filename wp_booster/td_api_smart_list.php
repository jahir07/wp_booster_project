<?php
/**
 * Created by ra on 2/13/2015.
 */

/**
 * Note: the smart lists are loaded via autoload
 * Class td_api_smart_list
 */
class td_api_smart_list extends td_api_base {

    /**
     * This method to register a new smart list
     *
     * @param $id string The smart list id. It must be unique
     * @param $params_array array The smart_list_parameter array
     *
     *      $params_array = array (
     *          'file' => '',                               - [string] the path to the smart list file
     *          'text' => '',                               - [string] name text used in the theme panel
     *          'img' => '',                                - [string] the path to the image icon
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($smart_list_id, $params_array = '') {
        parent::add_component(__CLASS__, $smart_list_id, $params_array);
    }

	static function update($smart_list_id, $params_array = '') {
		parent::update_component(__CLASS__, $smart_list_id, $params_array);
	}

    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }

    /**
     *  returns all the single post templates in a format that is usable for the panel
     *
     *  @internal
     *  @return array
     *
     *      array(
     *          array('text' => '', 'title' => '', 'val' => 'single_template_6', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/post-templates/post-templates-icons-6.png'),
     *      )
     */
    static function _helper_td_smart_list_api_to_panel_values() {
        $buffy_array = array();

        // add the default smart list
        $buffy_array[] =  array(
            'text' => '',
            'title' => '',
            'val' => '',
            'img' => td_global::$get_template_directory_uri . '/images/panel/smart_lists/td_smart_list_default.png'
        );

        foreach (self::get_all() as $template_value => $template_config) {
            $buffy_array[] = array(
                'text' => '',
                'title' => '',
                'val' => $template_value,
                'img' => $template_config['img']
            );
        }



        return $buffy_array;
    }
}

