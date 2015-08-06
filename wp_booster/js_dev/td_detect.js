/**
 *   ___________________________________________
 *  |   __  _ ___  _   _   __  __ __  __   __   |
 *  |  |  \| | __|| | | |/' _/|  V  |/  \ / _]  |
 *  |  | | ' | _| | 'V' |`._`.| \_/ | /\ | [/\  |
 *  |  |_|\__|___|!_/ \_!|___/|_| |_|_||_|\__/  |
 *  |___________________________________________|
 *
 * Our portfolio:  http://themeforest.net/user/tagDiv/portfolio
 * by tagDiv  2014
 * Thanks for your interest in our theme! :)
 *
 *
 */
/* global jQuery:false */


var td_detect = {};

( function(){
    "use strict";
    td_detect = {
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
         * @see td_events
         * The jQuery windows width is not reliable cross browser!
         */
        run_is_phone_screen: function () {
            if ((jQuery(window).width() < 768 || jQuery(window).height() < 768) && td_detect.isIpad === false) {
                td_detect.isPhoneScreen = true;

            } else {
                td_detect.isPhoneScreen = false;
            }
        },


        set: function (detector_name, value) {
            td_detect[detector_name] = value;
            alert('td_detect: ' + detector_name + ': ' + value);
        }
    };


    td_detect.htmlJqueryObj = jQuery('html');


    // is touch device ?
    if (-1 !== navigator.appVersion.indexOf("Win")) {
        td_detect.isWinOs = true;
    }

    // it looks like it has to have ontouchstart in window and NOT be windows OS. Why? we don't know.
    if (!!('ontouchstart' in window) && !td_detect.isWinOs) {
        td_detect.isTouchDevice = true;
    }


    // detect ie8
    if (td_detect.htmlJqueryObj.is('.ie8')) {
        td_detect.isIe8 = true;
        td_detect.isIe = true;
    }

    // detect ie9
    if (td_detect.htmlJqueryObj.is('.ie9')) {
        td_detect.isIe9 = true;
        td_detect.isIe = true;
    }

    // detect ie10 - also adds the ie10 class //it also detects windows mobile IE as IE10
    if(navigator.userAgent.indexOf("MSIE 10.0") > -1){
        td_detect.isIe10 = true;
        td_detect.isIe = true;
    }

    //ie 11 check - also adds the ie11 class - it may detect ie on windows mobile
    if(!!navigator.userAgent.match(/Trident.*rv\:11\./)){
        td_detect.isIe11 = true;
        //this.isIe = true; //do not flag ie11 as isIe
    }


    //do we have html5 history support?
    if (window.history && window.history.pushState) {
        td_detect.hasHistory = true;
    }

    //check for safary
    if (navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1) {
        td_detect.isSafari = true;
    }

    //chrome and chrome-ium check
    if (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) {
        td_detect.isChrome = true;
    }

    if (navigator.userAgent.match(/iPad/i) !== null) {
        td_detect.isIpad = true;
    }


    if (/(iPad|iPhone|iPod)/g.test( navigator.userAgent )) {
        td_detect.isIos = true;
    }


    //detect if we run on a mobile device - ipad included - used by the modal / scroll to @see scroll_into_view
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        td_detect.isMobileDevice = true;
    }

    td_detect.run_is_phone_screen();

    //test for android
    var user_agent = navigator.userAgent.toLowerCase();
    if(user_agent.indexOf("android") > -1) {
        td_detect.isAndroid = true;
    }


    if (navigator.userAgent.indexOf('Mac OS X') !== -1) {
        td_detect.isOsx = true;
    }

    if (navigator.userAgent.indexOf('Firefox') !== -1) {
        td_detect.isFirefox = true;
    }

})();
