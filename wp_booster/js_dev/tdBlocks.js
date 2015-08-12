/**
 * tdBlocks.js
 * v3.0  5 August 2015
 * Converted to WP JS standards + jsHint
 */

/* global jQuery:false */
/* global td_ajax_url:false */
/* global tds_theme_color_site_wide:false */



/* global td_smart_sidebar:false */
/* global td_animation_stack:false */
/* global tdUtil:false */                   //done
/* global tdLoadingBox:false */             //done
/* global tdInfiniteLoader:false */         //done
/* global tdBlocksArray:false */            //done
/* global tdDetect:false */                 //done
/* global tdLocalCache:false */             //done

var tdBlocks = {};

( function() {
    "use strict";

    /*  ----------------------------------------------------------------------------
     On load
     */
    jQuery().ready( function() {
        tdOnReadyAjaxBlocks();
    });






    function tdOnReadyAjaxBlocks() {


        /*  ----------------------------------------------------------------------------
            AJAX pagination next
         */
        jQuery(".td-ajax-next-page").click( function(event) {
            event.preventDefault();

            var currentBlockObj = tdBlocks.tdGetBlockObjById(jQuery(this).data('td_block_id'));

            if ( jQuery(this).hasClass('ajax-page-disabled') || true === currentBlockObj.is_ajax_running ) {
                return;
            }

            currentBlockObj.is_ajax_running = true; // ajax is running and we're wayting for a reply from server

            currentBlockObj.td_current_page++;
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'next');
        });


        /*  ----------------------------------------------------------------------------
            AJAX pagination prev
         */
        jQuery(".td-ajax-prev-page").click( function(event) {
            event.preventDefault();

            var currentBlockObj = tdBlocks.tdGetBlockObjById(jQuery(this).data('td_block_id'));

            if ( jQuery(this).hasClass('ajax-page-disabled') || true === currentBlockObj.is_ajax_running ) {
                return;
            }

            currentBlockObj.is_ajax_running = true; // ajax is running and we're wayting for a reply from server

            currentBlockObj.td_current_page--;
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'back');
        });


        /*  ----------------------------------------------------------------------------
            AJAX pagination load more
         */
        jQuery(".td_ajax_load_more").click( function(event) {
            event.preventDefault();
            if ( jQuery(this).hasClass('ajax-page-disabled') ) {
                return;
            }

            var currentBlockObj = tdBlocks.tdGetBlockObjById(jQuery(this).data('td_block_id'));

            currentBlockObj.td_current_page++;
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'load_more');

            // load_more is hidden if there are no more posts
            if ( currentBlockObj.max_num_pages <= currentBlockObj.td_current_page ) {
                jQuery(this).addClass('ajax-page-disabled');
            }
        });



        /*  ----------------------------------------------------------------------------
            pull down open/close //on mobile devices use click event
         */
        if ( tdDetect.isMobileDevice ) {

            jQuery(".td-pulldown-filter-display-option").click( function () {
                var currentBlockUid = jQuery(this).data('td_block_id');
                jQuery("#td_pulldown_" + currentBlockUid).addClass("td-pulldown-filter-list-open");

                //animate the list
                var tdPullDownList = jQuery("#td_pulldown_" + currentBlockUid + "_list");
                tdPullDownList.removeClass('fadeOut');
                tdPullDownList.addClass('animated fadeIn'); //used for opacity animation
                //tdPullDownList.css('visibility', 'visible');
            });

            //on desktop devices use hover event
        } else {

            /**
             * (hover) open and close the drop down menu (on blocks on hover)
             */
            jQuery(".td-pulldown-filter-display-option").hover( function () {
                    // hover in
                    var current_block_uid = jQuery(this).data('td_block_id');
                    jQuery("#td_pulldown_" + current_block_uid).addClass("td-pulldown-filter-list-open");

                    //animate the list
                    var tdPullDownList = jQuery("#td_pulldown_" + current_block_uid + "_list");
                    tdPullDownList.removeClass('fadeOut');
                    tdPullDownList.addClass('animated fadeIn'); //used for opacity animation
                    tdPullDownList.css('visibility', 'visible');

                },
                function () {
                    // hover out
                    var currentBlockUid = jQuery(this).data('td_block_id');
                    jQuery("#td_pulldown_" + currentBlockUid).removeClass("td-pulldown-filter-list-open");


                }
            );
        }



        /*  ----------------------------------------------------------------------------
            click on related posts in single posts
         */
        jQuery('.td-related-title a').click( function(event) {
            event.preventDefault();

            jQuery('.td-related-title').children('a').removeClass('td-cur-simple-item');
            jQuery(this).addClass('td-cur-simple-item');

            //get the current block id
            var currentBlockUid = jQuery(this).data('td_block_id');

            //get current block
            var currentBlockObj = tdBlocks.tdGetBlockObjById(currentBlockUid);

            //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
            currentBlockObj.td_filter_value = jQuery(this).data('td_filter_value');

            currentBlockObj.td_current_page = 1; //reset the page

            //do request
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'pull_down');
        });




        /*  ----------------------------------------------------------------------------
            MEGA MENU
         */
        // Used to simulate on mobile doubleclick at 300ms @see the function tdAjaxSubCatMegaRun()
        var tdSubCatMegaRunLink = false;   // run the link if this is true, instead of loading via ajax the mega menu content
        var tdSubCatMegaLastTarget = '';   // last event target - to make sure the double click is on the same element


        /**
         * On touch screens check for double click and redirect to the subcategory page if that's the case,
         * if not double click... do the normal ajax request
         * @param event
         * @param jQueryObject
         */
        function tdAjaxSubCatMegaRunOnTouch(event, jQueryObject) {
            if ( (true === tdSubCatMegaRunLink) && (event.target === tdSubCatMegaLastTarget) ) {
                window.location = event.target;
            } else {
                tdSubCatMegaRunLink = true;
                tdSubCatMegaLastTarget = event.target;
                event.preventDefault();

                setTimeout( function() {
                    tdSubCatMegaRunLink = false;
                }, 300);

                tdAjaxSubCatMegaRun(event, jQueryObject);
            }
        }

        /**
         * this one makes the ajax request for mega menu filter
         * hover or click on mega menu subcategories
         */
        function tdAjaxSubCatMegaRun(event, jQueryObject) {
            /* global this:false */
            //get the current block id
            var currentBlockUid = jQueryObject.data('td_block_id');
            var currentBlockObj = tdBlocks.tdGetBlockObjById(currentBlockUid);

            // on mega menu, we allow parallel ajax request for better UI. We set is_ajax_running so that the preloader cache will work as expected
            currentBlockObj.is_ajax_running = true;

            //switch cur cat
            jQuery('.mega-menu-sub-cat-' + currentBlockUid).removeClass('cur-sub-cat');
            jQueryObject.addClass('cur-sub-cat');

            //get current block


            //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
            currentBlockObj.td_filter_value = jQueryObject.data('td_filter_value');

            currentBlockObj.td_current_page = 1; //reset the page

            //do request
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'mega_menu');
        }


        /**
         * Mega menu filters
         */
        //on touch devices use click
        // @todo needs testing to determine why we need .click and touchend?
        // @todo trebuie refactorizata sa transmita jQuery(this) de aici la functii
        if ( tdDetect.isTouchDevice ) {
            jQuery(".block-mega-child-cats a")
                .click( function(event) {
                    tdAjaxSubCatMegaRunOnTouch(event, jQuery(this));
                }, false)
                .each(function(index, element) {
                    element.addEventListener('touchend', function(event) {
                        tdAjaxSubCatMegaRunOnTouch(event, jQuery(this));
                    }, false);
                });

        } else {
            jQuery(".block-mega-child-cats a").hover( function(event) {
                tdAjaxSubCatMegaRun(event, jQuery(this));
            }, function (event) {} );
        }



        /*  ----------------------------------------------------------------------------
            Subcategories
         */
        /**
         * Newspaper ONLY
         * used by the drop down ajax filter on blocks
         */
        jQuery('.td-subcat-item a').click( function(event) {
            event.preventDefault();

            var currentBlockObj = tdBlocks.tdGetBlockObjById(jQuery(this).data('td_block_id'));

            //if ( jQuery(this).hasClass('ajax-page-disabled') || true === currentBlockObj.is_ajax_running ) {
            //    return;
            //}
            //
            if ( true === currentBlockObj.is_ajax_running ) {
                return;
            }


            currentBlockObj.is_ajax_running = true; // ajax is running and we're waiting for a reply from server


            jQuery('.' + jQuery(this).data('td_block_id') + '_rand').find('.td-cur-simple-item').removeClass('td-cur-simple-item');
            jQuery(this).addClass('td-cur-simple-item');


            //change current filter value - the filter type is read by td_ajax from the atts of the shortcode
            currentBlockObj.td_filter_value = jQuery(this).data('td_filter_value');



            //reset the page
            currentBlockObj.td_current_page = 1;

            // we ues 'pull_down' just for the 'animated_xlong fadeInDown' effect
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'pull_down');
        });


        /**
         * Newsmag ONLY
         * when a item is from the dropdown menu is clicked (on all the blocks)
         * @todo asta face ceva cu ios slider-ul in plus fata de aia de pe Newspaper
         */
        jQuery(".td-pulldown-filter-link").click( function(event) {
            event.preventDefault();



            //get the current block id
            var currentBlockUid = jQuery(this).data('td_block_id');

            //destroy any iossliders to avoid bugs
            jQuery('#' + currentBlockUid).find('.iosSlider').iosSlider('destroy');

            //get current block
            var currentBlockObj = tdBlocks.tdGetBlockObjById(currentBlockUid);
            if ( true === currentBlockObj.is_ajax_running ) {
                return;
            }

            currentBlockObj.is_ajax_running = true;
            //change current filter value - the filter type is readed by td_ajax from the atts of the shortcode
            currentBlockObj.td_filter_value = jQuery(this).data('td_filter_value');


            currentBlockObj.td_current_page = 1;


            //put loading... text and hide the dropdown @todo - tranlation pt loading
            //tdBlocks.tdPullDownFilterChangeValue(currentBlockObj.id, '<span>Loading... </span><i class="td-icon-menu-down"></i>');
            tdBlocks.tdPullDownFilterChangeValue(currentBlockUid, '<span>' + jQuery(this).html() + ' </span><i class="td-icon-menu-down"></i>');


            //hide the dropdown
            jQuery('#td_pulldown_' + currentBlockUid).removeClass("td-pulldown-filter-list-open");


            //do request
            tdBlocks.tdAjaxDoBlockRequest(currentBlockObj, 'pull_down');


            //on mobile devices stop event propagation
            if ( tdDetect.isMobileDevice ) {
                tdUtil.stopBubble(event);
            }

        });
    } // end tdOnReadyAjaxBlocks()






    tdBlocks = {


        /**
         * Newsmag ONLY change the pull down filter value to loading... and to the current category after an ajax reply
         * is received
         * @param td_block_uid
         * @param td_text
         */
        tdPullDownFilterChangeValue: function(td_block_uid, td_text) {
            jQuery('#td-pulldown-' + td_block_uid + '-val').html(td_text);
        },



        /**
         * makes a ajax block request
         * @param current_block_obj
         * @param td_user_action - load more or infinite loader (used by the animation)
         * @returns {string}
         */
        tdAjaxDoBlockRequest: function(current_block_obj, td_user_action) {
            //search the cache
            var currentBlockObjSignature = JSON.stringify(current_block_obj);
            if ( tdLocalCache.exist(currentBlockObjSignature) ) {
                //do the animation with cache hit = true
                tdBlocks.tdBlockAjaxLoadingStart(current_block_obj, true, td_user_action);
                tdBlocks.tdAjaxBlockProcessResponse(tdLocalCache.get(currentBlockObjSignature), td_user_action);
                return 'cache_hit'; //cache HIT
            }


            //cache miss - we make a full request! - cache hit - false
            tdBlocks.tdBlockAjaxLoadingStart(current_block_obj, false, td_user_action);

            var requestData = {
                action: 'td_ajax_block',
                td_atts: current_block_obj.atts,
                td_block_id:current_block_obj.id,
                td_column_number:current_block_obj.td_column_number,
                td_current_page:current_block_obj.td_current_page,
                block_type:current_block_obj.block_type,
                td_filter_value:current_block_obj.td_filter_value,
                td_user_action:current_block_obj.td_user_action
            };

            //console.log('tdAjaxDoBlockRequest:');
            //console.log(requestData);

            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                cache:true,
                data: requestData,
                success: function(data, textStatus, XMLHttpRequest) {

                    tdLocalCache.set(currentBlockObjSignature, data);
                    tdBlocks.tdAjaxBlockProcessResponse(data, td_user_action);
                },
                error: function(MLHttpRequest, textStatus, errorThrown) {
                    //console.log(errorThrown);
                }
            });
        },


        /**
         * process the response from the ajax query (it also processes the responses stored in the cache)
         * @param data
         * @param td_user_action - load more or infinite loader (used by the animation)
         */
        tdAjaxBlockProcessResponse: function(data, td_user_action) {

            //read the server response
            var tdReplyObj = jQuery.parseJSON(data); //get the data object

            //console.log('tdAjaxBlockProcessResponse:');
            //console.log(tdReplyObj);
            /*
             td_data_object.td_block_id
             td_data_object.td_data
             td_data_object.td_cur_cat
             */






            //load the content (in place or append)
            if ( 'load_more' === td_user_action || 'infinite_load' === td_user_action ) {

                // fix needed to keep sidebars fixed down when they are bottom of the content and the content grows up
                for ( var i = 0; i < td_smart_sidebar.items.length; i++ ) {
                    if ( 'case_3_bottom_of_content' === td_smart_sidebar.items[i].sidebar_state ) {
                        td_smart_sidebar.items[i].sidebar_state = 'case_1_fixed_down';
                    }
                }

                jQuery(tdReplyObj.td_data).appendTo('#' + tdReplyObj.td_block_id);
                //jQuery(tdReplyObj.td_data).addClass('animated_xxlong').appendTo('#' + tdReplyObj.td_block_id).addClass('fadeIn');
                //jQuery('#' + tdReplyObj.td_block_id).append(tdReplyObj.td_data); //append
            } else {
                jQuery('#' + tdReplyObj.td_block_id).html(tdReplyObj.td_data); //in place
            }


            //hide or show prev
            if ( true === tdReplyObj.td_hide_prev ) {
                jQuery('#prev-page-' + tdReplyObj.td_block_id).addClass('ajax-page-disabled');
            } else {
                jQuery('#prev-page-' + tdReplyObj.td_block_id).removeClass('ajax-page-disabled');
            }

            //hide or show next
            if ( true === tdReplyObj.td_hide_next ) {
                jQuery('#next-page-' + tdReplyObj.td_block_id).addClass('ajax-page-disabled');
            } else {
                jQuery('#next-page-' + tdReplyObj.td_block_id).removeClass('ajax-page-disabled');
            }


            var  currentBlockObj = tdBlocks.tdGetBlockObjById(tdReplyObj.td_block_id);
            if ( 'slide' === currentBlockObj.block_type ) {
                //make the first slide active (to have caption)
                jQuery('#' + tdReplyObj.td_block_id + ' .slide-wrap-active-first').addClass('slide-wrap-active');
            }

            currentBlockObj.is_ajax_running = false; // finish the loading for this block


            //loading effects
            tdBlocks.tdBlockAjaxLoadingEnd(tdReplyObj, currentBlockObj, td_user_action);   //td_user_action - load more or infinite loader (used by the animation)
        },



        /**
         * loading start
         * @param current_block_obj
         * @param cache_hit boolean - is true if we have a cache hit
         * @param td_user_action - the request type / infinite_load ?
         */
        tdBlockAjaxLoadingStart: function(current_block_obj, cache_hit, td_user_action) {

            //get the element
            var elCurTdBlockInner = jQuery('#' + current_block_obj.id);

            //remove any remaining loaders
            jQuery('.td-loader-gif').remove();

            //remove animation classes
            elCurTdBlockInner.removeClass('fadeInRight fadeInLeft fadeInDown fadeInUp animated_xlong');

            elCurTdBlockInner.addClass('td_block_inner_overflow');
            //auto height => fixed height
            var tdTmpBlockHeight = elCurTdBlockInner.height();
            elCurTdBlockInner.css('height', tdTmpBlockHeight);


            //show the loader only if we have a cache MISS
            if ( false === cache_hit ) {
                if ( 'load_more' === td_user_action ) {
                    // on load more
                    elCurTdBlockInner.parent().append('<div class="td-loader-gif td-loader-gif-bottom td-loader-animation-start"></div>');
                    tdLoadingBox.init(current_block_obj.header_color ? current_block_obj.header_color : tds_theme_color_site_wide);  //init the loading box
                    setTimeout( function(){
                        jQuery('.td-loader-gif')
                            .removeClass('td-loader-animation-start')
                            .addClass('td-loader-animation-mid');
                    },50);

                } else if ( 'infinite_load' !== td_user_action ) {
                    /**
                     * the default animation if the user action is NOT load_more or infinite_load
                     * infinite load has NO animation !
                     */
                    elCurTdBlockInner.parent().append('<div class="td-loader-gif td-loader-animation-start"></div>');
                    tdLoadingBox.init(current_block_obj.header_color ? current_block_obj.header_color : tds_theme_color_site_wide);         //init the loading box (the parameter is the block title background color or tds_theme_color_site_wide)
                    setTimeout( function(){
                        jQuery('.td-loader-gif')
                            .removeClass('td-loader-animation-start')
                            .addClass('td-loader-animation-mid');
                    },50);
                    elCurTdBlockInner.addClass('animated_long fadeOut_to_1');

                }
            } // end cache_hit if
        },



        /**
         * we have a reply from the ajax request
         * @param td_reply_obj - the reply object that we got from the server, it's useful with infinite load
         * @param current_block_obj
         * @param td_user_action - load more or infinite loader (used by the animation)
         */
        tdBlockAjaxLoadingEnd: function(td_reply_obj, current_block_obj, td_user_action) {

            //jQuery('.td-loader-gif').remove();
            // remove the loader
            jQuery('.td-loader-gif')
                .removeClass('td-loader-animation-mid')
                .addClass('td-loader-animation-end');
            setTimeout( function() {
                jQuery('.td-loader-gif').remove();
                //stop the loading box
                tdLoadingBox.stop();
            },400);


            //get the current inner
            var elCurTdBlockInner = jQuery('#' + current_block_obj.id);

            elCurTdBlockInner.removeClass('animated_long fadeOut_to_1');


            // by default, the sort method used to animate the ajax response is left to the right

            var tdAnimationStackSortType;

            if ( true === td_animation_stack.activated ) {
                tdAnimationStackSortType = td_animation_stack.SORTED_METHOD.sort_left_to_right;
            }

            switch(td_user_action) {
                case 'next':
                    elCurTdBlockInner.addClass('animated_xlong fadeInRight');

                    // the default sort method is modified to work from right to the left
                    if ( undefined !== tdAnimationStackSortType ) {
                        tdAnimationStackSortType = td_animation_stack.SORTED_METHOD.sort_right_to_left;
                    }

                    break;
                case 'back':
                    elCurTdBlockInner.addClass('animated_xlong fadeInLeft');
                    break;

                case 'pull_down':
                    elCurTdBlockInner.addClass('animated_xlong fadeInDown');
                    break;

                case 'mega_menu':
                    elCurTdBlockInner.addClass('animated_xlong fadeInDown');
                    break;

                case 'infinite_load':
                    setTimeout( function() {
                        //refresh waypoints for infinit scroll tdInfiniteLoader
                        tdInfiniteLoader.computeTopDistances();
                        if ( '' !== td_reply_obj.td_data  ) {
                            tdInfiniteLoader.enable_is_visible_callback(current_block_obj.id);
                        }
                    }, 500);


                    setTimeout( function() {
                        tdInfiniteLoader.computeTopDistances();
                        // load next page only if we have new data coming from the last ajax request
                    }, 1000);

                    setTimeout( function() {
                        tdInfiniteLoader.computeTopDistances();
                    }, 1500);
                    break;

            }



            setTimeout( function() {
                jQuery('.td_block_inner_overflow').removeClass('td_block_inner_overflow');
                elCurTdBlockInner.css('height', 'auto');

                td_smart_sidebar.compute();
            },200);




            setTimeout( function () {
                td_smart_sidebar.compute();
            }, 500);



            // the .entry-thumb are searched for in the current block object, sorted and added into the view port array items
            if ( undefined !== tdAnimationStackSortType ) {
                setTimeout( function () {
                    td_animation_stack.check_for_new_items('#' + current_block_obj.id + ' .td-animation-stack', tdAnimationStackSortType, true);
                }, 200);
            }
        },

        /**
         * search by block _id
         * @param myID - block id
         * @returns {number} the index
         */
        tdGetBlockIndex: function(myID) {
            var cnt = 0;
            var tmpReturn = 0;
            jQuery.each(tdBlocksArray, function(index, td_block) {
                if ( td_block.id === myID ) {
                    tmpReturn = cnt;
                    return false; //brake jquery each
                } else {
                    cnt++;
                }
            });
            return tmpReturn;
        },



        /**
         * gets the block object using a block ID
         * @param myID
         * @returns {*} block object
         */
        tdGetBlockObjById: function(myID) {
            return tdBlocksArray[tdBlocks.tdGetBlockIndex(myID)];
        }





    };  //end tdBlocks



})();









