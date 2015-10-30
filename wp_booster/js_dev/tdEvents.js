/* tdEvents.js - handles the events that require throttling
 * v 2.0 - wp_010
 *
 * moved in theme from wp_booster
 */

/* global jQuery:{} */
/* global tdAffix:{} */
/* global tdSmartSidebar:{} */
/* global tdViewport:{} */
/* global tdInfiniteLoader:{} */
/* global td_more_articles_box:{} */
/* global tdDetect:{} */

/* global td_custom_events:{} */
/* global td_events_scroll_scroll_to_top:Function */

var tdEvents = {};

(function(){
    'use strict';

    tdEvents = {

        //the events - we have timers that look at the variables and fire the event if the flag is true
        scroll_event_slow_run: false,
        scroll_event_medium_run: false,

        resize_event_slow_run: false, //when true, fire up the resize event
        resize_event_medium_run: false,


        scroll_window_scrollTop: 0, //used to store the scrollTop

        window_pageYOffset: window.pageYOffset, // @todo see if it can replace scroll_window_scrollTop [used by others]
        window_innerHeight: window.innerHeight, // used to store the window height
        window_innerWidth: window.innerWidth, // used to store the window width

        init: function() {

            jQuery( window ).scroll(function() {
                tdEvents.scroll_event_slow_run = true;
                tdEvents.scroll_event_medium_run = true;

                //read the scroll top
                tdEvents.scroll_window_scrollTop = jQuery( window ).scrollTop();
                tdEvents.window_pageYOffset = window.pageYOffset;

                /*  ----------------------------------------------------------------------------
                 Run affix menu event
                 */

                tdAffix.td_events_scroll( tdEvents.scroll_window_scrollTop ); //main menu

                tdSmartSidebar.td_events_scroll( tdEvents.scroll_window_scrollTop ); //smart sidebar scroll


                // call real td_custom_events scroll
                td_custom_events._callback_scroll();
            });


            jQuery( window ).resize(function() {
                tdEvents.resize_event_slow_run = true;
                tdEvents.resize_event_medium_run = true;

                tdEvents.window_innerHeight = window.innerHeight;
                tdEvents.window_innerWidth = window.innerWidth;

                //var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

                //w = jQuery(document).width();
                //console.log(w);


                // call real td_custom_events resize
                td_custom_events._callback_resize();
            });



            //medium resolution timer for rest?
            setInterval(function() {

                // it must run before any others
                tdViewport.detectChanges();

                //scroll event
                if ( tdEvents.scroll_event_medium_run ) {
                    tdEvents.scroll_event_medium_run = false;
                    //compute events for the infinite scroll
                    tdInfiniteLoader.computeEvents();


                    // call lazy td_custom_events scroll
                    td_custom_events._lazy_callback_scroll_100();
                }

                if ( tdEvents.resize_event_medium_run ) {
                    tdEvents.resize_event_medium_run = false;
                    tdSmartSidebar.td_events_resize();


                    // call lazy td_custom_events resize
                    td_custom_events._lazy_callback_resize_100();
                }
            }, 100);



            //low resolution timer for rest?
            setInterval(function() {
                //scroll event
                if ( tdEvents.scroll_event_slow_run ) {
                    tdEvents.scroll_event_slow_run = false;

                    //back to top
                    td_events_scroll_scroll_to_top( tdEvents.scroll_window_scrollTop );

                    //more articles box
                    td_more_articles_box.td_events_scroll( tdEvents.scroll_window_scrollTop );


                    // call lazy td_custom_events scroll
                    td_custom_events._lazy_callback_scroll_500();
                }

                //resize event
                if ( tdEvents.resize_event_slow_run ) {
                    tdEvents.resize_event_slow_run = false;
                    tdAffix.compute_wrapper();
                    tdAffix.compute_top();
                    tdDetect.runIsPhoneScreen();


                    // call lazy td_custom_events resize
                    td_custom_events._lazy_callback_resize_500();
                }
            }, 500);
        }
    };

    tdEvents.init();
})();
