/* global jQuery:{} */
/* global tdUtil:{} */
/* global tdTrendingNow:{} */

jQuery( window ).load(function() {

    'use strict';

    jQuery( 'body' ).addClass( 'td-js-loaded' );

    window.tdAnimationStack.init();
});

jQuery( window ).ready(function() {

    'use strict';

    /*
     - code used to allow external links from td_smart_list, when the Google Yoast "Track outbound click and downloads" is checked
     - internal links ("#with-hash") are allowed too
     - test the links on incognito, by default Google analytics by yoast ignores the Administrator and Editor users
     */

    jQuery( '.td_smart_list_1 a, .td_smart_list_3 a').click(function( event ) {
        if ( event.target === event.currentTarget ) {
            var targetAttributeContent = jQuery( this ).attr( 'target' );
            var donwloadAttributeIsSet = jQuery( this )[0].hasAttribute( 'download' );
            var currentUrl = jQuery( this ).attr( 'href' );
            //if target is _blank open the link in a new window
            if (donwloadAttributeIsSet) {
                //link contains download attribute - do nothing, let it download
            } else if (targetAttributeContent == '_blank') {
                event.preventDefault();
                window.open(currentUrl);
            } else {
            //regular links
                if (( window.location.href !== currentUrl ) && tdUtil.isValidUrl(currentUrl)) {
                    window.location.href = currentUrl;
                }
            }
        }
    });

    //trending now
    jQuery('.td_block_trending_now').each(function(){
        var item = new tdTrendingNow.item(),
            wrapper = jQuery(this).find('.td-trending-now-wrapper'),
            autoStart = wrapper.data('start'),
            iCont = 0;

        //block unique ID
        item.blockUid = jQuery(this).data('td-block-uid');

        //set trendingNowAutostart
        if (autoStart !== 'manual') {
            item.trendingNowAutostart = autoStart;
        }

        //take the text from each post from current trending-now-wrapper
        jQuery('#' + item.blockUid + ' .td-trending-now-post').each(function() {
            //trending_list_posts[i_cont] = jQuery(this)[0].outerHTML;
            item.trendingNowPosts[iCont] = jQuery(this);
            //increment the counter
            iCont++;
        });
        //add the item
        tdTrendingNow.addItem(item);

    });
    jQuery('.td-trending-now-nav-left').on('click', function(event) {
        event.preventDefault();
        var blockUid = jQuery(this).data('block-id');
        tdTrendingNow.itemPrev(blockUid);
    });
    jQuery('.td-trending-now-nav-right').on('click', function(event) {
        event.preventDefault();
        var blockUid = jQuery(this).data('block-id');
        tdTrendingNow.itemNext(blockUid);

    });

    //trending now
    //tdTrendingNowObj.tdTrendingNow();

    //call to trending now function to start auto scroll
    //tdTrendingNowObj.tdTrendingNowAutoStart();
});
