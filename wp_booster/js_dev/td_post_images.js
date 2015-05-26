"use strict";

/* ----------------------------------------------------------------------------
 td_post_images.js
 --------------------------------------------------------------------------- */



/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {
    //handles the modal images
    td_modal_image();

    //move classes from post images to figure - td-post-image-full etc
    td_util.image_move_class_to_figure('td-post-image-full');
    td_util.image_move_class_to_figure('td-post-image-right');
    td_util.image_move_class_to_figure('td-post-image-left');

});



// used for scrolling to the last element
var td_modal_image_last_el = '';

// handles modal images for: Featured images, inline image, inline image with caption, galleries
function td_modal_image() {

    //fix wordpress figure + figcaption (we move the figcaption in the data-caption attribute of the link)
    jQuery('figure.wp-caption').each(function() {
        var caption_text = jQuery(this).children('figcaption').html();
        jQuery(this).children('a').data('caption', caption_text);
    });

    //move td-modal-image class to the parent a from the image. We can only add this class to the image via word press media editor
    jQuery('.td-modal-image').each(function() {
        jQuery(this).parent().addClass('td-modal-image');
        jQuery(this).removeClass('td-modal-image');
    });



    //popup on modal images in articles
    jQuery('article').magnificPopup({
        type:'image',
        delegate: ".td-modal-image",
        gallery:{
            enabled:true,
            tPrev: td_util.get_backend_var('td_magnific_popup_translation_tPrev'), // Alt text on left arrow
            tNext: td_util.get_backend_var('td_magnific_popup_translation_tNext'), // Alt text on right arrow
            tCounter: td_util.get_backend_var('td_magnific_popup_translation_tCounter') // Markup for "1 of 7" counter
        },
        ajax: {
            tError: td_util.get_backend_var('td_magnific_popup_translation_ajax_tError')
        },
        image: {
            tError: td_util.get_backend_var('td_magnific_popup_translation_image_tError'),
            titleSrc: function(item) {//console.log(item.el);
                //alert(jQuery(item.el).data("caption"));
                var td_current_caption = jQuery(item.el).data('caption');
                if (typeof td_current_caption != "undefined") {
                    return td_current_caption;
                } else {
                    return '';
                }


            }
        },
        zoom: {
            enabled: true,
            duration: 300,
            opener: function(element) {
                return element.find("img");
            }
        },
        callbacks: {
            change: function(item) {
                td_modal_image_last_el = item.el;
                //setTimeout(function(){
                td_util.scroll_into_view(item.el);
                //}, 100);

            },
            beforeClose: function() {
                td_util.scroll_into_view(td_modal_image_last_el);

                var interval_td_affix_scroll = setInterval(function() {

                    if (!td_is_scrolling_animation) {
                        clearInterval(interval_td_affix_scroll);
                        setTimeout(function() {
                            td_affix.allow_scroll = true;
                            td_affix.td_events_scroll(td_events.scroll_window_scrollTop);
                        }, 100);
                    }
                }, 100);
            }
        }
    });





    //gallery popup
    //detect jetpack carousel and disable the theme popup
    if (typeof jetpackCarouselStrings === 'undefined') {

        // copy gallery caption from figcaption to data-caption attribute of the link to the full image, in this way the modal can read the caption
        jQuery('figure.gallery-item').each(function() {
            var caption_text = jQuery(this).children('figcaption').html();
            jQuery(this).find('a').data('caption', caption_text);
        });



        //jquery tiled gallery
        jQuery('.tiled-gallery').magnificPopup({
            type:'image',
            delegate: "a",
            gallery:{
                enabled:true,
                tPrev: td_util.get_backend_var('td_magnific_popup_translation_tPrev'), // Alt text on left arrow
                tNext: td_util.get_backend_var('td_magnific_popup_translation_tNext'), // Alt text on right arrow
                tCounter: td_util.get_backend_var('td_magnific_popup_translation_tCounter') // Markup for "1 of 7" counter
            },
            ajax: {
                tError: td_util.get_backend_var('td_magnific_popup_translation_ajax_tError')
            },
            image: {
                tError: td_util.get_backend_var('td_magnific_popup_translation_image_tError'),
                titleSrc: function(item) {//console.log(item.el);
                    var td_current_caption = jQuery(item.el).parent().find('.tiled-gallery-caption').text();
                    if (typeof td_current_caption != "undefined") {
                        return td_current_caption;
                    } else {
                        return '';
                    }
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function(element) {
                    return element.find("img");
                }
            },
            callbacks: {
                change: function(item) {
                    td_modal_image_last_el = item.el;
                    td_util.scroll_into_view(item.el);
                },
                beforeClose: function() {
                    td_util.scroll_into_view(td_modal_image_last_el);
                }

            }
        });



        jQuery('.gallery').magnificPopup({
            type:'image',
            delegate: ".gallery-icon > a",
            gallery:{
                enabled:true,
                tPrev: td_util.get_backend_var('td_magnific_popup_translation_tPrev'), // Alt text on left arrow
                tNext: td_util.get_backend_var('td_magnific_popup_translation_tNext'), // Alt text on right arrow
                tCounter: td_util.get_backend_var('td_magnific_popup_translation_tCounter') // Markup for "1 of 7" counter
            },
            ajax: {
                tError: td_util.get_backend_var('td_magnific_popup_translation_ajax_tError')
            },
            image: {
                tError: td_util.get_backend_var('td_magnific_popup_translation_image_tError'),
                titleSrc: function(item) {//console.log(item.el);
                    var td_current_caption = jQuery(item.el).data('caption');
                    if (typeof td_current_caption != "undefined") {
                        return td_current_caption;
                    } else {
                        return '';
                    }
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function(element) {
                    return element.find("img");
                }
            },
            callbacks: {
                change: function(item) {
                    td_modal_image_last_el = item.el;
                    td_util.scroll_into_view(item.el);
                },
                beforeClose: function() {
                    td_util.scroll_into_view(td_modal_image_last_el);
                }

            }
        });


    }

} //end modal