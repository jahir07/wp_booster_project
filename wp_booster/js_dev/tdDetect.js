/* global jQuery:false */


var tdDetect = {};

( function(){
    "use strict";
    tdDetect = {
        isIe8: false,
        isIe9 : false,
        isIe10 : false,
        isIe11 : false,
        isIe : false,
        isSafari : false,
        isChrome : false,
        isIpad : false,
        isTouchDevice : false,
        hasHistory : false,
        isPhoneScreen : false,
        isIos : false,
        isAndroid : false,
        isOsx : false,
        isFirefox : false,
        isWinOs : false,
        isMobileDevice:false,
        htmlJqueryObj:null, //here we keep the jQuery object for the HTML element

        /**
         * function to check the phone screen
         * @see tdEvents
         * The jQuery windows width is not reliable cross browser!
         */
        runIsPhoneScreen: function () {
            if ( (jQuery(window).width() < 768 || jQuery(window).height() < 768) && false === tdDetect.isIpad ) {
                tdDetect.isPhoneScreen = true;

            } else {
                tdDetect.isPhoneScreen = false;
            }
        },


        set: function (detector_name, value) {
            tdDetect[detector_name] = value;
            //alert('tdDetect: ' + detector_name + ': ' + value);
        }
    };


    tdDetect.htmlJqueryObj = jQuery('html');


    // is touch device ?
    if ( -1 !== navigator.appVersion.indexOf("Win") ) {
        tdDetect.set('isWinOs', true);
    }

    // it looks like it has to have ontouchstart in window and NOT be windows OS. Why? we don't know.
    if ( !!('ontouchstart' in window) && !tdDetect.isWinOs ) {
        tdDetect.set('isTouchDevice', true);
    }


    // detect ie8
    if ( tdDetect.htmlJqueryObj.is('.ie8') ) {
        tdDetect.set('isIe8', true);
        tdDetect.set('isIe', true);
    }

    // detect ie9
    if ( tdDetect.htmlJqueryObj.is('.ie9') ) {
        tdDetect.set('isIe9', true);
        tdDetect.set('isIe', true);
    }

    // detect ie10 - also adds the ie10 class //it also detects windows mobile IE as IE10
    if( navigator.userAgent.indexOf("MSIE 10.0") > -1 ){
        tdDetect.set('isIe10', true);
        tdDetect.set('isIe', true);
    }

    //ie 11 check - also adds the ie11 class - it may detect ie on windows mobile
    if ( !!navigator.userAgent.match(/Trident.*rv\:11\./) ){
        tdDetect.set('isIe11', true);
        //this.isIe = true; //do not flag ie11 as isIe
    }


    //do we have html5 history support?
    if (window.history && window.history.pushState) {
        tdDetect.set('hasHistory', true);
    }

    //check for safary
    if ( -1 !== navigator.userAgent.indexOf('Safari')  && -1 === navigator.userAgent.indexOf('Chrome') ) {
        tdDetect.set('isSafari', true);
    }

    //chrome and chrome-ium check
    if (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) {
        tdDetect.set('isChrome', true);
    }

    if ( null !== navigator.userAgent.match(/iPad/i)) {
        tdDetect.set('isIpad', true);
    }


    if (/(iPad|iPhone|iPod)/g.test( navigator.userAgent )) {
        tdDetect.set('isIos', true);
    }


    //detect if we run on a mobile device - ipad included - used by the modal / scroll to @see scrollIntoView
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        tdDetect.set('isMobileDevice', true);
    }

    tdDetect.runIsPhoneScreen();

    //test for android
    var user_agent = navigator.userAgent.toLowerCase();
    if ( user_agent.indexOf("android") > -1 ) {
        tdDetect.set('isAndroid', true);
    }


    if ( -1 !== navigator.userAgent.indexOf('Mac OS X') ) {
        tdDetect.set('isOsx', true);
    }

    if ( -1 !== navigator.userAgent.indexOf('Firefox') ) {
        tdDetect.set('isFirefox', true);
    }

})();
