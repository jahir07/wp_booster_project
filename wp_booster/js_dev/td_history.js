"use strict";


/*  ----------------------------------------------------------------------------
    history js
 */

var td_history = {
    td_history_change_event: false,

    // static class init
    init: function() {
        //hook the popstate event
        window.addEventListener('popstate', function(event) {
            td_history.td_history_change_event = true;
            if (typeof(event.state) != "undefined" && event.state != null) {
                jQuery("#" + event.state.slide_id).iosSlider("goToSlide", event.state.current_slide);
            }
        });
    },


    /**
     * generally used on load
     * @param data
     */
    replace_history_entry: function (data) {
        if (td_detect.hasHistory === false) {
            return; //no history support
        }
        history.replaceState(data, null);
    },


    /**
     * ads an history entry - it also knows if we are using mod rewrite or not
     * @param data - the history data (state)
     * @param query_parm_id - 'slide' or other
     * @param query_parm_value - the value for slide
     */
    add_history_entry: function (data, query_parm_id, query_parm_value) {

        if (td_detect.hasHistory === false) {
            return; //no history support
        }


        if (query_parm_value == '') {
            history.pushState(data, null,  null); //add the hash via history api
            return;
        }

        // @todo - detect other types of pages ex: ?page_id
        var td_query_page_id = td_history.get_query_parameter('p');
        if (td_query_page_id != '') {
            //no mod rewrite, we go with ?p= etc
            if (query_parm_value == 1) {
                history.pushState(data, null,  '?p=' + td_query_page_id); //remove the parm for the first item
            } else {
                history.pushState(data, null,  '?p=' + td_query_page_id + '&' + query_parm_id + '=' + query_parm_value); //add the hash via history api
            }

        } else {
            //mod rewrite
            if (query_parm_value == 1) {
                history.pushState(data, null, td_history.get_mod_rewrite_base_url()); //add the hash via history api
            } else {
                history.pushState(data, null, td_history.get_mod_rewrite_base_url() + query_parm_value + '/'); //add the hash via history api
            }
        }

    },



    /**
     * returns the base url of urls with mod rewrite + pagination
     * @returns {string}
     */
    get_mod_rewrite_base_url: function () {
        var full_url = document.URL;

        //trim the last "/" in the url
        if (full_url.charAt(full_url.length - 1) == '/') {
            full_url = full_url.slice(0, - 1);
        }

        if (td_history.get_mod_rewrite_pagination(document.URL) === false) {
            // no pagination present
            return document.URL;
        }

        // we have pagination so we have to parse the url to remove it
        return full_url.substring(0, full_url.lastIndexOf("/"))+ '/';

    },



    /**
     * get the pagination from the urls with mod rewrite on
     * @returns {*}
     */
    get_mod_rewrite_pagination: function () {
        var full_url = document.URL;

        //trim the last "/" in the url
        if (full_url.charAt(full_url.length - 1) == '/') {
            full_url = full_url.slice(0, - 1);
        }

        var last_url_parameter = full_url.substring(full_url.lastIndexOf("/")+1, full_url.length);

        // return the page if it's indeed an integer
        if (td_history.isInt(last_url_parameter)) {
            return last_url_parameter;
        }

        //return false if we don't have a page
        return false;
    },


    /**
     * used by the iosslider @startAtSlide, it return 1 if there is no pagination or returns the pagination
     * @param query_parm_id
     * @returns {*}
     */
    get_current_page: function (query_parm_id) {
        var td_query_page_id = td_history.get_query_parameter('p');
        if (td_query_page_id != '') {
            //no mod rewrite, we go with ?p= etc
            var cur_page = td_history.get_query_parameter(query_parm_id);
            if (cur_page != '') {
                return cur_page;
            } else {
                return 1;
            }
        } else {
            //mod rewrite
            var cur_page = td_history.get_mod_rewrite_pagination();
            if (cur_page !== false) {
                return cur_page;
            } else {
                return 1;
            }
        }
    },


    /**
     * used to check if a number is an integer
     * @param n
     * @returns {boolean}
     */
    isInt: function (n) {
        return n % 1 === 0;
    },


    /**
     * returns a query parameter from the current url - we use it for ?p=
     * @param name
     * @returns {string}
     */
    get_query_parameter: function (name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },

    /**
     * callback for slides with history
     * @param args
     */
    slide_changed_callback: function(args) {
        //do not add another history entry if the slide moved due to the history change event
        if (td_history.td_history_change_event === true) {
            td_history.td_history_change_event = false;
            return;
        }
        var current_slide = args.currentSlideNumber;
        var slide_id = args.sliderContainerObject.attr('id');

        td_history.add_history_entry({current_slide:current_slide, slide_id:slide_id}, 'slide', current_slide);
    }

};

/**
 * ie8 does not have pushState and history
 */
if (window.history && window.history.pushState) {
    td_history.init();
}
