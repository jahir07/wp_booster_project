/**
 * Created by ra on 8/12/2015.
 */

/* global jQuery:false */
/* global tdInfiniteLoader:false */
/* global tdAnimationStack:{} */
/* global tdSmartSidebar:false */
/* global tdLoadingBox:{} */
/* global tds_theme_color_site_wide:string */


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
                        0 !== tdAjaxLoop.loopState.ajax_pagination_infinite_stop &&
                        tdAjaxLoop.loopState.currentPage >= tdAjaxLoop.loopState.ajax_pagination_infinite_stop &&
                        tdAjaxLoop.loopState.currentPage + 1 < tdAjaxLoop.loopState.max_num_pages  // do we have a next page?
                    ) {
                        // stop the callback and show the load more button
                        jQuery('.td-load-more-infinite-wrap')
                            .css('display', 'block')
                            .css('visibility', 'visible')
                        ;

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


                jQuery('.td-load-more-infinite-wrap').css('visibility', 'hidden');

                tdAjaxLoop.infiniteNextPage(true);
            });
        },


        infiniteNextPage: function (isLoadMoreButton) {

            // prepare the request object
            tdAjaxLoop.loopState.currentPage++ ;
            tdAjaxLoop.loopState.server_reply_html_data = '';

            // check here to avoid making an unnecessary ajax request when using infinite loading without button
            if ( tdAjaxLoop.loopState.currentPage > tdAjaxLoop.loopState.max_num_pages ) {
                //console.log('END' + tdAjaxLoop.loopState.currentPage + ' max: ' + tdAjaxLoop.loopState.max_num_pages);
                return;
            }



            jQuery('.td-ss-main-content').append('<div class="td-loader-gif td-loader-infinite td-loader-animation-start"></div>');
            tdLoadingBox.init(tds_theme_color_site_wide, 45);  //init the loading box
            setTimeout(function () {
                jQuery('.td-loader-gif')
                    .removeClass('td-loader-animation-start')
                    .addClass('td-loader-animation-mid');
            }, 50);


            var requestData = {
                action: 'td_ajax_loop',
                loopState: tdAjaxLoop.loopState
            };

            //console.log('request:');
            //console.log(tdAjaxLoop.loopState);
            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                cache:true,
                data: requestData,
                success: function(data, textStatus, XMLHttpRequest) {
                    tdAjaxLoop._processAjaxRequest(data, isLoadMoreButton);
                },
                error: function(MLHttpRequest, textStatus, errorThrown) {
                    //console.log(errorThrown);
                }
            });
        },

        _processAjaxRequest: function (data, isLoadMoreButton) {
            // stop the loader
            jQuery('.td-loader-gif').remove();
            tdLoadingBox.stop();

            var dataObj = jQuery.parseJSON(data);



            // empty reply - stop everything
            if ( '' === dataObj.server_reply_html_data  ) {
                jQuery('.td-load-more-infinite-wrap').css('visibility', 'hidden');
                return;
            }


            /**
             * @var {tdAjaxLoop.loopState}
             */

            jQuery('.td-ajax-loop-infinite').before(dataObj.server_reply_html_data);

            //console.log('reply:');
            //console.log(dataObj);

            if ( parseInt( dataObj.currentPage ) >= parseInt(dataObj.max_num_pages) ) {
                jQuery('.td-load-more-infinite-wrap').css('visibility', 'hidden');
            } else {
                if ( true === isLoadMoreButton ) {
                    jQuery('.td-load-more-infinite-wrap').css('visibility', 'visible');
                }
            }

            setTimeout( function () {
                tdAnimationStack.check_for_new_items('.td-main-content' + ' .td-animation-stack', tdAnimationStack.SORTED_METHOD.sort_left_to_right, true);
                //tdSmartSidebar.compute();
            }, 200);


            // on load more button, we don't have to compute the infinite loader event
            if ( true === isLoadMoreButton ) {
                return;
            }

            setTimeout( function() {
                //refresh waypoints for infinit scroll tdInfiniteLoader
                tdInfiniteLoader.computeTopDistances();
                tdInfiniteLoader.enable_is_visible_callback('tdAjaxLoop');
                //tdSmartSidebar.compute();
            }, 500);


            setTimeout( function() {
                tdInfiniteLoader.computeTopDistances();
            }, 1000);

            setTimeout( function() {
                tdInfiniteLoader.computeTopDistances();
            }, 1500);

        }
    };

})();