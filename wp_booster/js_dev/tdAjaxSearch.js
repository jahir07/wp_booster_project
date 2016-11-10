
/* global jQuery:{} */
/* global tdDetect:{} */
/* global tdLocalCache:{} */
/* global td_ajax_url:{} */

var tdAjaxSearch = {};

jQuery().ready(function() {

    'use strict';

    tdAjaxSearch.init();

});

(function() {

    'use strict';

    tdAjaxSearch = {

        // private vars
        _current_selection_index: 0,
        _last_request_results_count: 0,
        _first_down_up: true,
        _is_search_open: false,


        /**
         * init the class
         */
        init: function init() {

            // hide the drop down if we click outside of it
            jQuery(document).click(function(e) {
                if (
                    'td-icon-search' !== e.target.className &&
                    'td-header-search' !== e.target.id &&
                    'td-header-search-top' !== e.target.id &&
                    true === tdAjaxSearch._is_search_open
                ) {
                    tdAjaxSearch.hide_search_box();
                }
            });


            // show and hide the drop down on the search icon
            jQuery( '#td-header-search-button' ).click(function(event){
                event.preventDefault();
                if (tdAjaxSearch._is_search_open === true) {
                    tdAjaxSearch.hide_search_box();

                } else {
                    tdAjaxSearch.show_search_box();
                }
            });


            // show and hide the drop down on the search icon for mobile
            jQuery( '#td-header-search-button-mob' ).click(function(event){
                jQuery( 'body' ).addClass( 'td-search-opened' );

                var search_input = jQuery('#td-header-search-mob');

                /**
                 * Note: the autofocus does not work for iOS and windows phone devices as it's considered bad user experience
                 */
                    //autofocus
                setTimeout(function(){
                    search_input.focus();
                }, 1300);

                var current_query = search_input.val().trim();
                if ( current_query.length > 0 ) {
                    tdAjaxSearch.do_ajax_call_mob();
                }
            });


            //close the search
            jQuery( '.td-search-close a' ).click(function(){
                jQuery( 'body' ).removeClass( 'td-search-opened' );
            });


            // keydown on the text box
            jQuery( '#td-header-search' ).keydown(function(event) {
                if (
                    ( event.which && 39 === event.which ) ||
                    ( event.keyCode && 39 === event.keyCode ) ||
                    ( event.which && 37 === event.which ) ||
                    ( event.keyCode && 37 === event.keyCode ) )
                {
                    //do nothing on left and right arrows
                    tdAjaxSearch.td_aj_search_input_focus();
                    return;
                }

                if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
                    // on enter
                    var td_aj_cur_element = jQuery('.td-aj-cur-element');
                    if (td_aj_cur_element.length > 0) {
                        //alert('ra');
                        var td_go_to_url = td_aj_cur_element.find('.entry-title a').attr('href');
                        window.location = td_go_to_url;
                    } else {
                        jQuery(this).parent().parent().submit();
                    }
                    return false; //redirect for search on enter

                } else if ( ( event.which && 40 === event.which ) || ( event.keyCode && 40 === event.keyCode ) ) {
                    // down
                    tdAjaxSearch.move_prompt_down();
                    return false; //disable the envent

                } else if ( ( event.which && 38 === event.which ) || ( event.keyCode && 38 === event.keyCode ) ) {
                    //up
                    tdAjaxSearch.move_prompt_up();
                    return false; //disable the envent

                } else {
                    //for backspace we have to check if the search query is empty and if so, clear the list
                    if ( ( event.which && 8 === event.which ) || ( event.keyCode && 8 === event.keyCode ) ) {
                        //if we have just one character left, that means it will be deleted now and we also have to clear the search results list
                        var search_query = jQuery(this).val();
                        if ( 1 === search_query.length ) {
                            jQuery('#td-aj-search').empty();
                        }
                    }

                    //various keys
                    tdAjaxSearch.td_aj_search_input_focus();
                    //jQuery('#td-aj-search').empty();
                    setTimeout(function(){
                        tdAjaxSearch.do_ajax_call();
                    }, 100);
                }
                return true;
            });

            // keydown on the text box
            jQuery('#td-header-search-mob').keydown(function(event) {

                if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
                    // on enter
                    var td_aj_cur_element = jQuery('.td-aj-cur-element');
                    if (td_aj_cur_element.length > 0) {
                        //alert('ra');
                        var td_go_to_url = td_aj_cur_element.find( '.entry-title a' ).attr( 'href' );
                        window.location = td_go_to_url;
                    } else {
                        jQuery(this).parent().parent().submit();
                    }
                    return false; //redirect for search on enter
                } else {

                    //for backspace we have to check if the search query is empty and if so, clear the list
                    if ( ( event.which && 8 === event.which ) || ( event.keyCode && 8 === event.keyCode ) ) {
                        //if we have just one character left, that means it will be deleted now and we also have to clear the search results list
                        var search_query = jQuery(this).val();
                        if ( 1 === search_query.length ) {
                            jQuery('#td-aj-search-mob').empty();
                        }
                    }

                    setTimeout(function(){
                        tdAjaxSearch.do_ajax_call_mob();
                    }, 100);

                    return true;
                }
            });
        },


        show_search_box: function() {
            jQuery( '.td-drop-down-search' ).addClass( 'td-drop-down-search-open' );
            // do not try to autofocus on ios. It's still buggy as of 18 march 2015
            if ( true !== tdDetect.isIos ) {
                setTimeout(function(){
                    document.getElementById( 'td-header-search' ).focus();
                }, 200);
            }
            tdAjaxSearch._is_search_open = true;
        },


        hide_search_box: function hide_search_box() {
            jQuery(".td-drop-down-search").removeClass('td-drop-down-search-open');
            tdAjaxSearch._is_search_open = false;
        },



        /**
         * moves the select up
         */
        td_aj_search_move_prompt_up: function() {
            if (tdAjaxSearch._first_down_up === true) {
                tdAjaxSearch._first_down_up = false;
                if (tdAjaxSearch._current_selection_index === 0) {
                    tdAjaxSearch._current_selection_index = tdAjaxSearch._last_request_results_count - 1;
                } else {
                    tdAjaxSearch._current_selection_index--;
                }
            } else {
                if (tdAjaxSearch._current_selection_index === 0) {
                    tdAjaxSearch._current_selection_index = tdAjaxSearch._last_request_results_count;
                } else {
                    tdAjaxSearch._current_selection_index--;
                }
            }
            tdAjaxSearch._repaintCurrentElement();
        },



        /**
         * moves the select prompt down
         */
        move_prompt_down: function() {
            if (tdAjaxSearch._first_down_up === true) {
                tdAjaxSearch._first_down_up = false;
            } else {
                if (tdAjaxSearch._current_selection_index === tdAjaxSearch._last_request_results_count) {
                    tdAjaxSearch._current_selection_index = 0;
                } else {
                    tdAjaxSearch._current_selection_index++;
                }
            }
            tdAjaxSearch._repaintCurrentElement();
        },


        /**
         * Recompute the current element in the search results.
         * Used by the move_prompt_up and move_prompt_down
         * @private
         */
        _repaintCurrentElement: function() {
            jQuery( '.td_module_wrap' ).removeClass( 'td-aj-cur-element' );

            if (tdAjaxSearch._current_selection_index > tdAjaxSearch._last_request_results_count - 1 ) {
                //the input is selected
                jQuery( '.td-search-form' ).fadeTo( 100, 1 );
            } else {
                tdAjaxSearch.td_aj_search_input_remove_focus();
                jQuery( '.td_module_wrap' ).eq( tdAjaxSearch._current_selection_index ).addClass( 'td-aj-cur-element' );
            }
        },


        /**
         * puts the focus on the input box
         */
        td_aj_search_input_focus: function() {
            tdAjaxSearch._current_selection_index = 0;
            tdAjaxSearch._first_down_up = true;
            jQuery( '.td-search-form' ).fadeTo( 100, 1 );
            jQuery( '.td_module_wrap' ).removeClass( 'td-aj-cur-element' );
        },



        /**
         * removes the focus from the input box
         */
        td_aj_search_input_remove_focus: function() {
            if ( 0 !== tdAjaxSearch._last_request_results_count ) {
                jQuery( '.td-search-form' ).css( 'opacity', 0.5 );
            }
        },



        /**
         * AJAX: process the response from the server
         */
        process_ajax_response: function(data) {
            var current_query = jQuery( '#td-header-search' ).val();

            //the search is empty - drop results
            if ( '' === current_query ) {
                jQuery( '#td-aj-search' ).empty();
                return;
            }

            var td_data_object = jQuery.parseJSON(data); //get the data object
            //drop the result - it's from a old query
            if ( td_data_object.td_search_query !== current_query ) {
                return;
            }

            //reset the current selection and total posts
            tdAjaxSearch._current_selection_index = 0;
            tdAjaxSearch._last_request_results_count = td_data_object.td_total_in_list;
            tdAjaxSearch._first_down_up = true;

            //update the query
            jQuery( '#td-aj-search' ).html( td_data_object.td_data );

            /*
             td_data_object.td_data
             td_data_object.td_total_results
             td_data_object.td_total_in_list
             */

            // the .entry-thumb are searched for in the #td-aj-search object, sorted and added into the view port array items
            if ( ( 'undefined' !== typeof window.tdAnimationStack )  && ( true === window.tdAnimationStack.activated ) ) {
                window.tdAnimationStack.check_for_new_items( '#td-aj-search .td-animation-stack', window.tdAnimationStack.SORTED_METHOD.sort_left_to_right, true );
                window.tdAnimationStack.compute_items();
            }
        },


        /**
         * AJAX: process the response from the server for the responsive version of the theme
         */
        process_ajax_response_mob: function(data) {
            var current_query = jQuery( '#td-header-search-mob' ).val();

            //the search is empty - drop results
            if ( '' === current_query ) {
                jQuery('#td-aj-search-mob').empty();
                return;
            }

            var td_data_object = jQuery.parseJSON( data ); //get the data object
            //drop the result - it's from a old query
            if ( td_data_object.td_search_query !== current_query ) {
                return;
            }

            //update the query
            jQuery( '#td-aj-search-mob' ).html( td_data_object.td_data );

            // the .entry-thumb are searched for in the #td-aj-search-mob object, sorted and added into the view port array items
            if ( ( 'undefined' !== typeof window.tdAnimationStack )  && ( true === window.tdAnimationStack.activated ) ) {
                window.tdAnimationStack.check_for_new_items( '#td-aj-search-mob .td-animation-stack', window.tdAnimationStack.SORTED_METHOD.sort_left_to_right, true );
                window.tdAnimationStack.compute_items();
            }
        },


        /**
         * AJAX: do the ajax request
         */
        do_ajax_call: function() {
            var search_query = jQuery('#td-header-search').val();

            if ( '' === search_query ) {
                tdAjaxSearch.td_aj_search_input_focus();
                return;
            }

            //do we have a cache hit
            if (tdLocalCache.exist(search_query)) {
                tdAjaxSearch.process_ajax_response(tdLocalCache.get(search_query));
                return; //cache HIT
            }

            //fk no cache hit - do the real request

            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                data: {
                    action: 'td_ajax_search',
                    td_string: search_query
                },
                success: function(data, textStatus, XMLHttpRequest){
                    tdLocalCache.set(search_query, data);
                    tdAjaxSearch.process_ajax_response(data);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                    //console.log(errorThrown);
                }
            });
        },


        /**
         * AJAX: do the ajax request for the responsive version of the theme
         */
        do_ajax_call_mob: function() {
            var search_query = jQuery( '#td-header-search-mob' ).val();

            if ( '' === search_query) {
                return;
            }

            //do we have a cache hit
            if ( tdLocalCache.exist( search_query ) ) {
                tdAjaxSearch.process_ajax_response_mob( tdLocalCache.get( search_query ) );
                return; //cache HIT
            }

            //fk no cache hit - do the real request

            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                data: {
                    action: 'td_ajax_search',
                    td_string: search_query
                },
                success: function( data, textStatus, XMLHttpRequest ){
                    tdLocalCache.set( search_query, data );
                    tdAjaxSearch.process_ajax_response_mob( data );
                },
                error: function( MLHttpRequest, textStatus, errorThrown ){
                    //console.log(errorThrown);
                }
            });
        }
    };

})();
