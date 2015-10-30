/**
 * Infinite loader v1.0 by Radu O. / tagDiv
 * USES:
 *  - tdEvents.js
 *  - for blocks:
 *      - td_block::get_block_pagination - custom load more
 *      - in td_js_generator.php - main block object has ajax_pagination_infinite_stop - to stop the infinite scroll after x number of pages and show the load more button after that
 *
 */

/* global jQuery:false */
/* global tdBlocks:false */


/**
 * Global infinite loader object
 */
var tdInfiniteLoader = {};

(function () {
    "use strict";

    /**
     * - register and keep track of dom elements
     * - calculate position from the top of each element
     * - monitor on scroll event
     *  - if one or more of the dom elements is visible
     *  - fire the callback for that dom element! only ONCE
     */


    tdInfiniteLoader = {

        hasItems: false, // this class will only work when this flag is true. If we don't have any items, all the calculations on scroll will be disabled by this flag

        items: [], //the array that has all the items

        // one item object (instantiable)
        item: function() {
            this.uid=''; // - an unique id of the item, usually is the block id! - it is used to enable the callback on a per item basis
            this.jqueryObj = ''; //find the item easily for animation ??
            this.bottomTop = 0;  //distance from the bottom of the dom element to top - computed in - @see tdInfiniteLoader.compute_top_distances();
            this.isVisibleCallbackEnabled = true; //the callback will fire only when this flag is true. We set it to true after the blocks ajax run @see tdBlocks.tdBlockAjaxLoadingEnd
            this.isVisibleCallback = function () { //callback when the item's bottom is visible :)
            };
        },

        addItem: function(item) {
            tdInfiniteLoader.hasItems = true; //put the flag that we have items
            tdInfiniteLoader.items.push(item);
        },


        /**
         * foreach element from items, compute the distances from the top
         *  - this is done only on load or when the page is resized
         */
        computeTopDistances: function() {

            //check the flag to see if we have any items
            if ( tdInfiniteLoader.hasItems === false ) {
                return;
            }

            jQuery.each(tdInfiniteLoader.items, function(index, v_event) {
                var topTop = tdInfiniteLoader.items[index].jqueryObj.offset().top;
                //top of document to bottom of element
                tdInfiniteLoader.items[index].bottomTop = topTop + tdInfiniteLoader.items[index].jqueryObj.height();
            });

            //also calculate the events
            tdInfiniteLoader.computeEvents();

        },


        /**
         * calculate if we have to fire an event like isVisibleCallback()
         *  - this is done on scroll and on resize!
         */
        computeEvents: function() {
            //check the flag to see if we have any items
            if ( tdInfiniteLoader.hasItems === false ) {
                return;
            }

            var topToViewportBottom = jQuery(window).height() + jQuery(window).scrollTop();


            jQuery.each(tdInfiniteLoader.items, function(index, item) {
                if ( tdInfiniteLoader.items[index].bottomTop < topToViewportBottom + 700 ) {

                    //check to see if we can call the callback again
                    if ( tdInfiniteLoader.items[index].isVisibleCallbackEnabled === true ) {
                        tdInfiniteLoader.items[index].isVisibleCallbackEnabled = false;
                        //the call
                        tdInfiniteLoader.items[index].isVisibleCallback();
                    }
                }


            });
        },


        /**
         * enables the isVisibleCallback - it is called by td_blocks.js only when a block receives an infinite loading ajax reply
         * @param $item_uid - an unique id of the item, usually is the block id!
         * @see tdBlocks.tdBlockAjaxLoadingEnd
         */
        enable_is_visible_callback: function($item_uid) {
            jQuery.each(tdInfiniteLoader.items, function(index, item) {
                if ( item.uid === $item_uid ) {
                    tdInfiniteLoader.items[index].isVisibleCallbackEnabled = true;
                    return false; //brake jquery each
                }
            });
        }

    };






    /**
     * we are using td_ajax_infinite to know when to trigger a block loading
     */
    jQuery('.td_ajax_infinite').each( function() {

        // create a new infinite loader item
        var tdInfiniteLoaderItem = new tdInfiniteLoader.item();

        tdInfiniteLoaderItem.jqueryObj = jQuery(this);
        tdInfiniteLoaderItem.uid = jQuery(this).data('td_block_id');


        /**
         * the callback when the bottom of the element is visible on screen and we need to do something - like load another page
         * - the callback does not fire again until tdInfiniteLoader.enable_is_visible_callback is called @see tdInfiniteLoader.js:95
         */
        tdInfiniteLoaderItem.isVisibleCallback = function () {      // the is_visible callback is called when we have to pull new content up because the element is visible

            // get the current block object
            var currentBlockObj = tdBlocks.tdGetBlockObjById(tdInfiniteLoaderItem.jqueryObj.data('td_block_id'));

            // if we don't have a infinite stop limit or if we have one we dint' hit it yet
            if ( currentBlockObj.ajax_pagination_infinite_stop === '' ||
                    currentBlockObj.td_current_page < (parseInt(currentBlockObj.ajax_pagination_infinite_stop) + 1) ) {

                // get the block data and increment the pagination
                currentBlockObj.td_current_page++;
                tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'infinite_load');

            } else {
                /**
                 * show the load more button. The button is already there, hidden - do not know if it's the best solution :)
                 * @see td_block::get_block_pagination  in td_block.php
                 */
                if ( currentBlockObj.td_current_page < currentBlockObj.max_num_pages ) {
                    setTimeout( function(){
                        jQuery('#infinite-lm-' + currentBlockObj.id)
                            .css('display', 'block')
                            .css('visibility', 'visible')
                        ;
                    }, 400);
                }
            }
        };
        tdInfiniteLoader.addItem(tdInfiniteLoaderItem);
    });








    //compute to
    jQuery(window).load( function() {
        tdInfiniteLoader.computeTopDistances();
    });

    jQuery().ready( function() {
        tdInfiniteLoader.computeTopDistances();
    });
})();