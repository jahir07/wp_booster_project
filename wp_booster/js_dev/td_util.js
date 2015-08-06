/*
 td_util.js
 v1.1
 */

"use strict";



/*  ----------------------------------------------------------------------------
 tagDiv utility class
 */
var td_util = {


    /**
     * stop propagation of an event - we should check this if we can remove window.event.cancelBubble - possible
     * a windows mobile issue
     * @param event
     */
    stopBubble: function(event) {
        if(event && event.stopPropagation) {
            event.stopPropagation();
        } else {
            window.event.cancelBubble=true;
        }
    },

    /**
     * utility function, used by td_post_images.js
     * @param class_selector
     */
    image_move_class_to_figure: function (class_selector) {
        jQuery('figure .' + class_selector).each(function() {
            jQuery(this).parent().parent().addClass(class_selector);
            jQuery(this).removeClass(class_selector);
        });
    },



    /**
     * safe function to read variables passed by the theme via the js buffer. If by some kind of error the variable is missing from the global scope, this function will return false
     * @param variable_name
     * @returns {*}
     */
    get_backend_var: function(variable_name) {
        if (typeof window[variable_name] === 'undefined') {
            return '';
        }
        return window[variable_name];
    },






    /**
     * scrolls to a dom element
     * @param dom_element
     */
    scroll_to_element: function(dom_element, duration) {
        td_is_scrolling_animation = true;
        jQuery("html, body").stop();


        var dest;

        //calculate destination place
        if (dom_element.offset().top > jQuery(document).height() - jQuery(window).height()) {
            dest = jQuery(document).height() - jQuery(window).height();
        } else {
            dest = dom_element.offset().top;
        }
        //go to destination
        jQuery("html, body").animate({ scrollTop: dest }, {
                duration: duration,
                easing:'easeInOutQuart',
                complete: function(){
                    td_is_scrolling_animation = false;
                }
            }
        );
    },


    /**
     * scrolls to a dom element - the element will be close to the center of the screen
     * !!! compensates for long distances !!!
     */
    scroll_into_view: function (dom_element) {

        if (tdDetect.isMobileDevice === true) {
            return; //do not run on any mobile device
        }

        td_is_scrolling_animation = true;
        jQuery("html, body").stop();


        var destination = dom_element.offset().top;
        destination = destination - 150;

        var distance = Math.abs(jQuery(window).scrollTop() - destination);
        var computed_time = distance / 5;
        //console.log(distance + ' -> ' + computed_time +  ' -> ' + (1100+computed_time));

        //go to destination
        jQuery("html, body").animate({ scrollTop: destination }, {
                duration: 1100 + computed_time,
                easing:'easeInOutQuart',
                complete: function(){
                    td_is_scrolling_animation = false;
                }
            }
        );
    },

    /**
     * scrolls to a position
     * @param px_from_top - pixels from top
     */
    scroll_to_position: function(px_from_top, duration) {
        td_is_scrolling_animation = true;
        jQuery("html, body").stop();

        //go to destination
        jQuery("html, body").animate({ scrollTop: px_from_top }, {
                duration: duration,
                easing:'easeInOutQuart',
                complete: function(){
                    td_is_scrolling_animation = false;
                }
            }
        );
    },
    td_move_y: function td_move_Y (elm, value) {
        var translate = 'translate3d(0px,' + value + 'px, 0px)';
        elm.style['-webkit-transform'] = translate;
        elm.style['-moz-transform'] = translate;
        elm.style['-ms-transform'] = translate;
        elm.style['-o-transform'] = translate;
        elm.style.transform = translate;
    },


    is_valid_url: function is_valid_url(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator


        if(!pattern.test(str)) {
            return false;
        } else {
            return true;
        }
    }





};
