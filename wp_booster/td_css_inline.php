<?php

/**
 * Used to render inline css easy
 * Class td_css_inline
 */
class td_css_inline {
    private $buffy_array = array();


    /**
     * adds an inline css style
     * @param $css_array
     *
     *  array (
     *      'background-color' => 'red',
     *      'etc..'
     *  )
     *
     */
    public function add_css($css_array) {
        $this->buffy_array = array_merge($this->buffy_array, $css_array);
    }


    /**
     * returns the inline css, must be called in the atts section of a HTML tag ex: <div <?php echo $td_css_inline->get_inline_css() ?> class="test">
     * @param bool $wrap_in_style_att
     * @return string
     */
    public function get_inline_css($wrap_in_style_att = true) {

        if (empty($this->buffy_array)) {
            return '';
        }

        $buffy = '';
        foreach ($this->buffy_array as $css_property => $css_property_value) {
            if (empty($buffy)) {
                $buffy = $css_property . ':' . $css_property_value;
            } else {
                $buffy .= ';' . $css_property . ':' . $css_property_value;
            }
        }

        $buffy = trim($buffy);


        if (!empty($buffy) and $wrap_in_style_att === true) {
            $buffy = 'style="' . $buffy . '"';
        }


        return $buffy;
    }
}