/**
 * Created by tagdiv on 31.05.2016.
 */

var tdBigGridSlide = {};

(function( undefined ) {

    'use strict';

    tdBigGridSlide = {

        items: [],

        item: function() {

            // OPTIONAL - here we store the block Unique ID. This enables us to delete the item via this id @see tdBigGridSlide.deleteItem
            this.blockUid = '';

            this.iosSlider = undefined;
        },

        addItem: function( item ) {
            tdBigGridSlide.items.push( item );
        },

        deleteItem: function( blockUid ) {

            for (var i = 0; i < tdBigGridSlide.items.length; i++) {

                var currentItem = tdBigGridSlide.items[ i ];

                if ( currentItem.blockUid === blockUid ) {

                    tdBigGridSlide.items.splice( i, 1 ); // remove the item from the "array"

                    return true;
                }
            }
            return false;
        }

    };

})();