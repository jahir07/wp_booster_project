/**
 * Created by tagdiv on 23.02.2015.
 */

var td_backstr = {};

(function(){

    'use strict';

    td_backstr = {


        items: [],


        item: function() {

            // check if is necessary to apply modification (css)
            this.previous_value = 0;

            // the image aspect ratio
            this.image_aspect_rate = 0;

            // the wrapper jquery object
            this.wrapper_image_jquery_obj = '';

            // the image jquery object
            this.image_jquery_obj = '';
        },


        /**
         *
         * @param item
         */
        add_item: function( item ) {

            if ( item.constructor !== td_backstr.item ) {
                return;
            }

            //if ((item.image_jquery_obj.complete)
            //
            //    // this is a case when the image is still not loaded but the height() and width() return both 24px
            //    // @todo it must be modified. It's used because for backstretch are usually used large images
            //    && ((item.image_jquery_obj.height() != 24) && (item.image_jquery_obj.width() != 24))
            //)

            if ( item.image_jquery_obj.get( 0 ).complete ) {
                td_backstr._load_item_image( item );

            } else {

                item.image_jquery_obj.on( 'load', function() {
                    td_backstr._load_item_image( item );
                });


                //var currentTimeStart = Date.now();
                //
                //var loaded_image_jquery_ojb = false;
                //
                //item.image_jquery_obj.on('load', function() {
                //
                //    loaded_image_jquery_ojb = true;
                //
                //
                //    td_backstr._load_item_image(item);
                //    console.log('backstr tarziu ' + item.image_jquery_obj.height() + ' > timp : ' + (Date.now() - currentTimeStart));
                //});
                //
                //
                //var indexInterval = setInterval(function() {
                //    if (loaded_image_jquery_ojb) {
                //        clearInterval(indexInterval);
                //        console.log('imagine incarcata ' + item.image_jquery_obj.height() + ' > timp : ' + (Date.now() - currentTimeStart));
                //    }
                //}, 0);
            }
        },


        _load_item_image: function( item ) {
            item.image_aspect_rate = item.image_jquery_obj.width() / item.image_jquery_obj.height();
            td_backstr.items.push( item );
            td_backstr._compute_item( item );

            item.image_jquery_obj.css( 'opacity', '1' );
        },


        /**
         *
         * @param item
         * @private
         */
        _compute_item: function( item ) {

            // the wrapper aspect ratio can vary, so it's recomputed at computing item
            var wrapper_aspect_rate = item.wrapper_image_jquery_obj.width() / item.wrapper_image_jquery_obj.height();

            var current_value = 0;

            if ( wrapper_aspect_rate < item.image_aspect_rate ) {

                current_value = 1;

                if ( item.previous_value !== current_value ) {
                    item.image_jquery_obj.removeClass( 'td-stretch-width' );
                    item.image_jquery_obj.addClass( 'td-stretch-height' );

                    item.previous_value = current_value;
                }
            } else {

                current_value = 2;

                if ( item.previous_value !== current_value ) {
                    item.image_jquery_obj.removeClass( 'td-stretch-height' );
                    item.image_jquery_obj.addClass( 'td-stretch-width' );

                    item.previous_value = current_value;
                }
            }
        },


        /**
         *
         * @private
         */
        _compute_all_items: function() {
            for ( var i = 0; i < td_backstr.items.length; i++ ) {
                td_backstr._compute_item( td_backstr.items[ i ] );
            }
        },


        td_events_resize: function() {

            if ( 0 === td_backstr.items.length ) {
                return;
            }

            td_backstr._compute_all_items();
        },




        log: function( msg ) {
            window.console.log( msg );
        }
    };


})();



