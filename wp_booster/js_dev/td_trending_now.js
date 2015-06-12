/**
 * Created by RADU on 6/24/14.
 */

"use strict";

/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {
    //trending now
    td_trending_now();

    //call to trending now function to start auto scroll
    td_trending_now_auto_start();
});


/*  ----------------------------------------------------------------------------
 trending now
 */

//global object that holds each `trending-now-wrapper` list of posts
var td_trending_now_object = {
    trending_now_autostart_blocks:[]
};

//trending now function : creates the array of posts in each trend and add events to the nav buttons
function td_trending_now() {

    //move thru all `trending-now-wrapper's` on the page
    jQuery(".td-trending-now-wrapper").each(function() {
        var wrapper_id = jQuery(this).attr("id");
        var wrapper_id_navigation = jQuery(this).data("start");

        if(wrapper_id_navigation != 'manual') {
            td_trending_now_object.trending_now_autostart_blocks.push(wrapper_id);
        }

        var trending_list_posts = [];
        var i_cont = 0;

        //take the text from each post from current trending-now-wrapper
        jQuery("#" + wrapper_id + " .td-trending-now-post").each(function() {
            //trending_list_posts[i_cont] = jQuery(this)[0].outerHTML;
            trending_list_posts[i_cont] = jQuery(this);

            //increment the counter
            i_cont++;
        });

        //add this array to `td_trending_now_object`
        td_trending_now_object[wrapper_id] = trending_list_posts;
        td_trending_now_object[wrapper_id + '_position'] = 0;
    });

    jQuery(".td-trending-now-nav-left").click(function(event){
        event.preventDefault();
        var wrapper_id_for_nav = jQuery(this).data("wrapper-id");
        var data_moving = jQuery(this).data("moving");
        var control_start = jQuery(this).data("control-start");

        /**
         * used when the trending now block is used on auto mod and we click on show prev or show next article title
         * this will make the auto mode wait another xx seconds before displaying the next article title
         */
        if(control_start != 'manual'){
            clearInterval(td_trending_now_object[wrapper_id_for_nav + "_timer"]);
            td_trending_now_object[wrapper_id_for_nav + "_timer"] = setInterval(function() {td_trending_now_change_text([wrapper_id_for_nav, 'left'], true);}, 3000);
        }


        //call to change the text
        td_trending_now_change_text([wrapper_id_for_nav, 'right'], false);
    });


    jQuery(".td-trending-now-nav-right").click(function(event){
        event.preventDefault();
        var wrapper_id_for_nav = jQuery(this).data("wrapper-id");
        var data_moving = jQuery(this).data("moving");
        var control_start = jQuery(this).data("control-start");

        /**
         * used when the trending now block is used on auto mod and we click on show prev or show next article title
         * this will make the auto mode wait another xx seconds before displaying the next article title
         */
        if(control_start != 'manual'){
            clearInterval(td_trending_now_object[wrapper_id_for_nav + "_timer"]);
            td_trending_now_object[wrapper_id_for_nav + "_timer"] = setInterval(function() {td_trending_now_change_text([wrapper_id_for_nav, 'left'], true);}, 3000);
        }

        //call to change the text
        td_trending_now_change_text([wrapper_id_for_nav, 'left'], true);
    });

    //console.log(td_trending_now_object);
}


/*
 function for changing the posts in `trending now` display area
 *
 *array_param[0] : the id of current `trending now wrapper`
 *array_param[1] : moving direction (left or right)
 */
function td_trending_now_change_text(array_param, to_right) {

    //for consistency use the same variables names as thh parent function
    var wrapper_id_for_nav = array_param[0];
    var data_moving = array_param[1];

    //get the list of post and position for this trending now block
    var posts_array_list_for_this_trend = td_trending_now_object[wrapper_id_for_nav];

    // if there's just a single post to be shown, there's no need for next/prev/autostart
    if (posts_array_list_for_this_trend.length <= 1) {
        return;
    }

    var posts_array_list_position = td_trending_now_object[wrapper_id_for_nav + '_position'];

    var previous_post_array_list_position = posts_array_list_position;

    //count how many post are in the list
    var post_count = posts_array_list_for_this_trend.length - 1;

    if(data_moving == "left") {
        posts_array_list_position += 1;

        if(posts_array_list_position > post_count) {
            posts_array_list_position = 0;
        }

    } else {
        posts_array_list_position -= 1;

        if(posts_array_list_position < 0) {
            posts_array_list_position = post_count;
        }
    }

    //update the new position in the global `td_trending_now_object`
    td_trending_now_object[wrapper_id_for_nav + '_position'] = posts_array_list_position;

    posts_array_list_for_this_trend[previous_post_array_list_position].css('opacity', 0);
    posts_array_list_for_this_trend[previous_post_array_list_position].css('z-index', 0);

    for (var trending_post in posts_array_list_for_this_trend) {
        posts_array_list_for_this_trend[trending_post].removeClass('animated_xlong fadeInLeft fadeInRight fadeOutLeft fadeOutRight');
    }

    posts_array_list_for_this_trend[posts_array_list_position].css('opacity', 1);
    posts_array_list_for_this_trend[posts_array_list_position].css('z-index', 1);

    if (to_right === true) {

        posts_array_list_for_this_trend[previous_post_array_list_position].addClass('animated_xlong fadeOutLeft');
        posts_array_list_for_this_trend[posts_array_list_position].addClass('animated_xlong fadeInRight');
    } else {

        posts_array_list_for_this_trend[previous_post_array_list_position].addClass('animated_xlong fadeOutRight');
        posts_array_list_for_this_trend[posts_array_list_position].addClass('animated_xlong fadeInLeft');
    }
}


//trending now function to auto start
function td_trending_now_auto_start() {

    var list = td_trending_now_object.trending_now_autostart_blocks;

    for (var i = 0, len = list.length; i < len; i += 1) {
        (function(i) {
            td_trending_now_object[list[i] + "_timer"] = setInterval(function() {
                //console.log(i + "=>" + list[i] + "\n");
                td_trending_now_change_text([list[i], 'left'], true);
            }, 3000)
        })(i);
    }
}