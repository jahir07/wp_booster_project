"use strict";

/*  ----------------------------------------------------------------------------
    Menu script
 */



// top menu

if (td_detect.is_touch_device) {
    //touch
    jQuery('.td-header-sp-top-menu .top-header-menu').superfish({
        delay:300,
        speed:'fast',
        useClick:true
    });

} else {

    //not touch
    jQuery('.td-header-sp-top-menu .top-header-menu').superfish({
        delay:600,
        speed:200,
        useClick:false
    });
}




/*  ----------------------------------------------------------------------------
 On load
 */

// header menu
    jQuery('#td-header-menu .sf-menu').supersubs({
        minWidth: 10, // minimum width of sub-menus in em units
        maxWidth: 40, // maximum width of sub-menus in em units
        extraWidth: 1 // extra width can ensure lines don't sometimes turn over
    });



if (td_detect.is_touch_device) {
    //touch
    jQuery('#td-header-menu .sf-menu').superfish({
        delay:300,
        speed:'fast',
        useClick:true
    });

} else {

    //not touch
    jQuery('#td-header-menu .sf-menu').superfish({
        delay:600,
        speed:200,
        useClick:false
    });
}


