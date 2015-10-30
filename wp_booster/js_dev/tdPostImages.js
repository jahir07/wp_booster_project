;'use strict';

/* ----------------------------------------------------------------------------
 tdPostImages.js
 --------------------------------------------------------------------------- */

/* global jQuery:{} */
/* global tdUtil:{} */
/* global tdAffix:{} */
/* global tdIsScrollingAnimation:boolean */

/*  ----------------------------------------------------------------------------
 On load
 */
jQuery().ready(function() {

    //handles the modal images
    tdModalImage();

    //move classes from post images to figure - td-post-image-full etc
    tdUtil.imageMoveClassToFigure( 'td-post-image-full' );
    tdUtil.imageMoveClassToFigure( 'td-post-image-right' );
    tdUtil.imageMoveClassToFigure( 'td-post-image-left' );

    /**
     * - add a general td-modal-image class to the all post images
     */
    if ( ( 'undefined' !== typeof window.tds_general_modal_image ) && ( '' !== window.tds_general_modal_image ) ) {
        jQuery( '.single .td-post-content a > img' ).filter(function( index, element ) {
            if ( -1 !== element.className.indexOf( 'wp-image' ) ) {
                jQuery( element ).parent().addClass( 'td-modal-image' );
            }
        });
    }
});



// used for scrolling to the last element
var tdModalImageLastEl = '';

// handles modal images for: Featured images, inline image, inline image with caption, galleries
function tdModalImage() {

    //fix wordpress figure + figcaption (we move the figcaption in the data-caption attribute of the link)
    jQuery( 'figure.wp-caption' ).each(function() {
        var caption_text = jQuery( this ).children( 'figcaption' ).html();
        jQuery( this ).children( 'a' ).data( 'caption', caption_text );
    });

    //move td-modal-image class to the parent a from the image. We can only add this class to the image via word press media editor
    jQuery( '.td-modal-image' ).each(function() {
        jQuery( this ).parent().addClass( 'td-modal-image' );
        jQuery( this ).removeClass( 'td-modal-image' );
    });



    //popup on modal images in articles
    jQuery( 'article' ).magnificPopup({
        type: 'image',
        delegate: ".td-modal-image",
        gallery: {
            enabled: true,
            tPrev: tdUtil.getBackendVar( 'td_magnific_popup_translation_tPrev' ), // Alt text on left arrow
            tNext: tdUtil.getBackendVar( 'td_magnific_popup_translation_tNext' ), // Alt text on right arrow
            tCounter: tdUtil.getBackendVar( 'td_magnific_popup_translation_tCounter' ) // Markup for "1 of 7" counter
        },
        ajax: {
            tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_ajax_tError' )
        },
        image: {
            tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_image_tError' ),
            titleSrc: function( item ) {//console.log(item.el);
                //alert(jQuery(item.el).data("caption"));
                var td_current_caption = jQuery( item.el ).data( 'caption' );
                if ( 'undefined' !== typeof td_current_caption ) {
                    return td_current_caption;
                } else {
                    return '';
                }
            }
        },
        zoom: {
            enabled: true,
            duration: 300,
            opener: function( element ) {
                return element.find( 'img' );
            }
        },
        callbacks: {
            change: function( item ) {
                tdModalImageLastEl = item.el;
                //setTimeout(function(){
                tdUtil.scrollIntoView( item.el );
                //}, 100);
            },
            beforeClose: function() {
                tdAffix.allow_scroll = false;

                tdUtil.scrollIntoView( tdModalImageLastEl );

                var interval_td_affix_scroll = setInterval(function() {

                    if ( ! tdIsScrollingAnimation ) {
                        clearInterval( interval_td_affix_scroll );
                        setTimeout(function() {
                            tdAffix.allow_scroll = true;
                            //tdAffix.td_events_scroll(td_events.scroll_window_scrollTop);
                        }, 100 );
                    }
                }, 100 );
            }
        }
    });





    //gallery popup
    //detect jetpack carousel and disable the theme popup
    if ( 'undefined' === typeof jetpackCarouselStrings ) {

        // copy gallery caption from figcaption to data-caption attribute of the link to the full image, in this way the modal can read the caption
        jQuery( 'figure.gallery-item' ).each(function() {
            var caption_text = jQuery( this ).children( 'figcaption' ).html();
            jQuery( this ).find( 'a' ).data( 'caption', caption_text );
        });


        //jquery tiled gallery
        jQuery( '.tiled-gallery' ).magnificPopup({
            type: 'image',
            delegate: "a",
            gallery: {
                enabled: true,
                tPrev: tdUtil.getBackendVar( 'td_magnific_popup_translation_tPrev' ), // Alt text on left arrow
                tNext: tdUtil.getBackendVar( 'td_magnific_popup_translation_tNext' ), // Alt text on right arrow
                tCounter: tdUtil.getBackendVar( 'td_magnific_popup_translation_tCounter' ) // Markup for "1 of 7" counter
            },
            ajax: {
                tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_ajax_tError' )
            },
            image: {
                tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_image_tError' ),
                titleSrc: function( item ) {//console.log(item.el);
                    var td_current_caption = jQuery( item.el ).parent().find( '.tiled-gallery-caption' ).text();
                    if ( 'undefined' !== typeof td_current_caption ) {
                        return td_current_caption;
                    } else {
                        return '';
                    }
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function( element ) {
                    return element.find( 'img' );
                }
            },
            callbacks: {
                change: function( item ) {
                    tdModalImageLastEl = item.el;
                    tdUtil.scrollIntoView( item.el );
                },
                beforeClose: function() {
                    tdUtil.scrollIntoView( tdModalImageLastEl );
                }
            }
        });



        jQuery( '.gallery' ).magnificPopup({
            type: 'image',
            delegate: '.gallery-icon > a',
            gallery: {
                enabled: true,
                tPrev: tdUtil.getBackendVar( 'td_magnific_popup_translation_tPrev' ), // Alt text on left arrow
                tNext: tdUtil.getBackendVar( 'td_magnific_popup_translation_tNext' ), // Alt text on right arrow
                tCounter: tdUtil.getBackendVar( 'td_magnific_popup_translation_tCounter' ) // Markup for "1 of 7" counter
            },
            ajax: {
                tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_ajax_tError' )
            },
            image: {
                tError: tdUtil.getBackendVar( 'td_magnific_popup_translation_image_tError' ),
                titleSrc: function( item ) {//console.log(item.el);
                    var td_current_caption = jQuery( item.el ).data( 'caption' );
                    if ( 'undefined' !== typeof td_current_caption ) {
                        return td_current_caption;
                    } else {
                        return '';
                    }
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function( element ) {
                    return element.find( 'img' );
                }
            },
            callbacks: {
                change: function( item ) {
                    tdModalImageLastEl = item.el;
                    tdUtil.scrollIntoView( item.el );
                },
                beforeClose: function() {
                    tdUtil.scrollIntoView( tdModalImageLastEl );
                }
            }
        });
    }
} //end modal