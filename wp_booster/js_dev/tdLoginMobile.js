/*
 td_util.js
 v1.1
 */

/* global jQuery:{} */
/* global tdDetect:{} */
/* global td_ajax_url:string */

/* global td_please_wait:string */
/* global td_email_user_pass_incorrect:string */
/* global td_email_user_incorrect:string */
/* global td_email_incorrect:string */



/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    'use strict';

    //login
    jQuery( '#login-link-mob' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLoginMob.showHideElements( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );

        jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );

        if ( jQuery(window).width() > 700 && tdDetect.isIe === false ) {
            jQuery( '#login_email-mob' ).focus();
        }

        //empty error display div
        tdLoginMob.showHideMsg();
    });

    //register
    jQuery( '#register-link-mob' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLoginMob.showHideElements( [['#td-login-mob', 0], ['#td-register-mob', 1], ['#td-forgot-pass-mob', 0]] );

        jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );

        if ( jQuery( window ).width() > 700  && false === tdDetect.isIe ) {
            jQuery( '#register_email-mob' ).focus();
        }

        //empty error display div
        tdLoginMob.showHideMsg();
    });

    //forgot pass
    jQuery( '#forgot-pass-link-mob' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLoginMob.showHideElements( [['#td-login-mob', 0], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 1]] );

        if (jQuery( window ).width() > 700 && false === tdDetect.isIe ) {
            jQuery( '#forgot_email-mob' ).focus();
        }

        //empty error display div
        tdLoginMob.showHideMsg();
    });


    //login button
    jQuery( '#login_button-mob' ).on( 'click', function() {
        tdLoginMob.handlerLogin();
    });

    //enter key on #login_pass
    jQuery( '#login_pass-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLoginMob.handlerLogin();
        }
    });


    //register button
    jQuery( '#register_button-mob' ).on( 'click', function() {
        tdLoginMob.handlerRegister();
    });

    //enter key on #register_user
    jQuery( '#register_user-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLoginMob.handlerRegister();
        }
    });


    //forgot button
    jQuery( '#forgot_button-mob' ).on( 'click', function() {
        tdLoginMob.handlerForgotPass();
    });

    //enter key on #forgot_email
    jQuery( '#forgot_email-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLoginMob.handlerForgotPass();
        }
    });


    // marius
    // *****************************************************************************
    // *****************************************************************************
    // back login/register button
    jQuery( '#td-mobile-nav .td-login-close a, #td-mobile-nav .td-register-close a' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLoginMob.showHideElements( [['#td-login-mob', 0], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );

        jQuery( '#td-mobile-nav' ).removeClass( 'td-hide-menu-content' );
    });

    // back forgot pass button
    jQuery( '#td-mobile-nav .td-forgot-pass-close a' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLoginMob.showHideElements( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );
    });

});//end jquery ready





var tdLoginMob = {};


(function(){

    'use strict';

    tdLoginMob = {

        //patern to check emails
        email_pattern : /^[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/,

        /**
         * handle all request made from login tab
         */
        handlerLogin : function() {
            var loginEmailEl = jQuery( '#login_email-mob'),
                loginPassEl = jQuery( '#login_pass-mob' );

            if ( loginEmailEl.length && loginPassEl.length ) {
                var loginEmailVal = loginEmailEl.val().trim(),
                    loginPassVal = loginPassEl.val().trim();

                if ( loginEmailVal && loginPassVal ) {
                    tdLoginMob.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLoginMob.showHideMsg( td_please_wait );

                    //call ajax for log in
                    tdLoginMob.doAction( 'td_mod_login', loginEmailVal, '', loginPassVal );
                } else {
                    tdLoginMob.showHideMsg( td_email_user_pass_incorrect );
                }
            }
        },


        /**
         * handle all request made from register tab
         */
        handlerRegister : function() {
            var registerEmailEl = jQuery( '#register_email-mob' ),
                registerUserEl = jQuery( '#register_user-mob' );

            if ( registerEmailEl.length && registerUserEl.length ) {
                var registerEmailVal = registerEmailEl.val().trim(),
                    registerUserVal = registerUserEl.val().trim();

                if ( tdLoginMob.email_pattern.test( registerEmailVal ) && registerUserVal ) {

                    tdLoginMob.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLoginMob.showHideMsg( td_please_wait );

                    //call ajax
                    tdLoginMob.doAction( 'td_mod_register', registerEmailVal, registerUserVal, '' );
                } else {
                    tdLoginMob.showHideMsg( td_email_user_incorrect );
                }
            }
        },


        /**
         * handle all request made from forgot password tab
         */
        handlerForgotPass : function() {
            var forgotEmailEl = jQuery( '#forgot_email-mob' );

            if ( forgotEmailEl.length ) {
                var forgotEmailVal = forgotEmailEl.val().trim();

                if ( tdLoginMob.email_pattern.test( forgotEmailVal ) ){

                    tdLoginMob.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLoginMob.showHideMsg( td_please_wait );

                    //call ajax
                    tdLoginMob.doAction( 'td_mod_remember_pass', forgotEmailVal, '', '' );
                } else {
                    tdLoginMob.showHideMsg( td_email_incorrect );
                }
            }
        },


        /**
         * swhich the div's acordingly to the user action (Log In, Register, Remember Password)
         *
         * ids_array : array of ids that have to be showed or hidden
         */
        showHideElements : function( ids_array ) {
            if ( ids_array.constructor === Array ) {
                var length = ids_array.length;

                for ( var i = 0; i < length; i++ ) {
                    if ( ids_array[ i ].constructor === Array && 2 === ids_array[ i ].length ) {
                        var jqElement = jQuery( ids_array[ i ][0] );
                        if ( jqElement.length ) {
                            if ( 1 === ids_array[ i ][1] ) {
                                jqElement.removeClass( 'td-login-hide' ).addClass( 'td-login-show' );
                            } else {
                                jqElement.removeClass( 'td-login-show' ).addClass( 'td-login-hide' );
                            }
                        }
                    }
                }
            }
        },


        /**
         * adds or remove a class from an html object
         *
         * param : array with object identifier (id - # or class - .)
         * ex: ['.class_indetifier', 1, 'class_to_add'] or ['.class_indetifier', 0, 'class_to_remove']
         */
        addRemoveClass : function( param ) {
            if ( param.constructor === Array && 3 === param.length ) {
                var jqElement = jQuery( param[0] );
                if ( jqElement.length ) {
                    if ( 1 === param[1] ) {
                        jqElement.addClass( param[2] );
                    } else {
                        jqElement.removeClass( param[2] );
                    }
                }
            }
        },


        showHideMsg : function( msg ) {
            var tdDisplayErr = jQuery( '.td_display_err' );
            if ( tdDisplayErr.length ) {
                if ( undefined !== msg && msg.constructor === String && msg.length > 0 ) {
                    tdDisplayErr.show();
                    tdDisplayErr.html( msg );
                } else {
                    tdDisplayErr.hide();
                    tdDisplayErr.html( '' );
                }
            }
        },


        /**
         * empty all fields in modal window
         */
        clearFields : function() {
            //login fields
            jQuery( '#login_email-mob' ).val( '' );
            jQuery( '#login_pass-mob' ).val( '' );

            //register fields
            jQuery( '#register_email-mob' ).val( '' );
            jQuery( '#register_user-mob' ).val( '' );

            //forgot pass
            jQuery( '#forgot_email-mob' ).val( '' );
        },


        /**
         * call to server from modal window
         *
         * @param $action : what action (log in, register, forgot email)
         * @param $email  : the email beening sent
         * @param $user   : the user name beening sent
         */
        doAction : function( sent_action, sent_email, sent_user, sent_pass ) {
            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                data: {
                    action: sent_action,
                    email: sent_email,
                    user: sent_user,
                    pass: sent_pass
                },
                success: function( data, textStatus, XMLHttpRequest ){
                    var td_data_object = jQuery.parseJSON( data ); //get the data object

                    //check the response from server
                    switch( td_data_object[0] ) {
                        case 'login':
                            if ( 1 === td_data_object[1] ) {
                                location.reload( true );
                            } else {
                                tdLoginMob.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                                tdLoginMob.showHideMsg( td_data_object[2] );
                            }
                            break;

                        case 'register':
                            if ( 1 === td_data_object[1] ) {
                                tdLoginMob.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            } else {
                                tdLoginMob.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            }
                            tdLoginMob.showHideMsg( td_data_object[2] );
                            break;

                        case 'remember_pass':
                            if ( 1 === td_data_object[1] ) {
                                tdLoginMob.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            } else {
                                tdLoginMob.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            }
                            tdLoginMob.showHideMsg( td_data_object[2] );
                            break;
                    }
                },
                error: function( MLHttpRequest, textStatus, errorThrown ){
                    //console.log(errorThrown);
                }
            });
        }
    };

})();


