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

    /**
     * Modal window js code
     */

    var modalSettings = {
        type: 'inline',
        preloader: false,
        focus: '#name',
        removalDelay: 500,

        // When elemened is focused, some mobile browsers in some cases zoom in
        // It looks not nice, so we disable it:
        callbacks: {
            beforeOpen: function() {

                this.st.mainClass = this.st.el.attr('data-effect');

                //empty all fields
                tdLogin.clearFields();

                //empty error display div
                tdLogin.showHideMsg();

                if( jQuery( window ).width() < 700) {
                    this.st.focus = false;
                } else {
                    if ( false === tdDetect.isIe ) {
                        //do not focus on ie 10
                        this.st.focus = '#login_email';
                    }
                }
            },

            beforeClose: function() {
            }
        },

        // The modal login is disabled for widths under less than 750px
        disableOn: function() {
            if( jQuery(window).width() < 750 ) {
                return false;
            }
            return true;
        }
    };

    // The following settings are only for the modal magnific popup, which is disable when width is less than 750px
    jQuery( '.comment-reply-login' ).attr({
        'href': '#login-form',
        'data-effect': 'mpf-td-login-effect'
    });

    // Set the modal magnific popup settings
    jQuery( '.comment-reply-login, .td-login-modal-js' ).magnificPopup( modalSettings );


    // - Set the normal link that will apply only for windows widths less than 750px
    // - Used for log in to leave a comment on post page to open the login section
    jQuery( '.td-login-modal-js, .comment-reply-login' ).on( 'click', function( event ) {

        if ( jQuery( window ).width() < 750 ) {

            event.preventDefault();

            // open the menu background
            jQuery( 'body' ).addClass( 'td-menu-mob-open-menu' );

            // hide the menu content
            jQuery( '.td-mobile-container' ).hide();
            jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );

            setTimeout(function(){
                jQuery( '.td-mobile-container' ).show();
            }, 500);

            //hides or shows the divs with inputs
            tdLogin.showHideElements( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );
        }
    });


    //login
    jQuery( '#login-link' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLogin.showHideElements( [['#td-login-div', 1], ['#td-register-div', 0], ['#td-forgot-pass-div', 0]] );

        //moves focus on the tab
        tdLogin.showTabs( [['#login-link', 1], ['#register-link', 0]] );

        if ( jQuery(window).width() > 700 && tdDetect.isIe === false ) {
            jQuery( '#login_email' ).focus();
        }

        //empty error display div
        tdLogin.showHideMsg();
    });


    //register
    jQuery( '#register-link' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLogin.showHideElements( [['#td-login-div', 0], ['#td-register-div', 1], ['#td-forgot-pass-div', 0]] );

        //moves focus on the tab
        tdLogin.showTabs( [['#login-link', 0], ['#register-link', 1]] );

        if ( jQuery( window ).width() > 700  && false === tdDetect.isIe ) {
            jQuery( '#register_email' ).focus();
        }

        //empty error display div
        tdLogin.showHideMsg();
    });


    //forgot pass
    jQuery( '#forgot-pass-link' ).on( 'click', function() {
        //hides or shows the divs with inputs
        tdLogin.showHideElements( [['#td-login-div', 0], ['#td-register-div', 0], ['#td-forgot-pass-div', 1]] );

        //moves focus on the tab
        tdLogin.showTabs( [['#login-link', 0], ['#register-link', 0]] );

        if (jQuery( window ).width() > 700 && false === tdDetect.isIe ) {
            jQuery( '#forgot_email' ).focus();
        }

        //empty error display div
        tdLogin.showHideMsg();
    });
    

    //login button
    jQuery( '#login_button' ).on( 'click', function() {
        tdLogin.handlerLogin();
    });

    //enter key on #login_pass
    jQuery( '#login_pass' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLogin.handlerLogin();
        }
    });


    //register button
    jQuery( '#register_button' ).on( 'click', function() {
        tdLogin.handlerRegister();
    });

    //enter key on #register_user
    jQuery( '#register_user' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLogin.handlerRegister();
        }
    });


    //forgot button
    jQuery( '#forgot_button' ).on( 'click', function() {
        tdLogin.handlerForgotPass();
    });

    //enter key on #forgot_email
    jQuery( '#forgot_email' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            tdLogin.handlerForgotPass();
        }
    });
});//end jquery ready




var tdLogin = {};


(function(){

    'use strict';

    tdLogin = {

        //patern to check emails
        email_pattern : /^[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/,

        /**
         * handle all request made from login tab
         */
        handlerLogin : function() {
            var loginEmailEl = jQuery( '#login_email'),
                loginPassEl = jQuery( '#login_pass' );

            if ( loginEmailEl.length && loginPassEl.length ) {
                var loginEmailVal = loginEmailEl.val().trim(),
                    loginPassVal = loginPassEl.val().trim();

                if ( loginEmailVal && loginPassVal ) {
                    tdLogin.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLogin.showHideMsg( td_please_wait );

                    //call ajax for log in
                    tdLogin.doAction( 'td_mod_login', loginEmailVal, '', loginPassVal );
                } else {
                    tdLogin.showHideMsg( td_email_user_pass_incorrect );
                }
            }
        },


        /**
         * handle all request made from register tab
         */
        handlerRegister : function() {
            var registerEmailEl = jQuery( '#register_email' ),
                registerUserEl = jQuery( '#register_user' );

            if ( registerEmailEl.length && registerUserEl.length ) {
                var registerEmailVal = registerEmailEl.val().trim(),
                    registerUserVal = registerUserEl.val().trim();

                if ( tdLogin.email_pattern.test( registerEmailVal ) && registerUserVal ) {

                    tdLogin.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLogin.showHideMsg( td_please_wait );

                    //call ajax
                    tdLogin.doAction( 'td_mod_register', registerEmailVal, registerUserVal, '' );
                } else {
                    tdLogin.showHideMsg( td_email_user_incorrect );
                }
            }
        },


        /**
         * handle all request made from forgot password tab
         */
        handlerForgotPass : function() {
            var forgotEmailEl = jQuery( '#forgot_email' );

            if ( forgotEmailEl.length ) {
                var forgotEmailVal = forgotEmailEl.val().trim();

                if ( tdLogin.email_pattern.test( forgotEmailVal ) ){

                    tdLogin.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                    tdLogin.showHideMsg( td_please_wait );

                    //call ajax
                    tdLogin.doAction( 'td_mod_remember_pass', forgotEmailVal, '', '' );
                } else {
                    tdLogin.showHideMsg( td_email_incorrect );
                }
            }
        },


        /**
         * swhich the div's acordingly to the user action (Log In, Register, Remember Password)
         *
         * ids_array : array of ids that have to be showed or hidden
         */
        //showHideElements : function( ids_array ) {
        //    if ( ids_array.constructor === Array ) {
        //        var length = ids_array.length;
        //
        //        for ( var i = 0; i < length; i++ ) {
        //            if ( ids_array[ i ].constructor === Array && 2 === ids_array[ i ].length ) {
        //                var jqElement = jQuery( ids_array[ i ][0] );
        //                if ( jqElement.length ) {
        //                    if ( 1 === ids_array[ i ][1] ) {
        //                        jqElement.removeClass( 'td-display-none' ).addClass( 'td-display-block' );
        //                    } else {
        //                        jqElement.removeClass( 'td-display-block' ).addClass( 'td-display-none' );
        //                    }
        //                }
        //            }
        //        }
        //    }
        //},

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


        showTabs : function( ids_array ) {
            if ( ids_array.constructor === Array ) {
                var length = ids_array.length;

                for ( var i = 0; i < length; i++ ) {
                    if ( ids_array[ i ].constructor === Array && 2 === ids_array[ i ].length ) {
                        var jqElement = jQuery( ids_array[ i ][0] );
                        if ( jqElement.length ) {
                            if ( 1 === ids_array[ i ][1] ) {
                                jqElement.addClass( 'td_login_tab_focus' );
                            } else {
                                jqElement.removeClass( 'td_login_tab_focus' );
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
                var tdElement = jQuery( param[0] );
                if ( tdElement.length ) {
                    if ( 1 === param[1] ) {
                        tdElement.addClass( param[2] );
                    } else {
                        tdElement.removeClass( param[2] );
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
            jQuery( '#login_email' ).val( '' );
            jQuery( '#login_pass' ).val( '' );

            //register fields
            jQuery( '#register_email' ).val( '' );
            jQuery( '#register_user' ).val( '' );

            //forgot pass
            jQuery( '#forgot_email' ).val( '' );
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
                                tdLogin.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                                tdLogin.showHideMsg( td_data_object[2] );
                            }
                            break;

                        case 'register':
                            if ( 1 === td_data_object[1] ) {
                                tdLogin.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            } else {
                                tdLogin.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            }
                            tdLogin.showHideMsg( td_data_object[2] );
                            break;

                        case 'remember_pass':
                            if ( 1 === td_data_object[1] ) {
                                tdLogin.addRemoveClass( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            } else {
                                tdLogin.addRemoveClass( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            }
                            tdLogin.showHideMsg( td_data_object[2] );
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