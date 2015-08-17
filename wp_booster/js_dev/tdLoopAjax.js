/**
 * Created by ra on 8/12/2015.
 */

/* global jQuery:false */
/* global tdInfiniteLoader:false */
/* global td_animation_stack:false */
/* global td_smart_sidebar:false */

/* global td_ajax_url:false */

/**
 *   tdAjaxLoop.init() is called from: @see includes/wp_booster/td_page_generator::render_infinite_pagination
 */


var tdAjaxLoop = {};




(function () {
    'use strict';




    tdAjaxLoop = {
        loopState: {
            'sidebarPosition': '',
            'moduleId': 1,
            'currentPage': 1,
            'max_num_pages': 0,
            'atts' : {},
            'ajax_pagination_infinite_stop' : 0,
            'server_reply_html_data': ''
        },


        /**
         *   tdAjaxLoop.init() is called from: @see includes/wp_booster/td_page_generator::render_infinite_pagination
         *   only when needed
         */
        init: function () {
            jQuery('.td-ajax-loop-infinite').each( function() {
                // create a new infinite loader item
                var tdInfiniteLoaderItem = new tdInfiniteLoader.item();

                tdInfiniteLoaderItem.jqueryObj = jQuery(this);
                tdInfiniteLoaderItem.uid = 'tdAjaxLoop';


                /**
                 * the callback when the bottom of the element is visible on screen and we need to do something - like load another page
                 * - the callback does not fire again until tdInfiniteLoader.enable_is_visible_callback is called @see tdInfiniteLoader.js:95
                 */
                tdInfiniteLoaderItem.isVisibleCallback = function () {      // the is_visible callback is called when we have to pull new content up because the element is visible

                    if (
                        tdAjaxLoop.loopState.currentPage >= tdAjaxLoop.loopState.ajax_pagination_infinite_stop &&
                        tdAjaxLoop.loopState.currentPage + 1 < tdAjaxLoop.loopState.max_num_pages  // do we have a next page?
                    ) {
                        // stop the callback and show the load more button
                        jQuery('.td-load-more-infinite-wrap').show();
                    } else {
                        // load up the next page
                        tdAjaxLoop.infiniteNextPage(false);
                    }
                };
                tdInfiniteLoader.addItem(tdInfiniteLoaderItem);
            });


            // click on load more - the button should not be visible only when the  ajax_pagination_infinite_stop limit is reached
            jQuery('.td-load-more-infinite-wrap').click(function(event) {
                event.preventDefault();

                if (tdAjaxLoop.loopState.currentPage >= tdAjaxLoop.loopState.max_num_pages) {
                    jQuery(this).hide();
                }

                tdAjaxLoop.infiniteNextPage(true);
            });
        },


        infiniteNextPage: function (isLoadMoreButton) {

            // check here to avoid making an unnecessary ajax request when using infinite loading without button
            if ( tdAjaxLoop.loopState.currentPage >= tdAjaxLoop.loopState.max_num_pages ) {
                console.log('END' + tdAjaxLoop.loopState.currentPage + ' max: ' + tdAjaxLoop.loopState.max_num_pages);
                return;
            }

            // prepare the request object
            tdAjaxLoop.loopState.currentPage++ ;
            tdAjaxLoop.loopState.server_reply_html_data = '';

            var requestData = {
                action: 'td_ajax_loop',
                loopState: tdAjaxLoop.loopState
            };

            console.log(tdAjaxLoop.loopState);

            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                cache:true,
                data: requestData,
                success: function(data, textStatus, XMLHttpRequest) {
                    tdAjaxLoop._processAjaxRequest(data);
                 },
                error: function(MLHttpRequest, textStatus, errorThrown) {
                    //console.log(errorThrown);
                }
            });


        },





        _processAjaxRequest: function (data) {

            /**
             * @var {tdAjaxLoop.loopState}
             */
            var dataObj = jQuery.parseJSON(data);

            jQuery('.td-ajax-loop-infinite').before(dataObj.server_reply_html_data);


            console.log(dataObj);

            if (dataObj)
            //jQuery('.td-load-more-infinite-wrap').show();

            setTimeout( function () {
                td_animation_stack.check_for_new_items('.td-main-content' + ' .td-animation-stack', td_animation_stack.SORTED_METHOD.sort_left_to_right, true);
                //td_smart_sidebar.compute();
            }, 200);

            setTimeout( function() {
                //refresh waypoints for infinit scroll tdInfiniteLoader
                tdInfiniteLoader.computeTopDistances();
                if ( '' !== dataObj.server_reply_html_data  ) {
                    tdInfiniteLoader.enable_is_visible_callback('tdAjaxLoop');
                }

                //td_smart_sidebar.compute();
            }, 500);


            setTimeout( function() {
                tdInfiniteLoader.computeTopDistances();
            }, 1000);

            setTimeout( function() {
                tdInfiniteLoader.computeTopDistances();
            }, 1500);



            //console.log('ajax_reply');
            //console.log(dataObj);
        }
    };






})();