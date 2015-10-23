/**
 * Created by tagdiv on 16.02.2015.
 */


/* global tdPullDown:{} */

var tdPullDown = {};

( function(){

    "use strict";

    tdPullDown = {


        // - keeps internally the current interval index
        // - it's set at init()
        _view_port_interval_index : td_viewport.INTERVAL_INITIAL_INDEX,



        // this flag mark that the tdPullDown.items must be reinitialized at the changing view port size
        reinitialize_items_at_change_view_port: false,



        // - the list of items
        items: [],



        // - the item represents a pair of lists (a horizontal and a vertical one)
        // - to be initialized, every property with 'IT MUST BE SPECIFIED' is mandatory
        item: function item() {

            // - the jquery object of the horizontal list.
            // IT MUST BE SPECIFIED.
            this.horizontal_jquery_obj = '';

            // - the jquery object of the vertical list.
            // IT MUST BE SPECIFIED
            this.vertical_jquery_obj = '';

            // - the jquery container object.
            // - it contains the horizontal and the vertical jquery objects
            // IT MUST BE SPECIFIED.
            this.container_jquery_obj = '';

            // - the css class of an horizontal element.
            // IT MUST BE SPECIFIED
            this.horizontal_element_css_class = '';



            // the minimum no. of elements to be shown by the horizontal list
            // - IT CAN BE SPECIFIED
            this.minimum_elements = 2;



            // - the array of jquery elements whose widths must be excluded from the width of the container object
            // IT CAN BE SPECIFIED
            this.excluded_jquery_elements = [];

            // - the extra space of the horizontal jquery object occupied by the excluded jquery elements
            // - it's not initialized with 0 because widths of the elements can not be integer values
            // - now, it's set to 1px
            this._horizontal_extra_space = 1;



            // - the array of objects from the horizontal list
            this._horizontal_elements = [];

            // - the array of objects from the vertical list
            this._vertical_elements = [];



            // - the jquery object of the first ul container in the vertical list
            // - it is calculated as the first 'ul' of the vertical jquery object
            this._vertical_ul_jquery_obj = '';



            // - the outer width of the vertical top header (ex.'More')
            // - it's used to calculate if the last vertical element has enough space in the horizontal list,
            // without considering the vertical top header width
            this._vertical_jquery_obj_outer_width = 0;



            // flag used to mark the initialization item
            this._is_initialized = false;
        },




        /**
         * - function used to init the tdPullDown object
         * - it must be called before any item adding
         * - it initializes the _view_port_interval_index
         * - the items list is initialized
         */
        init: function() {

            tdPullDown._view_port_interval_index = td_viewport.get_current_interval_index();

            tdPullDown.items = [];
        },




        /**
         * - add an item to the item list and initialize it
         *
         * @param item The item to be added and initialized
         */
        add_item: function( item ) {

            // the item is added in the item list
            tdPullDown.items.push( item );

            // the item is initialized only once when it is added
            tdPullDown._initialize_item( item );

            //  the item is ready to be computed
            tdPullDown._compute_item( item );
        },




        /**
         * - internal utility function used to initialize an item
         * - an item must be initialized only once
         * - every element having a specified css class is added in the horizontal list
         *
         * @param item {tdPullDown.item} The item to be initialized
         * @private
         */
        _initialize_item: function( item ) {

            // an item must be initialized only once
            if ( true === item._is_initialized ) {
                return;
            }


            // the mandatory item properties are verified
            if ( ( '' === item.horizontal_jquery_obj ) ||
                ( '' === item.vertical_jquery_obj ) ||
                ( '' === item.container_jquery_obj ) ||
                ( '' === item.horizontal_element_css_class ) ) {

                tdPullDown.log( 'Item can\' be initialized. It doesn\'t have all the mandatory properties' );
                return;
            }


            // the jquery object of the first ul container in the vertical list is initialized
            item._vertical_ul_jquery_obj = item.vertical_jquery_obj.find( 'ul:first' );

            if ( 0 === item._vertical_ul_jquery_obj.length ) {

                tdPullDown.log( 'Item can\' be initialized. The vertical list doesn\'t have an \'ul\' container' );
                return;
            }


            // the elements of the horizontal jquery object, having a specified css class
            var elements = item.horizontal_jquery_obj.find( '.' + item.horizontal_element_css_class );

            var local_jquery_element = null;
            var local_object = null;

            // for each element an object is added in the horizontal list
            elements.each( function ( index, element ) {

                local_jquery_element = jQuery( element );

                // @todo here we need a css class
                local_jquery_element.css( '-webkit-transition', 'opacity 0.2s' );
                local_jquery_element.css( '-moz-transition', 'opacity 0.2s' );
                local_jquery_element.css( '-o-transition', 'opacity 0.2s' );
                local_jquery_element.css( 'transition', 'opacity 0.2s' );

                local_jquery_element.css( 'opacity', '1' );


                // the cached object used to keep the jquery object and its outerWidth
                local_object = {

                    // the jquery element
                    jquery_object: local_jquery_element,

                    // the outer width including border
                    calculated_width: local_jquery_element.outerWidth( true )
                };

                // the horizontal list is populated
                item._horizontal_elements.push( local_object );
            });


            // the outer width of the vertical top header (ex.'More') is initialized
            item._vertical_jquery_obj_outer_width = item.vertical_jquery_obj.outerWidth( true );


            // by default, the vertical jquery object is hidden, being shown when at least one element is moved in it
            item.vertical_jquery_obj.css( 'display', 'none' );



            // the the extra space occupied by the horizontal jquery object is calculated

            var horizontal_jquery_obj_padding_left = item.horizontal_jquery_obj.css( 'padding-left' );
            if ( ( undefined !== horizontal_jquery_obj_padding_left ) && ( '' !== horizontal_jquery_obj_padding_left ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_padding_left.replace( 'px', '' ) );
            }

            var horizontal_jquery_obj_padding_right = item.horizontal_jquery_obj.css( 'padding-right' );
            if ( ( undefined !== horizontal_jquery_obj_padding_right ) && ( '' !== horizontal_jquery_obj_padding_right ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_padding_right.replace( 'px', '' ) );
            }


            var horizontal_jquery_obj_margin_left = item.horizontal_jquery_obj.css( 'margin-left' );
            if ( ( undefined !== horizontal_jquery_obj_margin_left ) && ( '' !== horizontal_jquery_obj_margin_left ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_margin_left.replace( 'px', '' ) );
            }

            var horizontal_jquery_obj_margin_right = item.horizontal_jquery_obj.css( 'margin-right' );
            if ( ( undefined !== horizontal_jquery_obj_margin_right ) && ( '' !== horizontal_jquery_obj_margin_right ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_margin_right.replace( 'px', '' ) );
            }


            var horizontal_jquery_obj_border_left = item.horizontal_jquery_obj.css( 'border-left' );
            if ( ( undefined !== horizontal_jquery_obj_border_left ) && ( '' !== horizontal_jquery_obj_border_left ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_border_left.replace( 'px', '' ) );
            }

            var horizontal_jquery_obj_border_right = item.horizontal_jquery_obj.css( 'border-right' );
            if ( ( undefined !== horizontal_jquery_obj_border_right ) && ( '' !== horizontal_jquery_obj_border_right ) ) {
                item._horizontal_extra_space += parseInt( horizontal_jquery_obj_border_right.replace( 'px', '' ) );
            }


            // the item is marked as initialized, being ready to be computed
            item._is_initialized = true;
        },




        /**
         * - internal utility function used to summarize width of the horizontal elements
         *
         * @param item {tdPullDown.item} The item whose horizontal list is processed
         * @returns {number}
         * @private
         */
        _get_horizontal_elements_width: function( item ) {

            var sum_width = 0;

            for ( var i = item._horizontal_elements.length - 1; i >= 0; i-- ) {
                sum_width += item._horizontal_elements[ i ].calculated_width;
            }
            return sum_width;
        },




        /**
         * - internal utility function used to reinitialize all items at the view resolution changing
         */
        _reinitialize_all_items: function() {

            for ( var i = tdPullDown.items.length - 1; i >= 0; i-- ) {
                tdPullDown._reinitialize_item( tdPullDown.items[ i ] );
            }
        },




        /**
         * - internal utility function used to reinitialize an item at the view resolution changing
         *
         * @param item The item being reinitialized
         */
        _reinitialize_item: function( item ) {

            // a not initialized item can't be reinitialized
            if ( false === item._is_initialized ) {
                return;
            }

            //  the flag is marked, so any further operation on this item is stopped
            item._is_initialized = false;

            // the html elements of the vertical list are all moved into the horizontal jquery object
            item.horizontal_jquery_obj.html( item.horizontal_jquery_obj.html() + item._vertical_ul_jquery_obj.html() );

            // the html content of the vertical list is cleared
            item._vertical_ul_jquery_obj.html( '' );

            // the horizontal list is empty initialized
            item._horizontal_elements = [];

            // the vertical list is empty initialized
            item._vertical_elements = [];

            // the extra space is initialized
            item._horizontal_extra_space = 1;

            // the item is ready to be initialized again
            tdPullDown._initialize_item( item );
        },




        /**
         * - an internal function used to move elements from the horizontal to the vertical list and vice versa, in according with
         * the space for horizontal elements.
         * - it's called every time at the viewport resize, when the space for horizontal elements is modified
         *
         * @param item - the item being computed
         * @private
         */
        _compute_item: function( item ) {

            // the item must be initialized first
            if ( false === item._is_initialized ) {
                return;
            }



            // the horizontal header margin is set 0 and the horizontal space is computing without its margin
            // @see tdPullDown._prepare_horizontal_header
            tdPullDown._prepare_horizontal_header( item, true );



            // - the space where horizontal elements lie
            // - it is the container width minus any extra horizontal space
            var space_for_horizontal_elements = 0;

            // the object container width
            var container_jquery_width = item.container_jquery_obj.css( 'width' );

            if ( ( undefined !== container_jquery_width ) && ( '' !== container_jquery_width ) ) {

                // the space for new horizontal elements is initialized by the container width
                space_for_horizontal_elements = container_jquery_width.replace( 'px', '' );

                // then this space is reduced by the widths of the excluded elements
                for ( var i = item.excluded_jquery_elements.length - 1; i >= 0; i-- ) {
                    space_for_horizontal_elements -= item.excluded_jquery_elements[ i ].contents().outerWidth( true );
                }
            }


            // if the vertical list is empty, the space for horizontal elements does not contain the width of the vertical head list
            if ( item._vertical_elements.length > 0 ) {
                space_for_horizontal_elements -= item._vertical_jquery_obj_outer_width;
            }

            // the space occupied by the horizontal elements is removed
            space_for_horizontal_elements -= tdPullDown._get_horizontal_elements_width( item );

            // the horizontal extra space is used to add an extra gap when the width of one element or a js math computation does a not integer value
            space_for_horizontal_elements -= item._horizontal_extra_space;


            // the current element being moved between the lists
            var local_current_element;


            // if there's not enough space for the horizontal elements, then the last of them are moved to the vertical list
            while ( space_for_horizontal_elements < 0 ) {

                // if there's specified a minimum number of horizontal elements, this must be considered
                if ( ( item.minimum_elements !== 0 ) && ( item._horizontal_elements.length <= item.minimum_elements ) ) {

                    // all elements are moved to the vertical list
                    tdPullDown._make_all_elements_vertical( item );




                    // the horizontal header margin is set before return
                    tdPullDown._prepare_horizontal_header( item );



                    // the following checks are not more eligible to do
                    return;

                } else {

                    // If the vertical list does not contain any elements yet,
                    // the space for horizontal elements is minimized by the vertical top header width
                    if ( 0 === item._vertical_elements.length ) {
                        space_for_horizontal_elements -= item._vertical_jquery_obj_outer_width;
                    }

                    local_current_element = tdPullDown._make_element_vertical( item );
                    space_for_horizontal_elements += local_current_element.calculated_width;
                }
            }



            // This is the case when there's specified a no. of minimum horizontal elements and the horizontal list is empty.
            // If the following conditions are accomplished the horizontal list is refilled with elements from the vertical list
            //
            //  - if there's specified a no. of minimum horizontal elements
            //  - if there is no horizontal elements
            //  - if there are vertical elements
            //  - if there's enough horizontal space for the first vertical element

            if ( ( 0 !== item.minimum_elements )
                && ( 0 === item._horizontal_elements.length )
                && ( item._vertical_elements.length > 0 )
                && ( space_for_horizontal_elements >= item._vertical_elements[ 0 ].calculated_width ) ) {

                // the necessary space needed for the minimum no. of horizontal elements
                var local_necessary_space = 0;

                for ( var i = 0; ( i < item.minimum_elements ) && ( i < item._vertical_elements.length ); i++ ) {
                    local_necessary_space += item._vertical_elements[ i ].calculated_width;
                }

                // the necessary space really occupied by the minimum no. of horizontal elements
                var local_space = 0;
                var local_minimum_elements = item.minimum_elements;

                while ( ( local_minimum_elements > 0 )
                    && ( item._vertical_elements.length > 0 )
                    && ( space_for_horizontal_elements >= local_necessary_space ) ) {

                    local_current_element = tdPullDown._make_element_horizontal( item );

                    if ( null !== local_current_element ) {
                        local_space += local_current_element.calculated_width;
                        local_minimum_elements--;
                    } else {

                        // the horizontal header margin is set before return
                        tdPullDown._prepare_horizontal_header( item );

                        return;
                    }

                }
                space_for_horizontal_elements -= local_space;
            }



            // It's the case when there isn't specified a no. of minimum horizontal elements or it is specified and the
            // horizontal list is not empty, and in the same time there's enough horizontal space for more elements
            while ( ( ( item._horizontal_elements.length > 0 ) || ( 0 === item._horizontal_elements.length && 0 === item.minimum_elements ) )
                && ( item._vertical_elements.length > 0 )
                && ( space_for_horizontal_elements >= item._vertical_elements[ 0 ].calculated_width ) ) {

                local_current_element = tdPullDown._make_element_horizontal( item );

                if ( null !== local_current_element ) {
                    space_for_horizontal_elements -= local_current_element.calculated_width;
                } else {

                    // the horizontal header margin is set before return
                    tdPullDown._prepare_horizontal_header( item );

                    return;
                }
            }



            // if the vertical list contains just one element, the horizontal space for it must be calculated without considering the vertical top header width (ex.'More')
            if ( ( 1 === item._vertical_elements.length )
                && ( space_for_horizontal_elements + item._vertical_jquery_obj_outer_width >= item._vertical_elements[ 0 ].calculated_width ) ) {
                tdPullDown._make_element_horizontal( item );
            }


            // the horizontal header margin is set before return
            tdPullDown._prepare_horizontal_header( item );
        },


        /**
         * - add margin to the element with '.block-title' css class, to keep the vertical_jquery_obj not overlapping over it when
         * there are no horizontal elements and it is too wide [more strings in name]
         * @param item tdPullDown.item
         * @param clear_margin boolean True to just clear margin, or false to check the horizontal elements length and then set the margin
         * @private
         */
        _prepare_horizontal_header: function _prepare_horizontal_header( item, clear_margin ) {
            var block_title_jquery_obj = item.horizontal_jquery_obj.parent().siblings( '.block-title:first' );

            if ( 1 === block_title_jquery_obj.length ) {
                var content_element = block_title_jquery_obj.find( 'span:first' );

                if ( 1 === content_element.length ) {

                    if ( 'undefined' !== typeof( clear_margin ) && true === clear_margin ) {
                        content_element.css( 'margin-right', 0 );
                    } else {
                        if ( 0 === item._horizontal_elements.length ) {
                            content_element.css( 'margin-right', item._vertical_jquery_obj_outer_width + 'px' );
                        } else {
                            content_element.css( 'margin-right', 0 );
                        }
                    }
                }
            }
        },




        /**
         * - function used to compute all items in the item list
         *
         * @private
         */
        _compute_all_items: function() {
            for ( var i = tdPullDown.items.length - 1; i >= 0; i-- ) {

                // a type check is done for every item in the item list
                if ( tdPullDown.items[ i ].constructor === tdPullDown.item ) {
                    tdPullDown._compute_item( tdPullDown.items[ i ] );
                }
            }
        },




        /**
         * - function used to move one element from the vertical list to the horizontal one
         * - the function returns the element that has been moved, otherwise null
         * - the last element moving hides the vertical top header
         *
         * @param item - the item whose element is moved
         * @returns {T} - the moved element
         * @private
         */
        _make_element_horizontal: function( item ) {

            // the item must be initialized and the vertical list must contain at least an element
            if ( false === item._is_initialized || 0 === item._vertical_elements.length ) {
                return null;
            }

            // the first element of the vertical list is shifted
            var local_element = item._vertical_elements.shift();

            // the vertical list is shown when there's at least one vertical element
            if ( 0 === item._vertical_elements.length ) {
                item.vertical_jquery_obj.css( 'display', 'none' );
            }

            // the element is added on the last position in the horizontal list
            item._horizontal_elements.push( local_element );

            local_element.jquery_object.css( 'opacity', '0' );

            // the DOM is changing
            local_element.jquery_object.detach().appendTo( item.horizontal_jquery_obj );

            setTimeout( function() {
                local_element.jquery_object.css( 'opacity', '1' );
            }, 50);

            //tdPullDown.log('horizontal');

            return local_element;
        },




        /**
         * - function used to move one element from the horizontal list to the vertical one
         * - the function returns the element that has been moved, otherwise null
         * - the first element moving shows the vertical top header
         *
         * @param item - the item whose element is moved
         * @returns {T} - the moved element
         * @private
         */
        _make_element_vertical: function( item ) {

            // the item must be initialized and the horizontal list must contain at least an element
            if ( false === item._is_initialized || 0 === item._horizontal_elements.length ) {
                return null;
            }

            // the last element of the horizontal list is popped out
            var local_element = item._horizontal_elements.pop();

            // the vertical list is hidden when there are no vertical elements
            if ( 0 === item._vertical_elements.length ) {
                item.vertical_jquery_obj.css( 'display', '' );
            }

            //the element is added on the first position into the vertical list
            item._vertical_elements.unshift( local_element );

            // the DOM is changed
            local_element.jquery_object.detach().prependTo( item._vertical_ul_jquery_obj );

            //tdPullDown.log('vertical');

            return local_element;
        },




        /**
         * - function used to move all elements to the vertical list
         * - it's used when the minimum horizontal elements is greater than 0
         *
         * @param item - the item whose elements are moved
         * @private
         */
        _make_all_elements_vertical: function( item ) {
            while ( item._horizontal_elements.length > 0 ) {
                tdPullDown._make_element_vertical( item );
            }
        },






        /**
         * - function necessary to be called when the window is being resized
         */
        td_events_resize: function() {

            if ( 0 === tdPullDown.items.length ) {
                return;
            }

            if ( true === tdPullDown.reinitialize_items_at_change_view_port && tdPullDown._view_port_interval_index !== td_viewport.get_current_interval_index() ) {
                tdPullDown._reinitialize_all_items();
            }

            tdPullDown._compute_all_items();
        },




        log: function log( msg ) {
            //console.log(msg);
        }
    };


    tdPullDown.init();

})();







