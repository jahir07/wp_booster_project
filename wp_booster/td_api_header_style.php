<?php
/**
 * Created by ra on 2/13/2015.
 */

class td_api_header_style extends td_api_base {

    /**
     * This method to register a new header style
     *
     * @param $id string The header style id. It must be unique
     * @param $params_array array The heade style parameter array
     *
     *      $params_array = array (
     *          'text' => '',   - [string] the text used inside
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


    /**
     * get all the header styles as a array for the panel ui controll. It also adds the default value
     *
     * @internal
     * @return array
     */
    static function _helper_generate_tds_header_style() {
        $buffy_array = array();


        //add each value
        foreach (self::get_all() as $id => $config) {
            $buffy_array[] = array(
                'text' => $config['text'],
                'val' => $id,
            );
        }

        // the first template is the default one, ex: it has no value in the database
        $buffy_array[0]['val'] = '';
        return $buffy_array;
    }


    /**
     * helper function to show the header of the theme.
     *
     * @internal
     */
    static function _helper_show_header() {
        $tds_header_style = self::_helper_get_active_id();
        $template_path = '';

        // look for the user selected template
        try {
            $template_path = self::get_key($tds_header_style, 'file');
        } catch (ErrorException $ex) {
            td_util::error(__FILE__, "The header style: $tds_header_style isn't set. Did you disable a tagDiv plugin?");  //does not stop execution
        }


        // load the template
        if (!empty($template_path) and file_exists($template_path)) {
            load_template($template_path);
        } else {
            td_util::error(__FILE__, "The path $template_path of the $tds_header_style header style not found. Did you disable a tagDiv plugin?");
        }
    }


    private static function _helper_get_active_id() {
        $tds_header_style = td_util::get_option('tds_header_style');

        if (empty($tds_header_style)) { // nothing is set, check the default value
            $tds_header_style = parent::get_default_component_id(__CLASS__);
        }

        return $tds_header_style;
    }
}

