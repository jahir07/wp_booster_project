/* global jQuery:{} */
/* global tdUtil:{} */
/* global tdTrendingNowObject:{} */

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
     */

    jQuery( '.td_smart_list_1 a, .td_smart_list_3 a' ).click(function( event ) {

        if ( event.target === event.currentTarget ) {
            var currentUrl = jQuery( this ).attr( 'href' );

            if ( ( window.location.href !== currentUrl ) && tdUtil.isValidUrl( currentUrl ) ) {
                window.location.href = currentUrl;
            }
        }
    });

    //trending now
    tdTrendingNowObject.tdTrendingNow();

    //call to trending now function to start auto scroll
    tdTrendingNowObject.tdTrendingNowAutoStart();
});
