/**
 * Created by ra on 6/27/14.
 * copyright tagDiv 2014
 * V 1.1 - better iOS 8 support
 */

/* global jQuery:{} */
/* global tdDetect:{} */
/* global tdUtil:{} */

var tdAffix = {};

(function(){
    'use strict';

    tdAffix = {

        // flag used to stop scrolling
        allow_scroll: true,

        //settings, obtained from ext
        menu_selector: '', //the affix menu (this element will get the td-affix)
        menu_wrap_selector: '', //the menu wrapper / placeholder
        tds_snap_menu: '', //the panel setting
        tds_snap_menu_logo: '', //the panel setting

        is_menu_affix_height_computed: false, // flag used to compute menu_affix_height only once, when the menu is affix
        is_menu_affix_height_on_mobile_computed: false, // flag used to compute menu_affix_height_on_mobile only once, when the menu is affix

        menu_affix_height: 0, // the menu affix height [the height when it's really affix]
        menu_affix_height_on_mobile: 0, // the menu affix height on mobile [the height when it's really affix]


        main_menu_height: 0, // main menu height
        top_offset: 0, //how much the menu is moved from the original position when it's affixed
        menu_offset: 0, //used to hide the menu on scroll
        is_requestAnimationFrame_running: false, //prevent multiple calls to requestAnimationFrame
        is_menu_affix: false, //the current state of the menu, true if the menu is affix
        is_top_menu: false, //true when the menu is at the top of the screen (0px topScroll)

        //menu offset boundaries - so we do not fire the animation event when the boundary is hit
        menu_offset_max_hit: false,
        menu_offset_min_hit: true,


        scroll_window_scrollTop_last: 0, //last scrollTop position, used to calculate the scroll direction

        /**
         * run the affix, we use the menu wrap selector to compute the menu position from top
         *
         {
              menu_selector: '.td-header-main-menu',
              menu_wrap_selector: '.td-header-menu-wrap',
              tds_snap_menu: tdUtil.getBackendVar('tds_snap_menu')
          }
         */
        init : function ( atts ) {

            //read the settings
            tdAffix.menu_selector = atts.menu_selector;
            tdAffix.menu_wrap_selector = atts.menu_wrap_selector;
            tdAffix.tds_snap_menu = atts.tds_snap_menu;
            tdAffix.tds_snap_menu_logo = atts.tds_snap_menu_logo;
            tdAffix.menu_affix_height = atts.menu_affix_height;
            tdAffix.menu_affix_height_on_mobile = atts.menu_affix_height_on_mobile;

            //the snap menu is disabled from the panel
            if ( ! tdAffix.tds_snap_menu ) {
                return;
            }


            // a computation before jquery.ready is necessary for firefox, where td_events.scroll comes before
            if ( tdDetect.isFirefox ) {
                tdAffix.compute_top();
                tdAffix.compute_wrapper();
            }

            jQuery().ready(function() {
                //compute on semi dom ready
                tdAffix.compute_top();
                tdAffix.compute_wrapper();
            });

            //recompute when all the page + logos are loaded
            jQuery( window ).load(function() {
                tdAffix.compute_top();
                tdAffix.compute_wrapper();

                //recompute after 1 sec for retarded phones
                setTimeout(function(){
                    tdAffix.compute_top();
                }, 1000 );
            });
        },


        /**
         * - get the real affix height.
         * The real affix height is computed only once, when the menu is affix. Till then, the function
         * return the values set at init.
         *
         * These values are important because they are used in the td_smart_sidebar.js for the
         * td_affix_menu_computed_height variable, which then is used to determine the sidebar position.
         *
         * For 'Newspaper', the sidebar needs a custom padding top (see @td_smart_sidebar.js), otherwise
         * the sidebar is sticked to the affix menu.
         *
         *
         * @returns {number} affix height
         * @private
         */
        _get_menu_affix_height: function() {

            //if (tdDetect.isPhoneScreen === true) {
            //    return tdAffix.menu_affix_height_on_mobile;
            //}
            //return tdAffix.menu_affix_height;

            if ( true === tdDetect.isPhoneScreen ) {
                if ( ! tdAffix.is_menu_affix_height_on_mobile_computed && tdAffix.is_menu_affix ) {

                    tdAffix.is_menu_affix_height_on_mobile_computed = true;

                    // overwrite the tdAffix.menu_affix_height_on_mobile variable with the real affix height
                    tdAffix.menu_affix_height_on_mobile = jQuery(tdAffix.menu_selector).height();
                }
                return tdAffix.menu_affix_height_on_mobile;
            }

            if ( ! tdAffix.is_menu_affix_height_computed && tdAffix.is_menu_affix ) {

                tdAffix.is_menu_affix_height_computed = true;

                // overwrite the tdAffix.menu_affix_height variable with the real affix height
                tdAffix.menu_affix_height = jQuery(tdAffix.menu_selector).height();
            }
            return tdAffix.menu_affix_height;
        },



        /**
         * called by td_events.js on scroll
         */
        td_events_scroll: function( scrollTop ) {

            if ( ! tdAffix.allow_scroll ) {
                return;
            }

            //do not run if we don't have a snap menu
            if ( ! tdAffix.tds_snap_menu ) {
                return;
            }


            /*  ----------------------------------------------------------------------------
             scroll direction + delta (used by affix for now)
             to run thios code:
             - tdAffix.tds_snap_menu != '' (from above)
             - tdAffix.tds_snap_menu != 'snap'
             */
            var scroll_direction = '';

            if ( 'snap' !== tdAffix.tds_snap_menu ) { //do not run on snap
                if ( ( 'smart_snap_mobile' !== tdAffix.tds_snap_menu || true === tdDetect.isPhoneScreen ) ) {  // different from smart_snap_mobile or tdDetect.isPhoneScreen === true

                    var scrollDelta = 0;

                    //check the direction
                    if ( scrollTop !== tdAffix.scroll_window_scrollTop_last ) { //compute direction only if we have different last scroll top
                        // compute the direction of the scroll
                        if ( scrollTop > tdAffix.scroll_window_scrollTop_last ) {
                            scroll_direction = 'down';
                        } else {
                            scroll_direction = 'up';
                        }
                        //calculate the scroll delta
                        scrollDelta = Math.abs( scrollTop - tdAffix.scroll_window_scrollTop_last );
                    }

                    tdAffix.scroll_window_scrollTop_last = scrollTop;
                }
            }

            /*  ---------------------------------------------------------------------------- */

            // show the logo on sticky menu if is always snap setting
            if ( 'snap' === tdAffix.tds_snap_menu && '' !== tdAffix.tds_snap_menu_logo ) {
                jQuery( '.td-main-menu-logo' ).addClass( 'td-logo-sticky' );
            }




            //if the menu is in the affix state

            // the next check is to keep the text from the menu at the same position, when the menu comes from affix off to affix off
            if ( ( scrollTop > tdAffix.top_offset + ( tdAffix.main_menu_height / 2 - tdAffix._get_menu_affix_height() / 2 ) ) ||

                    // - the affix is OFF when the next condition is not accomplished, which means that the affix is ON
                    // and the scroll to the top is LOWER than the initial tdAffix.top_offset reduced by the affix real height
                    // - this condition makes the transition from the small affix menu to the larger menu of the page
                ( ( tdAffix.is_menu_affix === true ) && ( 'smart_snap_always' === tdAffix.tds_snap_menu) && scrollTop > ( tdAffix.top_offset - tdAffix._get_menu_affix_height() ) ) ||

                tdAffix.is_top_menu === true ) {

                //get the menu element
                var td_affix_menu_element = jQuery( tdAffix.menu_selector );

                //turn affix on for it
                tdAffix._affix_on( td_affix_menu_element );


                //if the menu is only with snap or we are on smart_snap_mobile + mobile, our job here in this function is done, return
                if ( 'snap' === tdAffix.tds_snap_menu || ( 'smart_snap_mobile' === tdAffix.tds_snap_menu && false === tdDetect.isPhoneScreen ) ) {
                    return;
                }

                /*    ---  end simple snap  ---  */


                /*  ----------------------------------------------------------------------------
                 check scroll directions (we may also have scroll_direction = '', that's why we have to check for the specific state (up or down))
                 */


                // boundary check - to not run the position on each scroll event
                if ( ( false === tdAffix.menu_offset_max_hit && 'down' === scroll_direction ) || ( false === tdAffix.menu_offset_min_hit && 'up' === scroll_direction ) ) {
                    //request animation frame
                    //if (tdAffix.is_requestAnimationFrame_running === false) {
                    window.requestAnimationFrame(function(){

                        //console.log(tdAffix.menu_offset);
                        //console.log(scrollDelta);
                        var offset = 0;


                        if ( scrollTop > 0 ) { // ios returns negative scrollTop values
                            if ( 'down' === scroll_direction ) {

                                //compute the offset
                                offset = tdAffix.menu_offset - scrollDelta;

                                // the offset is a value in the [-tdAffix.menu_affix_height, 0] and
                                // not into the interval [-tdAffix.main_menu_height, 0]
                                if ( offset < -tdAffix._get_menu_affix_height() ) {
                                    offset = -tdAffix._get_menu_affix_height();
                                }

                            } else if ( 'up' === scroll_direction ) {
                                //compute the offset
                                offset = tdAffix.menu_offset + scrollDelta;
                                if ( offset > 0 ) {
                                    offset = 0;
                                }
                            }
                        }

                        //td_debug.log_live(scroll_direction + ' | scrollTop: ' + scrollTop + '  | offset: ' + offset);

                        //tdAffix.is_requestAnimationFrame_running = true;

                        //console.log(offset);

                        //move the menu
                        tdUtil.tdMoveY( td_affix_menu_element[0], offset );

                        //td_affix_menu_element.css({top: (offset) + 'px'});  //legacy menu move code

                        //check boundaries
                        if ( 0 === offset ) {
                            tdAffix.menu_offset_min_hit = true;
                        } else {
                            tdAffix.menu_offset_min_hit = false;
                        }


                        if ( offset === -tdAffix._get_menu_affix_height() ) {
                            tdAffix.menu_offset_max_hit = true;
                            //also hide the menu when it's 100% out of view on ios - the safari header is transparent and we can see the menu
                            if ( ( true === tdDetect.isIos ) || tdDetect.isSafari ) { // safari also
                                td_affix_menu_element.hide();
                            }

                            //show the logo on smart sticky menu
                            if ( '' !== tdAffix.tds_snap_menu_logo ) {
                                jQuery( '.td-main-menu-logo' ).addClass( 'td-logo-sticky' );
                            }
                        } else {
                            tdAffix.menu_offset_max_hit = false;

                            if ( ( true === tdDetect.isIos ) || tdDetect.isSafari ) { //ios safari fix
                                td_affix_menu_element.show();
                            }
                        }

                        //tdAffix.is_requestAnimationFrame_running = false;

                        tdAffix.menu_offset = offset; //update the current offset of the menu

                    }, td_affix_menu_element[0] );

                    //}
                    //console.log(offset + ' ' + scroll_direction);

                } //end boundary check

            } else {
                tdAffix._affix_off( jQuery( tdAffix.menu_selector ) );
            }
        },


        /**
         * calculates the affix point (the distance from the top when affix should be enabled)
         * @see tdAffix.init()
         * @see td_events
         */
        compute_top: function() {

            // to compute from the bottom of the menu, the top offset is incremented by the menu wrap height
            tdAffix.top_offset = jQuery( tdAffix.menu_wrap_selector ).offset().top;// + jQuery(tdAffix.menu_wrap_selector).height();


            // The top_offset is incremented with the menu_affix_height only on 'smart_snap_always', because of the sidebar
            // which use the menu_offset (and menu_offset depends on this top_offset)
            //
            // Consider that the smart sidebar, increment the td_affix_menu_computed_height with the menu_offset value
            // when the menu is on 'smart_snap_always'
            if ( 'smart_snap_always' === tdAffix.tds_snap_menu ) {
                tdAffix.top_offset += tdAffix.menu_affix_height;
            }


            //check to see if the menu is at the top of the screen
            if ( 1 === tdAffix.top_offset ) {
                //switch to affix - because the menu is at the top of the page
                //tdAffix._affix_on(jQuery(tdAffix.menu_selector));
                tdAffix.is_top_menu = true;
            } else {
                //check to see the current top offset
                tdAffix.is_top_menu = false;
            }
            tdAffix.td_events_scroll( jQuery(window).scrollTop() );

            //alert(tdAffix.top_offset);
            //console.log('computed: ' + tdAffix.top_offset);
        },


        /**
         * recalculate the wrapper height. To support different menu heights
         */
        compute_wrapper: function() {

            // td-affix class is removed to compute a real height when the compute_wrapper is done on a scrolled page
            if ( jQuery( tdAffix.menu_selector ).hasClass( 'td-affix' ) ) {
                jQuery( tdAffix.menu_selector ).removeClass( 'td-affix' );

                //read the height of the menu
                tdAffix.main_menu_height = jQuery( tdAffix.menu_selector ).height();

                jQuery( tdAffix.menu_selector ).addClass( 'td-affix' );

            } else {
                //read the height of the menu
                tdAffix.main_menu_height = jQuery( tdAffix.menu_selector ).height();
            }

            // put the menu height to the wrapper. The wrapper remains in the place when the menu is affixed
            jQuery( tdAffix.menu_wrap_selector ).css( 'height', tdAffix.main_menu_height );
        },

        /**
         * turns affix on for the menu element
         * @param td_affix_menu_element
         * @private
         */
        _affix_on: function( td_affix_menu_element ) {
            if ( false === tdAffix.is_menu_affix ) {


                // Bug.Fix - affix menu flickering
                // - the td_affix_menu_element is hidden because he is outside of the viewport
                // - without it, there's a flicker effect of applying css style (classes) over it

                if ( ( 'smart_snap_always' === tdAffix.tds_snap_menu ) && ( tdDetect.isPhoneScreen !== true ) ) {
                    td_affix_menu_element.css( 'visibility', 'hidden' );
                }

                tdAffix.menu_offset = -tdAffix.top_offset;

                //make the menu fixed
                td_affix_menu_element.addClass( 'td-affix' );

                //add body-td-affix class on body for header style 8 -> when scrolling down the window jumps 76px up when the menu is changing from header style 8 default to header style 8 affix
                jQuery( 'body' ).addClass( 'body-td-affix' );

                tdAffix.is_menu_affix = true;
            } else {

                // the td_affix_menu element is kept visible
                if ( true  !== tdDetect.isPhoneScreen ) {
                    td_affix_menu_element.css( 'visibility', '' );
                }
            }
        },



        /**
         * Turns affix off for the menu element
         * @param td_affix_menu_element
         * @private
         */
        _affix_off: function( td_affix_menu_element ) {
            if ( true === tdAffix.is_menu_affix ) {
                //make the menu normal
                jQuery( tdAffix.menu_selector ).removeClass( 'td-affix' );

                //hide the logo from sticky menu when the menu is not affix
                if( '' !== tdAffix.tds_snap_menu_logo ) {
                    jQuery( '.td-main-menu-logo' ).removeClass( 'td-logo-sticky' );
                }

                //remove body-td-affix class on body for header style 8 -> when scrolling down the window jumps 76px up when the menu is changing from header style 8 default to header style 8 affix
                jQuery( 'body' ).removeClass( 'body-td-affix' );

                tdAffix.is_menu_affix = false;

                //move the menu to 0 (ios seems to skip animation frames)
                tdUtil.tdMoveY( td_affix_menu_element[0], 0 );

                if ( ( true === tdDetect.isIos ) || tdDetect.isSafari ) {
                    td_affix_menu_element.show();
                }
            }
        }
    };
})();






