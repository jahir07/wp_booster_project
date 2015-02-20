<?php

/**
 * Class td_block_widget - used to create widgets from our blocks
 */



class td_block_widget extends WP_Widget {
    var $td_widget_builder;

    var $td_block_id = 0; // this is changed by td_blockx_widget s

    function __construct() {
        $this->td_widget_builder = new td_widget_builder($this);
        //get block map
        $this->td_widget_builder->td_map(td_api_block::get_by_id($this->td_block_id));
    }

    function form($instance) {
        $this->td_widget_builder->form($instance);
    }

    function update($new_instance, $old_instance) {
        return $this->td_widget_builder->update($new_instance, $old_instance);
    }

    function widget($args, $instance) {
        /**
          * add the td_block_widget class to the block via the short code atts, we can add td_block_widget multiple times because array_unique in  @see td_block::get_block_classes
         */
        if (!empty($instance['class'])) {
            $instance['class'] =  $instance['class'] . ' td_block_widget';
        } else {
            $instance['class'] = 'td_block_widget';
        }

        if (!empty($instance['content'])) {
            //render the instance - but also send the content parameter to the shortcode
            echo td_global_blocks::get_instance($this->td_block_id)->render($instance, $instance['content']);
        } else {
            //render the instance without the content parameter
            echo td_global_blocks::get_instance($this->td_block_id)->render($instance);
        }


    }
}
