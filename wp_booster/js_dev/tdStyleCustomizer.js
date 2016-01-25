/*  ----------------------------------------------------------------------------
 tagDiv live css compiler ( 2013 )
 - this script is used on our demo site to customize the theme live
 - not used on production sites
 */

/* global jQuery:{} */
/* global td_read_site_cookie:Function */
/* global td_set_cookies_life:Function */

(function() {

    'use strict';

    var td_current_panel_stat = td_read_site_cookie( 'td_show_panel' );

    if ( 'hide' === td_current_panel_stat ) {

        var jQueryObj = jQuery( '#td-theme-settings' );
        if ( jQueryObj.length ) {
            jQueryObj.addClass( 'td-theme-settings-no-transition' );
            jQueryObj.removeClass( 'td-theme-settings-small' );
            jQuery( '#td-theme-set-hide' ).html( 'DEMOS' );
        }
    } else {
        jQuery( '#td-theme-set-hide' ).html( 'CLOSE ' );
    }

})();


var tdDemoMenu = {

    // document - horizontal mouse position
    mousePosX: 0,

    // document - vertical mouse position
    mousePosY: 0,

    // The timer waiting to start de interval
    startTimeout: undefined,

    // The interval that decreases the padding-left css value and increases the left css value of the screen demo (previewer of the demo)
    startInterval: undefined,



    // Flag marks that it's possible to move the mouse to the original demo
    _extendedDemo: false,

    // The current demo element (for which the counters have been applied)
    _currentElement: undefined,

    // The timer waiting to start the interval for extended demo
    _startExtendedTimeout: undefined,

    // The interval that decreases the width css value of the extended element
    _startExtendedInterval: undefined,



    init: function() {

        'use strict';

        // Get document mouse position
        jQuery( document ).mousemove(function( event ) {
            if ( event.pageX || event.pageY ) {
                tdDemoMenu.mousePosX = event.pageX;
                tdDemoMenu.mousePosY = event.pageY;
            } else if ( event.clientX || event.clientY ) {
                tdDemoMenu.mousePosX = event.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                tdDemoMenu.mousePosY = event.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
        });

        // Show/hide the arrow skin scroll element
        jQuery( '#td-theme-settings' ).find( '.td-skin-wrap:first').scroll(function( event ) {
            //console.log( event );

            var theTarget = event.currentTarget,
                tdSkinScroll = jQuery( this ).find( '.td-skin-scroll:first' );

            if ( theTarget.clientHeight + theTarget.scrollTop < theTarget.scrollHeight ) {
                tdSkinScroll.css({
                    bottom: 0
                });
            } else {
                tdSkinScroll.css({
                    bottom: -40
                });
            }
        });

        jQuery( '#td-theme-settings' ).find( '.td-skin-scroll:first').click(function( event ) {
            //console.log( event );

            var theTarget = event.currentTarget,
                tdSkinWrap = jQuery(this).closest('.td-skin-wrap');

            tdSkinWrap.animate(
                { scrollTop: tdSkinWrap.scrollTop() + 200 },
                {
                    duration: 800,
                    easing:'easeInOutQuart'
                });
        }).mouseenter(function(event) {
            // Any existing timeout is cleard to stop any further css settings
            if ( undefined !== tdDemoMenu.startTimeout ) {
                window.clearTimeout( tdDemoMenu.startTimeout );
            }

            // Any existing interval is cleard to stop any further css settings
            if ( undefined !== tdDemoMenu.startInterval ) {
                window.clearInterval( tdDemoMenu.startInterval );
            }

            jQuery( '#td-theme-settings' ).find( '.td-screen-demo:first' ).hide();
        });

        // Show/hide the demo menu panel
        jQuery( '#td-theme-set-hide' ).click(function(event ){
            event.preventDefault();
            event.stopPropagation();

            var $this = jQuery(this),
                jQueryObj = jQuery( '#td-theme-settings' );

            jQueryObj.removeClass( 'td-theme-settings-no-transition' );

            if ( jQueryObj.hasClass( 'td-theme-settings-small' ) ) {
                jQueryObj.removeClass( 'td-theme-settings-small' );
                jQueryObj.addClass( 'td-theme-settings-closed' );
                $this.html( 'DEMOS' );

                //show full
                td_set_cookies_life( ['td_show_panel', 'hide', 86400000] );//86400000 is the number of milliseconds in a day
            } else {
                jQueryObj.addClass( 'td-theme-settings-small' );
                jQueryObj.removeClass( 'td-theme-settings-closed' );
                $this.html( 'CLOSE' );

                //hide
                td_set_cookies_life( ['td_show_panel', 'show', 86400000] );//86400000 is the number of milliseconds in a day
            }
        });


        jQuery( '.td-set-theme-style-link' ).hover(

            // The mouse enter event handler
            function( event ) {

                //console.log( 'in MAIN ' + contor++);

                // Any existing timeout is cleard to stop any further css settings
                if ( undefined !== tdDemoMenu.startTimeout ) {
                    window.clearTimeout( tdDemoMenu.startTimeout );
                }

                // Any existing interval is cleard to stop any further css settings
                if ( undefined !== tdDemoMenu.startInterval ) {
                    window.clearInterval( tdDemoMenu.startInterval );
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
                    rightValue = 0,

                // The padding value set to the css padding-left setting
                    paddingRightValue = 0,

                // The extra value added to the css padding-left setting and removed from the css left setting (if we need to start earlier or later - does nothing with 0 value)
                    extraRightValue = 0,

                // The jquery wpadminbar element
                    jqWPAdminBar = jQuery( '#wpadminbar' );



                // Show the image into the image previewer
                var imgElement = jQueryDisplayEl.find( 'img:first' ),
                    dataImgUrl = $this.data( 'img-url' );

                if ( imgElement.length ) {
                    imgElement.attr( 'src', dataImgUrl );
                } else {
                    jQueryDisplayEl.html('<img src="' + dataImgUrl + '"/>');
                }



                // The first (paired - 0) column
                if ( 0 === jQuery( '.td-set-theme-style-link' ).index( this ) % 2 ) {
                    topValue = $thisContainer.position().top + $thisContainer.outerHeight() / 2 - jQueryDisplayEl.outerHeight(true) / 2;
                    rightValue = $thisContainer.outerWidth(true) * 2;

                    // The second (impaired - 1) column
                } else {
                    var $thisPrevContainer = $thisContainer.prev( '.' + cssClassContainer );

                    if ( $thisPrevContainer.length ) {
                        topValue = $thisPrevContainer.position().top + $thisContainer.outerHeight() / 2 - jQueryDisplayEl.outerHeight(true) / 2;
                        rightValue = $thisPrevContainer.outerWidth(true) - extraRightValue;
                        paddingRightValue = $thisPrevContainer.outerWidth(true) + extraRightValue;

                    }
                }


                // Do not allow displaying the previewer demo below the bottom of the window screen
                if ( topValue + jQueryDisplayEl.outerHeight(true) > window.innerHeight ) {
                    topValue -= (topValue + jQueryDisplayEl.outerHeight(true)) - window.innerHeight;
                }

                // Do not allow displaying the previewer demo above the top of the window screen. It also checks if the wpadminbar exists.
                if ( jqWPAdminBar.length ) {
                    refTopValue = jqWPAdminBar.outerHeight(true);
                } else {
                    refTopValue = 0;
                }

                if ( refTopValue > topValue ) {
                    topValue = refTopValue;
                }

                // The 'width' css property is used for Chrome and IE browsers which do not display the previewer image with auto width and auto height
                var cssSettings = {
                        'top' : topValue,
                        'right' : rightValue,
                        'padding-right': paddingRightValue,
                        'width': ''
                    },
                    dataWidthPreview = jQueryDisplayEl.data( 'width-preview' );


                // For the first column of demos, the previewer has padding
                if ( paddingRightValue > 0 ) {
                    cssSettings.width = dataWidthPreview + paddingRightValue;
                }




                // Apply the computed css to the element
                jQueryDisplayEl.css( cssSettings );

                // The 'right-value' data will be used to set 'right' css value when the computed padding is < 0
                jQueryDisplayEl.data( 'right-value', rightValue + paddingRightValue );

                jQueryDisplayEl.show();
            },

            // The mouse exit event handler
            function( event ) {

                //console.log('out MAIN ');

                jQuery( '.td-screen-demo-extend:first').hide();

                var
                // The jquery object of the previewer demo element
                    jQueryDisplayEl = jQuery('.td-screen-demo:first'),

                // The css right value
                    existingRightValue = jQueryDisplayEl.css('right'),

                // The css padding-right value
                    existingExtraRightValue = jQueryDisplayEl.css('padding-right'),

                // The css width value
                    existingWidthValue = jQueryDisplayEl.css('width'),

                // The integer css right value
                    newRightValue = parseInt(existingRightValue.replace('px', '')),

                // The integer css padding-right value
                    newExtraRightValue = parseInt(existingExtraRightValue.replace('px', '')),

                // The step value used to decrease the padding-left css value and to increase the left css value
                    step = 10,

                // The waiting time (ms) for the timeout
                    startTimeoutWait = 50,

                // The time (ms) for the interval
                    //startIntervalWait = 15,
                    startIntervalWait = 15,

                    newWidthValue = parseInt(existingWidthValue.replace('px', ''));


                var $this = jQuery(this);
                tdDemoMenu._currentElement = $this;


                if (newExtraRightValue > 0) {

                    // Clear any timeout if there's one, because a new one will be created
                    if (undefined !== tdDemoMenu.startTimeout) {
                        window.clearTimeout( tdDemoMenu.startTimeout );
                        tdDemoMenu.startTimeout = undefined;
                    }

                    // Clear any interval if there's one, because a new one will be created
                    if (undefined !== tdDemoMenu.startInterval) {
                        window.clearInterval( tdDemoMenu.startInterval );
                        tdDemoMenu.startInterval = undefined;
                    }

                    tdDemoMenu.startTimeout = setTimeout(function () {


                        // Extended demo is eligible to be shown (true)
                        // The flag is set to false when the mouse is found in wrong position (mouse position is reached)
                        // The flag is set to true when the counters (the timer and the interval) finish, there the extended demo element being shown
                        tdDemoMenu._extendedDemo = true;

                        tdDemoMenu.startInterval = setInterval(function () {

                                var dataWidthPreview = jQueryDisplayEl.data( 'width-preview' );

                                newRightValue += step;
                                newExtraRightValue -= step;
                                newWidthValue -= step;

                                var mousePositionFound = false;

                                if ( newExtraRightValue <= 0 || newWidthValue < dataWidthPreview || tdDemoMenu.mousePosX >= jQuery(window).width() - newRightValue ) {

                                    // Clear any timeout, and we should have one, because we finished
                                    if ( undefined !== tdDemoMenu.startTimeout ) {
                                        window.clearTimeout( tdDemoMenu.startTimeout );
                                        tdDemoMenu.startTimeout = undefined;
                                    }

                                    // Clear any interval, and we should have one, because we finished
                                    if ( undefined !== tdDemoMenu.startInterval ) {
                                        window.clearInterval( tdDemoMenu.startInterval );
                                        tdDemoMenu.startInterval = undefined;
                                    }

                                    newExtraRightValue = 0;
                                    newRightValue = jQueryDisplayEl.data('right-value');
                                    newWidthValue = dataWidthPreview;

                                    if ( tdDemoMenu.mousePosX >= jQuery(window).width() - newRightValue ) {
                                        mousePositionFound = true;
                                    }
                                }

                                jQueryDisplayEl.css({
                                    'right': newRightValue,
                                    'padding-right': newExtraRightValue,
                                    'width': newWidthValue
                                });

                                // The timeout started and the interval are stopped (The mouse was reached or the css computation is done)
                                if ( mousePositionFound ) {
                                    tdDemoMenu._extendedDemo = false;
                                    tdDemoMenu._checkMousePosition();
                                } else if ( undefined === tdDemoMenu.startTimeout && undefined === tdDemoMenu.startInterval ) {
                                    tdDemoMenu._extendedDemo = true;
                                    tdDemoMenu._showExtendedScreenDemo();
                                }

                            }, startIntervalWait
                        );
                    }, startTimeoutWait);

                } else {
                    jQueryDisplayEl.hide();
                }
            }
        );

        jQuery( '.td-screen-demo' ).hover(
            function( event ) {
                jQuery(this).show();
            },
            function( event ) {

                // We are on mouseleave event, and because of this, if the main counters (the timer and the interval) are not finished, it means we
                // don't have any extended demo element, so it's okay to set its flag to false and hide the extended demo element and the previewer demo element (this element)
                if ( undefined !== tdDemoMenu.startTimeout || undefined !== tdDemoMenu.startInterval ) {
                    tdDemoMenu._extendedDemo = false;
                }

                jQuery(this).hide();
                jQuery( '.td-screen-demo-extend:first' ).hide();

            }
        );

        jQuery( '.td-screen-demo-extend' ).hover(
            function( event ) {

                if ( tdDemoMenu._extendedDemo ) {

                    // Set the flag to false to not execute this routine twice on mouseenter event
                    tdDemoMenu._extendedDemo = false;

                    var

                    // The jquery current element
                        $this = jQuery(this),

                    // The jquery '.td-screen-demo' element
                        $tdScreenDemo = jQuery( '.td-screen-demo:first'),

                    // The css width value
                        columnWidth = $this.data('width-column'),

                    // The step value used to decrease the padding-left css value and to increase the left css value
                        step = 10,

                    // The waiting time (ms) for the timeout
                        startTimeoutWait = 50,

                    // The time (ms) for the interval
                    //startIntervalWait = 15,
                        startIntervalWait = 15,

                        newWidthValue = columnWidth;

                        $this.css({
                            'width': columnWidth + 'px',
                            'top': $tdScreenDemo.css( 'top')
                        });

                        $this.show();
                        $tdScreenDemo.show();


                    tdDemoMenu._startExtendedTimeout = setTimeout(function () {

                        tdDemoMenu._startExtendedInterval = setInterval(function () {

                                newWidthValue -= step;

                                var mousePositionFound = false;

                                if ( newWidthValue < 0 || tdDemoMenu.mousePosX <= jQuery(window).width() - columnWidth - newWidthValue ) {

                                    // Clear any timeout, and we should have one, because we finished
                                    if ( undefined !== tdDemoMenu._startExtendedTimeout ) {
                                        window.clearTimeout( tdDemoMenu._startExtendedTimeout );
                                        tdDemoMenu._startExtendedTimeout = undefined;
                                    }

                                    // Clear any interval, and we should have one, because we finished
                                    if ( undefined !== tdDemoMenu._startExtendedInterval ) {
                                        window.clearInterval( tdDemoMenu._startExtendedInterval );
                                        tdDemoMenu._startExtendedInterval = undefined;
                                    }

                                    if ( tdDemoMenu.mousePosX <= jQuery(window).width() - columnWidth - newWidthValue ) {
                                        mousePositionFound = true;
                                    }

                                    newWidthValue = columnWidth;

                                    $this.hide();
                                }

                                $this.css({
                                    'width': newWidthValue,
                                    'top': $tdScreenDemo.css( 'top')
                                });

                                if ( mousePositionFound ) {
                                    tdDemoMenu._checkMousePosition();
                                }

                            }, startIntervalWait
                        );
                    }, startTimeoutWait);

                }
            },
            function( event ) {

                /**
                 * 1. clear any extended timer/interval
                 * 2. hide the element
                 * 3. adjust its width to the initial value
                 * 4. hide the previewer element (this will be shown by the a mouseenter event if it's the case)
                 */

                // Clear any timeout, and we should have one, because we finished
                if ( undefined !== tdDemoMenu._startExtendedTimeout ) {
                    window.clearTimeout( tdDemoMenu._startExtendedTimeout );
                    tdDemoMenu._startExtendedTimeout = undefined;
                }

                // Clear any interval, and we should have one, because we finished
                if ( undefined !== tdDemoMenu._startExtendedInterval ) {
                    window.clearInterval( tdDemoMenu._startExtendedInterval );
                    tdDemoMenu._startExtendedInterval = undefined;
                }

                var $this = jQuery(this),
                    widthColumn = $this.data( 'width-column' );

                $this.css({
                    'width': widthColumn + 'px'
                }).hide();

                jQuery( '.td-screen-demo:first').hide();
            }
        );
    },

    _showExtendedScreenDemo: function() {

        'use strict';

        jQuery( '.td-screen-demo-extend:first').css({
            top: jQuery( '.td-screen-demo:first').css( 'top')
        }).show();
    },

    _checkMousePosition: function() {

        'use strict';

        var theElement;

        jQuery( '.td-set-theme-style-link' ).each(function(index, element) {

            tdDemoMenu._log(index);

            var $this = jQuery(element),
                cssClassContainer = 'td-set-theme-style',
                $thisContainer = $this.closest( '.' + cssClassContainer );

            var verticalPosition = false;
            var horizontalPosition = false;

            if ( 0 === jQuery( '.td-set-theme-style-link' ).index( element ) % 2 ) {

                if ( parseInt($thisContainer.position().top) + parseInt(jQuery(window).scrollTop()) < tdDemoMenu.mousePosY && tdDemoMenu.mousePosY < parseInt($thisContainer.position().top) + parseInt(jQuery(window).scrollTop()) + parseInt($thisContainer.outerHeight()) ) {
                    verticalPosition = true;

                    if ( parseInt(jQuery( window ).width()) - 2 * parseInt($thisContainer.outerWidth()) < tdDemoMenu.mousePosX && tdDemoMenu.mousePosX < parseInt(jQuery( window ).width()) - parseInt($thisContainer.outerWidth()) ) {
                        horizontalPosition = true;
                    }
                }
                //tdDemoMenu._log( 'caz A : ' + index + ' > vert: ' + verticalPosition + ' > hori: ' + horizontalPosition + ' > posY: ' + tdDemoMenu.mousePosY + ' > posX: ' + tdDemoMenu.mousePosX +
                //    ' > top: ' + (parseInt($thisContainer.position().top) + parseInt(jQuery(window).scrollTop())) + ' > bottom: ' + (parseInt($thisContainer.position().top) + parseInt(jQuery(window).scrollTop()) + parseInt($thisContainer.outerHeight())) +
                //    ' > left: ' + (parseInt(jQuery( window ).width()) - 2 * parseInt($thisContainer.outerWidth())) + ' > right: ' + (parseInt(jQuery( window ).width()) - parseInt($thisContainer.outerWidth())) );

            } else {
                var $thisPrevContainer = $thisContainer.prev( '.' + cssClassContainer );

                if ( $thisPrevContainer.length ) {
                    if ( parseInt($thisPrevContainer.position().top) + parseInt(jQuery(window).scrollTop()) < tdDemoMenu.mousePosY && tdDemoMenu.mousePosY < (parseInt($thisPrevContainer.position().top) + parseInt(jQuery(window).scrollTop()) + parseInt($thisPrevContainer.outerHeight())) ) {
                        verticalPosition = true;

                        if ( parseInt(jQuery( window ).width()) - parseInt($thisContainer.outerWidth()) < tdDemoMenu.mousePosX && tdDemoMenu.mousePosX < parseInt(jQuery( window ).width()) ) {
                            horizontalPosition = true;
                        }
                    }
                }
                //tdDemoMenu._log( 'caz B : ' + index + ' > vert: ' + verticalPosition + ' > hori: ' + horizontalPosition + ' > posY: ' + tdDemoMenu.mousePosY + ' > posX: ' + tdDemoMenu.mousePosX +
                //    ' > top: ' + ($thisPrevContainer.position().top + parseInt(jQuery(window).scrollTop())) + ' > bottom: ' + (parseInt($thisPrevContainer.position().top) + parseInt(jQuery(window).scrollTop()) + parseInt($thisPrevContainer.outerHeight())) +
                //    ' > left: ' + (parseInt(jQuery( window ).width()) - parseInt($thisContainer.outerWidth())) + ' > right: ' + parseInt(jQuery( window ).width()) );
            }

            // The element where the mouse is positioned, was found
            if ( verticalPosition && horizontalPosition ) {
                theElement = element;
                return false;
            }

        });

        if ( undefined === theElement ) {
            jQuery( '#td-theme-settings').find( '.td-screen-demo:first' ).hide();
        } else {
            jQuery( theElement).mouseenter();
        }
    },

    _log: function( msg ) {

        'use strict';

        //window.console.log( msg );
    }

};



jQuery().ready(function() {

    'use strict';

    tdDemoMenu.init();
});






/*  ----------------------------------------------------------------------------
@todo REMOVE THIS WHEN THE SITE IS READY
 tagDiv live css compiler ( 2013 )
 - this script is used on our demo site to customize the theme live
 - not used on production sites
 */
var td_current_panel_stat_old = td_read_site_cookie('td_show_panel_old');
if (td_current_panel_stat_old == 'show' || td_current_panel_stat_old == null) {
    jQuery('.td-theme-settings-small-old').addClass('td-theme-settings-no-transition-old');
    jQuery('.td-theme-settings-small-old').removeClass('td-theme-settings-small-old');
}

/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    //hide panel
    jQuery("#td-theme-set-hide-old").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        //hide
        td_set_cookies_life(['td_show_panel_old', 'hide', 86400000]);//86400000 is the number of milliseconds in a day
        jQuery('#td-theme-settings-old').removeClass('td-theme-settings-no-transition-old');
        jQuery('#td-theme-settings-old').addClass('td-theme-settings-small-old');


        jQuery('.td-set-theme-style-link-old').removeClass('fadeInLeft');

    });

    //show panel
    jQuery("#td-theme-settings-old").click(function(){
        if (jQuery(this).hasClass('td-theme-settings-small-old')) {

            jQuery('.td-set-theme-style-link-old').addClass('animated_xlong fadeInLeft');

            //show full
            td_set_cookies_life(['td_show_panel_old', 'show', 86400000]);//86400000 is the number of milliseconds in a day
            jQuery('.td-theme-settings-small-old').removeClass('td-theme-settings-small-old');
        }
    });

}); //end on load