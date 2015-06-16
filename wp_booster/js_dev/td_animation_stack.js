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

"use strict";

var td_animation_stack = {

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



    // - td_animation_stack runs just only when this flag is true
    // - it's done true by the init function
    activated: false,



    // flag checked by the major animation operations
    _ready_for_initialization: true,

    // interval used by ready_init to check td_animation_stack state
    _ready_init_timeout: undefined,


    // max time[ms] interval waiting for first td_animation_stack.init call
    max_waiting_for_init: 3000,



    // the specific selectors are used to look for new elements inside of the specific sections
    _specific_selectors: '',

    // the general selectors are used to look for elements over extend areas in DOM
    _general_selectors: '',






    /**
     * - wait for td_animation_stack.init() for max_waiting_for_init time
     * - if time is elapsed, the animation is canceled
     * - the ready_init is canceled by a fast td_animation_stack.init call
     */
    ready_init: function ready_init() {

        // - special case for IE8 and IE9
        // - the animation is forced removed and the altered css body is cleaned
        if (td_detect.is_ie8 || td_detect.is_ie9) {
            td_animation_stack._ready_for_initialization = false;

            if (window.td_animation_stack_effect != undefined) {
                if (window.td_animation_stack_effect == '') {
                    window.td_animation_stack_effect = td_animation_stack._animation_default_effect;
                }
                jQuery('body').removeClass('td-animation-stack-' + window.td_animation_stack_effect);
            }
            return;
        }


        if (window.tds_animation_stack != undefined && window.td_animation_stack_effect != undefined) {

            // the td_animation_stack._specific_selectors is set by the global variable window.td_animation_stack_specific_selectors
            if (window.td_animation_stack_specific_selectors != undefined) {
                td_animation_stack._specific_selectors = window.td_animation_stack_specific_selectors;
            }


            // if the global variable window.td_animation_stack_effect has the empty string value, the 'full fade' (type0) effect is prepared to be applied
            if (window.td_animation_stack_effect == '') {
                window.td_animation_stack_effect = td_animation_stack._animation_default_effect;
            } else {
                // - if not, the td-animation-stacks with class 1 and class 2 are applied
                td_animation_stack._animation_css_class1 = 'td-animation-stack-' + window.td_animation_stack_effect + '-1';
                td_animation_stack._animation_css_class2 = 'td-animation-stack-' + window.td_animation_stack_effect + '-2';


                // - the td_animation_stack._general_selectors is set by the global variable window.td_animation_stack_general_selectors
                // - it's used only by the non 'full fade' (type0) effects
                if (window.td_animation_stack_general_selectors != undefined) {
                    td_animation_stack._general_selectors = window.td_animation_stack_general_selectors;
                }

                // the td_animation_stack._animation_css_class1 css class is applied for all elements need to be animated later
                jQuery(td_animation_stack._general_selectors).addClass(td_animation_stack._animation_css_class1);
            }


            // - timeout used by the ready_init function, to cut down td_animation_stack.init calling at loading page, when the call comes too late
            // - if td_animation_stack.init comes earlier, it does a clearTimeout call over the td_animation_stack._ready_init_timeout variable
            td_animation_stack._ready_init_timeout = setTimeout(function() {

                // if td_animation_stack is activated, do nothing
                if (td_animation_stack.activated === true) {
                    return;
                }

                // lock any further operation using the _ready_for_initialization flag
                td_animation_stack._ready_for_initialization = false;

                // remove the loading animation css class effect from the body
                // this class is applied from the theme settings
                if (window.td_animation_stack_effect != undefined) {
                    jQuery('body').removeClass('td-animation-stack-' + window.td_animation_stack_effect);
                }

            }, td_animation_stack.max_waiting_for_init);

        } else {
            // lock any further operation using the _ready_for_initialization flag
            td_animation_stack._ready_for_initialization = false;
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

        sort_left_to_right: function sort_left_to_right(item1, item2) {
            if (item1.offset_top > item2.offset_top) {
                return 1;
            } else if (item1.offset_top < item2.offset_top) {
                return -1;
            } else if (item1._order > item2._order) {
                return 1;
            } else if (item1._order < item2._order) {
                return -1;
            }
            return 0;
        },


        sort_right_to_left: function sort_right_to_left(item1, item2) {
            if (item1.offset_top > item2.offset_top) {
                return 1;
            } else if (item1.offset_top < item2.offset_top) {
                return -1;
            } else if (item1._order > item2._order) {
                return -1;
            } else if (item1._order < item2._order) {
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
     * - td_animation_stack.item
     */
    item: function item() {
        // offset from the top of the item, to the top
        // it's set at the initialization item
        this.offset_top = undefined;


        // offset from the bottom of the item, to the top
        // it's set at the initialization item
        this.offset_bottom_to_top = undefined;


        // jquery object reference
        // it's set before the initialization of the item
        this.jquery_obj = undefined;


        // the reading order from DOM
        // it's set at the initialization item
        this._order = undefined;
    },




    /**
     * - initialize a td_animation_stack.item and add it in td_animation_stack.items
     * @param item td_animation_stack.item
     */
    //add_item: function add_item(item) {
    //
    //    if (item.constructor != td_animation_stack.item) {
    //        return;
    //    }
    //
    //    td_animation_stack.items.push(item);
    //},



    /**
     * - initialize the offset top of the td_animation_stack.item parameter
     * @param item td_animation_stack.item
     * @private
     */
    _initialize_item: function _initialize_item(item) {
        item._order = td_animation_stack._order++;

        item.offset_top = item.jquery_obj.offset().top;
        //item.offset_relative = Math.sqrt(Math.pow(item.jquery_obj.offset().top, 2) + Math.pow(item.jquery_obj.offset().left, 2));

        item.offset_bottom_to_top = item.offset_top + item.jquery_obj.height();

        //item.jquery_obj.parent().prepend('<div class="debug_item" style="position: absolute; width: 100%; height: 20px; border: 1px solid red; background-color: white">' + item.offset_top + '</div>');
    },


    /**
     * - dynamically search for new elements to create new td_animation_stack.item
     * - the items are added in the td_animation_stack._items_in_view_port, that means they are ready to be animated,
     * or in the td_animation_stack.items to be computed later (checked if they are in the view port and animated)
     * @param selector {string} - jQuery selector
     * @param sort_type {td_animation_stack.SORTED_METHOD} - a preferred td_animation_stack.SORTED_METHOD
     * @param in_view_port {boolean} - add an item in the td_animation_stack._items_in_view_port or in the td_animation_stack.items
     */
    check_for_new_items: function(selector, sort_type, in_view_port) {

        // td_animation_stack must be activated and not stopped for initialization by the ready_init checker
        if ((td_animation_stack.activated === false) || (td_animation_stack._ready_for_initialization === false)) {
            return;
        }


        if (selector === undefined) {
            selector = '';
        }



        // the local stack of searched items
        var local_stack = [];



        //if (window.td_animation_stack_effect === 'type0') {
        //    // for every founded element there's an instantiated td_animation_stack.item, then initialized and added to the local stack
        //    var founded_elements = jQuery(selector + ', .post').find(td_animation_stack._specific_selectors).filter(function() {
        //        return jQuery(this).css('opacity') === '0';
        //    });
        //
        //} else {
            jQuery(td_animation_stack._general_selectors).not('.' + td_animation_stack._animation_css_class2).addClass(td_animation_stack._animation_css_class1);

            // for every founded element there's an instantiated td_animation_stack.item, then initialized and added to the local stack
            var founded_elements = jQuery(selector + ', .post').find(td_animation_stack._specific_selectors).filter(function() {
                return jQuery(this).hasClass(td_animation_stack._animation_css_class1);
            });
        //}



        founded_elements.each(function(index, element) {

            var item_animation_stack = new td_animation_stack.item();

            item_animation_stack.jquery_obj = jQuery(element);

            td_animation_stack.log(index);

            td_animation_stack._initialize_item(item_animation_stack);

            local_stack.push(item_animation_stack);
        });



        // new scope having its own timer used for checking not yet loaded images
        (function(){

            var images_loaded = true;

            for (var i = 0; i < local_stack.length; i++) {

                // for every image element the 'complete' property is checked
                // "If the image is finished loading, the complete property returns true"
                // when td_animation_stack.init is called on load, as normally, it calls td_animation_stack.check_for_new_items and all these element has 'complete' property true
                // when td_animation_stack.check_for_new_items is called by block's ajax response, the next timer is used to wait for all elements being loaded
                if (founded_elements[i].complete == false) {
                    images_loaded = false;
                    break;
                }
            }

            // if there's at least one element not loaded, a timer is started to wait for
            if (images_loaded === false) {

                var date = new Date();
                var start_time = date.getTime();


                td_animation_stack.log('TIMER - started');


                // the timer is started
                var interval_check_loading_image = setInterval(function() {

                    // if there's too much time waiting for image loading, they are made visible
                    var date = new Date();

                    var i = 0;

                    if ((date.getTime() - start_time) > td_animation_stack.max_waiting_for_init) {

                        clearInterval(interval_check_loading_image);

                        for (i = 0; i < local_stack.length; i++) {




                            //if (window.td_animation_stack_effect === 'type0') {
                            //    local_stack[i].jquery_obj.css('opacity', 1);
                            //} else {
                                local_stack[i].jquery_obj.removeClass(td_animation_stack._animation_css_class1);
                                local_stack[i].jquery_obj.addClass(td_animation_stack._animation_css_class2);
                            //}






                        }
                        return;
                    }


                    // at every interval step, the element's 'complete' property is checked again
                    images_loaded = true;

                    for (i = 0; i < local_stack.length; i++) {

                        if (founded_elements[i].complete == false) {
                            images_loaded = false;
                            break;
                        }
                    }

                    if (images_loaded === true) {

                        clearInterval(interval_check_loading_image);

                        td_animation_stack.log('TIMER - stopped');

                        td_animation_stack._precompute_items(local_stack, sort_type, in_view_port);
                        td_animation_stack.compute_items();
                    }

                }, 100);

            } else {
                td_animation_stack._precompute_items(local_stack, sort_type, in_view_port);
                td_animation_stack.compute_items();
            }

        })();

        td_animation_stack.log('checked for new items finished');
    },


    /**
     * - _precompute_items sorts and adds items in the td_animation_stack.items array or even in the
     * td_animation_stack._items_in_view_port array
     * - this function is necessary because at scroll just the td_animation_stack.compute_items function is called
     *
     * @param stack_items {[]} founded items
     * @param sort_type {function} sorting method
     * @param in_view_port {boolean} add in view port to be already computed, or in the general items array
     * @private
     */
    _precompute_items: function _precompute_items(stack_items, sort_type, in_view_port) {

        stack_items.sort(sort_type);

        if (in_view_port === true) {

            while (stack_items.length > 0) {
                td_animation_stack.log('add item 1 : ' + stack_items.length);
                td_animation_stack._items_in_view_port.push(stack_items.shift());
            }

        } else {

            while (stack_items.length > 0) {
                td_animation_stack.log('add item 2 : ' + stack_items.length);
                td_animation_stack.items.push(stack_items.shift());
            }
        }
    },



    /**
     * - IT'S CALLED ON PAGE LOAD [actually in td_last_init.js]
     * - the general init function
     * - the items are added to the td_animation_stack.items using check_for_new_items method, and then computed
     * - the arrays are cleared to be prepared for a reinitialization
     */
    init: function init() {
        if (window.tds_animation_stack === undefined) {
            return;
        }

        // td_animation_stack must not be already stopped for initialization by a pre_init checker
        if (td_animation_stack._ready_for_initialization === false) {
            return;
        }

        // clear the _ready_init_timeout, to stop it doing more checking
        clearTimeout(td_animation_stack._ready_init_timeout);

        // the td_animation_stack is activated
        td_animation_stack.activated = true;

        td_animation_stack.check_for_new_items('.td-animation-stack', td_animation_stack.SORTED_METHOD.sort_left_to_right, false);
    },


    /**
     * - the arrays are cleared to be prepared for a reinitialization
     * - the init call is done
     */
    reinit: function reinit() {

        // td_animation_stack must not be already stopped for initialization by a pre_init checker
        if (td_animation_stack._ready_for_initialization === false) {
            return;
        }

        td_animation_stack.items = [];
        td_animation_stack._items_in_view_port = [];
        td_animation_stack._items_above_view_port = [];

        td_animation_stack.init();
    },


    /**
     * - compute all items
     */
    compute_items: function compute_items() {

        // td_animation_stack must be activated and not stopped for initialization by the ready_init checker
        if ((td_animation_stack.activated === false) || (td_animation_stack._ready_for_initialization === false)) {
            return;
        }

        // the td_animation_stack.items are processed
        td_animation_stack._separate_items();

        // the items above the port view are animated
        while (td_animation_stack._items_above_view_port.length > 0) {
            td_animation_stack.log('animation - above the view port');

            var item_above_view_port = td_animation_stack._items_above_view_port.shift();



            //if (window.td_animation_stack_effect === 'type0') {
            //    item_above_view_port.jquery_obj.css('opacity', 1);
            //} else {
                item_above_view_port.jquery_obj.removeClass(td_animation_stack._animation_css_class1);
                item_above_view_port.jquery_obj.addClass(td_animation_stack._animation_css_class2);
            //}




        }


        // the items in the port view are prepared to be animated
        if (td_animation_stack._items_in_view_port.length > 0) {

            // clear any opened interval by a previous compute_items call
            clearInterval(td_animation_stack._current_interval);

            var current_animation_item = td_animation_stack._get_item_from_view_port();




            //if (window.td_animation_stack_effect === 'type0') {
            //    current_animation_item.jquery_obj.css('opacity', 1);
            //} else {
                current_animation_item.jquery_obj.removeClass(td_animation_stack._animation_css_class1);
                current_animation_item.jquery_obj.addClass(td_animation_stack._animation_css_class2);
//            }





            if (td_animation_stack._items_in_view_port.length > 0) {

                td_animation_stack.log('start animation timer');

                td_animation_stack._to_timer(td_animation_stack._get_right_interval(td_animation_stack.interval * (1 / td_animation_stack._items_in_view_port.length)));
            }
        }
    },


    /**
     * - timer function initially called by a td_animation_stack.compute_items function, and then it's auto called
     * - it calls a setInterval using the interval parameter
     * @param interval {int} - interval ms
     */
    _to_timer: function _to_timer(interval) {

        td_animation_stack._current_interval = setInterval(function () {

            if (td_animation_stack._items_in_view_port.length > 0) {

                var current_animation_item = td_animation_stack._get_item_from_view_port();

                td_animation_stack.log('animation at interval: ' + interval);




                //if (window.td_animation_stack_effect === 'type0') {
                //    current_animation_item.jquery_obj.css('opacity', 1);
                //} else {
                    current_animation_item.jquery_obj.removeClass(td_animation_stack._animation_css_class1);
                    current_animation_item.jquery_obj.addClass(td_animation_stack._animation_css_class2);
                //}




                clearInterval(td_animation_stack._current_interval);

                if (td_animation_stack._items_in_view_port.length > 0) {
                    td_animation_stack._to_timer(td_animation_stack._get_right_interval(td_animation_stack.interval * (1 / td_animation_stack._items_in_view_port.length)));
                }
            }
        }, interval);
    },


    /**
     * - get an item from the td_animation_stack._items_in_view_port array
     * @returns {td_animation_stack.item}
     * @private
     */
    _get_item_from_view_port: function _get_item_from_view_port() {

        return td_animation_stack._items_in_view_port.shift();
    },



    /**
     * - get the interval considering td_animation_stack.min_interval and td_animation_stack.max_interval
     * @param interval {int} - the checked interval value
     * @returns {int} - the result interval value
     * @private
     */
    _get_right_interval: function _get_right_interval(interval) {

        if (interval < td_animation_stack.min_interval) {
            return td_animation_stack.min_interval;

        } else if (interval > td_animation_stack.max_interval) {
            return td_animation_stack.max_interval;
        }
        return interval;
    },


    /**
     * - check where the item is to the view port
     * @param item {td_animation_stack.item}
     * @returns {number} _ITEM_TO_VIEW_PORT value
     * @private
     */
    _item_to_view_port: function _item_to_view_port(item) {

        if (td_events.window_pageYOffset + td_events.window_innerHeight < item.offset_top) {
            return td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_UNDER_VIEW_PORT;

        } else if ((td_events.window_pageYOffset + td_events.window_innerHeight >= item.offset_top) && (td_events.window_pageYOffset <= item.offset_bottom_to_top)) {
            return td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_IN_VIEW_PORT;

        }
        return td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_ABOVE_VIEW_PORT;
    },


    /**
     * - check the sorted td_animation_stack.items and move them into the _items_above_view_port array or into the _items_in_view_port
     * - the remaining items are kept by the td_animation_stack.items for next processing
     * @private
     */
    _separate_items: function _separate_items() {
        if (td_animation_stack.items.length == 0) {
            return;
        }

        while (td_animation_stack.items.length > 0) {
            var item_to_view_port = td_animation_stack._item_to_view_port(td_animation_stack.items[0]);

            switch (item_to_view_port) {
                case td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_ABOVE_VIEW_PORT :
                    td_animation_stack._items_above_view_port.push(td_animation_stack.items.shift());
                    break;

                case td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_IN_VIEW_PORT :
                    td_animation_stack._items_in_view_port.push(td_animation_stack.items.shift());
                    break;

                case td_animation_stack._ITEM_TO_VIEW_PORT.ITEM_UNDER_VIEW_PORT : return;
            }
        }
    },


    /**
     * - scroll event usually called by td_custom_events
     */
    td_events_scroll: function td_events_scroll() {
        td_animation_stack.compute_items();
    },



    /**
     * - resize event usually called by td_custom_events
     */
    td_events_resize: function td_events_resize() {
        // clear an existing interval
        clearInterval(td_animation_stack._current_interval);

        // reinitialize td_animation_stack searching in page for not already animated items [which were already repositioned by resize]
        td_animation_stack.reinit();
    },



    log: function log(msg) {
        //console.log(msg);
    }

};
