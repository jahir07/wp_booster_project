<?php
class td_set_homepage_loop_filter {

    public function __construct()  { }

    /**
     *  setting the array that will be used for homepage filter
     * @return array
     */
    function homepage_filter_get_map () {

        //get the generic filter array
        $generic_filter_array = td_wp_booster_config::get_map_filter_array();

        //remove items from array
        $offset = 0;
        foreach ($generic_filter_array as $field_array) {
            if ($field_array['param_name'] == "hide_title") {
                array_splice($generic_filter_array, $offset, 1);
            }
            $offset++;
        }


        //change the default limit for Featured posts
        $generic_filter_array[6]['value'] = 10;


        //add the show featured posts in the loop setting
        array_push ($generic_filter_array,
            array(
                "param_name" => "show_featured_posts",
                "type" => "dropdown",
                "value" => array('- Show featured posts -' => '', 'Hide featured posts' => 'hide_featured'),
                "heading" => 'Featured posts:',
                "description" => "",
                "holder" => "div",
                "class" => ""
            )
        );



        return array(
            "name" => 'Templates with articles',
            "base" => "",
            "class" => "",
            "controls" => "full",
            "category" => "",
            'icon' => '',
            "params" => $generic_filter_array
        );

    }

}//end class

$obj_td_homepage_filter_add = new td_set_homepage_loop_filter;

//instantiates the filter render object, passing metabox object
$obj_homepage_filter = new td_set_homepage_loop_filter_render($mb);

//call to create the filter
$obj_homepage_filter->td_render_homepage_loop_filter($obj_td_homepage_filter_add->homepage_filter_get_map());