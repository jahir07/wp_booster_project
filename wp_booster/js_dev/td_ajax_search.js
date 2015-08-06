
/*  ----------------------------------------------------------------------------
    On load
 */
jQuery().ready(function() {
    td_ajax_search.init();
});


var td_ajax_search = {

    // private vars
    _current_selection_index:0,
    _last_request_results_count:0,
    _first_down_up:true,
    _is_search_open:false,


    /**
     * init the class
     */
    init: function init() {


        // hide the drop down if we click outside of it
        jQuery(document).click(function(e) {
            if(
                e.target.className !== "td-icon-search"
                && e.target.id !== "td-header-search"
                && e.target.id !== "td-header-search-top"
                && td_ajax_search._is_search_open === true
            ) {
                td_ajax_search.hide_search_box();
            }
        });


        // show and hide the drop down on the search icon
        jQuery('#td-header-search-button').click(function(event){
            event.preventDefault();
            if (td_ajax_search._is_search_open === true) {
                td_ajax_search.hide_search_box();

            } else {
                td_ajax_search.show_search_box();
            }
        });


        // keydown on the text box
        jQuery('#td-header-search').keydown(function(event) {
            if (
                (event.which && event.which == 39)
                || (event.keyCode && event.keyCode == 39)
                || (event.which && event.which == 37)
                || (event.keyCode && event.keyCode == 37))
            {
                //do nothing on left and right arrows
                td_ajax_search.td_aj_search_input_focus();
                return;
            }




            if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {
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
            } else {

                if ((event.which && event.which == 40) || (event.keyCode && event.keyCode == 40)) {
                    // down
                    td_ajax_search.td_aj_search_move_prompt_down();
                    return false; //disable the envent

                } else if((event.which && event.which == 38) || (event.keyCode && event.keyCode == 38)) {
                    //up
                    td_ajax_search.td_aj_search_move_prompt_up();
                    return false; //disable the envent
                } else {

                    //for backspace we have to check if the search query is empty and if so, clear the list
                    if ((event.which && event.which == 8) || (event.keyCode && event.keyCode == 8)) {
                        //if we have just one character left, that means it will be deleted now and we also have to clear the search results list
                        var search_query = jQuery(this).val();
                        if (search_query.length == 1) {
                            jQuery('#td-aj-search').empty();
                        }

                    }

                    //various keys
                    td_ajax_search.td_aj_search_input_focus();
                    //jQuery('#td-aj-search').empty();
                    setTimeout(function(){
                        td_ajax_search.do_ajax_call();
                    }, 100);
                }
                return true;
            }

        });

    },


    show_search_box: function open_search_box() {
        jQuery(".td-drop-down-search").addClass('td-drop-down-search-open');
        // do not try to autofocus on ios. It's still buggy as of 18 march 2015
        if (td_detect.isIos !== true) {
            setTimeout(function(){
                document.getElementById("td-header-search").focus();
            }, 200);
        }
        td_ajax_search._is_search_open = true;
    },


    hide_search_box: function hide_search_box() {
        jQuery(".td-drop-down-search").removeClass('td-drop-down-search-open');
        td_ajax_search._is_search_open = false;
    },



    /**
     * moves the select up
     */
    td_aj_search_move_prompt_up: function td_aj_search_move_prompt_up() {
        if (td_ajax_search._first_down_up === true) {
            td_ajax_search._first_down_up = false;
            if (td_ajax_search._current_selection_index === 0) {
                td_ajax_search._current_selection_index = td_ajax_search._last_request_results_count - 1;
            } else {
                td_ajax_search._current_selection_index--;
            }
        } else {
            if (td_ajax_search._current_selection_index === 0) {
                td_ajax_search._current_selection_index = td_ajax_search._last_request_results_count;
            } else {
                td_ajax_search._current_selection_index--;
            }
        }
        jQuery('.td_module_wrap').removeClass('td-aj-cur-element');
        if (td_ajax_search._current_selection_index  > td_ajax_search._last_request_results_count -1) {
            //the input is selected
            jQuery('.td-search-form').fadeTo(100, 1);
        } else {
            td_ajax_search.td_aj_search_input_remove_focus();
            jQuery('.td_module_wrap').eq(td_ajax_search._current_selection_index).addClass('td-aj-cur-element');
        }
    },



    /**
     * moves the select prompt down
     */
    td_aj_search_move_prompt_down: function td_aj_search_move_prompt_down() {
        if (td_ajax_search._first_down_up === true) {
            td_ajax_search._first_down_up = false;
        } else {
            if (td_ajax_search._current_selection_index === td_ajax_search._last_request_results_count) {
                td_ajax_search._current_selection_index = 0;
            } else {
                td_ajax_search._current_selection_index++;
            }
        }
        jQuery('.td_module_wrap').removeClass('td-aj-cur-element');
        if (td_ajax_search._current_selection_index > td_ajax_search._last_request_results_count - 1 ) {
            //the input is selected
            jQuery('.td-search-form').fadeTo(100, 1);
        } else {
            td_ajax_search.td_aj_search_input_remove_focus();
            jQuery('.td_module_wrap').eq(td_ajax_search._current_selection_index).addClass('td-aj-cur-element');
        }
    },



    /**
     * puts the focus on the input box
     */
    td_aj_search_input_focus: function td_aj_search_input_focus() {
        td_ajax_search._current_selection_index = 0;
        td_ajax_search._first_down_up = true;
        jQuery('.td-search-form').fadeTo(100, 1);
        jQuery('.td_module_wrap').removeClass('td-aj-cur-element');
    },



    /**
     * removes the focus from the input box
     */
    td_aj_search_input_remove_focus: function td_aj_search_input_remove_focus() {
        if (td_ajax_search._last_request_results_count !== 0) {
            jQuery('.td-search-form').css('opacity', 0.5);
        }
    },



    /**
     * AJAX: process the response from the server
     */
    process_ajax_response: function (data) {
        var current_query = jQuery('#td-header-search').val();

        //the search is empty - drop results
        if (current_query == '') {
            jQuery('#td-aj-search').empty();
            return;
        }

        var td_data_object = jQuery.parseJSON(data); //get the data object
        //drop the result - it's from a old query
        if (td_data_object.td_search_query !== current_query) {
            return;
        }

        //reset the current selection and total posts
        td_ajax_search._current_selection_index = 0;
        td_ajax_search._last_request_results_count = td_data_object.td_total_in_list;
        td_ajax_search._first_down_up = true;


        //update the query
        jQuery('#td-aj-search').html(td_data_object.td_data);

        /*
         td_data_object.td_data
         td_data_object.td_total_results
         td_data_object.td_total_in_list
         */

        // the .entry-thumb are searched for in the #td-aj-search object, sorted and added into the view port array items
        if ((typeof window['td_animation_stack'] !== 'undefined')  && (window['td_animation_stack'].activated === true)) {
            window['td_animation_stack'].check_for_new_items('#td-aj-search .td-animation-stack', window['td_animation_stack'].SORTED_METHOD.sort_left_to_right, true);
            window['td_animation_stack'].compute_items();
        }
    },



    /**
     * AJAX: do the ajax request
     */
    do_ajax_call: function do_ajax_call() {
        if (jQuery('#td-header-search').val() == '') {
            td_ajax_search.td_aj_search_input_focus();
            return;
        }



        var search_query = jQuery('#td-header-search').val();


        //do we have a cache hit
        if (tdLocalCache.exist(search_query)) {
            td_ajax_search.process_ajax_response(tdLocalCache.get(search_query));
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
                td_ajax_search.process_ajax_response(data);
            },
            error: function(MLHttpRequest, textStatus, errorThrown){
                //console.log(errorThrown);
            }
        });
    }


};


