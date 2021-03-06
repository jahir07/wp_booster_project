/**
 * @depends on:
 * td_util
 * td_events
 * tdAffix
 */

/* global jQuery:{} */
/* global tdUtil:{} */
/* global tdViewport:{} */
/* global tdAffix:{} */


var tdSmartSidebar = {};

(function(){

    'use strict';

    tdSmartSidebar = {
        hasItems: false, // this class will only work when this flag is true. If we don't have any items, all the calculations on scroll will be disabled by this flag
        items: [], //the array that has all the items
        scroll_window_scrollTop_last: 0, //last scrollTop position, used to calculate the scroll direction


        tds_snap_menu: tdUtil.getBackendVar( 'tds_snap_menu' ),   //read the snap menu setting from theme panel


        /**
         * @see tdSmartSidebar.td_events_resize
         */
        is_enabled: true, //if the smart sidebar is not needed (ex on mobile) put this flag to true
        is_enabled_state_run_once: false, // make sure that we dun enable and disable only once
        is_disabled_state_run_once: false,


        is_tablet_grid: false, //we detect if the current grid is the tablet portrait one


        _view_port_current_interval_index: tdViewport.getCurrentIntervalIndex(),


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
        add_item: function( item ) {
            tdSmartSidebar.hasItems = true; //put the flag that we have items

            /**
             * add clear fix to the content and sidebar.
             * we need the clear fix to clear the margin of the first and last element
             */
            item.sidebar_jquery_obj
                .prepend( '<div class="clearfix"></div>' )
                .append( '<div class="clearfix"></div>' );

            item.content_jquery_obj
                .prepend( '<div class="clearfix"></div>' )
                .append( '<div class="clearfix"></div>' );


            tdSmartSidebar.items.push( item );
        },


        td_events_scroll: function( scrollTop ) {


            // we don't have any smart sidebars, return
            if ( false === tdSmartSidebar.hasItems ) {
                return;
            }


            // check if the smart sidebar is enabled ( the sidebar can be enabled / disabled on runtime )
            if ( false === tdSmartSidebar.is_enabled ) {

                if ( false === tdSmartSidebar.is_disabled_state_run_once ) { // this call runs only ONCE / state change - we don't want any code to run on mobile
                    tdSmartSidebar.is_disabled_state_run_once = true;
                    for ( var item_index = 0; item_index < tdSmartSidebar.items.length; item_index++ ) {
                        tdSmartSidebar.items[ item_index ].sidebar_jquery_obj.css({
                            width: 'auto',
                            position: 'static',
                            top: 'auto',
                            bottom: 'auto'
                        });
                    }
                    tdSmartSidebar.log( 'smart_sidebar_disabled' );
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
                if ( '' !== tdSmartSidebar.tds_snap_menu ) { // if the menu is not snapping in any way - do not calculate this

                    // The main_menu_height was replaced with the _get_menu_affix_height(), because we need the size of the
                    // affix menu. In the 'Newspaper' the menu has different sizes when it is affix 'on' and 'off'.
                    td_affix_menu_computed_height = tdAffix._get_menu_affix_height();

                    // Menu offset value is added when we are on 'smart_snap_always' case
                    if ('smart_snap_always' === tdAffix.tds_snap_menu) {
                        td_affix_menu_computed_height += tdAffix.menu_offset;
                    }
                }
                // The following height is added just for Newspaper theme.
                // In the Newsmag theme, the sidebar elements have already a 'padding-top' of 20px

                if ( ( 'undefined' !== typeof window.tdThemeName ) && ( 'Newspaper' === window.tdThemeName ) ) {
                    td_affix_menu_computed_height += 20;
                }





                // compute the scrolling direction
                var scroll_direction = '';
                //check the direction
                if ( scrollTop !== tdSmartSidebar.scroll_window_scrollTop_last ) { // compute direction only if we have different last scroll top
                    // compute the direction of the scroll
                    if ( scrollTop > tdSmartSidebar.scroll_window_scrollTop_last ) {
                        scroll_direction = 'down';
                    } else {
                        scroll_direction = 'up';
                    }
                }
                tdSmartSidebar.scroll_window_scrollTop_last = scrollTop;



                /**
                 * scrollTop - is the distance that is scrolled from the top of the document PLUS the height of the menu
                 */



                var view_port_height = jQuery( window ).height(); // ~ we can get this only once + on resize
                var view_port_bottom = scrollTop + view_port_height;

                scrollTop = scrollTop + td_affix_menu_computed_height;

                // go in all the sidebar items
                for ( var item_index = 0; item_index < tdSmartSidebar.items.length; item_index++ ) {

                    var cur_item_ref = tdSmartSidebar.items[ item_index ];

                    cur_item_ref.content_top = cur_item_ref.content_jquery_obj.offset().top;
                    cur_item_ref.content_height = cur_item_ref.content_jquery_obj.height();
                    cur_item_ref.content_bottom = cur_item_ref.content_top + cur_item_ref.content_height;

                    cur_item_ref.sidebar_top = cur_item_ref.sidebar_jquery_obj.offset().top;
                    cur_item_ref.sidebar_height = cur_item_ref.sidebar_jquery_obj.height();
                    cur_item_ref.sidebar_bottom = cur_item_ref.sidebar_top + cur_item_ref.sidebar_height;





                    /**
                     * Is the sidebar smaller than the content ?
                     */
                    if ( cur_item_ref.content_height <= cur_item_ref.sidebar_height ) {
                        cur_item_ref.sidebar_state = 'case_6_content_too_small';



                        /**
                         * the sidebar is smaller than the view port?  that means that we have to switch to a more simpler sidebar AKA affix
                         */

                    } else if ( cur_item_ref.sidebar_height < view_port_height ) {

                        // ref value used to compare the scroll top
                        var ref_value = cur_item_ref.content_top;

                        // For 'Newsmag' the ref value is incremented with td_affix_menu_computed_height
                        // It solves a case when the affix menu leaves the 'case_2_top_of_content' phase to 'case_4_fixed_up' too early
                        // It's because of how the grid, and smart sidebar, are built on Newspaper vs Newsmag
                        if ( ! tdAffix.is_menu_affix && ( 'undefined' !== typeof window.tdThemeName ) && ( 'Newsmag' === window.tdThemeName ) && ( 'smart_snap_always' === tdAffix.tds_snap_menu ) ) {
                            ref_value += td_affix_menu_computed_height;
                        }

                        //if (tdSmartSidebar._is_smaller_or_equal(scrollTop, cur_item_ref.content_top)) {
                        if ( tdSmartSidebar._is_smaller_or_equal( scrollTop, ref_value ) ) {
                            // not affix - we did not scroll to reach the sidebar
                            cur_item_ref.sidebar_state = 'case_2_top_of_content';
                        }

                        // [1] if the sidebar is visible and we have enough space in the sidebar, place it at the top affix top
                        // [2] if the sidebar is above the view port and nothing is visible, place the sidebar at the bottom of the column

                        else if ( true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, scrollTop ) ) {
                            if ( tdSmartSidebar._is_smaller( scrollTop, cur_item_ref.content_bottom - cur_item_ref.sidebar_height ) ) { //this is a special case where on the initial load, the bottom of the content is visible and we have a lot of space to show the widget at the top affixed.
                                cur_item_ref.sidebar_state = 'case_4_fixed_up'; // [1]87
                            } else {
                                cur_item_ref.sidebar_state = 'case_3_bottom_of_content'; // [2]
                            }


                        } else {

                            // affix
                            if ( tdSmartSidebar._is_smaller_or_equal( cur_item_ref.content_bottom, cur_item_ref.sidebar_bottom ) ) { // check to see if we reached the bottom of the content / row
                                if ( 'up' === scroll_direction && tdSmartSidebar._is_smaller_or_equal( scrollTop, cur_item_ref.sidebar_top ) ) {
                                    cur_item_ref.sidebar_state = 'case_4_fixed_up'; // get out of the case_3_bottom_of_content state
                                } else {
                                    cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                                }

                            } else {
                                if ( cur_item_ref.content_bottom - scrollTop >= cur_item_ref.sidebar_height ) {
                                    // Make sure that we have space for the sidebar to affix it to the top
                                    cur_item_ref.sidebar_state = 'case_4_fixed_up';  // we are not at the bottom of the content
                                } else {

                                    // this case isn't reached. It's accomplish by the tdSmartSidebar._is_smaller_or_equal(cur_item_ref.content_bottom, cur_item_ref.sidebar_bottom) case

                                    cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                                }
                                //console.log(cur_item_ref.content_bottom + ' >= ' +  cur_item_ref.sidebar_bottom); //@todo fix this case pe ? @20may2016 era un url aici dar l-am sters din motive de securitate
                            }
                        }



                        /**
                         * the sidebar is larger than the view port and the content is bigger
                         */


                    } else {

                        //// if the sidebar is above the view port and nothing is visible, place the sidebar at the bottom of the column
                        //if (tdSmartSidebar._is_smaller(cur_item_ref.sidebar_bottom, scrollTop) === true) {
                        //    cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                        //    tdSmartSidebar.log(cur_item_ref.sidebar_bottom + ' ~ ' + scrollTop);
                        //}


                        // if the sidebar is above the view port and nothing is visible, place the sidebar fixed up if it's smaller than the viewport,
                        //      fixed down, meaning that a possible previous operation could be 'scroll down'
                        // if none of the above operations meets the conditions, the sidebar is placed at the bottom of the content
                        if ( true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, scrollTop ) ) {

                            if ( true === tdSmartSidebar._is_smaller_or_equal(scrollTop, cur_item_ref.sidebar_top ) &&

                                true === tdSmartSidebar._is_smaller_or_equal( cur_item_ref.content_top, scrollTop ) //we are scrolling up ... make sure that we don't overshoot the sidebar by going over content_top. This happens when the sidebar is offseted by x number of pixels vs content
                            ) {
                                //console.log('sidebar_top' + cur_item_ref.sidebar_top + ' content top:' + cur_item_ref.content_top);
                                cur_item_ref.sidebar_state = 'case_4_fixed_up';
                            }
                            else if (
                                true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, view_port_bottom ) &&
                                true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, cur_item_ref.content_bottom ) &&
                                cur_item_ref.content_bottom >= view_port_bottom
                            ) {
                                cur_item_ref.sidebar_state = 'case_1_fixed_down';
                            }
                            else {
                                cur_item_ref.sidebar_state = 'case_3_bottom_of_content';
                            }
                        }



                        // position:fixed; bottom:0
                        else if (
                            true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, view_port_bottom ) &&
                            true === tdSmartSidebar._is_smaller( cur_item_ref.sidebar_bottom, cur_item_ref.content_bottom ) &&
                            'down' === scroll_direction &&
                            cur_item_ref.content_bottom >= view_port_bottom
                        ) {
                            //console.log(cur_item_ref.sidebar_bottom + ' < ' + cur_item_ref.content_bottom);
                            cur_item_ref.sidebar_state = 'case_1_fixed_down';
                        }

                        // the sidebar is at the top of the content ( position:static )
                        else if (
                            true === tdSmartSidebar._is_smaller_or_equal( cur_item_ref.sidebar_top, cur_item_ref.content_top ) &&
                            'up' === scroll_direction &&
                            cur_item_ref.content_bottom >= view_port_bottom
                        ) {
                            cur_item_ref.sidebar_state = 'case_2_top_of_content';
                        }




                        // the sidebar reached the bottom of the content
                        else if (
                            ( true === tdSmartSidebar._is_smaller_or_equal(cur_item_ref.content_bottom, cur_item_ref.sidebar_bottom) && 'down' === scroll_direction ) ||
                            cur_item_ref.content_bottom < view_port_bottom

                        ) {
                            cur_item_ref.sidebar_state = 'case_3_bottom_of_content';

                        }
                        // scrolling up, the sidebar is fixed up ( position:fixed; top:0 )
                        else if ( true === tdSmartSidebar._is_smaller_or_equal( scrollTop, cur_item_ref.sidebar_top ) && 'up' === scroll_direction &&

                            true === tdSmartSidebar._is_smaller_or_equal( cur_item_ref.content_top, scrollTop ) //we are scrolling up ... make sure that we don't overshoot the sidebar by going over content_top. This happens when the sidebar is offseted by x number of pixels vs content
                        ) {
                            //console.log('sidebar_top' + cur_item_ref.sidebar_top + ' content top:' + cur_item_ref.content_top);
                            cur_item_ref.sidebar_state = 'case_4_fixed_up';
                        }




                        /**
                         * This is the case when the scroll direction is 'up', but the sidebar is above the viewport (it could be left behind by a fast operation like typing HOME key)
                         */
                        else if ('up' === scroll_direction && true === tdSmartSidebar._is_smaller_or_equal( view_port_bottom, cur_item_ref.sidebar_top ))
                        {
                            cur_item_ref.sidebar_state = 'case_2_top_of_content';
                        }



                        // when to put absolute?
                        if (
                            ( 'case_1_fixed_down' === cur_item_ref.sidebar_state && 'up' === scroll_direction ) ||
                            ( 'case_4_fixed_up' === cur_item_ref.sidebar_state && 'down' === scroll_direction )
                        ) {
                            cur_item_ref.sidebar_state = 'case_5_absolute'; //absolute while going up?
                        }

                    } // end sidebar length check   cur_item_ref.sidebar_height < view_port_height




                    /**
                     * after we have the state, we enter this switch that makes sure that we only have one state change
                     */

                    // we have to set the content width via JS
                    //var column_content_width = 339;
                    //if (tdSmartSidebar.is_tablet_grid) {
                    //    column_content_width = 251;
                    //}


                    var column_content_width = 0;

                    var view_port_current_item = tdViewport.getCurrentIntervalItem();

                    if ( null !== view_port_current_item ) {
                        column_content_width = view_port_current_item.sidebarWidth;
                        //tdSmartSidebar.log("column sidebar width : " + column_content_width);
                    }



                    switch ( cur_item_ref.sidebar_state ) {
                        case 'case_1_fixed_down':

                            if ( true === cur_item_ref.case_1_run_once ) {
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = true;
                            cur_item_ref.case_2_run_once = false;
                            cur_item_ref.case_3_run_once = false;
                            cur_item_ref.case_4_run_once = false;
                            cur_item_ref.case_5_run_once = false;
                            cur_item_ref.case_6_run_once = false;


                            cur_item_ref.sidebar_jquery_obj.css({
                                width: column_content_width,
                                position: 'fixed',
                                top: 'auto',
                                bottom: '0',
                                'z-index': '1'
                            });


                            break;

                        case 'case_2_top_of_content':

                            if ( true === cur_item_ref.case_2_run_once ) {
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = false;
                            cur_item_ref.case_2_run_once = true;
                            cur_item_ref.case_3_run_once = false;
                            cur_item_ref.case_4_run_once = false;
                            cur_item_ref.case_5_run_once = false;
                            cur_item_ref.case_6_run_once = false;


                            cur_item_ref.sidebar_jquery_obj.css({
                                width: 'auto',
                                position: 'static',
                                top: 'auto',
                                bottom: 'auto'
                            });
                            break;

                        case 'case_3_bottom_of_content':
                            // case 3 has to be recalculated if the sidebar height changes

                            if ( true === cur_item_ref.case_3_run_once &&
                                cur_item_ref.case_3_last_sidebar_height === cur_item_ref.sidebar_height &&
                                cur_item_ref.case_3_last_content_height === cur_item_ref.content_height
                            ) { //if the case already runned AND the sidebar height did not change
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = false;
                            cur_item_ref.case_2_run_once = false;
                            cur_item_ref.case_3_run_once = true;
                            cur_item_ref.case_3_last_sidebar_height = cur_item_ref.sidebar_height;
                            cur_item_ref.case_3_last_content_height = cur_item_ref.content_height;
                            cur_item_ref.case_4_run_once = false;
                            cur_item_ref.case_5_run_once = false;
                            cur_item_ref.case_6_run_once = false;


                            cur_item_ref.sidebar_jquery_obj.css({
                                width: column_content_width,
                                position: 'absolute',
                                top: cur_item_ref.content_bottom - cur_item_ref.sidebar_height - cur_item_ref.content_top,
                                bottom: 'auto'
                            });
                            break;

                        case 'case_4_fixed_up':

                            if ( true === cur_item_ref.case_4_run_once && cur_item_ref.case_4_last_menu_offset === td_affix_menu_computed_height ) { //if the case already runned AND the menu height did not changed
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = false;
                            cur_item_ref.case_2_run_once = false;
                            cur_item_ref.case_3_run_once = false;
                            cur_item_ref.case_4_run_once = true;
                            cur_item_ref.case_4_last_menu_offset = td_affix_menu_computed_height;
                            cur_item_ref.case_5_run_once = false;
                            cur_item_ref.case_6_run_once = false;


                            cur_item_ref.sidebar_jquery_obj.css({
                                width: column_content_width,
                                position: 'fixed',
                                top: td_affix_menu_computed_height,
                                bottom: 'auto'
                            });
                            break;

                        case 'case_5_absolute':

                            if ( true === cur_item_ref.case_5_run_once ) {
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = false;
                            cur_item_ref.case_2_run_once = false;
                            cur_item_ref.case_3_run_once = false;
                            cur_item_ref.case_4_run_once = false;
                            cur_item_ref.case_5_run_once = true;
                            cur_item_ref.case_6_run_once = false;


                            cur_item_ref.sidebar_jquery_obj.css({
                                width: column_content_width,
                                position: 'absolute',
                                top: cur_item_ref.sidebar_top - cur_item_ref.content_top,
                                bottom: 'auto'
                            });
                            break;

                        case 'case_6_content_too_small':

                            if ( true === cur_item_ref.case_6_run_once ) {
                                break;
                            }

                            tdSmartSidebar.log( 'sidebar_id: ' + item_index + ' ' + cur_item_ref.sidebar_state );

                            cur_item_ref.case_1_run_once = false;
                            cur_item_ref.case_2_run_once = false;
                            cur_item_ref.case_3_run_once = false;
                            cur_item_ref.case_4_run_once = false;
                            cur_item_ref.case_5_run_once = false;
                            cur_item_ref.case_6_run_once = true;

                            cur_item_ref.sidebar_jquery_obj.css({
                                width: 'auto',
                                position: 'static',
                                top: 'auto',
                                bottom: 'auto'
                            });
                            break;
                    }
                } // end for loop
            }); // end request animation frame
        }, // end td_events_scroll


        compute: function() {

            tdSmartSidebar.td_events_scroll( jQuery( window ).scrollTop() );
        },


        // resets the run once flags. It may fail sometimes due to case_3_last_sidebar_height & case_4_last_menu_offset
        reset_run_once_flags: function () {
            for ( var item_index = 0; item_index < tdSmartSidebar.items.length; item_index++ ) {
                tdSmartSidebar.items[ item_index ].case_1_run_once = false;
                tdSmartSidebar.items[ item_index ].case_2_run_once = false;
                tdSmartSidebar.items[ item_index ].case_3_run_once = false;
                tdSmartSidebar.items[ item_index ].case_3_last_sidebar_height = 0;
                tdSmartSidebar.items[ item_index ].case_3_last_content_height = 0;
                tdSmartSidebar.items[ item_index ].case_4_run_once = false;
                tdSmartSidebar.items[ item_index ].case_4_last_menu_offset = 0;
                tdSmartSidebar.items[ item_index ].case_5_run_once = false;
                tdSmartSidebar.items[ item_index ].case_6_run_once = false;
            }
        },



        td_events_resize: function() {
            // enable and disable the smart sidebar

            tdSmartSidebar._view_port_current_interval_index = tdViewport.getCurrentIntervalIndex();

            switch ( tdSmartSidebar._view_port_current_interval_index ) {

                case 0 :

                    tdSmartSidebar.is_enabled = false;

                    // flag marked false to be made true only once, when the view port has not the first interval index [0]
                    tdSmartSidebar.is_enabled_state_run_once = false;

                    break;

                case 1 :
                    if ( false === tdSmartSidebar.is_tablet_grid ) { // we switched

                        tdSmartSidebar.reset_run_once_flags();

                        tdSmartSidebar.is_tablet_grid = true;
                        tdSmartSidebar.is_desktop_grid = false;

                        tdSmartSidebar.log( 'view port tablet' );
                    }
                    tdSmartSidebar.is_enabled = true;
                    tdSmartSidebar.is_disabled_state_run_once = false;

                    if ( false === tdSmartSidebar.is_enabled_state_run_once ) {
                        tdSmartSidebar.is_enabled_state_run_once = true;
                        tdSmartSidebar.log( 'smart_sidebar_enabled' );
                    }
                    break;

                case 2 :
                case 3 :
                    if ( true === tdSmartSidebar.is_tablet_grid ) { // we switched

                        tdSmartSidebar.reset_run_once_flags();

                        tdSmartSidebar.is_tablet_grid = false;
                        tdSmartSidebar.is_desktop_grid = true;

                        tdSmartSidebar.log( 'view port desktop' );
                    }
                    tdSmartSidebar.is_enabled = true;
                    tdSmartSidebar.is_disabled_state_run_once = false;

                    if ( false === tdSmartSidebar.is_enabled_state_run_once ) {
                        tdSmartSidebar.is_enabled_state_run_once = true;
                        tdSmartSidebar.log( 'smart_sidebar_enabled' );
                    }
                    break;
            }

            // @todo we may be able to delay the compute a bit (aka run it on the 500ms timer)
            tdSmartSidebar.compute();
        },


        log: function( msg ) {
            //console.log(msg);
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
        _is_smaller_or_equal: function( number1, number2 ) {
            // check if the two numbers are approximately equal
            // - first we check if the difference between the numbers is bigger than 1 unit
            // - second we check if the first number is bigger than the second one
            // if the two conditions are met, we return false


            if ( Math.abs( number1 - number2 ) >= 1) {
                // we have a difference that is bigger than 1 unit (px), check if the numbers are smaller or bigger
                if ( number1 < number2 ) {
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
        _is_smaller: function( number1, number2 ) {
            if ( Math.abs( number1 - number2 ) >= 1) {
                if ( number1 < number2 ) {
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

    //console.log(tdSmartSidebar.items);
})();


