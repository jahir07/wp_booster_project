/*
 td_util.js
 v2.0
 */
/* global jQuery:false */
/* global tdDetect:false */
/* global tdIsScrollingAnimation:false */
/* jshint -W020 */

var tdUtil = {};

( function() {
    "use strict";

    tdUtil = {


        /**
         * stop propagation of an event - we should check this if we can remove window.event.cancelBubble - possible
         * a windows mobile issue
         * @param event
         */
        stopBubble: function( event ) {
            if ( event && event.stopPropagation ) {
                event.stopPropagation();
            } else {
                window.event.cancelBubble=true;
            }
        },

        /**
         * utility function, used by td_post_images.js
         * @param classSelector
         */
        imageMoveClassToFigure: function ( classSelector ) {
            jQuery('figure .' + classSelector).each( function() {
                jQuery(this).parent().parent().addClass(classSelector);
                jQuery(this).removeClass(classSelector);
            });
        },



        /**
         * safe function to read variables passed by the theme via the js buffer. If by some kind of error the variable is missing from the global scope, this function will return false
         * @param variableName
         * @returns {*}
         */
        getBackendVar: function ( variableName ) {
            if ( typeof window[variableName] === 'undefined' ) {
                return '';
            }
            return window[variableName];
        },






        /**
         * scrolls to a dom element
         * @param domElement
         */
        scrollToElement: function( domElement, duration ) {
            tdIsScrollingAnimation = true;
            jQuery("html, body").stop();


            var dest;

            //calculate destination place
            if ( domElement.offset().top > jQuery(document).height() - jQuery(window).height() ) {
                dest = jQuery(document).height() - jQuery(window).height();
            } else {
                dest = domElement.offset().top;
            }
            //go to destination
            jQuery("html, body").animate(
                { scrollTop: dest },
                {
                    duration: duration,
                    easing:'easeInOutQuart',
                    complete: function(){
                        tdIsScrollingAnimation = false;
                    }
                }
            );
        },


        /**
         * scrolls to a dom element - the element will be close to the center of the screen
         * !!! compensates for long distances !!!
         */
        scrollIntoView: function ( domElement ) {
            tdIsScrollingAnimation = true;

            if ( tdDetect.isMobileDevice === true ) {
                return; //do not run on any mobile device
            }

            jQuery("html, body").stop();


            var destination = domElement.offset().top;
            destination = destination - 150;

            var distance = Math.abs( jQuery(window).scrollTop() - destination );
            var computed_time = distance / 5;
            //console.log(distance + ' -> ' + computed_time +  ' -> ' + (1100+computed_time));

            //go to destination
            jQuery("html, body").animate(
                { scrollTop: destination },
                {
                    duration: 1100 + computed_time,
                    easing:'easeInOutQuart',
                    complete: function(){
                        tdIsScrollingAnimation = false;
                    }
                }
            );
        },

        /**
         * scrolls to a position
         * @param pxFromTop - pixels from top
         */
        scrollToPosition: function( pxFromTop, duration ) {

            tdIsScrollingAnimation = true;
            jQuery("html, body").stop();

            //go to destination
            jQuery("html, body").animate(
                { scrollTop: pxFromTop },
                {
                    duration: duration,
                    easing:'easeInOutQuart',
                    complete: function(){
                        tdIsScrollingAnimation = false;
                    }
                }
            );
        },
        tdMoveY: function ( elm, value ) {
            var translate = 'translate3d(0px,' + value + 'px, 0px)';
            elm.style['-webkit-transform'] = translate;
            elm.style['-moz-transform'] = translate;
            elm.style['-ms-transform'] = translate;
            elm.style['-o-transform'] = translate;
            elm.style.transform = translate;
        },


        isValidUrl: function ( str ) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                '(\\#[-a-z\\d_]*)?$','i'); // fragment locator


            if( !pattern.test(str) ) {
                return false;
            } else {
                return true;
            }
        }





    };
})();





