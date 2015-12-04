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
        show_hide_content_modala_mob( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );

        //moves focus on the tab
        //*****************************************************************************
        //*****************************************************************************
        //modala_swich_tabs( [['#login-link', 1], ['#register-link', 0]] );

        // marius
        //*****************************************************************************
        //*****************************************************************************
        jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );

        if ( jQuery(window).width() > 700 && tdDetect.isIe === false ) {
            jQuery( '#login_email-mob' ).focus();
        }

        //empty all fields
        //td_modala_empty_all_fields();

        //empty error display div
        td_modala_empty_err_div_mob();
    });

    //login button
    jQuery( '#login_button-mob' ).on( 'click', function() {
        handle_login_for_modal_window_mob();
    });

    //enter key on #login_pass
    jQuery( '#login_pass-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            handle_login_for_modal_window_mob();
        }
    });

    //register
    jQuery( '#register-link-mob' ).on( 'click', function() {
        //hides or shows the divs with inputs
        show_hide_content_modala_mob( [['#td-login-mob', 0], ['#td-register-mob', 1], ['#td-forgot-pass-mob', 0]] );

        // marius
        //*****************************************************************************
        //*****************************************************************************
        jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );


        //moves focus on the tab
        //*****************************************************************************
        //*****************************************************************************
        //modala_swich_tabs( [['#login-link', 0], ['#register-link', 1]] );

        if ( jQuery( window ).width() > 700  && false === tdDetect.isIe ) {
            jQuery( '#register_email-mob' ).focus();
        }

        //empty all fields
        //td_modala_empty_all_fields();

        //empty error display div
        td_modala_empty_err_div_mob();
    });

    //register button
    jQuery( '#register_button-mob' ).on( 'click', function() {
        handle_register_for_modal_window_mob();
    });

    //enter key on #register_user
    jQuery( '#register_user-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            handle_register_for_modal_window_mob();
        }
    });

    //forgot pass
    jQuery( '#forgot-pass-link-mob' ).on( 'click', function() {
        //hides or shows the divs with inputs
        show_hide_content_modala_mob( [['#td-login-mob', 0], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 1]] );

        //moves focus on the tab
        //*****************************************************************************
        //*****************************************************************************
        //modala_swich_tabs( [['#login-link', 0], ['#register-link', 0]] );

        if (jQuery( window ).width() > 700 && false === tdDetect.isIe ) {
            jQuery( '#forgot_email-mob' ).focus();
        }

        //empty all fields
        //td_modala_empty_all_fields();

        //empty error display div
        td_modala_empty_err_div_mob();
    });

    //forgot button
    jQuery( '#forgot_button-mob' ).on( 'click', function() {
        handle_forgot_password_for_modal_window_mob();
    });

    //enter key on #forgot_email
    jQuery( '#forgot_email-mob' ).keydown(function(event) {
        if ( ( event.which && 13 === event.which ) || ( event.keyCode && 13 === event.keyCode ) ) {
            handle_forgot_password_for_modal_window_mob();
        }
    });


    // marius
    // *****************************************************************************
    // *****************************************************************************
    // back login/register button
    jQuery( '#td-mobile-nav .td-login-close a, #td-mobile-nav .td-register-close a' ).on( 'click', function() {
        //hides or shows the divs with inputs
        show_hide_content_modala_mob( [['#td-login-mob', 0], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );

        jQuery( '#td-mobile-nav' ).removeClass( 'td-hide-menu-content' );
    });

    // back forgot pass button
    jQuery( '#td-mobile-nav .td-forgot-pass-close a' ).on( 'click', function() {
        //hides or shows the divs with inputs
        show_hide_content_modala_mob( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );
    });

    // used for log in to leave a comment on post page to open the login section
    //jQuery( '.td-login-modal-js' ).on( 'click', function() {
    //
    //    // open the menu background
    //    jQuery( 'body' ).addClass( 'td-menu-mob-open-menu' );
    //
    //    // hide the menu content
    //    jQuery( '.td-mobile-container' ).hide();
    //    jQuery( '#td-mobile-nav' ).addClass( 'td-hide-menu-content' );
    //
    //    setTimeout(function(){
    //        jQuery( '.td-mobile-container' ).show();
    //    }, 500);
    //
    //    //hides or shows the divs with inputs
    //    show_hide_content_modala( [['#td-login-mob', 1], ['#td-register-mob', 0], ['#td-forgot-pass-mob', 0]] );
    //});
});//end jquery ready



//patern to check emails
var td_mod_pattern_email = /^[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/,
    handle_login_for_modal_window_mob,
    handle_register_for_modal_window_mob,
    handle_forgot_password_for_modal_window_mob,
    show_hide_content_modala_mob,
    //modala_swich_tabs,
    modala_add_remove_class_mob,
    td_modala_empty_err_div_mob,
    td_modala_write_err_div_mob,
    td_modala_empty_all_fields_mob,
    td_modala_call_ajax_mob;


(function(){

    'use strict';

    /**
     * handle all request made from login tab
     */
    handle_login_for_modal_window_mob = function() {
        var login_email = jQuery( '#login_email-mob' ).val();
        var login_pass = jQuery( '#login_pass-mob' ).val();

        if ( login_email && login_pass ) {
            //empty error display div
            //td_modala_empty_err_div();

            modala_add_remove_class_mob( ['.td_display_err', 1, 'td_display_msg_ok'] );
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_please_wait );

            //call ajax for log in
            td_modala_call_ajax_mob( 'td_mod_login', login_email, '', login_pass );
        } else {
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_email_user_pass_incorrect );
        }
    };

    /**
     * handle all request made from register tab
     */
    handle_register_for_modal_window_mob = function() {
        var register_email = jQuery( '#register_email-mob' ).val();
        var register_user = jQuery( '#register_user-mob' ).val();

        if ( td_mod_pattern_email.test( register_email ) && register_user ) {
            //empty error display div
            //td_modala_empty_err_div();

            modala_add_remove_class_mob( ['.td_display_err', 1, 'td_display_msg_ok'] );
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_please_wait );

            //call ajax
            td_modala_call_ajax_mob( 'td_mod_register', register_email, register_user, '' );
        } else {
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_email_user_incorrect );
        }
    };

    /**
     * handle all request made from forgot password tab
     */
    handle_forgot_password_for_modal_window_mob = function() {
        var forgot_email = jQuery( '#forgot_email-mob' ).val();

        if ( td_mod_pattern_email.test( forgot_email ) ){
            //empty error display div
            //td_modala_empty_err_div();

            modala_add_remove_class_mob( ['.td_display_err', 1, 'td_display_msg_ok'] );
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_please_wait );

            //call ajax
            td_modala_call_ajax_mob( 'td_mod_remember_pass', forgot_email, '', '' );
        } else {
            jQuery( '.td_display_err' ).show();
            td_modala_write_err_div_mob( td_email_incorrect );
        }
    };


    /**
     * swhich the div's acordingly to the user action (Log In, Register, Remember Password)
     *
     * ids_array : array of ids that have to be showed or hidden
     */
    show_hide_content_modala_mob = function( ids_array ) {
        var length = ids_array.length;

        for ( var i = 0; i < length; i++ ) {
            var element_id = ids_array[ i ][0];
            var element_visibility = ids_array[ i ][1];

            if ( 1 === element_visibility ) {
                jQuery( element_id ).removeClass( 'td-login-hide' ).addClass( 'td-login-show' );
            } else {
                jQuery( element_id ).removeClass( 'td-login-show' ).addClass( 'td-login-hide' );
            }
        }
    };


    /**
     * swhich the tab's acordingly to the user action (Log In, Register, Remember Password)
     *
     * ids_array : array of ids that have to be focus on or unfocus
     */

    //   MARIUS *********************************************************************************************
    // ******************************************************************************************************
    // ******************************************************************************************************
    // ******************************************************************************************************
    // ******************************************************************************************************
    // ******************************************************************************************************

    //modala_swich_tabs = function( ids_array ) {
    //    var length = ids_array.length;
    //
    //    for ( var i = 0; i < length; i++ ) {
    //        var element_id = ids_array[ i ][0];
    //        var element_visibility = ids_array[ i ][1];
    //
    //        if ( 1 === element_visibility ) {
    //            jQuery( element_id ).addClass( 'td_login_tab_focus' );
    //        } else {
    //            jQuery( element_id ).removeClass( 'td_login_tab_focus' );
    //        }
    //    }
    //};


    /**
     * adds or remove a class from an html object
     *
     * param : array with object identifier (id - # or class - .)
     * ex: ['.class_indetifier', 1, 'class_to_add'] or ['.class_indetifier', 0, 'class_to_remove']
     */
    modala_add_remove_class_mob = function( param ) {

        //add class
        if ( 1 === param[1] ) {
            jQuery( param[0] ).addClass( param[2] );

            //remove class
        } else {
            jQuery( param[0] ).removeClass( param[2] );
        }
    };


    /**
     * empty the error div
     */
    td_modala_empty_err_div_mob = function() {
        var jQueryObj = jQuery( '.td_display_err');
        if ( jQueryObj.length ) {
            jQueryObj.html( '' );
            jQueryObj.hide();
        }
    };


    /**
     * write text to error div
     */
    td_modala_write_err_div_mob = function( message ) {
        jQuery( '.td_display_err' ).html( message );
    };


    /**
     * empty all fields in modal window
     */
    td_modala_empty_all_fields_mob = function() {
        //login fields
        jQuery( '#login_email-mob' ).val( '' );
        jQuery( '#login_pass-mob' ).val( '' );

        //register fields
        jQuery( '#register_email-mob' ).val( '' );
        jQuery( '#register_user-mob' ).val( '' );

        //forgot pass
        jQuery( '#forgot_email-mob' ).val( '' );
    };


    /**
     * call to server from modal window
     *
     * @param $action : what action (log in, register, forgot email)
     * @param $email  : the email beening sent
     * @param $user   : the user name beening sent
     */
    td_modala_call_ajax_mob = function( sent_action, sent_email, sent_user, sent_pass ) {
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
                            modala_add_remove_class_mob( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            jQuery( '.td_display_err' ).show();
                            td_modala_write_err_div_mob( td_data_object[2] );
                        }
                        break;

                    case 'register':
                        if ( 1 === td_data_object[1] ) {
                            modala_add_remove_class_mob( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            jQuery( '.td_display_err' ).show();
                        } else {
                            modala_add_remove_class_mob( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            jQuery( '.td_display_err' ).show();
                        }
                        td_modala_write_err_div_mob( td_data_object[2] );
                        break;

                    case 'remember_pass':
                        if ( 1 === td_data_object[1] ) {
                            modala_add_remove_class_mob( ['.td_display_err', 1, 'td_display_msg_ok'] );
                            jQuery( '.td_display_err' ).show();
                        } else {
                            modala_add_remove_class_mob( ['.td_display_err', 0, 'td_display_msg_ok'] );
                            jQuery( '.td_display_err' ).show();
                        }
                        td_modala_write_err_div_mob( td_data_object[2] );
                        break;

                }


            },
            error: function( MLHttpRequest, textStatus, errorThrown ){
                //console.log(errorThrown);
            }
        });
    };

})();


