<?php
/**
 * Created by ra on 2/13/2015.
 */



class td_api_tinymce_formats extends td_api_base {

    private static $tiny_mce_format_list = array ();


    static function add($tinymce_format_id, $params_array = '') {
        parent::add_component(__CLASS__, $tinymce_format_id, $params_array);
        /*
        if (empty($params_array['parent_id'])) {
            // root level
            self::$tiny_mce_format_list[$tinymce_format_id] = $params_array;
        } else {

            // first level
            if (isset(self::$tiny_mce_format_list[$params_array['parent_id']])) {
                self::$tiny_mce_format_list[$params_array['parent_id']]['items'][] = $params_array;
            } else {
                echo 'errrrrr';
            }
        }
        */
    }

    static function update($tinymce_format_id, $params_array = '') {
        parent::update_component(__CLASS__, $tinymce_format_id, $params_array);
    }

    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }


    static function _helper_get_tiny_mce_format() {
        $all_formats = self::get_all();


        $lvl_0_components = array();
        $lvl_1_components = array();
        $lvl_2_components = array();
        $lvl_3_components = array();



    }

}

