/**
 * Created by RADU on 6/24/14.
 */

/* global jQuery: {} */

//global object that holds each `trending-now-wrapper` list of posts
//var td_trending_now_object = {};

var tdTrendingNowObject = {};

(function() {

    "use strict";

    //jQuery().ready(function() {
    //    //trending now
    //    td_trending_now();
    //
    //    //call to trending now function to start auto scroll
    //    td_trending_now_auto_start();
    //});

    tdTrendingNowObject = {

        trendingNowAutostartBlocks: [],

        //trending now function : creates the array of posts in each trend and add events to the nav buttons
        tdTrendingNow: function() {

            //move thru all `trending-now-wrapper's` on the page
            jQuery('.td-trending-now-wrapper').each(function() {
                var wrapperId = jQuery(this).attr('id');
                var wrapperIdNavigation = jQuery(this).data('start');

                if ('manual' !== wrapperIdNavigation) {
                    tdTrendingNowObject.trendingNowAutostartBlocks.push(wrapperId);
                }

                var trendingListPosts = [];
                var i_cont = 0;

                //take the text from each post from current trending-now-wrapper
                jQuery('#' + wrapperId + ' .td-trending-now-post').each(function() {
                    //trending_list_posts[i_cont] = jQuery(this)[0].outerHTML;
                    trendingListPosts[i_cont] = jQuery(this);

                    //increment the counter
                    i_cont++;
                });

                //add this array to `tdTrendingNowObject`
                tdTrendingNowObject[wrapperId] = trendingListPosts;
                tdTrendingNowObject[wrapperId + '_position'] = 0;
            });

            jQuery('.td-trending-now-nav-left').click( function(event) {
                event.preventDefault();
                var wrapperIdForNav = jQuery(this).data('wrapper-id');

                // if there's just a single post to be shown, there's no need for next/prev/autostart
                if ((undefined !== wrapperIdForNav) && (1 >= tdTrendingNowObject[wrapperIdForNav].length))  {
                    return;
                }

                //var data_moving = jQuery(this).data('moving');
                var controlStart = jQuery(this).data('control-start');

                /**
                 * used when the trending now block is used on auto mod and we click on show prev or show next article title
                 * this will make the auto mode wait another xx seconds before displaying the next article title
                 */
                if ('manual' !== controlStart) {
                    clearInterval(tdTrendingNowObject[wrapperIdForNav + '_timer']);
                    tdTrendingNowObject[wrapperIdForNav + '_timer'] = setInterval(function() {
                        tdTrendingNowObject.tdTrendingNowChangeText([wrapperIdForNav, 'left'], true);
                    }, 3000);
                }


                //call to change the text
                tdTrendingNowObject.tdTrendingNowChangeText([wrapperIdForNav, 'right'], false);
            });


            jQuery('.td-trending-now-nav-right').click(function(event) {
                event.preventDefault();
                var wrapperIdForNav = jQuery(this).data('wrapper-id');

                // if there's just a single post to be shown, there's no need for next/prev/autostart
                if ((undefined !== wrapperIdForNav) && (1 >= tdTrendingNowObject[wrapperIdForNav].length)) {
                    return;
                }

                //var data_moving = jQuery(this).data('moving');
                var controlStart = jQuery(this).data('control-start');

                /**
                 * used when the trending now block is used on auto mod and we click on show prev or show next article title
                 * this will make the auto mode wait another xx seconds before displaying the next article title
                 */
                if ('manual' !== controlStart) {
                    clearInterval(tdTrendingNowObject[wrapperIdForNav + '_timer']);
                    tdTrendingNowObject[wrapperIdForNav + '_timer'] = setInterval(function() {
                        tdTrendingNowObject.tdTrendingNowChangeText([wrapperIdForNav, 'left' ], true);
                    }, 3000);
                }

                //call to change the text
                tdTrendingNowObject.tdTrendingNowChangeText([wrapperIdForNav, 'left'], true);
            });

            //console.log(tdTrendingNowObject);
        },


        /*
         function for changing the posts in `trending now` display area
         *
         *array_param[0] : the id of current `trending now wrapper`
         *array_param[1] : moving direction (left or right)
         */
        tdTrendingNowChangeText: function(array_param, to_right) {

            //for consistency use the same variables names as thh parent function
            var wrapperIdForNav = array_param[0];
            var dataMoving = array_param[1];

            //get the list of post and position for this trending now block
            var postsArrayListForThisTrend = tdTrendingNowObject[wrapperIdForNav];


            // the following method is not so good because using it, many timers are already created
            //
            // if there's just a single post to be shown, there's no need for next/prev/autostart
            //if (posts_array_list_for_this_trend.length <= 1) {
            //    return;
            //}

            var postsArrayListPosition = tdTrendingNowObject[wrapperIdForNav + '_position'];

            var previous_post_array_list_position = postsArrayListPosition;

            //count how many post are in the list
            var post_count = postsArrayListForThisTrend.length - 1;

            if ('left' === dataMoving) {
                postsArrayListPosition += 1;

                if (postsArrayListPosition > post_count) {
                    postsArrayListPosition = 0;
                }

            } else {
                postsArrayListPosition -= 1;

                if (postsArrayListPosition < 0) {
                    postsArrayListPosition = post_count;
                }
            }

            //update the new position in the global `tdTrendingNowObject`
            tdTrendingNowObject[wrapperIdForNav + '_position'] = postsArrayListPosition;

            postsArrayListForThisTrend[previous_post_array_list_position].css('opacity', 0);
            postsArrayListForThisTrend[previous_post_array_list_position].css('z-index', 0);

            for (var trending_post in postsArrayListForThisTrend) {
                if (true === postsArrayListForThisTrend.hasOwnProperty(trending_post)) {
                    postsArrayListForThisTrend[trending_post].removeClass('td_animated_xlong td_fadeInLeft td_fadeInRight td_fadeOutLeft td_fadeOutRight');
                }
            }

            postsArrayListForThisTrend[postsArrayListPosition].css('opacity', 1);
            postsArrayListForThisTrend[postsArrayListPosition].css('z-index', 1);

            if (true === to_right) {

                postsArrayListForThisTrend[previous_post_array_list_position].addClass('td_animated_xlong td_fadeOutLeft');
                postsArrayListForThisTrend[postsArrayListPosition].addClass('td_animated_xlong td_fadeInRight');
            } else {

                postsArrayListForThisTrend[previous_post_array_list_position].addClass('td_animated_xlong td_fadeOutRight');
                postsArrayListForThisTrend[postsArrayListPosition].addClass('td_animated_xlong td_fadeInLeft');
            }
        },


        //trending now function to auto start
        tdTrendingNowAutoStart: function() {

            var list = tdTrendingNowObject.trendingNowAutostartBlocks;

            for (var i = 0, len = list.length; i < len; i += 1) {
                (function(i) {

                    // if there's just a single post to be shown, there's no need for next/prev/autostart
                    if (1 >= tdTrendingNowObject[list[i]].length) {
                        return;
                    }

                    tdTrendingNowObject[list[i] + '_timer'] = setInterval(function () {
                        //console.log(i + "=>" + list[i] + "\n");
                        tdTrendingNowObject.tdTrendingNowChangeText([list[i], 'left'], true);
                    }, 3000);
                })(i);
            }
        }
    };

})();