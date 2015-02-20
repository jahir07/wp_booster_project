<?php
//handles background click ad
class td_tagdiv_ads {

    //holds the array that comes from the database, for the background click ad
    private $background_click_td_option_array;

    function __construct() {
        $this->background_click_td_option_array =  td_util::get_td_ads('background_click');



        //adds the javascript variables with background click options
        if(!empty($this->background_click_td_option_array['background_click']['link'])) {
            td_js_buffer::add_variable('td_ad_background_click_link', $this->background_click_td_option_array['background_click']['link']);
        } else {
            //add empty variables to prevent errors in js (js dosn't check for undefined ! ) @todo fix this
            td_js_buffer::add_variable('td_ad_background_click_link', '');
        }



        //adds the javascript variables with background click options
        if(!empty($this->background_click_td_option_array['background_click']['target'])) {
            td_js_buffer::add_variable('td_ad_background_click_target', $this->background_click_td_option_array['background_click']['target']);
        } else {
            //add empty variables to prevent errors in js (js dosn't check for undefined ! ) @todo fix this
            td_js_buffer::add_variable('td_ad_background_click_target', '');
        }
    }

}//end class

//instanciate the class (only run the constructor)
new td_tagdiv_ads();
