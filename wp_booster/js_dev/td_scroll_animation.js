"use strict";

/**
 * @todo IT IS NOT IMPLEMENTED FFS :) AND NOT USED IN TD_GLOBAL
 * Created by ra on 6/26/14.
 * - register and keep track of dom elements
 * - calculate position from the top of each element
 * - monitor on scroll event
 * - if one or more of the dom elements is visible
 *   -> calculate element in vie progress 0 (on top of view port there is 1 px visible of the item) - 100 (on bottom of view port is 1 px visible of the item)
 *   -> run the animation for that frame (we may have multiple animations types)
 */


var td_scroll_animation = {

    items: [], //the array that has all the items

    // one item object (instantiable)
    item: function() {
        this.jquery_obj = ''; //find the item easly for animation ??
        this.top_top = 0;     //distance from the top of the element to top of document
        this.bottom_top = 0;  //distance from the bottom of the dom element to top
        this.animate_item_callback = function (progress) { //callback for item animation @param progress 0 - 100 float

        };
    },

    add_item: function(item) {
        td_scroll_animation.items.push(item);
    },


    /**
     * foreach element from items, compute the distances
     */
    compute_top_distances: function() {

        jQuery.each(td_scroll_animation.items, function(index, v_event) {

            // top of document to top of element
            var top_top = td_scroll_animation.items[index].jquery_obj.offset().top;
            td_scroll_animation.items[index].top_top = top_top;

            //top of document to bottom of element
            td_scroll_animation.items[index].bottom_top = top_top + td_scroll_animation.items[index].jquery_obj.height();
        });


        //console.log(td_scroll_animation.items);
    }





};





jQuery('.td-post-image-3d-1').each(function() {
    var td_post_image_3d_item = new td_scroll_animation.item();

    td_post_image_3d_item.jquery_obj = jQuery(this);
    td_post_image_3d_item.animate_item_callback = function (progress) {
        //console.log('Animate item callback ' + progress);
    };

    td_scroll_animation.add_item(td_post_image_3d_item);
});


// @todo - tre sa scoatem on load-ul de pe body ii shitty - inlocuit cu asta
jQuery(window).load(function() {
    td_scroll_animation.compute_top_distances();


});




/*

- monitor on scroll event
- i

 */