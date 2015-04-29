<?php
/**
 * Created by ra on 4/28/2015.
 */

class td_background_render {

    // here we store the background parameters
    private $background_parameters = array();



    function __construct($background_parameters) {

        // save a local copy
        $this->background_parameters = $background_parameters;

        // bg click ad
        td_js_buffer::add_variable('td_ad_background_click_link', stripslashes($this->background_parameters['td_ad_background_click_link'])); // the slashes are added by wp in the panel submit
        td_js_buffer::add_variable('td_ad_background_click_target', $this->background_parameters['td_ad_background_click_target']);


        // add the css if needed - the css knows about the stretched background!
        if ($this->background_parameters['theme_bg_image'] != '' or  $this->background_parameters['theme_bg_color'] != '') {
            td_css_buffer::add_to_header($this->add_css_custom_background());
        }

        // add the js if needed - needed only for stretch background
        if ($this->background_parameters['is_stretched_bg'] == true) {
            td_js_buffer::add_to_footer($this->add_js_hook());
        }

        // here we manipulate the body_class-es, we remove the WordPress ones and add our own + boxed version class
        add_filter('body_class', array($this,'add_slug_to_body_class'));
    }





    /**
     * we emulate the WordPress background function using our own setting
     * @param $css string - the existing css rendered by wp booster
     * @return string - the new css
     */
    private function add_css_custom_background() {
        $css = '';

        //color handling
        if (!empty($this->background_parameters['theme_bg_color'])) {
            $css.= "\n" . 'background-color:' . $this->background_parameters['theme_bg_color'] . ';';
        }


        // image handling; if there is no image stretching
        if(!empty($this->background_parameters['theme_bg_image']) and $this->background_parameters['is_stretched_bg'] == false) {
            //add the image
            $css.= "\n" . "background-image:url('" . $this->background_parameters['theme_bg_image'] . "');";

            //repeat image option
            switch ($this->background_parameters['theme_bg_repeat']) {
                case '':
                    $css.= "\n" . 'background-repeat:no-repeat;';
                    break;

                case 'repeat':
                    //$css.= "\n" . 'background-repeat:repeat;';//default value `background-repeat`
                    break;

                case 'repeat-x':
                    $css.= "\n" . 'background-repeat:repeat-x;';
                    break;

                case 'repeat-y':
                    $css.= "\n" . 'background-repeat:repeat-y;';
                    break;
            }//end switch


            //position image option
            switch ($this->background_parameters['theme_bg_position']) {
                case '':
                    //$css.= "\n" . 'background-position:left top;';//default value `background-position`
                    break;

                case 'center':
                    $css.= "\n" . 'background-position:center top;';
                    break;

                case 'right':
                    $css.= "\n" . 'background-position:right top;';
                    break;
            }//end switch


            //background attachment options
            switch ($this->background_parameters['theme_bg_attachment']) {
                case '':
                    //$css.= "\n" . 'background-attachment:scroll;';//default value `background-attachment`
                    break;

                case 'fixed':
                    $css.= "\n" . 'background-attachment:fixed;';
                    break;
            }//end switch
        }

        if (!empty($css)) {
            $css = PHP_EOL . "body {" . $css . PHP_EOL . '}';
        }
        return $css;
    }



    //custom background js
    private function add_js_hook() {
        if (!empty($this->background_parameters['theme_bg_image']) and $this->background_parameters['is_stretched_bg'] == true) {
            ob_start();
            // @todo chestia asta ar trebuii trecuta pe flag sau ceva in td_config ?
            ?>

            <script>

                jQuery(window).ready(function() {

                    // if the theme has td_backstr support, it means this already uses it
                    if (typeof window.td_backstr !== 'undefined') {

                        (function(){

                            // the site background td-backstretch jquery object is dynamically added in DOM, and after any translation effects are applied over td-backstretch
                            var wrapper_image_jquery_obj = jQuery('<div class=\'backstretch\'></div>');
                            var image_jquery_obj = jQuery('<img class=\'td-backstretch\' src=\'<?php echo $this->background_parameters['theme_bg_image']; ?>\'>');

                            wrapper_image_jquery_obj.append(image_jquery_obj);

                            jQuery('body').prepend(wrapper_image_jquery_obj);

                            var td_backstr_item = new td_backstr.item();

                            td_backstr_item.wrapper_image_jquery_obj = wrapper_image_jquery_obj;
                            td_backstr_item.image_jquery_obj = image_jquery_obj;

                            td_backstr.add_item(td_backstr_item);

                        })();

                    } else {

                        // - this is the old backstretch jquery plugin call
                        // - td_backstretch.js is in wp_booster, so it is still used by the themes that don't use new td_backstr.js
                        jQuery.backstretch('<?php echo $this->background_parameters['theme_bg_image']; ?>', {fade:1200, centeredY:false});
                    }
                });

            </script>
            <?php
            $buffer = ob_get_clean();
            $js = "\n". td_util::remove_script_tag($buffer);
        } //end if

        return $js;
    }



    /**
     * Adds the boxed layout or full layout classes
     * @param $classes
     * @return array
     */
    function add_slug_to_body_class($classes) {
        if ($this->background_parameters['is_boxed_layout']) {
            $classes[] = 'td-boxed-layout';
        } else {
            $classes[] = 'td-full-layout';
        }
        return $classes;
    }

}
