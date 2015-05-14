/**
 * @depends on:
 * td_util
 * td_events
 * td_affix
 */

"use strict";


var td_smart_sidebar = {
    has_items: false, // this class will only work when this flag is true. If we don't have any items, all the calculations on scroll will be disabled by this flag
    items: [], //the array that has all the items
    scroll_window_scrollTop_last: 0, //last scrollTop position, used to calculate the scroll direction




    tds_snap_menu: td_util.get_backend_var('tds_snap_menu'),   //read the snap menu setting from theme panel


    /**
     * @see td_smart_sidebar.td_events_resize
     */
    is_enabled: true, //if the smart sidebar is not needed (ex on mobile) put this flag to true
    is_enabled_state_run_once: false, // make sure that we dun enable and disable only once
    is_disabled_state_run_once:false,


    is_tablet_grid: false, //we detect if the current grid is the tablet portrait one



    item: function() {
        this.content_jquery_obj = '';
        this.sidebar_jquery_obj = '';


        // the position variables
        this.sidebar_top = 0;
        this.sidebar_bottom = 0;
        this.sidebar_height = 0;



        this.content_top = 0;
        this.content_bottom = 0;

        // the sidebar state
        this.sidebar_state = '';

        this.case_1_run_once = false;
        this.case_2_run_once = false;
        this.case_3_run_once = false;
        this.case_3_last_sidebar_height = 0; // case 3 has to be recalculated if the sidebar height changes
        this.case_3_last_content_height = 0; // recalculate case 3 if content height has changed
        this.case_4_run_once = false;
        this.case_4_last_menu_offset = 0;
        this.case_5_run_once = false;
        this.case_6_run_once = false;

    },





    //add item to the array
    add_item: function add_item(item) {
        td_smart_sidebar.has_items = true; //put the flag that we have items

        /**
         * add clear fix to the content and sidebar.
         * we need the clear fix to clear the margin of the first and last element
         */
        item.sidebar_jquery_obj
            .prepend('<div class="clearfix"></div>')
            .append('<div class="clearfix"></div>');

        item.content_jquery_obj
            .prepend('<div class="clearfix"></div>')
            .append('<div class="clearfix"></div>');


        td_smart_sidebar.items.push(item);


    },



    td_events_scroll: function td_events_scroll(scrollTop) {


        // we don't have any smart sidebars, return
        if (td_smart_sidebar.has_items === false) {
            return;
        }


        // check if the smart sidebar is enabled ( the sidebar can be enabled / disabled on runtime )
        if (td_smart_sidebar.is_enabled == false) {

            if (td_smart_sidebar.is_disabled_state_run_once === false) { // this call runs only ONCE / state change - we don't want any code to run on mobile
                td_smart_sidebar.is_disabled_state_run_once = true;
                for (var item_index = 0; item_index < td_smart_sidebar.items.length; item_index++) {
                    td_smart_sidebar.items[item_index].sidebar_jquery_obj.css({
                        "width": "auto",
                        "position": "static",
                        "top": "auto",
                        "bottom": "auto"
                    });
                }
                td_smart_sidebar.log('smart_sidebar_disabled');
            }

            return;
        }



        // all is done in an animation frame
        window.requestAnimationFrame(function() {




            /**
             * this is the height of the menu, computed live. We
             * @type {number}
             */
            var td_affix_menu_computed_height = 0;
            if (td_smart_sidebar.tds_snap_menu != '') { // if the menu is not snapping in any way - do not calculate this
                // we cannot get the live offset because it's running in a requestAnimationFrame ~ probably async
                td_affix_menu_computed_height = td_affix.main_menu_height + td_affix.menu_offset;
            }




            // compute the scrolling direction
            var scroll_direction = '';
            //check the direction
            if (scrollTop != td_smart_sidebar.scroll_window_scrollTop_last) { // compute direction only if we have different last scroll top
                // compute the direction of the scroll
                if (scrollTop > td_smart_sidebar.scroll_window_scrollTop_last) {
                    scroll_direction = 'down';
                } else {
                    scroll_direction = 'up';
                }
            }
            td_smart_sidebar.scroll_window_scrollTop_last = scrollTop;



            /**
             * scrollTop - is the distance that is scrolled from the top of the document PLUS the height of the menu
             */
            scrollTop = scrollTop + td_affix_menu_computed_height;



            var view_port_height = jQuery(window).height(); // ~ we can get this only once + on resize
            var view_port_bottom = scrollTop + view_port_height;


            // go in all the sidebar items
            for (var item_index = 0; item_index < td_smart_sidebar.items.length; item_index++) {

                var cur_item_ref = td_smart_sidebar.items[item_index];

                cur_item_ref.content_top = cur_item_ref.content_jquery_obj.offset().top;
                cur_item_ref.content_height = cur_item_ref.content_jquery_obj.height();
                cur_item_ref.content_bottom = cur_item_ref.content_top + cur_item_ref.content_height;

                cur_item_ref.sidebar_top = cur_item_ref.sidebar_jquery_obj.offset().top;
                cur_item_ref.sidebar_height = cur_item_ref.sidebar_jquery_obj.height();
                cur_item_ref.sidebar_bottom = cur_item_ref.sidebar_top + cur_item_ref.sidebar_height;





                /**
                 * Is the sidebar smaller than the content ?
                 */
                if (cur_item_ref.content_height <= cur_item_ref.sidebar_height) {
                    cur_item_ref.sidebar_state = 'case_6_content_too_small';



                /**
                 * the sidebar is smaller than the view port?  that means that we have to switch to a more simpler sidebar AKA affix
                */

                } else if (cur_item_ref.sidebar_height < view_port_height) {

                    if (td_smart_sidebar._is_smaller_or_equal(scrollTop, cur_item_ref.content_top)) {
                        // not affix - we did not scroll to reach the sidebar
                        cur_item_ref.sidebar_state = 'case_2_top_of_content';
                    }

                    // [1] if the sidebar is visible and we have enought space in the sidebar, place it at the top affix top
                    // [2] if the sidebar is above the view port and nothing is visible, place the sidebar at the bottom of the column

                    else if (td_smart_sidebar._is_smaller(cur_item_ref.sidebar_bottom, scrollTop) === true) {
                        if (td_smart_sidebar._is_smaller(scrollTop, cur_item_ref.content_bottom - cur_item_ref.sidebar_height)) { //this is a special case where on the initial load, the bottom of the content is visible and we have a lot of space to show the widget at the top affixed.
                            cur_item_ref.sidebar_state = 'case_4_fixed_up'; // [1]
                        } else {
                            cur_item_ref.sidebar_state = 'case_3_bottom_of_content'; // [2]
                        }


                    } else {

                        // affix
                        if (td_smart_sidebar._is_smaller_or_equal(cur_item_ref.content_bottom, cur_item_ref.sidebar_bottom)) { // check to see if we reached the bottom of the content / row
                            if (scroll_direction == 'up' && td_smart_sidebar._is_smaller_or_equal(scrollTop, cur_item_ref.sidebar_top)) {
                                cur_item_ref.sidebar_state = 'case_4_fixed_up'; // get out of the case_3_bottom_of_content state
                            } else {
                                cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                            }

                        } else {
                            if (cur_item_ref.content_bottom - scrollTop >= cur_item_ref.sidebar_height) {
                                // Make sure that we have space for the sidebar to affix it to the top
                                cur_item_ref.sidebar_state = 'case_4_fixed_up';  // we are not at the bottom of the content
                            } else {
                                cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                            }
                            //console.log(cur_item_ref.content_bottom + ' >= ' +  cur_item_ref.sidebar_bottom); //@todo fix this case pe http://0div.com:69/wp_marius/wp_010/homepage-with-a-post-featured/

                        }

                    }





                /**
                 * the sidebar is larger than the view port and the content is bigger
                 */


                } else {

                    // if the sidebar is above the view port and nothing is visible, place the sidebar at the bottom of the column
                    if (td_smart_sidebar._is_smaller(cur_item_ref.sidebar_bottom, scrollTop) === true) {
                        cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                    }


                    // position:fixed; bottom:0
                    else if (
                        td_smart_sidebar._is_smaller(cur_item_ref.sidebar_bottom, view_port_bottom) === true &&
                        td_smart_sidebar._is_smaller(cur_item_ref.sidebar_bottom, cur_item_ref.content_bottom) === true &&
                        scroll_direction == 'down' &&
                        cur_item_ref.content_bottom >= view_port_bottom
                    ) {
                        //console.log(cur_item_ref.sidebar_bottom + ' < ' + cur_item_ref.content_bottom);
                        cur_item_ref.sidebar_state = 'case_1_fixed_down';
                    }

                    // the sidebar is at the top of the content ( position:static )
                    else if (
                        td_smart_sidebar._is_smaller_or_equal(cur_item_ref.sidebar_top, cur_item_ref.content_top) === true &&
                        scroll_direction == 'up' &&
                        cur_item_ref.content_bottom >= view_port_bottom
                    ) {
                        cur_item_ref.sidebar_state = 'case_2_top_of_content';
                    }




                    // the sidebar reached the bottom of the content
                    else if (
                        (td_smart_sidebar._is_smaller_or_equal(cur_item_ref.content_bottom, cur_item_ref.sidebar_bottom) === true && scroll_direction == 'down')
                        ||
                        cur_item_ref.content_bottom < view_port_bottom

                    ) {
                        cur_item_ref.sidebar_state = 'case_3_bottom_of_content';

                    }
                    // scrolling up, the sidebar is fixed up ( position:fixed; top:0 )
                    else if (td_smart_sidebar._is_smaller_or_equal(scrollTop, cur_item_ref.sidebar_top) === true && scroll_direction == 'up'

                    && td_smart_sidebar._is_smaller_or_equal(cur_item_ref.content_top, scrollTop) === true //we are scrolling up ... make sure that we don't overshoot the sidebar by going over content_top. This happens when the sidebar is offseted by x number of pixels vs content
                    ) {
                        //console.log('sidebar_top' + cur_item_ref.sidebar_top + ' content top:' + cur_item_ref.content_top);
                        cur_item_ref.sidebar_state = 'case_4_fixed_up';
                    }



                    // when to put absolute?
                    if (
                        (cur_item_ref.sidebar_state == 'case_1_fixed_down' && scroll_direction == 'up') ||
                        (cur_item_ref.sidebar_state == 'case_4_fixed_up' && scroll_direction == 'down')
                    ) {
                        cur_item_ref.sidebar_state = 'case_5_absolute'; //absolute while going up?
                    }

                } // end sidebar length check   cur_item_ref.sidebar_height < view_port_height







                /**
                 * after we have the state, we enter this switch that makes sure that we only have one state change
                 */

                // we have to set the content width via JS
                var column_content_width = 339;
                if (td_smart_sidebar.is_tablet_grid) {
                    column_content_width = 251;
                }



                switch (cur_item_ref.sidebar_state) {
                    case 'case_1_fixed_down':

                        if (cur_item_ref.case_1_run_once === true) {
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = true;
                        cur_item_ref.case_2_run_once = false;
                        cur_item_ref.case_3_run_once = false;
                        cur_item_ref.case_4_run_once = false;
                        cur_item_ref.case_5_run_once = false;
                        cur_item_ref.case_6_run_once = false;


                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": column_content_width,
                            "position": "fixed",
                            "top": "auto",
                            "bottom": "0",
                            "z-index": "1"
                        });


                        break;

                    case 'case_2_top_of_content':

                        if (cur_item_ref.case_2_run_once === true) {
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = false;
                        cur_item_ref.case_2_run_once = true;
                        cur_item_ref.case_3_run_once = false;
                        cur_item_ref.case_4_run_once = false;
                        cur_item_ref.case_5_run_once = false;
                        cur_item_ref.case_6_run_once = false;


                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": "auto",
                            "position": "static",
                            "top": "auto",
                            "bottom": "auto"
                        });
                        break;

                    case 'case_3_bottom_of_content':
                        // case 3 has to be recalculated if the sidebar height changes

                        if (cur_item_ref.case_3_run_once === true &&
                            cur_item_ref.case_3_last_sidebar_height == cur_item_ref.sidebar_height &&
                            cur_item_ref.case_3_last_content_height == cur_item_ref.content_height
                        ) { //if the case already runned AND the sidebar height did not change
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = false;
                        cur_item_ref.case_2_run_once = false;
                        cur_item_ref.case_3_run_once = true;
                        cur_item_ref.case_3_last_sidebar_height = cur_item_ref.sidebar_height;
                        cur_item_ref.case_3_last_content_height = cur_item_ref.content_height;
                        cur_item_ref.case_4_run_once = false;
                        cur_item_ref.case_5_run_once = false;
                        cur_item_ref.case_6_run_once = false;


                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": column_content_width,
                            "position": "absolute",
                            "top": cur_item_ref.content_bottom - cur_item_ref.sidebar_height - cur_item_ref.content_top,
                            "bottom": "auto"
                        });
                        break;

                    case 'case_4_fixed_up':

                        if (cur_item_ref.case_4_run_once === true && cur_item_ref.case_4_last_menu_offset == td_affix_menu_computed_height) { //if the case already runned AND the menu height did not changed
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = false;
                        cur_item_ref.case_2_run_once = false;
                        cur_item_ref.case_3_run_once = false;
                        cur_item_ref.case_4_run_once = true;
                        cur_item_ref.case_4_last_menu_offset = td_affix_menu_computed_height;
                        cur_item_ref.case_5_run_once = false;
                        cur_item_ref.case_6_run_once = false;


                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": column_content_width,
                            "position": "fixed",
                            "top": td_affix_menu_computed_height,
                            "bottom": "auto"
                        });
                        break;

                    case 'case_5_absolute':

                        if (cur_item_ref.case_5_run_once === true) {
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = false;
                        cur_item_ref.case_2_run_once = false;
                        cur_item_ref.case_3_run_once = false;
                        cur_item_ref.case_4_run_once = false;
                        cur_item_ref.case_5_run_once = true;
                        cur_item_ref.case_6_run_once = false;


                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": column_content_width,
                            "position": "absolute",
                            "top": cur_item_ref.sidebar_top - cur_item_ref.content_top,
                            "bottom": "auto"
                        });
                        break;


                    case 'case_6_content_too_small':

                        if (cur_item_ref.case_6_run_once === true) {
                            break;
                        }

                        td_smart_sidebar.log('sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state);

                        cur_item_ref.case_1_run_once = false;
                        cur_item_ref.case_2_run_once = false;
                        cur_item_ref.case_3_run_once = false;
                        cur_item_ref.case_4_run_once = false;
                        cur_item_ref.case_5_run_once = false;
                        cur_item_ref.case_6_run_once = true;

                        cur_item_ref.sidebar_jquery_obj.css({
                            "width": "auto",
                            "position": "static",
                            "top": "auto",
                            "bottom": "auto"
                        });
                        break;


                }


            } // end for loop


        }); // end request animation frame
    }, // end td_events_scroll


    compute: function conpute() {

        td_smart_sidebar.td_events_scroll(jQuery(window).scrollTop());
    },


    // resets the run once flags. It may fail sometimes due to case_3_last_sidebar_height & case_4_last_menu_offset
    reset_run_once_flags: function () {
        for (var item_index = 0; item_index < td_smart_sidebar.items.length; item_index++) {
            td_smart_sidebar.items[item_index].case_1_run_once = false;
            td_smart_sidebar.items[item_index].case_2_run_once = false;
            td_smart_sidebar.items[item_index].case_3_run_once = false;
            td_smart_sidebar.items[item_index].case_3_last_sidebar_height = 0;
            td_smart_sidebar.items[item_index].case_3_last_content_height = 0;
            td_smart_sidebar.items[item_index].case_4_run_once = false;
            td_smart_sidebar.items[item_index].case_4_last_menu_offset = 0;
            td_smart_sidebar.items[item_index].case_5_run_once = false;
            td_smart_sidebar.items[item_index].case_6_run_once = false;
        }
    },



    td_events_resize: function td_events_resize() {
        // enable and disable the smart sidebar


        //var real_view_port_width = 0;
        //
        //if (td_detect.is_safari === true) {
        //    real_view_port_width = td_safari_view_port_width.get_real_width();
        //} else {
        //    // not safari
        //    real_view_port_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        //}




        /*
        jQuery('<div>')
            .css('position', "absolute")
            .appendTo('body');


        console.log(window.clientWidth);

        console.log(real_view_port_width);
        */

        //if (real_view_port_width > 767 && real_view_port_width <= 1023) {
        //    // we are on tablet
        //    if (td_smart_sidebar.is_tablet_grid === false) { // we switched
        //        td_smart_sidebar.reset_run_once_flags();
        //
        //        td_smart_sidebar.is_tablet_grid = true;
        //        td_smart_sidebar.log('view port tablet');
        //    }
        //
        //
        //} else if (real_view_port_width > 1023) {
        //    // we are on desktop
        //    if (td_smart_sidebar.is_tablet_grid === true) {
        //        td_smart_sidebar.reset_run_once_flags();
        //
        //        td_smart_sidebar.is_tablet_grid = false;
        //
        //        td_smart_sidebar.log('view port desktop');
        //    }
        //
        //}
        //
        //
        //// check to see if we are mobile grid / AKA disable the sidebar
        //if (real_view_port_width <= 767) {
        //    // disable the sidebar
        //    td_smart_sidebar.is_enabled_state_run_once = false;
        //    td_smart_sidebar.is_enabled = false;
        //
        //} else {
        //    // enable the sidebar
        //    td_smart_sidebar.is_disabled_state_run_once = false;
        //    if (td_smart_sidebar.is_enabled_state_run_once === false) {
        //        td_smart_sidebar.is_enabled_state_run_once = true;
        //        td_smart_sidebar.log('smart_sidebar_enabled');
        //        td_smart_sidebar.is_enabled = true;
        //    }
        //
        //}




        //if (real_view_port_width > 1023) {
        //
        //    // we are on desktop
        //    if (td_smart_sidebar.is_tablet_grid === true) { // we switched
        //
        //        td_smart_sidebar.reset_run_once_flags();
        //        td_smart_sidebar.is_tablet_grid = false;
        //
        //        td_smart_sidebar.log('view port desktop');
        //    }
        //
        //    td_smart_sidebar.is_enabled = true;
        //
        //    td_smart_sidebar.is_disabled_state_run_once = false;
        //
        //}
        //else if (real_view_port_width > 767 && real_view_port_width <= 1023) {
        //
        //    // we are on tablet
        //    if (td_smart_sidebar.is_tablet_grid === false) { // we switched
        //
        //        td_smart_sidebar.reset_run_once_flags();
        //        td_smart_sidebar.is_tablet_grid = true;
        //
        //        td_smart_sidebar.log('view port tablet');
        //    }
        //
        //    td_smart_sidebar.is_enabled = true;
        //    td_smart_sidebar.is_disabled_state_run_once = false;
        //
        //} else { // check to see if we are mobile grid / AKA disable the sidebar
        //
        //    // disable the sidebar
        //    td_smart_sidebar.is_enabled = false;
        //
        //}



        switch (td_viewport.view_port_flag) {

            case 0 :

                td_smart_sidebar.is_enabled = false;
                td_smart_sidebar.is_enabled_state_run_once = false;

                break;

            case 1 :

                if (td_smart_sidebar.is_tablet_grid === false) { // we switched

                    td_smart_sidebar.reset_run_once_flags();
                    td_smart_sidebar.is_tablet_grid = true;
                    td_smart_sidebar.log('view port tablet');
                }
                td_smart_sidebar.is_enabled = true;
                td_smart_sidebar.is_disabled_state_run_once = false;

                if (td_smart_sidebar.is_enabled_state_run_once === false) {
                    td_smart_sidebar.is_enabled_state_run_once = true;
                    td_smart_sidebar.log('smart_sidebar_enabled');
                }

                break;

            case 2 :
            case 3 :
                if (td_smart_sidebar.is_tablet_grid === true) { // we switched

                    td_smart_sidebar.reset_run_once_flags();
                    td_smart_sidebar.is_tablet_grid = false;
                    td_smart_sidebar.log('view port desktop');
                }
                td_smart_sidebar.is_enabled = true;
                td_smart_sidebar.is_disabled_state_run_once = false;

                if (td_smart_sidebar.is_enabled_state_run_once === false) {
                    td_smart_sidebar.is_enabled_state_run_once = true;
                    td_smart_sidebar.log('smart_sidebar_enabled');
                }

                break;
        }


        // @todo we may be able to delay the compute a bit (aka run it on the 500ms timer)
        td_smart_sidebar.compute();
    },





    log: function log(msg) {
        console.log(msg);
    },





    /**
     * check if the two numbers are approximately equal OR the number1 is smaller.
     * This function is used to compensate for differences in the offset top reported by IE, FF but not chrome
     * IE and FF have an error for offset top of +- 0.5
     * @param number1 - this has to be smaller or approximately equal with number2 to return true
     * @param number2
     * @returns {boolean}
     * @private
     */
    _is_smaller_or_equal: function _is_smaller_or_equal(number1, number2) {
        // check if the two numbers are approximately equal
        // - first we check if the difference between the numbers is bigger than 1 unit
        // - second we check if the first number is bigger than the second one
        // if the two conditions are met, we return false


        if ( Math.abs(number1 - number2) >= 1) {
            // we have a difference that is bigger than 1 unit (px), check if the numbers are smaller or bigger
            if (number1 < number2) {
                return true;
            } else {
                return false;
            }


        } else {
            // the difference between the two numbers is smaller than one unit (1 px), this means that the two numbers are the same
            return true;
        }
    },


    /**
     * Checks to see if number1 < number2 by at least one unit!
     * @param number1
     * @param number2
     * @returns {boolean}
     * @private
     */
    _is_smaller: function _is_smaller (number1, number2) {
        if ( Math.abs(number1 - number2) >= 1) {
            if (number1 < number2) {
                return true;
            } else {
                return false;
            }
        } else {
            // the difference between the two numbers is smaller than one unit (1 px), this means that the two numbers are the same
            return false;
        }
    }





};









//console.log(td_smart_sidebar.items);
