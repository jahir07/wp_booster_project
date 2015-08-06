
jQuery(window).load(function() {
    jQuery('body').addClass('td-js-loaded');

    window.td_animation_stack.init();
});

jQuery(window).ready(function() {
    /*
     - code used to allow external links from td_smart_list, when the Google Yoast "Track outbound click and downloads" is checked
     - internal links ("#with-hash") are allowed too
     */

    jQuery('.td_smart_list_1 a, .td_smart_list_3 a').click(function(event) {

        if (event.target == event.currentTarget) {
            var current_url = jQuery(this).attr('href');

            if ((window.location.href != current_url) && tdUtil.isValidUrl(current_url)) {
                window.location.href = current_url;
            }
        }

    });
});

