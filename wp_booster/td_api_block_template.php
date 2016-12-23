<?php
/**
 * Created by ra on 2/13/2015.
 */


/**
 * The theme's block template api, usable via the td_global_after hook
 * Class td_api_block static block api
 */
class td_api_block_template extends td_api_base{

    /**
     * This method to register a new block
     *
     * @param $id string The block template id. It must be unique
     * @param $params_array array The block template array
     *
     *      $params_array = array(
     *          'file' => '',           - Where we can find the shortcode class
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($id, $params_array) {
        parent::add_component(__CLASS__, $id, $params_array);
    }


	static function update($id, $params_array) {
		parent::update_component(__CLASS__, $id, $params_array);
	}


    static function get_all() {
        return parent::get_all_components_metadata(__CLASS__);
    }


    /**
     * get all the header styles as a array for the panel ui controll. It also adds the default value
     *
     * @internal
     * @return array
     */
    static function _helper_generate_block_templates() {
        $buffy_array = array();


        //add each value
        foreach (self::get_all() as $id => $config) {
            $buffy_array[] = array(
                'text' => $config['text'],
                'val' => $id,
                'title' => '',
                'img' => $config['img'],
            );
        }

        // the first template is the default one, ex: it has no value in the database
        $buffy_array[0]['val'] = '';
        return $buffy_array;
    }
}

