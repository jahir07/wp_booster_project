<?php

/**
 * The theme's css buffer. Has a _hook method that is called at the end of this file.
 * Class td_css_buffer
 */
class td_css_buffer {


    private static $css_header_buffer = '';
    private static $css_header_buffer_has_rendered = false; // used to double check if we call the add function correctly


    private static $css_footer_buffer = '';
    private static $css_footer_buffer_has_rendered = false; // used to double check if we call the add function correctly



    /**
     * - add css to the buffer. Must be called before wp_head hook.
     * - if called on wp_head hook, it must be called with priority < 15
     * - if defined('TD_SPEED_BOOSTER'), this css will appear at the bottom, else it will appear in the header
     * @param $css - the css WITHOUT THE <style> TAG
     * @throws ErrorException - if it's called to late you will get this message
     */
    static function add_to_header($css) {
        if (self::$css_header_buffer_has_rendered === true) {
            throw new ErrorException("td_css_buffer::add - css was already rendered when you called td_css_buffer::add() (ex: add was called to late)");
        }
        self::$css_header_buffer .= "\n" . $css;
    }



    /**
     * - adds the css to the footer. Must be called before wp_footer hook
     * - if called on wp_head hook, it must be called with priority < 100
     * @param $css - the css WITHOUT THE <style> TAG
     * @throws ErrorException - if it's called to late you will get this message
     */
    static function add_to_footer($css) {
        if (self::$css_footer_buffer_has_rendered === true) {
            throw new ErrorException("td_css_buffer::add_to_footer - css was already rendered when you called td_css_buffer::add_to_footer() (ex: add was called to late)");
        }
        self::$css_footer_buffer .= "\n" . $css;
    }



    static function _render_header() {
        self::$css_header_buffer_has_rendered = true;
        //run the filter
        self::$css_header_buffer = apply_filters("td_css_buffer_render", self::$css_header_buffer);

        if (trim(self::$css_header_buffer) != '') {
            self::$css_header_buffer = "\n<!-- Header style compiled by theme -->" . "\n\n<style>\n    " . self::$css_header_buffer . "\n</style>\n\n";
            echo self::$css_header_buffer; // echo out the buffer
        } else {
            return '';
        }
    }



    static function _render_footer() {
        self::$css_footer_buffer_has_rendered = true;
        if (trim(self::$css_footer_buffer) != '') {
            self::$css_footer_buffer = "\n<!-- Footer style compiled by theme -->" . "\n\n<style>\n    " . self::$css_footer_buffer . "\n</style>\n\n";
            echo self::$css_footer_buffer; // echo out the buffer
        } else {
            return '';
        }
    }



    static function _hook() {
        // render the header css section according to the speed booster plugin
        if (defined('TD_SPEED_BOOSTER')) {
             add_action('wp_footer',  array('td_css_buffer', '_render_header'), 100);
        } else {
            add_action('wp_head', array('td_css_buffer', '_render_header'), 15); //priority 10 is used by the css compiler, that means that on 10 we don't have the css ready
        }

        // render the bottom section always at the end
        add_action('wp_footer',  array('td_css_buffer', '_render_footer'), 100);
    }
}

td_css_buffer::_hook();



