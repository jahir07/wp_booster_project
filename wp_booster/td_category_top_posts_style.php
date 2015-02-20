<?php

abstract class td_category_top_posts_style {

    private $rendered_block_buffer = '';
    private $rendered_posts_count = 0;


    protected function render_posts_to_buffer() {
        //parameters to filter to for big grid
        $atts_for_big_grid = array(
            'limit' => td_api_category_top_posts_style::_helper_get_posts_shown_in_the_loop(),
            'category_id' => td_global::$current_category_obj->cat_ID,
            'sort' => get_query_var('filter_by')
        );


        //show the big grid

        $block_name = td_api_category_top_posts_style::get_key(get_class($this), 'td_block_name');
        $this->rendered_block_buffer = td_global_blocks::get_instance($block_name)->render($atts_for_big_grid);
        $this->rendered_posts_count = td_global_blocks::get_instance($block_name)->td_query->post_count;

        if ($this->rendered_posts_count > 0) {
            td_global::$custom_no_posts_message = false;
        }
        // use class_name($this) to get the id :)
    }



    protected function get_buffer() {
        return $this->rendered_block_buffer;
    }


    protected function get_rendered_post_count() {
        return $this->rendered_posts_count;
    }



}