/*
 td_util.js
 v2.0
 */
/* global jQuery:false */
/* global tdDetect:false */
/* global td`ScrollingAnimation:false */
/* jshint -W020 */

var tdUtil = {};

( function() {
    "use strict";

    tdUtil = {

        //patern to check emails
        email_pattern : /^[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/,

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
         * checks if a form input field value is a valid email address
         * @param val
         * @returns {boolean}
         */
        isEmail: function( val ) {
            return tdUtil.email_pattern.test(val);
        },

        /**
         * utility function, used by td_post_images.js
         * @param classSelector
         */
        imageMoveClassToFigure: function ( classSelector ) {
            jQuery('figure .' + classSelector).each( function() {
                jQuery(this).parents('figure:first').addClass(classSelector);
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
         * is a given variable undefined? - this is the underscore method of checking this
         * @param obj
         * @returns {boolean}
         */
        isUndefined : function ( obj ) {
            return obj === void 0;
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
        },


        round: function ( value, precision, mode ) {
            var m, f, isHalf, sgn; // helper variables
            // making sure precision is integer
            precision |= 0;
            m = Math.pow(10, precision);
            value *= m;
            // sign of the number
            sgn = (value > 0) | -(value < 0);
            isHalf = value % 1 === 0.5 * sgn;
            f = Math.floor(value);

            if (isHalf) {
                switch (mode) {
                    case 'PHP_ROUND_HALF_DOWN':
                        // rounds .5 toward zero
                        value = f + (sgn < 0);
                        break;
                    case 'PHP_ROUND_HALF_EVEN':
                        // rouds .5 towards the next even integer
                        value = f + (f % 2 * sgn);
                        break;
                    case 'PHP_ROUND_HALF_ODD':
                        // rounds .5 towards the next odd integer
                        value = f + !(f % 2);
                        break;
                    default:
                        // rounds .5 away from zero
                        value = f + (sgn > 0);
                }
            }

            return (isHalf ? value : Math.round(value)) / m;
        }







    };
})();





