/*  ----------------------------------------------------------------------------
 Ajax search
 */
var td_aj_search_cur_sel = 0;
var td_aj_search_results = 0;
var td_aj_first_down_up = true;

/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    // live search via ajax
    td_ajax_search();

}); //end on load


function td_ajax_search() {

    /*
     *
     * click on document to hide certain stuff
     * when user click outside the used item
     * here is used to hide the search box
     *
     * */

    jQuery(document).click(function(e) {//alert(e.target.className);
        if(e.target.className!=="td-icon-search" && e.target.id!=="td-header-search" && e.target.id!=="td-header-search-top") {
            jQuery(".dropdown-menu").hide();
        }

        /*/pulldown filter
         if(e.target.className!=="td-pulldown-filter-display-option") {
         //jQuery("#td-pulldown-filter-display-option").removeClass("td-puldown-filter-remove-bottom-border");
         jQuery(".td-pulldown-filter-list").hide();
         }*/
    });


    //show the search field and put focus on search box in header
    jQuery('#td-header-search-button').click(function(event){
        event.preventDefault();
        //show the search box
        jQuery('.dropdown-menu').toggle();
    });


    jQuery('#td-header-search').keydown(function(event) {

        //console.log(event.keyCode);


        if ((event.which && event.which == 39) || (event.keyCode && event.keyCode == 39) || (event.which && event.which == 37) || (event.keyCode && event.keyCode == 37)) {
            //do nothing on left and right arrows
            td_aj_search_input_focus();
            return;
        }

        if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {

            //redirectSearch('q');
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
                td_aj_search_move_prompt_down();
                return false; //disable the envent

            } else if((event.which && event.which == 38) || (event.keyCode && event.keyCode == 38)) {
                //up
                td_aj_search_move_prompt_up();
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
                td_aj_search_input_focus();
                //jQuery('#td-aj-search').empty();
                setTimeout(function(){
                    td_ajax_search_do_request();
                }, 100);
            }
            return true;
        }

    });



}

//moves the select up
function td_aj_search_move_prompt_up() {


    if (td_aj_first_down_up === true) {
        td_aj_first_down_up = false;
        if (td_aj_search_cur_sel === 0) {
            td_aj_search_cur_sel = td_aj_search_results - 1;
        } else {
            td_aj_search_cur_sel--;
        }
    } else {
        if (td_aj_search_cur_sel === 0) {
            td_aj_search_cur_sel = td_aj_search_results;
        } else {
            td_aj_search_cur_sel--;
        }
    }


    jQuery('.td_module_wrap').removeClass('td-aj-cur-element');



    if (td_aj_search_cur_sel  > td_aj_search_results -1) {
        //the input is selected
        jQuery('.td-search-form').fadeTo(100, 1);
    } else {
        td_aj_search_input_remove_focus();
        jQuery('.td_module_wrap').eq(td_aj_search_cur_sel).addClass('td-aj-cur-element');
    }



}

//moves the select prompt down
function td_aj_search_move_prompt_down() {

    if (td_aj_first_down_up === true) {
        td_aj_first_down_up = false;
    } else {
        if (td_aj_search_cur_sel === td_aj_search_results) {
            td_aj_search_cur_sel = 0;
        } else {
            td_aj_search_cur_sel++;
        }
    }


    jQuery('.td_module_wrap').removeClass('td-aj-cur-element');

    if (td_aj_search_cur_sel > td_aj_search_results - 1 ) {
        //the input is selected
        jQuery('.td-search-form').fadeTo(100, 1);
    } else {
        td_aj_search_input_remove_focus();
        jQuery('.td_module_wrap').eq(td_aj_search_cur_sel).addClass('td-aj-cur-element');
    }


}


// puts the focus on the input box
function td_aj_search_input_focus() {
    td_aj_search_cur_sel = 0;
    td_aj_first_down_up = true;
    jQuery('.td-search-form').fadeTo(100, 1);
    jQuery('.td_module_wrap').removeClass('td-aj-cur-element');
}

//removes the focus from the input box
function td_aj_search_input_remove_focus() {
    if (td_aj_search_results !== 0) {
        jQuery('.td-search-form').css('opacity', 0.5);
    }
}

//makes an ajax request
function td_ajax_search_do_request() {

    //console.log("log:" + jQuery('#td-header-search').val());


    if (jQuery('#td-header-search').val() == '') {
        td_aj_search_input_focus();
        return;
    }



    var search_query = jQuery('#td-header-search').val();


    //do we have a cache hit
    if (td_local_cache.exist(search_query)) {
        td_ajax_search_process_request(td_local_cache.get(search_query));
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
            td_local_cache.set(search_query, data);
            td_ajax_search_process_request(data);
        },
        error: function(MLHttpRequest, textStatus, errorThrown){
            //console.log(errorThrown);
        }
    });
}

function td_ajax_search_process_request(data) {
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
    td_aj_search_cur_sel = 0;
    td_aj_search_results = td_data_object.td_total_in_list;
    td_aj_first_down_up = true;


    //update the query
    jQuery('#td-aj-search').html(td_data_object.td_data);

    /*
     td_data_object.td_data
     td_data_object.td_total_results
     td_data_object.td_total_in_list
     */


    // the .entry-thumb are searched for in the #td-aj-search object, sorted and added into the view port array items
    if (window.td_animation_stack != undefined) {
        window.td_animation_stack.check_for_new_items('#td-aj-search .entry-thumb', window.td_animation_stack.SORTED_METHOD.sort_left_to_right, true);
        window.td_animation_stack.compute_items();
    }
}



