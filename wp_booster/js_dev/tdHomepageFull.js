/**
 * Created by tagdiv on 30.05.2016.
 */

/* global jQuery: {} */

var tdHomepageFull = {};

(function( jQuery, undefined ) {

    'use strict';

    tdHomepageFull = {

        items: [],

        item: function() {

            // OPTIONAL - here we store the block Unique ID. This enables us to delete the item via this id @see tdHomepageFull.deleteItem
            this.blockUid = '';

            this.$tmplBlock = undefined;
        },

        /**
         *
         * @param item tdHomepageFull.item
         */
        addItem: function( item ) {
            if ( tdHomepageFull.items.length ) {
                return;
            }

            // The block template script
            item.$tmplBlock = jQuery( '#' + item.blockUid + '_tmpl' );

            // add the template
            jQuery( '.td-header-wrap' ).after( item.$tmplBlock.html() );

            // make the wrapper and the image -> and add the image inside
            var td_homepage_full_bg_image_wrapper = jQuery( '<div class="backstretch"></div>' );
            var td_homepage_full_bg_image = jQuery( '<img class="td-backstretch not-parallax" src="' + item.postFeaturedImage + '"/>' );
            td_homepage_full_bg_image_wrapper.append( td_homepage_full_bg_image );

            // add to body
            jQuery( 'body' ).prepend( td_homepage_full_bg_image_wrapper );

            // run the backstracher
            var td_backstr_item = new tdBackstr.item();
            td_backstr_item.wrapper_image_jquery_obj = td_homepage_full_bg_image_wrapper;
            td_backstr_item.image_jquery_obj = td_homepage_full_bg_image;
            tdBackstr.add_item( td_backstr_item );


            // The DOM article reference (article has already been inserted)
            item.$article = jQuery( '#post-' + item.postId );

            // The background image
            item.$bgImageWrapper = td_homepage_full_bg_image_wrapper;

            // The backstretch item
            item.backstrItem = td_backstr_item;

            tdHomepageFull.items.push( item );
        },


        deleteItem: function( blockUid ) {

            for (var i = 0; i < tdHomepageFull.items.length; i++) {

                var currentItem = tdHomepageFull.items[ i ];

                if ( currentItem.blockUid === blockUid ) {

                    // Remove the block template script
                    currentItem.$tmplBlock.remove();

                    // Remove the article
                    currentItem.$article.remove();

                    // Remove the backgroun image
                    currentItem.$bgImageWrapper.remove();

                    tdHomepageFull.items.splice(i, 1); // remove the item from the "array"

                    if ( tdBackstr.deleteItem( blockUid ) ) {

                        item.backstrItem = undefined;
                    }
                }
            }
            return false;
        }
    };

})( jQuery );
