<?php

class td_api_autoload extends td_api_base {
    static function add($class_id, $file) {
        $params_array['file'] = $file;
        parent::add_component(__CLASS__, $class_id, $params_array);
    }



    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }

}