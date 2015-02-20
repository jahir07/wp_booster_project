<?php
/**
 * Created by ra on 2/10/2015.
 */
class td_block_template {

    /**
     * @var string the template data, it's set on construct
     */
    var $template_data_array = '';

    /**
     * @param $template_data_array array - all the data for the template
     */
    function __construct($template_data_array) {
        $this->template_data_array = $template_data_array;
    }
}