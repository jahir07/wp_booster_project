/**
 * Created by ra on 10/10/2014.
 */



/*  ----------------------------------------------------------------------------
 smooth hash scrolling - fix for hash links
 */
var td_hash_scroll_to_dom_element = '';


/**
 *  if we have link.php#hash we search the document for elements with that name or id so we can rename them to avoid the default screen scrolling
 */
if ( document.location.hash) {
    if( jQuery(document.location.hash).length != 0 ) { //we have elements that match the id

        //prepare a new name for the hash
        var new_target_hash = window.location.hash.substring(1) + '_td';

        //get the target element
        td_hash_scroll_to_dom_element = jQuery(document.location.hash);


        //change the element id
        td_hash_scroll_to_dom_element.attr("id", new_target_hash);

        //scroll to element
        td_util.scroll_into_view(td_hash_scroll_to_dom_element);

        /*
         // do not rename the hash back! bug in firefox after comment submition + an enter in the nav bar
         setTimeout(function(){
         //put the hash back after the rename
         td_hash_scroll_to_dom_element.attr("id", window.location.hash.substring(1));
         }, 2000);
         */

    }


    if (jQuery('[name="' + window.location.hash.substring(1) + '"]').length != 0) { //we have elements that have the name
        //prepare a new name for the hash
        var new_target_hash = window.location.hash.substring(1) + '_td';

        //get the target element
        td_hash_scroll_to_dom_element = jQuery('[name="' + window.location.hash.substring(1) + '"]');


        //change the element id
        td_hash_scroll_to_dom_element.attr("name", new_target_hash);

        //scroll to element
        td_util.scroll_into_view(td_hash_scroll_to_dom_element);


        /*
         // do not rename the hash back! bug in firefox after comment submition + an enter in the nav bar
         setTimeout(function(){
         //put the hash back after the rename
         td_hash_scroll_to_dom_element.attr("id", window.location.hash.substring(1));
         }, 2000);
         */
    }

}








jQuery(window).load(function() {

    //we have a hash that dosn't have an element
    if (td_hash_scroll_to_dom_element == '') {
        return;
    }

    if (td_mouse_wheel_or_touch_moved === true) { //the user has moved the wheel or touched the screen
        return;
    }
    //make sure that we scroll to element
    td_util.scroll_into_view(td_hash_scroll_to_dom_element);
});



/**
 * replace the default behaviour of links with #links
 */
jQuery("a[href*=#]").click(function(e) {

    var current_url = window.location.href;


    var window_url = window.location.href.split('#')[0]; //window url without hash
    if(window_url.substr(window_url.length - 1) == '/') { //remove trailing slash if needed
        window_url =  window_url.substr(0, window_url.length - 1);
    }


    var link_url = jQuery(this).attr('href');





    if (
        link_url.indexOf(window_url) > -1   // check to see if the link is an internal one, if it's url matches the window url. If it's not we do nothing (ex: go to the external url on click, no prevent default)
        || link_url.substring(0,1) == '#'   // if the links url start with # ex: we have a link like <a href="#test"
    ) {
        // the link contains our site url
        e.preventDefault();

        // fix tabs?
        if (this.hash.indexOf('tab') > -1) {
            return;
        }

        if( jQuery(this.hash).length != 0 ) {
            // go to the id = #hash
            td_util.scroll_into_view(jQuery(this.hash));
        } else if (jQuery('[name="' + this.hash.substring(1) + '"]').length != 0 ) {
            td_util.scroll_into_view(jQuery('[name="' + this.hash.substring(1) + '"]'));
        } else {

            // check to see if the url is valid
            if (td_util.is_valid_url(jQuery(this).attr("href"))) {
                window.location.href = jQuery(this).attr("href");
            } else {

            }


        }
    }


});



