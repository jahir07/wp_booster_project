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
            jQueryObj.removeClass( 'td-theme-settings-small' );
            jQueryObj.find( '.td-set-theme-style-link' ).addClass( 'td_animated_xlong td_fadeInLeft' );
        }
    }

})();



/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    'use strict';

    // The timer waiting to start de interval
    var startTimeout;

    // The interval that decreases the padding-left css value and increases the left css value of the screen demo (previewer of the demo)
    var startInterval;


    //hide panel
    jQuery( '#td-theme-set-hide' ).click(function(event ){
        event.preventDefault();
        event.stopPropagation();

        var jQueryObj = jQuery( '#td-theme-settings' );

        if ( jQueryObj.length ) {
            if ( jQueryObj.hasClass( 'td-theme-settings-small' ) ) {
                jQueryObj.removeClass( 'td-theme-settings-small' );
                jQueryObj.find( '.td-set-theme-style-link' ).addClass( 'td_animated_xlong td_fadeInLeft' );

                //show full
                td_set_cookies_life( ['td_show_panel', 'show', 86400000] );//86400000 is the number of milliseconds in a day
            } else {
                jQueryObj.addClass( 'td-theme-settings-small' );
                jQueryObj.removeClass( 'td-theme-settings-no-transition' );
                jQueryObj.find( '.td-set-theme-style-link' ).removeClass( 'td_fadeInLeft' );

                //hide
                td_set_cookies_life( ['td_show_panel', 'hide', 86400000] );//86400000 is the number of milliseconds in a day
            }
        }
    });

    jQuery( '.td-set-theme-style-link' ).hover(

        // The mouse enter event handler
        function( event ) {

            //console.log( 'in MAIN ' + contor++);

            // Any existing timeout is cleard to stop any further css settings
            if ( undefined !== startTimeout ) {
                window.clearTimeout( startTimeout );
            }

            // Any existing interval is cleard to stop any further css settings
            if ( undefined !== startInterval ) {
                window.clearInterval( startInterval );
            }

            var
                // The css class of the container element
                cssClassContainer = 'td-set-theme-style',

                // The jquery object of the current element
                $this = jQuery(this),

                // The jquery object of the container of the current element
                $thisContainer = $this.closest( '.' + cssClassContainer ),

                // The demo previewer
                jQueryDisplayEl = jQuery( '.td-screen-demo:first' ),

                // The ref top value considers the existing of the wpadminbar element
                refTopValue = 0,

                // The top value set to the css top setting
                topValue = 0,

                // The left value set to the css left setting
                leftValue = 0,

                // The padding value set to the css padding-left setting
                paddingLeftValue = 0,

                // The extra value added to the css padding-left setting and removed from the css left setting (if we need to start earlier or later - does nothing with 0 value)
                extraLeftValue = 0,

                // The jquery wpadminbar element
                jqWPAdminBar = jQuery( '#wpadminbar' );


            jQueryDisplayEl.find( 'img:first' ).attr( 'src', $this.data( 'screen-url' ));

            // The second (paired) column
            if ( 0 === jQuery( '.td-set-theme-style-link' ).index( this ) % 2 ) {
                topValue = $thisContainer.position().top + $thisContainer.outerHeight() / 2 - jQueryDisplayEl.outerHeight(true) / 2;
                leftValue = $thisContainer.outerWidth(true) - extraLeftValue;
                paddingLeftValue = $thisContainer.outerWidth(true) + extraLeftValue;

            // The first (impaired) column
            } else {
                var $thisPrevContainer = $thisContainer.prev( '.' + cssClassContainer );

                if ( $thisPrevContainer.length ) {
                    topValue = $thisPrevContainer.position().top + $thisContainer.outerHeight() / 2 - jQueryDisplayEl.outerHeight(true) / 2;
                    leftValue = $thisPrevContainer.outerWidth(true) * 2;
                }
            }


            // Do not allow displaying the previewer demo below the bottom of the window screen
            if ( topValue + jQueryDisplayEl.outerHeight(true) > window.innerHeight ) {
                topValue -= (topValue + jQueryDisplayEl.outerHeight(true)) - window.innerHeight;
            }

            // Do not allow displaying the previewer demo above the top of the window screen. It also checks if the wpadminbar exists.
            if ( jqWPAdminBar.length ) {
                refTopValue = jqWPAdminBar.outerHeight(true);

                if ( refTopValue > topValue ) {
                    topValue = refTopValue;
                }
            }


            // Apply the computed css to the element
            jQueryDisplayEl.css({
                'top' : topValue,
                'left' : leftValue,
                'padding-left': paddingLeftValue
            });

            // The 'left-value' data will be used to set 'left' css value when the computed padding is < 0
            jQueryDisplayEl.data( 'left-value', leftValue + paddingLeftValue );

            jQueryDisplayEl.show();
        },

        // The mouse exit event handler
        function( event ) {

            //console.log( 'out MAIN ' + contor++);

            var
                // The jquery object of the previewer demo element
                jQueryDisplayEl = jQuery( '.td-screen-demo:first' ),

                // The css left value
                existingLeftValue = jQueryDisplayEl.css( 'left' ),

                // The css padding-left value
                existingExtraLeftValue = jQueryDisplayEl.css( 'padding-left' ),

                // The integer css left value
                newLeftValue = parseInt( existingLeftValue.replace( 'px', '' ) ),

                // The integer css padding-left value
                newExtraLeftValue = parseInt( existingExtraLeftValue.replace( 'px', '' ) ),

                // The step value used to decrease the padding-left css value and to increase the left css value
                step = 10,

                // The waiting time (ms) for the timeout
                startTimeoutWait = 40,

                // The wait time (ms) for the interval
                startIntervalWait = 15;


            if ( newExtraLeftValue > 0 ) {

                // Clear any timeout if there's one, because a new one will be created
                if ( undefined !== startTimeout ) {
                    window.clearTimeout( startTimeout );
                }

                // Clear any interval if there's one, because a new one will be created
                if ( undefined !== startInterval ) {
                    window.clearInterval( startInterval );
                }

                startTimeout = setTimeout(function() {
                    startInterval = setInterval(function() {

                            if ( newExtraLeftValue <= 0 ) {

                                // Clear any timeout, and we should have one, because we finished
                                if ( undefined !== startTimeout ) {
                                    window.clearTimeout( startTimeout );
                                }

                                // Clear any interval, and we should have one, because we finished
                                if ( undefined !== startInterval ) {
                                    window.clearInterval( startInterval );
                                }
                            }

                            newLeftValue += step;
                            newExtraLeftValue -= step;

                            if ( newExtraLeftValue < 0 ) {
                                newExtraLeftValue = 0;
                                var dataLeftValue = jQueryDisplayEl.data( 'left-value' );
                                newLeftValue = dataLeftValue;
                            }

                            jQueryDisplayEl.css({
                                'left' : newLeftValue,
                                'padding-left': newExtraLeftValue
                            });
                        }, startIntervalWait
                    );
                }, startTimeoutWait);

            } else {
                jQueryDisplayEl.hide();
            }

            //console.log(existingLeftValue);
        }
    );



    jQuery( '.td-screen-demo' ).hover(
        function( event ) {
            jQuery(this).show();
            //console.log( 'in demo ' + contor++);
        },
        function( event ) {
            jQuery(this).hide();
            //console.log( 'out demo ' + contor++);
        }
    );

}); //end on load