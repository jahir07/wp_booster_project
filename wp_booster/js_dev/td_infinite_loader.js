/**
 * Infinite loader v1.0 by Radu O. / tagDiv
 * USES:
 *  - td_events.js
 *  - for blocks:
 *      - td_block::get_block_pagination - custom load more
 *      - in td_js_generator.php - main block object has ajax_pagination_infinite_stop - to stop the infinit scroll after x number of pages and show the load more button after that
 *
 */
"use strict";

/**
 * - register and keep track of dom elements
 * - calculate position from the top of each element
 * - monitor on scroll event
 *  - if one or more of the dom elements is visible
 *  - fire the callback for that dom element! only ONCE
 */


var td_infinite_loader = {

    has_items: false, // this class will only work when this flag is true. If we don't have any items, all the calculations on scroll will be disabled by this flag

    items: [], //the array that has all the items

    // one item object (instantiable)
    item: function() {
        this.uid=''; // - an unique id of the item, usually is the block id!
        this.jquery_obj = ''; //find the item easily for animation ??
        this.bottom_top = 0;  //distance from the bottom of the dom element to top - computed in - @see td_infinite_loader.compute_top_distances();
        this.is_visible_callback_enabled = true; //the callback will fire only when this flag is true. We set it to true after the blocks ajax run @see td_block_ajax_loading_end
        this.is_visible_callback = function () { //callback when the item's bottom is visible :)

        };
    },

    add_item: function(item) {
        td_infinite_loader.has_items = true; //put the flag that we have items
        td_infinite_loader.items.push(item);
    },


    /**
     * foreach element from items, compute the distances from the top
     *  - this is done only on load or when the page is resized
     */
    compute_top_distances: function compute_top_distances() {

        //check the flag to see if we have any items
        if (td_infinite_loader.has_items === false) {
            return;
        }

        jQuery.each(td_infinite_loader.items, function(index, v_event) {
            var top_top = td_infinite_loader.items[index].jquery_obj.offset().top;
            //top of document to bottom of element
            td_infinite_loader.items[index].bottom_top = top_top + td_infinite_loader.items[index].jquery_obj.height();
        });

        //also calculate the events
        td_infinite_loader.compute_events();

    },


    /**
     * calculate if we have to fire an event like is_visible_callback()
     *  - this is done on scroll and on resize!
     */
    compute_events: function compute_events() {
        //check the flag to see if we have any items
        if (td_infinite_loader.has_items === false) {
            return;
        }

        var top_to_viewport_bottom = jQuery(window).height() + jQuery(window).scrollTop();


        jQuery.each(td_infinite_loader.items, function(index, item) {
            if (td_infinite_loader.items[index].bottom_top < top_to_viewport_bottom + 400) {

                //check to see if we can call the callback again
                if (td_infinite_loader.items[index].is_visible_callback_enabled === true) {
                    td_infinite_loader.items[index].is_visible_callback_enabled = false;
                    //the call
                    td_infinite_loader.items[index].is_visible_callback();
                }
            }


        });
    },


    /**
     * enables the is_visible_callback - it is called by td_blocks.js only when a block receives an infinite loading ajax reply
     * @param $item_uid - an unique id of the item, usually is the block id!
     * @see td_block_ajax_loading_end
     */
    enable_is_visible_callback: function enable_is_visible_callback($item_uid) {
        jQuery.each(td_infinite_loader.items, function(index, item) {
            if (item.uid === $item_uid) {
                td_infinite_loader.items[index].is_visible_callback_enabled = true;
                return false; //brake jquery each
            }
        });
    }

};






/**
 * we are using td_ajax_infinite to know when to trigger a block loading
 */
jQuery('.td_ajax_infinite').each(function() {

    // create a new infinite loader item
    var td_infinite_loader_item = new td_infinite_loader.item();

    td_infinite_loader_item.jquery_obj = jQuery(this);
    td_infinite_loader_item.uid = jQuery(this).data('td_block_id');


    /**
     * the callback when the bottom of the element is visible on screen and we need to do something - like load another page
     * - the callback does not fire again until td_infinite_loader.enable_is_visible_callback is called @see td_infinite_loader.js:95
     */
    td_infinite_loader_item.is_visible_callback = function () {      // the is_visible callback is called when we have to pull new content up because the element is visible

        // get the current block object
        var current_block_obj = td_getBlockObjById(td_infinite_loader_item.jquery_obj.data('td_block_id'));

        // if we don't have a infinite stop limit or if we have one we dint' hit it yet
        if (current_block_obj.ajax_pagination_infinite_stop == '' || current_block_obj.td_current_page < (parseInt(current_block_obj.ajax_pagination_infinite_stop) + 1)) {

            // get the block data and increment the pagination
            current_block_obj.td_current_page++;
            td_ajax_do_block_request(current_block_obj, 'infinite_load');

        } else {
            /**
             * show the load more button. The button is already there, hidden - do not know if it's the best solution :)
             * @see td_block::get_block_pagination  in td_block.php
             */
            setTimeout(function(){
                jQuery('#infinite-lm-' + current_block_obj.id).show();
            }, 400);

        }




    };


    td_infinite_loader.add_item(td_infinite_loader_item);
});


//compute to
jQuery(window).load(function() {
    td_infinite_loader.compute_top_distances();
});

jQuery().ready(function() {
    td_infinite_loader.compute_top_distances();
});
