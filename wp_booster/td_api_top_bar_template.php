<?php
/**
 * Created by ra on 2/13/2015.
 */

class td_api_top_bar_template extends td_api_base {
    static function add($template_id, $params_array = '') {
        parent::add_component(__CLASS__, $template_id, $params_array);
    }

	static function update($template_id, $params_array = '') {
		parent::update_component(__CLASS__, $template_id, $params_array);
	}

    static function get_all() {
        return parent::get_all_components_metadata(__CLASS__);
    }

    static function _helper_show_top_bar() {

        // find the current active template's id
        $template_id = self::_helper_get_active_id();
        try {
            $template_path = self::get_key($template_id, 'file');
        } catch (ErrorException $ex) {
            td_util::error(__FILE__, "td_api_top_bar_template::_helper_show_top_bar : $template_id isn't set. Did you disable a tagDiv plugin?");  //does not stop execution
        }

        // load the template
        if (!empty($template_path) and file_exists($template_path)) {
            load_template($template_path);
        } else {
            td_util::error(__FILE__, "The path $template_path of the template id: $template_id not found.");   //shoud be fatal?
        }

    }

    static function _helper_to_panel_values() {
        // add the rest
        foreach (self::get_all() as $id => $config) {
            $buffy_array[] = array(
                'text' => '',
                'title' => '',
                'val' => $id,
                'img' => $config['img']
            );
        }

        // the first template is the default one, ex: it has no value in the database
        $buffy_array[0]['val'] = '';

        return $buffy_array;
    }






    private static function _helper_get_active_id() {
        $template_id = td_util::get_option('tds_top_bar_template');

        if (empty($template_id)) { // nothing is set, check the default value
            $template_id = parent::get_default_component_id(__CLASS__);
        }

        return $template_id;
    }

}

