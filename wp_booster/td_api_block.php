<?php
/**
 * Created by ra on 2/13/2015.
 */

/**
 * @deprecated used by social counter 2.9 @todo delete in v2
 * Class td_block_api
 */
class td_block_api {
    static function add($block_id, $params_array = '') {
        td_api_block::add($block_id, $params_array);
    }
}

/**
 * The theme's block api, usable via the td_global_after hook
 * Class td_api_block static block api
 */
class td_api_block extends td_api_base {

    /**
     * This method to register a new block
     *
     * @param $id string The block id. It must be unique
     * @param $params_array array The block_parameter array
     *
     *      $params_array = array( - A wp_map array @link https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524332
     *          'map_in_visual_composer' => ,
     *          'file' => '',                   - Where we can find the shortcode class
     *          'name' => '',                   - string Name of your shortcode for human reading inside element list
     *          'base' => '',                   - string Shortcode tag. For [my_shortcode] shortcode base is my_shortcode
     *          'class' => '',                  - string CSS class which will be added to the shortcode's content element in the page edit screen in Visual Composer backend edit mode
     *          'controls' => '',               - string ?? no used?
     *          'category' => '',               - string Category which best suites to describe functionality of this shortcode. Default categories: Content, Social, Structure. You can add your own category, simply enter new category title here
     *          'icon' => '',                   - URL or CSS class with icon image
     *          'params' => array ()            - array List of shortcode attributes. Array which holds your shortcode params, these params will be editable in shortcode settings page
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($block_id, $params_array = '') {
        parent::add_component(__CLASS__, $block_id, $params_array);
    }


    /**
     * This method gets the value for the ('td_api_block') key in the main settings array of the theme.
     *
     * @return mixed array The value set for the 'td_api_block' in the main settings array of the theme
     */
    static function get_all() {
        return parent::get_all_components(__CLASS__);
    }
}

