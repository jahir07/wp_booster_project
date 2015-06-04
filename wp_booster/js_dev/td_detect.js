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

"use strict";

/*  ----------------------------------------------------------------------------
 td_detect - browser detection object (instance)
 v1.1
 */

var td_detect = new function () {

    //constructor
    this.is_ie8 = false;
    this.is_ie9 = false;
    this.is_ie10 = false;
    this.is_ie11 = false;
    this.is_ie = false;
    this.is_safari = false;
    this.is_chrome = false;
    this.is_ipad = false;
    this.is_touch_device = false;
    this.has_history = false;
    this.is_phone_screen = false;
    this.is_ios = false;
    this.is_android = false;
    this.is_osx = false;
    this.is_firefox = false;




    // is touch device ?
    this.is_touch_device = !!('ontouchstart' in window);
    this.is_mobile_device = false;

    this.html_jquery_obj = jQuery('html');


    // detect ie8
    if (this.html_jquery_obj.is('.ie8')) {
        this.is_ie8 = true;
        this.is_ie = true;
    }



    // detect ie9
    if (this.html_jquery_obj.is('.ie9')) {
        this.is_ie9 = true;
        this.is_ie = true;
    }

    // detect ie10 - also adds the ie10 class //it also detects windows mobile IE as IE10
    if(navigator.userAgent.indexOf("MSIE 10.0") > -1){
        this.is_ie10 = true;
        this.is_ie = true;
        //alert('10');
    }

    //ie 11 check - also adds the ie11 class - it may detect ie on windows mobile
    if(!!navigator.userAgent.match(/Trident.*rv\:11\./)){
        this.is_ie11 = true;
        //this.is_ie = true; //do not flag ie11 as is_ie
        //alert('11');
    }


    //do we have html5 history support?
    if (window.history && window.history.pushState) {
        this.has_history = true;
    }

    //check for safary
    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
        this.is_safari = true;
    }

    //chrome and chrome-ium check
    this.is_chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());

    this.is_ipad = navigator.userAgent.match(/iPad/i) != null;



    if (/(iPad|iPhone|iPod)/g.test( navigator.userAgent )) {
        this.is_ios = true;
    } else {
        this.is_ios = false;
    }



    //detect if we run on a mobile device - ipad included - used by the modal / scroll to @see scroll_into_view
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        this.is_mobile_device = true;
    }

    /**
     * function to check the phone screen
     * @see td_events
     * The jQuery windows width is not reliable cross browser!
     */
    this.run_is_phone_screen = function () {
        if ((jQuery(window).width() < 768 || jQuery(window).height() < 768) && this.is_ipad === false) {
            this.is_phone_screen = true;

        } else {
            this.is_phone_screen = false;
        }

        //console.log(this.is_phone_screen + ' ' + jQuery(window).width() + ' ' + jQuery(window).height());
    };



    this.run_is_phone_screen();


    //test for android
    var user_agent = navigator.userAgent.toLowerCase();
    if(user_agent.indexOf("android") > -1) {
        this.is_android = true;
    }


    if (navigator.userAgent.indexOf('Mac OS X') != -1) {
        this.is_osx = true;
    }

    if (navigator.userAgent.indexOf('Firefox') != -1) {
        this.is_firefox = true;
    }

};


