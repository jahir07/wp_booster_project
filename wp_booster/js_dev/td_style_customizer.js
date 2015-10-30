/*  ----------------------------------------------------------------------------
 tagDiv live css compiler ( 2013 )
 - this script is used on our demo site to customize the theme live
 - not used on production sites
 */

/* global jQuery:{} */
/* global td_read_site_cookie:Function */
/* global td_set_cookies_life:Function */

var td_current_panel_stat = '';

(function() {

    'use strict';

    td_current_panel_stat = td_read_site_cookie( 'td_show_panel' );

    if ( 'show' === td_current_panel_stat || null === td_current_panel_stat ) {

        var jQueryObj = jQuery( '.td-theme-settings-small' );
        if ( jQueryObj.length ) {
            jQueryObj.addClass('td-theme-settings-no-transition');
            jQueryObj.removeClass('td-theme-settings-small');
        }
    }

})();



/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    'use strict';

    //hide panel
    jQuery( '#td-theme-set-hide' ).click(function(event){
        event.preventDefault();
        event.stopPropagation();
        //hide
        td_set_cookies_life( ['td_show_panel', 'hide', 86400000] );//86400000 is the number of milliseconds in a day

        var jQueryObj = jQuery( '#td-theme-settings' );
        if ( jQueryObj.length ) {
            jQueryObj.removeClass( 'td-theme-settings-no-transition' );
            jQueryObj.addClass( 'td-theme-settings-small' );
        }

        jQuery('.td-set-theme-style-link').removeClass('td_fadeInLeft');

    });

    //show panel
    jQuery( '#td-theme-settings' ).click(function(){
        if ( jQuery( this).hasClass( 'td-theme-settings-small' ) ) {

            jQuery( '.td-set-theme-style-link' ).addClass( 'td_animated_xlong td_fadeInLeft' );

            //show full
            td_set_cookies_life( ['td_show_panel', 'show', 86400000] );//86400000 is the number of milliseconds in a day
            jQuery( '.td-theme-settings-small' ).removeClass( 'td-theme-settings-small' );
        }
    });

}); //end on load