/* td_events.js - handles the events that require throttling
 * v 2.0 - wp_010
 *
 * moved in theme from wp_booster
 */

"use strict";

var td_events = {

    //the events - we have timers that look at the variables and fire the event if the flag is true
    scroll_event_slow_run: false,
    scroll_event_medium_run: false,

    resize_event_slow_run: false, //when true, fire up the resize event
    resize_event_medium_run: false,


    scroll_window_scrollTop: 0, //used to store the scrollTop

    window_pageYOffset: window.pageYOffset, // @todo see if it can replace scroll_window_scrollTop [used by others]
    window_innerHeight: window.innerHeight, // used to store the window height
    window_innerWidth: window.innerWidth, // used to store the window width

    init: function init() {

        jQuery(window).scroll(function() {
            td_events.scroll_event_slow_run = true;
            td_events.scroll_event_medium_run = true;

            //read the scroll top
            td_events.scroll_window_scrollTop = jQuery(window).scrollTop();
            td_events.window_pageYOffset = window.pageYOffset;

            /*  ----------------------------------------------------------------------------
             Run affix menu event
             */

            td_affix.td_events_scroll(td_events.scroll_window_scrollTop); //main menu

            td_smart_sidebar.td_events_scroll(td_events.scroll_window_scrollTop); //smart sidebar scroll


            // call real td_custom_events scroll
            td_custom_events._callback_scroll();
        });


        jQuery(window).resize(function() {
            td_events.resize_event_slow_run = true;
            td_events.resize_event_medium_run = true;

            td_events.window_innerHeight = window.innerHeight;
            td_events.window_innerWidth = window.innerWidth;

            //var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            //w = jQuery(document).width();
            //console.log(w);


            // call real td_custom_events resize
            td_custom_events._callback_resize();
        });



        //medium resolution timer for rest?
        setInterval(function() {
            //scroll event
            if (td_events.scroll_event_medium_run) {
                td_events.scroll_event_medium_run = false;
                //compute events for the infinite scroll
                td_infinite_loader.compute_events();


                // call lazy td_custom_events scroll
                td_custom_events._lazy_callback_scroll();
            }

            if (td_events.resize_event_medium_run) {
                td_events.resize_event_medium_run = false;
                td_smart_sidebar.td_events_resize();


                // call lazy td_custom_events resize
                td_custom_events._lazy_callback_resize();
            }
        }, 100);



        //low resolution timer for rest?
        setInterval(function() {
            //scroll event
            if (td_events.scroll_event_slow_run) {
                td_events.scroll_event_slow_run = false;

                //back to top
                td_events_scroll_scroll_to_top(td_events.scroll_window_scrollTop);

                //more articles box
                td_more_articles_box.td_events_scroll(td_events.scroll_window_scrollTop);


                // call lazy td_custom_events scroll
                td_custom_events._lazy_callback_scroll();
            }

            //resize event
            if (td_events.resize_event_slow_run) {
                td_events.resize_event_slow_run = false;
                td_affix.compute_top();
                td_affix.compute_wrapper();
                td_detect.run_is_phone_screen();


                // call lazy td_custom_events resize
                td_custom_events._lazy_callback_resize();
            }
        }, 500);

    }



};

td_events.init();