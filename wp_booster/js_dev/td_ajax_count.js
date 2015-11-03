/**
 * updates the view counter thru ajax
 */

/* global jQuery:{} */
/* global td_ajax_url:string */

var tdAjaxCount = {};

(function(){

    'use strict';

    tdAjaxCount = {

        //td_get_views_counts_ajax : function( page_type, array_ids ) {
        tdGetViewsCountsAjax : function( postType, arrayIds ) {

            //what function to call based on postType
            var pageTypeAction = 'td_ajax_get_views';//postType = page
            if ( 'post' === postType ) {
                pageTypeAction = 'td_ajax_update_views';
            }

            jQuery.ajax({
                type: 'POST',
                url: td_ajax_url,
                cache: true,
                data: {
                    action: pageTypeAction,
                    td_post_ids: arrayIds
                },
                success: function( data, textStatus, XMLHttpRequest ) {
                    var tdAjaxPostCounts = jQuery.parseJSON( data );//get the return dara

                    //check the return var to be object
                    if ( tdAjaxPostCounts instanceof Object ) {
                        //alert('value is Object!');

                        //iterate throw the object
                        jQuery.each( tdAjaxPostCounts, function( idPost, value ) {
                            //alert(id_post + ": " + value);

                            //this is the count placeholder in witch we write the post count
                            var currentPostCount = '.td-nr-views-' + idPost;

                            jQuery( currentPostCount ).html( value );
                            //console.log(current_post_count + ': ' + value);
                        });
                    }
                },
                error: function( MLHttpRequest, textStatus, errorThrown ) {
                    //console.log(errorThrown);
                }
            });
        }
    };
})();
