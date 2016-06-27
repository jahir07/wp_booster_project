/**
 * Created by RADU on 6/24/14.
 */

/* global jQuery: {} */

var tdTrendingNow = {};

(function() {

    "use strict";

    tdTrendingNow = {

        // - the list of items
        items: [],

        // - trending now item
        item: function item() {
            //the block Unique id
            this.blockUid = '';
            //autostart
            this.trendingNowAutostart = 'manual';
            //autostart timer
            this.trendingNowTimer = 0;
            //slider position
            this.trendingNowPosition = 0;
            //posts list
            this.trendingNowPosts = [];
            // flag used to mark the initialization item
            this._is_initialized = false;
        },

        //function used to init tdTrendingNow
        init: function() {
            tdTrendingNow.items = [];
        },

        //internal utility function used to initialize an item
        _initialize_item: function( item ) {
            // an item must be initialized only once
            if ( true === item._is_initialized ) {
                return;
            }
            // the item is marked as initialized
            item._is_initialized = true;
        },

        //add an item
        addItem: function( item ) {

            //todo - add some checks on item
            // check to see if the item is ok
            if (typeof item.blockUid === 'undefined') {
                throw 'item.blockUid is not valid';
            }
            if (typeof item.trendingNowPosts === 'undefined' || item.trendingNowPosts.length < 1) {
                throw 'item.trendingNowPosts is not valid';
            }

            // the item is added in the items list
            tdTrendingNow.items.push( item );

            // the item is initialized only once when it is added
            tdTrendingNow._initialize_item( item );

            //autostart
            tdTrendingNow.tdTrendingNowAutoStart(item.blockUid);
        },

        //deletes an item base on blockUid
        deleteItem: function( blockUid ) {
            for (var cnt = 0; cnt < tdTrendingNow.items.length; cnt++) {
                if (tdTrendingNow.items[cnt].blockUid === blockUid) {
                    tdTrendingNow.items.splice(cnt, 1); // remove the item from the "array"
                    return true;
                }
            }
            return false;
        },

        //switch to the previous item
        itemPrev: function( blockUid ) {
            //current item
            var i, currentItem;
            //get current item
            for (var cnt = 0; cnt < tdTrendingNow.items.length; cnt++) {
                if (tdTrendingNow.items[cnt].blockUid === blockUid) {
                    currentItem = tdTrendingNow.items[cnt];
                }
            }

            // if there's just a single post to be shown, there's no need for next/prev/autostart
            if ((blockUid !== undefined) && (1 >= currentItem.trendingNowPosts.length))  {
                return;
            }

            /**
             * used when the trending now block is used on auto mod and we click on show prev or show next article title
             * this will make the auto mode wait another xx seconds before displaying the next article title
             */
            if ('manual' !== currentItem.trendingNowAutostart) {
                clearInterval(currentItem.trendingNowTimer);
                currentItem.trendingNowTimer = setInterval(function () {
                    tdTrendingNow.tdTrendingNowChangeText([blockUid, 'left'], true);
                }, 3000);
            }

            //call to change the text
            tdTrendingNow.tdTrendingNowChangeText([blockUid, 'right'], false);
        },

        //switch to the next item
        itemNext: function ( blockUid ) {
            //current item
            var i, currentItem;
            //get current item
            for (var cnt = 0; cnt < tdTrendingNow.items.length; cnt++) {
                if (tdTrendingNow.items[cnt].blockUid === blockUid) {
                    currentItem = tdTrendingNow.items[cnt];
                }
            }

            // if there's just a single post to be shown, there's no need for next/prev/autostart
            if ((blockUid !== undefined) && (1 >= currentItem.trendingNowPosts.length))  {
                return;
            }

            /**
             * used when the trending now block is used on auto mod and we click on show prev or show next article title
             * this will make the auto mode wait another xx seconds before displaying the next article title
             */
            if ('manual' !== currentItem.trendingNowAutostart) {
                clearInterval(currentItem.trendingNowTimer);
                currentItem.trendingNowTimer = setInterval(function () {
                    tdTrendingNow.tdTrendingNowChangeText([blockUid, 'left'], true);
                }, 3000);
            }

            //call to change the text
            tdTrendingNow.tdTrendingNowChangeText([blockUid, 'left'], true);
        },

        /*
         function for changing the posts in `trending now` display area
         *
         *array_param[0] : the id of current `trending now wrapper`
         *array_param[1] : moving direction (left or right)
         */
        tdTrendingNowChangeText: function(array_param, to_right) {

            //for consistency use the same variables names as thh parent function
            var blockUid = array_param[0],
                movingDirection = array_param[1],
                postsArrayListForThisTrend = [],
                postsArrayListPosition = 0,
                itemPosition;

            for (var cnt = 0; cnt < tdTrendingNow.items.length; cnt++) {
                if (tdTrendingNow.items[cnt].blockUid === blockUid) {
                    itemPosition = cnt;
                    postsArrayListForThisTrend = tdTrendingNow.items[cnt].trendingNowPosts;
                    postsArrayListPosition = tdTrendingNow.items[cnt].trendingNowPosition;
                }
            }
            
            if (typeof itemPosition !== 'undefined' && itemPosition !== null) {
                var previousPostArrayListPosition = postsArrayListPosition,
                    post_count = postsArrayListForThisTrend.length - 1;//count how many post are in the list

                if ('left' === movingDirection) {
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

                //update the new position in the global `tdTrendingNow`
                tdTrendingNow.items[itemPosition].trendingNowPosition = postsArrayListPosition;

                postsArrayListForThisTrend[previousPostArrayListPosition].css('opacity', 0);
                postsArrayListForThisTrend[previousPostArrayListPosition].css('z-index', 0);

                for (var trending_post in postsArrayListForThisTrend) {
                    if (true === postsArrayListForThisTrend.hasOwnProperty(trending_post)) {
                        postsArrayListForThisTrend[trending_post].removeClass('td_animated_xlong td_fadeInLeft td_fadeInRight td_fadeOutLeft td_fadeOutRight');
                    }
                }

                postsArrayListForThisTrend[postsArrayListPosition].css('opacity', 1);
                postsArrayListForThisTrend[postsArrayListPosition].css('z-index', 1);

                if (true === to_right) {

                    postsArrayListForThisTrend[previousPostArrayListPosition].addClass('td_animated_xlong td_fadeOutLeft');
                    postsArrayListForThisTrend[postsArrayListPosition].addClass('td_animated_xlong td_fadeInRight');
                } else {

                    postsArrayListForThisTrend[previousPostArrayListPosition].addClass('td_animated_xlong td_fadeOutRight');
                    postsArrayListForThisTrend[postsArrayListPosition].addClass('td_animated_xlong td_fadeInLeft');
                }
            }
        },

        //trending now function to auto start
        tdTrendingNowAutoStart: function(blockUid) {
            for (var cnt = 0; cnt < tdTrendingNow.items.length; cnt++) {
                // if there's just a single post to be shown, there's no need for next/prev/autostart
                if (tdTrendingNow.items[cnt].blockUid === blockUid && tdTrendingNow.items[cnt].trendingNowAutostart !== 'manual') {
                    tdTrendingNow.items[cnt].trendingNowTimer = tdTrendingNow.setTimerChangingText(blockUid);
                }
            }
        },

        setTimerChangingText: function( blockUid ) {
            return setInterval(function () {
                //console.log(i + "=>" + list[i] + "\n");
                tdTrendingNow.tdTrendingNowChangeText([blockUid, 'left'], true);
            }, 3000);
        }

    };

    tdTrendingNow.init();

})();