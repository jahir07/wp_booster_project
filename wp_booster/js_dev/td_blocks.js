"use strict";


/**
 * td_blocks js
 * v 2.1  10 oct 2014
 */



/*  ----------------------------------------------------------------------------
    On load
 */
jQuery().ready(function() {
    td_on_ready_ajax_blocks();
});





function td_on_ready_ajax_blocks() {


    /*  ----------------------------------------------------------------------------
        AJAX pagination next
     */
    jQuery(".td-ajax-next-page").click(function(event){
        event.preventDefault();

        var current_block_obj = td_getBlockObjById(jQuery(this).data('td_block_id'));

        if(jQuery(this).hasClass('ajax-page-disabled') || current_block_obj.is_ajax_running === true) {
            return;
        }

        current_block_obj.is_ajax_running = true; // ajax is running and we're wayting for a reply from server

        current_block_obj.td_current_page++;
        td_ajax_do_block_request(current_block_obj, 'next');
    });


    /*  ----------------------------------------------------------------------------
        AJAX pagination prev
     */
    jQuery(".td-ajax-prev-page").click(function(event){
        event.preventDefault();

        var current_block_obj = td_getBlockObjById(jQuery(this).data('td_block_id'));

        if(jQuery(this).hasClass('ajax-page-disabled') || current_block_obj.is_ajax_running === true) {
            return;
        }

        current_block_obj.is_ajax_running = true; // ajax is running and we're wayting for a reply from server

        current_block_obj.td_current_page--;
        td_ajax_do_block_request(current_block_obj, 'back');
    });


    /*  ----------------------------------------------------------------------------
        AJAX pagination load more
     */
    jQuery(".td_ajax_load_more").click(function(event){
        event.preventDefault();
        if(jQuery(this).hasClass('ajax-page-disabled')) {
            return;
        }

        var current_block_obj = td_getBlockObjById(jQuery(this).data('td_block_id'));

        current_block_obj.td_current_page++;
        td_ajax_do_block_request(current_block_obj, 'load_more');
    });



    //on mobile devices use click event
    if(td_detect.is_mobile_device) {

        jQuery(".td-pulldown-filter-display-option").click(function () {
            var current_block_uid = jQuery(this).data('td_block_id');
            jQuery("#td_pulldown_" + current_block_uid).addClass("td-pulldown-filter-list-open");

            //animate the list
            var td_pull_down_list = jQuery("#td_pulldown_" + current_block_uid + "_list");
            td_pull_down_list.removeClass('fadeOut');
            td_pull_down_list.addClass('animated fadeIn'); //used for opacity animation
            //td_pull_down_list.css('visibility', 'visible');
        });

    //on desktop devices use hover event
    } else {

        /**
         * (hover) open and close the drop down menu (on blocks on hover)
         */
        jQuery(".td-pulldown-filter-display-option").hover(function () {
                // hover in
                var current_block_uid = jQuery(this).data('td_block_id');
                jQuery("#td_pulldown_" + current_block_uid).addClass("td-pulldown-filter-list-open");

                //animate the list
                var td_pull_down_list = jQuery("#td_pulldown_" + current_block_uid + "_list");
                td_pull_down_list.removeClass('fadeOut');
                td_pull_down_list.addClass('animated fadeIn'); //used for opacity animation
                td_pull_down_list.css('visibility', 'visible');

            },
            function () {
                // hover out
                var current_block_uid = jQuery(this).data('td_block_id');
                jQuery("#td_pulldown_" + current_block_uid).removeClass("td-pulldown-filter-list-open");


            }
        );
    }


    /**
     * when a item is from the dropdown menu is clicked (on all the blocks)
     */
    jQuery(".td-pulldown-filter-link").click(function(event){
        event.preventDefault();



        //get the current block id
        var current_block_uid = jQuery(this).data('td_block_id');

        //destroy any iossliders to avoid bugs
        jQuery('#' + current_block_uid).find('.iosSlider').iosSlider('destroy');

        //get current block
        var current_block_obj = td_getBlockObjById(current_block_uid);

        //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
        current_block_obj.td_filter_value = jQuery(this).data('td_filter_value');

        //the item id that took the click
        current_block_obj.td_filter_ui_uid = jQuery(this).attr('id');

        current_block_obj.td_current_page = 1;


        //put loading... text and hide the dropdown @todo - tranlation pt loading
        td_pull_down_filter_change_value(current_block_obj.id, '<span>Loading... </span><i class="td-icon-menu-down"></i>');

        //hide the dropdown
        jQuery('#td_pulldown_' + current_block_uid).removeClass("td-pulldown-filter-list-open");


        //do request
        td_ajax_do_block_request(current_block_obj, 'pull_down');


        //on mobile devices stop event propagation
        if(td_detect.is_mobile_device) {
            stopBubble(event)
        }

    });


    /**
     * click on related posts in single posts
     */
    jQuery('.td-related-title a').click(function(event){
        event.preventDefault();

        jQuery('.td-related-title').children('a').removeClass('td-cur-simple-item');
        jQuery(this).addClass('td-cur-simple-item');

        //get the current block id
        var current_block_uid = jQuery(this).data('td_block_id');

        //get current block
        var current_block_obj = td_getBlockObjById(current_block_uid);

        //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
        current_block_obj.td_filter_value = jQuery(this).data('td_filter_value');

        current_block_obj.td_current_page = 1; //reset the page

        //do request
        td_ajax_do_block_request(current_block_obj, 'pull_down');
    });





    /**
     * hover or click on mega menu subcategories
     */
    function td_ajax_sub_cat_mega_run() {
        //get the current block id
        var current_block_uid = jQuery(this).data('td_block_id');

        //switch cur cat
        jQuery('.mega-menu-sub-cat-' + current_block_uid).removeClass('cur-sub-cat');
        jQuery(this).addClass('cur-sub-cat');

        //get current block
        var current_block_obj = td_getBlockObjById(current_block_uid);

        //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
        current_block_obj.td_filter_value = jQuery(this).data('td_filter_value');

        current_block_obj.td_current_page = 1; //reset the page

        //do request
        td_ajax_do_block_request(current_block_obj, 'mega_menu');
    }


    //on touch devices use click
    if (td_detect.is_touch_device) {
        jQuery(".block-mega-child-cats a").click(td_ajax_sub_cat_mega_run);
    } else {
        jQuery(".block-mega-child-cats a").hover(td_ajax_sub_cat_mega_run, function (event) {} );
    }



} // end td_on_ready_ajax_blocks()



/**
 * change the pull down filter value (when we have a reply)
 * @param td_block_uid
 * @param td_text
 */
function td_pull_down_filter_change_value(td_block_uid, td_text) {
    jQuery('#td-pulldown-' + td_block_uid + '-val').html(td_text);
}


/**
 * makes a ajax block request
 * @param current_block_obj
 * @param td_user_action - load more or infinite loader (used by the animation)
 * @returns {string}
 */
function td_ajax_do_block_request(current_block_obj, td_user_action) {

    //console.log(current_block_obj);

    //search the cache
    var current_block_obj_signature = JSON.stringify(current_block_obj);
    if (td_local_cache.exist(current_block_obj_signature)) {
        //do the animation with cache hit = true
        td_block_ajax_loading_start(current_block_obj, true, td_user_action);
        td_ajax_block_process_response(td_local_cache.get(current_block_obj_signature), td_user_action);
        return 'cache_hit'; //cache HIT
    }


    //cache miss - we make a full request! - cache hit - false
    td_block_ajax_loading_start(current_block_obj, false, td_user_action);

    var request_data = {
        action: 'td_ajax_block',
        td_atts: current_block_obj.atts,
        td_block_id:current_block_obj.id,
        td_column_number:current_block_obj.td_column_number,
        td_current_page:current_block_obj.td_current_page,
        block_type:current_block_obj.block_type,
        td_filter_value:current_block_obj.td_filter_value,
        td_filter_ui_uid:current_block_obj.td_filter_ui_uid,
        td_user_action:current_block_obj.td_user_action
    };

    //console.log('td_ajax_do_block_request:');
    //console.log(request_data);

    jQuery.ajax({
        type: 'POST',
        url: td_ajax_url,
        cache:true,
        data: request_data,
        success: function(data, textStatus, XMLHttpRequest){
            td_local_cache.set(current_block_obj_signature, data);
            td_ajax_block_process_response(data, td_user_action);
        },
        error: function(MLHttpRequest, textStatus, errorThrown){
            //console.log(errorThrown);
        }
    });
}


/**
 * process the response from the ajax query (it also processes the responses stored in the cache)
 * @param data
 * @param td_user_action - load more or infinite loader (used by the animation)
 */
function td_ajax_block_process_response(data, td_user_action) {

    //read the server response
    var td_reply_obj = jQuery.parseJSON(data); //get the data object

    //console.log('td_ajax_block_process_response:');
    //console.log(td_reply_obj);
    /*
     td_data_object.td_block_id
     td_data_object.td_data
     td_data_object.td_cur_cat
     */


    //check if we have a changed filter setting from the server and only if td_data_object.td_filter_ui_uid is not empty - change the dropdown
    if (td_reply_obj.td_filter_ui_uid) { //this should be empty if the request is not from an ordinary block that has a dropdown
        //show the current selected item in the drop down box of the block
        //console.log(td_reply_obj);
        td_pull_down_filter_change_value(td_reply_obj.td_block_id, '<span>' + jQuery('#' + td_reply_obj.td_filter_ui_uid).html() + ' </span><i class="td-icon-menu-down"></i>');
    }




    //load the content (in place or append)
    if (td_user_action == 'load_more' || td_user_action == 'infinite_load') {
        jQuery(td_reply_obj.td_data).addClass('animated_xxlong').appendTo('#' + td_reply_obj.td_block_id).addClass('fadeIn');
        //jQuery('#' + td_reply_obj.td_block_id).append(td_reply_obj.td_data); //append
    } else {
        jQuery('#' + td_reply_obj.td_block_id).html(td_reply_obj.td_data); //in place
    }


    //hide or show prev
    if (td_reply_obj.td_hide_prev === true) {
        jQuery('#prev-page-' + td_reply_obj.td_block_id).addClass('ajax-page-disabled');
    } else {
        jQuery('#prev-page-' + td_reply_obj.td_block_id).removeClass('ajax-page-disabled');
    }

    //hide or show next
    if (td_reply_obj.td_hide_next === true) {
        jQuery('#next-page-' + td_reply_obj.td_block_id).addClass('ajax-page-disabled');
    } else {
        jQuery('#next-page-' + td_reply_obj.td_block_id).removeClass('ajax-page-disabled');
    }


    var  current_block_obj = td_getBlockObjById(td_reply_obj.td_block_id);
    if (current_block_obj.block_type === 'slide') {
        //make the first slide active (to have caption)
        jQuery('#' + td_reply_obj.td_block_id + ' .slide-wrap-active-first').addClass('slide-wrap-active');
    }

    current_block_obj.is_ajax_running = false; // finish the loading for this block


    //loading effects
    td_block_ajax_loading_end(td_reply_obj, current_block_obj, td_user_action);   //td_user_action - load more or infinite loader (used by the animation)
}


/**
 * loading start
 * @param current_block_obj
 * @param cache_hit
 * @param td_user_action - the request type / infinite_load ?
 */
function td_block_ajax_loading_start(current_block_obj, cache_hit, td_user_action) {

    //get the element
    var el_cur_td_block_inner = jQuery('#' + current_block_obj.id);

    //remove any remaining loaders
    jQuery('.td-loader-gif').remove();

    //remove animation classes
    jQuery('#' + current_block_obj.id).removeClass('fadeInRight fadeInLeft fadeInDown fadeInUp animated_xlong');

    el_cur_td_block_inner.addClass('td_block_inner_overflow');
    //auto height => fixed height
    var td_tmp_block_height = el_cur_td_block_inner.height();
    el_cur_td_block_inner.css('height', td_tmp_block_height);


    //show the loader only if it's needed
    if (cache_hit === true) {
        //el_cur_td_block_inner.stop();
    } else {

        if (td_user_action == 'load_more') {
            // on load more
            el_cur_td_block_inner.parent().append('<div class="td-loader-gif td-loader-gif-bottom td-loader-animation-start"></div>');
            td_loading_box.init(current_block_obj.header_color ? current_block_obj.header_color : tds_theme_color_site_wide);  //init the loading box
            setTimeout(function(){
                jQuery('.td-loader-gif').removeClass('td-loader-animation-start');
                jQuery('.td-loader-gif').addClass('td-loader-animation-mid');
            },50);

        } else if (td_user_action != 'infinite_load') {
            /**
             * the default animation if the user action is NOT load_more or infinite_load
             * infinite load has NO animation !
             */
            el_cur_td_block_inner.parent().append('<div class="td-loader-gif td-loader-animation-start"></div>');
            td_loading_box.init(current_block_obj.header_color ? current_block_obj.header_color : tds_theme_color_site_wide);         //init the loading box (the parameter is the block title background color or tds_theme_color_site_wide)
            setTimeout(function(){
                jQuery('.td-loader-gif').removeClass('td-loader-animation-start');
                jQuery('.td-loader-gif').addClass('td-loader-animation-mid');
            },50);

            //el_cur_td_block_inner.stop();

            //el_cur_td_block_inner.fadeTo('500',0.1, 'easeInOutCubic');
            el_cur_td_block_inner.addClass('animated_long fadeOut_to_1');

        }



    }







}


/**
 * we have a reply from the ajax request
 * @param td_reply_obj - the reply object that we got from the server, it's useful with infinite load
 * @param current_block_obj
 * @param td_user_action - load more or infinite loader (used by the animation)
 */
function td_block_ajax_loading_end(td_reply_obj, current_block_obj, td_user_action) {

    //jQuery('.td-loader-gif').remove();
    // remove the loader
    jQuery('.td-loader-gif').removeClass('td-loader-animation-mid');
    jQuery('.td-loader-gif').addClass('td-loader-animation-end');
    setTimeout(function(){
        jQuery('.td-loader-gif').remove();
        //stop the loading box
        td_loading_box.stop();
    },400);


    //get the current inner
    var el_cur_td_block_inner = jQuery('#' + current_block_obj.id);

    el_cur_td_block_inner.removeClass('animated_long fadeOut_to_1');




    switch(td_user_action) {
        case 'next':
            el_cur_td_block_inner.addClass('animated_xlong fadeInRight');
            break;
        case 'back':
            el_cur_td_block_inner.addClass('animated_xlong fadeInLeft');
            break;

        case 'pull_down':
            el_cur_td_block_inner.addClass('animated_xlong fadeInDown');
            break;

        case 'mega_menu':
            el_cur_td_block_inner.addClass('animated_xlong fadeInUp');
            break;

        case 'infinite_load':
            setTimeout(function(){
                //refresh waypoints for infinit scroll td_infinite_loader
                td_infinite_loader.compute_top_distances();
                if (td_reply_obj.td_data != '') {
                    td_infinite_loader.enable_is_visible_callback(current_block_obj.id);
                }
            }, 500);


            setTimeout(function(){
                td_infinite_loader.compute_top_distances();
                // load next page only if we have new data comming from the last ajax request
            }, 1000);

            setTimeout(function(){
                td_infinite_loader.compute_top_distances();
            }, 1500);
            break;
            break;

    }



    setTimeout(function(){
        jQuery('.td_block_inner_overflow').removeClass('td_block_inner_overflow');
        el_cur_td_block_inner.css('height', 'auto');

        td_smart_sidebar.compute();
    },200);




    setTimeout(function () {
        td_smart_sidebar.compute();
    }, 500);


    setTimeout(function () {
        td_animation_stack.td_events_scroll('#' + current_block_obj.id + ' .entry-thumb');
    }, 200);
}


/**
 * search by block _id
 * @param myID - block id
 * @returns {number} the index
 */
function td_getBlockIndex(myID) {
    var cnt = 0;
    var tmpReturn = 0;
    jQuery.each(td_blocks, function(index, td_block) {
        if (td_block.id === myID) {
            tmpReturn = cnt;
            return false; //brake jquery each
        } else {
            cnt++;
        }
    });
    return tmpReturn;
}

/**
 * gets the block object using a block ID
 * @param myID
 * @returns {*} block object
 */
function td_getBlockObjById(myID) {
    return td_blocks[td_getBlockIndex(myID)];
}

