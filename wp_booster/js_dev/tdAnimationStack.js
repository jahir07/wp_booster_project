/**
 * Created by tagdiv on 11.03.2015.
 */

/**
 * abstract:
 * - check all items in page, sort them using one of a sorted methods and add them in the items array
 * - at every scroll the items are verified if they are in view port or above
 * - every item in view port is added into the _items_in_view_port array and they are ready for animation
 * - items above view port are animated all at once
 * - items in view port are animated at crescendo intervals [interval / remaining items]
 * - there's a max and a min interval
 * - td_block ajax request response use a sort method and add founded items into view port array or into items array
 */


/* global jQuery:false */
/* global tdDetect:false */
/* global tdEvents:{} */

var tdAnimationStack = {};

( function() {

    "use strict";

    tdAnimationStack = {

        /*
            Important:
            1. The first animation step is produced by the the body selector @see animation-stack.less
            2. The second animation step can be applied by the animation_css_class1
            3. The final (the main) animation step is applied by the animation_css_class2
         */




        // - flag css class used by the non 'type0' animation effect
        // - flag used just to look for not yet computed item
        // - it's set by ready_init (on ready)
        // - all dom components that need to be animated will be marked with this css class in ready_init
        // - it can be used for a precomputed style, but carefully, because it's applied at ready_init (on ready)
        _animation_css_class1: '',



        // - flag css class used by the non 'type0' animation effect
        // - flag css class used to animate custom
        // - this css class applies the final animation
        _animation_css_class2: '',



        // - the default animation effect 'type0' is applied if the global window.td_animation_stack_effect is the empty string
        // - it's used for consistency of animation effects presented into the animation-stack.less [all types have a name (...type...)]
        _animation_default_effect: 'type0',



        // - tdAnimationStack runs just only when this flag is true
        // - it's done true by the init function
        activated: false,



        // flag checked by the major animation operations
        _ready_for_initialization: true,

        // interval used by ready_init to check tdAnimationStack state
        _ready_init_timeout: undefined,


        // max time[ms] interval waiting for first tdAnimationStack.init call
        max_waiting_for_init: 3000,



        // the specific selectors are used to look for new elements inside of the specific sections
        _specific_selectors: '',

        // the general selectors are used to look for elements over extend areas in DOM
        _general_selectors: '',






        /**
         * - wait for tdAnimationStack.init() for max_waiting_for_init time
         * - if time is elapsed, the animation is canceled
         * - the ready_init is canceled by a fast tdAnimationStack.init call
         */
        ready_init: function() {

            // - special case for IE8 and IE9 (and if Visual Composer image carousel exists)
            // Important! The Visual Compose images carousel has hidden elements (images) that does not allow for computing the real position of the other DOM elements in the viewport
            // - the animation is forced removed and the altered css body is cleaned
            if ( tdDetect.isIe8 || tdDetect.isIe9 || ( jQuery( '.vc_images_carousel' ).length > 0 ) ) {
                tdAnimationStack._ready_for_initialization = false;

                if ( undefined !== window.td_animation_stack_effect ) {
                    if ( '' === window.td_animation_stack_effect ) {
                        window.td_animation_stack_effect = tdAnimationStack._animation_default_effect;
                    }
                    jQuery( 'body' ).removeClass( 'td-animation-stack-' + window.td_animation_stack_effect );
                }
                return;
            }


            if ( undefined === window.tds_animation_stack || undefined === window.td_animation_stack_effect ) {

                // lock any further operation using the _ready_for_initialization flag
                tdAnimationStack._ready_for_initialization = false;

            } else {

                // the tdAnimationStack._specific_selectors is set by the global variable window.td_animation_stack_specific_selectors
                if ( undefined !== window.td_animation_stack_specific_selectors ) {
                    tdAnimationStack._specific_selectors = window.td_animation_stack_specific_selectors;
                }


                // if the global variable window.td_animation_stack_effect has the empty string value, the 'full fade' (type0) effect is prepared to be applied
                if ( '' === window.td_animation_stack_effect ) {
                    window.td_animation_stack_effect = tdAnimationStack._animation_default_effect;
                }

                tdAnimationStack._animation_css_class1 = 'td-animation-stack-' + window.td_animation_stack_effect + '-1';
                tdAnimationStack._animation_css_class2 = 'td-animation-stack-' + window.td_animation_stack_effect + '-2';


                // - the tdAnimationStack._general_selectors is set by the global variable window.td_animation_stack_general_selectors
                if ( undefined !== window.td_animation_stack_general_selectors ) {
                    tdAnimationStack._general_selectors = window.td_animation_stack_general_selectors;
                }

                // the tdAnimationStack._animation_css_class1 css class is applied for all elements need to be animated later
                jQuery( tdAnimationStack._general_selectors ).addClass( tdAnimationStack._animation_css_class1 );


                // - timeout used by the ready_init function, to cut down tdAnimationStack.init calling at loading page, when the call comes too late
                // - if tdAnimationStack.init comes earlier, it does a clearTimeout call over the tdAnimationStack._ready_init_timeout variable
                tdAnimationStack._ready_init_timeout = setTimeout( function() {

                    // if tdAnimationStack is activated, do nothing
                    if ( true === tdAnimationStack.activated ) {
                        return;
                    }

                    // lock any further operation using the _ready_for_initialization flag
                    tdAnimationStack._ready_for_initialization = false;

                    // remove the loading animation css class effect from the body
                    // this class is applied from the theme settings
                    if ( undefined !== window.td_animation_stack_effect ) {
                        jQuery( 'body' ).removeClass( 'td-animation-stack-' + window.td_animation_stack_effect );
                    }

                }, tdAnimationStack.max_waiting_for_init );
            }
        },


        // flag marks items where they are
        _ITEM_TO_VIEW_PORT: {

            ITEM_ABOVE_VIEW_PORT: 0,

            ITEM_IN_VIEW_PORT: 1,

            ITEM_UNDER_VIEW_PORT: 2
        },


        // predefined sorting methods
        SORTED_METHOD: {

            sort_left_to_right: function sort_left_to_right( item1, item2 ) {
                if ( item1.offset_top > item2.offset_top ) {
                    return 1;
                } else if ( item1.offset_top < item2.offset_top ) {
                    return -1;
                } else if ( item1._order > item2._order ) {
                    return 1;
                } else if ( item1._order < item2._order ) {
                    return -1;
                }
                return 0;
            },


            sort_right_to_left: function sort_right_to_left( item1, item2 ) {
                if ( item1.offset_top > item2.offset_top ) {
                    return 1;
                } else if ( item1.offset_top < item2.offset_top ) {
                    return -1;
                } else if ( item1._order > item2._order ) {
                    return -1;
                } else if ( item1._order < item2._order ) {
                    return 1;
                }
                return -1;
            }
        },


        // keeps the DOM reading order, used in the sorting methods
        _order: 0,


        // interval divided to animate items
        // ex. interval 100 and 2 items => one item at 100 / 2 and one item at 100 / 1, but not lower than min_interval and not higher than max_interval
        interval: 70,

        // min interval of a set timer
        min_interval: 17,

        // max interval of a set timer
        max_interval: 40,



        // keep current setInterval
        _current_interval: undefined,

        // items in view port are moved here
        _items_in_view_port: [],

        // items above the view port are moved here
        _items_above_view_port: [],

        // all items that will be processed
        items: [],








        /**
         * - tdAnimationStack.item
         */
        item: function() {
            // offset from the top of the item, to the top
            // it's set at the initialization item
            this.offset_top = undefined;


            // offset from the bottom of the item, to the top
            // it's set at the initialization item
            this.offset_bottom_to_top = undefined;


            // jquery object reference
            // it's set before the initialization of the item
            this.jqueryObj = undefined;


            // the reading order from DOM
            // it's set at the initialization item
            this._order = undefined;
        },




        /**
         * - initialize a tdAnimationStack.item and add it in tdAnimationStack.items
         * @param item tdAnimationStack.item
         */
        //add_item: function add_item(item) {
        //
        //    if (item.constructor != tdAnimationStack.item) {
        //        return;
        //    }
        //
        //    tdAnimationStack.items.push(item);
        //},



        /**
         * - initialize the offset top of the tdAnimationStack.item parameter
         * @param item tdAnimationStack.item
         * @private
         */
        _initialize_item: function( item ) {
            item._order = tdAnimationStack._order++;

            item.offset_top = item.jqueryObj.offset().top;
            //item.offset_relative = Math.sqrt(Math.pow(item.jqueryObj.offset().top, 2) + Math.pow(item.jqueryObj.offset().left, 2));

            item.offset_bottom_to_top = item.offset_top + item.jqueryObj.height();

            //item.jqueryObj.parent().prepend('<div class="debug_item" style="position: absolute; width: 100%; height: 20px; border: 1px solid red; background-color: white">' + item.offset_top + '</div>');
        },


        /**
         * - dynamically search for new elements to create new tdAnimationStack.item
         * - the items are added in the tdAnimationStack._items_in_view_port, that means they are ready to be animated,
         * or in the tdAnimationStack.items to be computed later (checked if they are in the view port and animated)
         * @param selector {string} - jQuery selector
         * @param sort_type {tdAnimationStack.SORTED_METHOD} - a preferred tdAnimationStack.SORTED_METHOD
         * @param in_view_port {boolean} - add an item in the tdAnimationStack._items_in_view_port or in the tdAnimationStack.items
         */
        check_for_new_items: function( selector, sort_type, in_view_port ) {

            // tdAnimationStack must be activated and not stopped for initialization by the ready_init checker
            if ( ( false === tdAnimationStack.activated ) || ( false === tdAnimationStack._ready_for_initialization ) ) {
                return;
            }


            if ( undefined === selector ) {
                selector = '';
            }



            // the local stack of searched items
            var local_stack = [];



            //if (window.td_animation_stack_effect === 'type0') {
            //    // for every founded element there's an instantiated tdAnimationStack.item, then initialized and added to the local stack
            //    var founded_elements = jQuery(selector + ', .post').find(tdAnimationStack._specific_selectors).filter(function() {
            //        return jQuery(this).css('opacity') === '0';
            //    });
            //
            //} else {
                jQuery( tdAnimationStack._general_selectors).not( '.' + tdAnimationStack._animation_css_class2 ).addClass( tdAnimationStack._animation_css_class1 );

                // for every founded element there's an instantiated tdAnimationStack.item, then initialized and added to the local stack
                var founded_elements = jQuery( selector + ', .post' ).find( tdAnimationStack._specific_selectors ).filter( function() {
                    return jQuery( this ).hasClass( tdAnimationStack._animation_css_class1 );
                });
            //}



            founded_elements.each( function( index, element ) {

                var item_animation_stack = new tdAnimationStack.item();

                item_animation_stack.jqueryObj = jQuery( element );

                tdAnimationStack.log( index );

                tdAnimationStack._initialize_item( item_animation_stack );

                local_stack.push( item_animation_stack );
            });



            // new scope having its own timer used for checking not yet loaded images
            ( function(){

                var images_loaded = true;

                for ( var i = 0; i < local_stack.length; i++ ) {

                    // for every image element the 'complete' property is checked
                    // "If the image is finished loading, the complete property returns true"
                    // when tdAnimationStack.init is called on load, as normally, it calls tdAnimationStack.check_for_new_items and all these element has 'complete' property true
                    // when tdAnimationStack.check_for_new_items is called by block's ajax response, the next timer is used to wait for all elements being loaded
                    if ( false === founded_elements[ i ].complete ) {
                        images_loaded = false;
                        break;
                    }
                }

                // if there's at least one element not loaded, a timer is started to wait for
                if ( false === images_loaded ) {

                    var date = new Date();
                    var start_time = date.getTime();


                    tdAnimationStack.log( 'TIMER - started' );


                    // the timer is started
                    var interval_check_loading_image = setInterval( function() {

                        // if there's too much time waiting for image loading, they are made visible
                        var date = new Date();

                        var i = 0;

                        if ( ( date.getTime() - start_time ) > tdAnimationStack.max_waiting_for_init ) {

                            clearInterval( interval_check_loading_image );

                            for ( i = 0; i < local_stack.length; i++ ) {




                                //if (window.td_animation_stack_effect === 'type0') {
                                //    local_stack[i].jqueryObj.css('opacity', 1);
                                //} else {
                                    local_stack[ i ].jqueryObj.removeClass( tdAnimationStack._animation_css_class1 );
                                    local_stack[ i ].jqueryObj.addClass( tdAnimationStack._animation_css_class2 );
                                //}






                            }
                            return;
                        }


                        // at every interval step, the element's 'complete' property is checked again
                        images_loaded = true;

                        for ( i = 0; i < local_stack.length; i++ ) {

                            if ( false === founded_elements[ i ].complete ) {
                                images_loaded = false;
                                break;
                            }
                        }

                        if ( true === images_loaded ) {

                            clearInterval( interval_check_loading_image );

                            tdAnimationStack.log( 'TIMER - stopped' );

                            tdAnimationStack._precompute_items( local_stack, sort_type, in_view_port );
                            tdAnimationStack.compute_items();
                        }

                    }, 100);

                } else {
                    tdAnimationStack._precompute_items( local_stack, sort_type, in_view_port );
                    tdAnimationStack.compute_items();
                }

            })();

            tdAnimationStack.log( 'checked for new items finished' );
        },


        /**
         * - _precompute_items sorts and adds items in the tdAnimationStack.items array or even in the
         * tdAnimationStack._items_in_view_port array
         * - this function is necessary because at scroll just the tdAnimationStack.compute_items function is called
         *
         * @param stack_items {[]} founded items
         * @param sort_type {function} sorting method
         * @param in_view_port {boolean} add in view port to be already computed, or in the general items array
         * @private
         */
        _precompute_items: function( stack_items, sort_type, in_view_port ) {

            stack_items.sort( sort_type );

            if ( true === in_view_port ) {

                while ( stack_items.length > 0 ) {
                    tdAnimationStack.log( 'add item 1 : ' + stack_items.length );
                    tdAnimationStack._items_in_view_port.push( stack_items.shift() );
                }

            } else {

                while (stack_items.length > 0) {
                    tdAnimationStack.log( 'add item 2 : ' + stack_items.length );
                    tdAnimationStack.items.push( stack_items.shift() );
                }
            }
        },



        /**
         * - IT'S CALLED ON PAGE LOAD [actually in td_last_init.js]
         * - the general init function
         * - the items are added to the tdAnimationStack.items using check_for_new_items method, and then computed
         * - the arrays are cleared to be prepared for a reinitialization
         */
        init: function() {
            if ( undefined === window.tds_animation_stack ) {
                return;
            }

            // tdAnimationStack must not be already stopped for initialization by a pre_init checker
            if ( false === tdAnimationStack._ready_for_initialization ) {
                return;
            }

            // clear the _ready_init_timeout, to stop it doing more checking
            clearTimeout( tdAnimationStack._ready_init_timeout );

            // the tdAnimationStack is activated
            tdAnimationStack.activated = true;

            tdAnimationStack.check_for_new_items( '.td-animation-stack', tdAnimationStack.SORTED_METHOD.sort_left_to_right, false );
        },


        /**
         * - the arrays are cleared to be prepared for a reinitialization
         * - the init call is done
         */
        reinit: function() {

            // tdAnimationStack must not be already stopped for initialization by a pre_init checker
            if ( false === tdAnimationStack._ready_for_initialization ) {
                return;
            }

            tdAnimationStack.items = [];
            tdAnimationStack._items_in_view_port = [];
            tdAnimationStack._items_above_view_port = [];

            tdAnimationStack.init();
        },


        /**
         * - compute all items
         */
        compute_items: function() {

            // tdAnimationStack must be activated and not stopped for initialization by the ready_init checker
            if ( ( false === tdAnimationStack.activated ) || ( false === tdAnimationStack._ready_for_initialization ) ) {
                return;
            }

            // the tdAnimationStack.items are processed
            tdAnimationStack._separate_items();

            // the items above the port view are animated
            while ( tdAnimationStack._items_above_view_port.length > 0 ) {
                tdAnimationStack.log( 'animation - above the view port' );

                var item_above_view_port = tdAnimationStack._items_above_view_port.shift();



                //if (window.td_animation_stack_effect === 'type0') {
                //    item_above_view_port.jqueryObj.css('opacity', 1);
                //} else {
                    item_above_view_port.jqueryObj.removeClass( tdAnimationStack._animation_css_class1 );
                    item_above_view_port.jqueryObj.addClass( tdAnimationStack._animation_css_class2 );
                //}




            }


            // the items in the port view are prepared to be animated
            if ( tdAnimationStack._items_in_view_port.length > 0 ) {

                // clear any opened interval by a previous compute_items call
                clearInterval( tdAnimationStack._current_interval );

                var current_animation_item = tdAnimationStack._get_item_from_view_port();




                //if (window.td_animation_stack_effect === 'type0') {
                //    current_animation_item.jqueryObj.css('opacity', 1);
                //} else {
                    current_animation_item.jqueryObj.removeClass( tdAnimationStack._animation_css_class1 );
                    current_animation_item.jqueryObj.addClass( tdAnimationStack._animation_css_class2 );
    //            }





                if ( tdAnimationStack._items_in_view_port.length > 0 ) {

                    tdAnimationStack.log( 'start animation timer' );

                    tdAnimationStack._to_timer( tdAnimationStack._get_right_interval( tdAnimationStack.interval * ( 1 / tdAnimationStack._items_in_view_port.length ) ) );
                }
            }
        },


        /**
         * - timer function initially called by a tdAnimationStack.compute_items function, and then it's auto called
         * - it calls a setInterval using the interval parameter
         * @param interval {int} - interval ms
         */
        _to_timer: function( interval ) {

            tdAnimationStack._current_interval = setInterval( function () {

                if ( tdAnimationStack._items_in_view_port.length > 0 ) {

                    var current_animation_item = tdAnimationStack._get_item_from_view_port();

                    tdAnimationStack.log( 'animation at interval: ' + interval );




                    //if (window.td_animation_stack_effect === 'type0') {
                    //    current_animation_item.jqueryObj.css('opacity', 1);
                    //} else {
                        current_animation_item.jqueryObj.removeClass( tdAnimationStack._animation_css_class1 );
                        current_animation_item.jqueryObj.addClass( tdAnimationStack._animation_css_class2 );
                    //}




                    clearInterval( tdAnimationStack._current_interval );

                    if ( tdAnimationStack._items_in_view_port.length > 0 ) {
                        tdAnimationStack._to_timer( tdAnimationStack._get_right_interval( tdAnimationStack.interval * ( 1 / tdAnimationStack._items_in_view_port.length ) ) );
                    }
                }
            }, interval );
        },


        /**
         * - get an item from the tdAnimationStack._items_in_view_port array
         * @returns {tdAnimationStack.item}
         * @private
         */
        _get_item_from_view_port: function() {

            return tdAnimationStack._items_in_view_port.shift();
        },



        /**
         * - get the interval considering tdAnimationStack.min_interval and tdAnimationStack.max_interval
         * @param interval {int} - the checked interval value
         * @returns {int} - the result interval value
         * @private
         */
        _get_right_interval: function( interval ) {

            if ( interval < tdAnimationStack.min_interval ) {
                return tdAnimationStack.min_interval;

            } else if ( interval > tdAnimationStack.max_interval ) {
                return tdAnimationStack.max_interval;
            }
            return interval;
        },


        /**
         * - check where the item is to the view port
         * @param item {tdAnimationStack.item}
         * @returns {number} _ITEM_TO_VIEW_PORT value
         * @private
         */
        _item_to_view_port: function( item ) {

            tdAnimationStack.log( 'position item relative to the view port >> ' + tdEvents.window_pageYOffset + tdEvents.window_innerHeight + ' : ' + item.offset_top );

            if ( tdEvents.window_pageYOffset + tdEvents.window_innerHeight < item.offset_top ) {
                return tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_UNDER_VIEW_PORT;

            } else if ( ( tdEvents.window_pageYOffset + tdEvents.window_innerHeight >= item.offset_top ) && ( tdEvents.window_pageYOffset <= item.offset_bottom_to_top ) ) {
                return tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_IN_VIEW_PORT;

            }
            return tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_ABOVE_VIEW_PORT;
        },


        /**
         * - check the sorted tdAnimationStack.items and move them into the _items_above_view_port array or into the _items_in_view_port
         * - the remaining items are kept by the tdAnimationStack.items for next processing
         * @private
         */
        _separate_items: function() {
            if ( 0 === tdAnimationStack.items.length ) {
                return;
            }

            while ( tdAnimationStack.items.length > 0 ) {
                var item_to_view_port = tdAnimationStack._item_to_view_port( tdAnimationStack.items[ 0 ] );

                switch ( item_to_view_port ) {
                    case tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_ABOVE_VIEW_PORT :
                        tdAnimationStack._items_above_view_port.push( tdAnimationStack.items.shift() );
                        break;

                    case tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_IN_VIEW_PORT :
                        tdAnimationStack._items_in_view_port.push( tdAnimationStack.items.shift() );
                        break;

                    case tdAnimationStack._ITEM_TO_VIEW_PORT.ITEM_UNDER_VIEW_PORT :
                        tdAnimationStack.log( 'after separation items >> above: ' + tdAnimationStack._items_above_view_port.length + ' in: ' + tdAnimationStack._items_in_view_port.length + ' under: ' + tdAnimationStack.items.length );
                        return;
                }
            }
        },


        /**
         * - scroll event usually called by td_custom_events
         */
        td_events_scroll: function() {
            tdAnimationStack.compute_items();
        },



        /**
         * - resize event usually called by td_custom_events
         */
        td_events_resize: function() {
            // clear an existing interval
            clearInterval( tdAnimationStack._current_interval );

            // reinitialize tdAnimationStack searching in page for not already animated items [which were already repositioned by resize]
            tdAnimationStack.reinit();
        },



        log: function( msg ) {
            //console.log(msg);
        }

    };
})();
