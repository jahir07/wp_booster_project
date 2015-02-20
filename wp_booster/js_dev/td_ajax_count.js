'use strict';
/**
 * updates the view counter thru ajax
 */
var td_ajax_count = {

    td_get_views_counts_ajax : function td_get_views_counts_ajax (page_type, array_ids) {

        //what function to call based on page type
        var page_type_action = 'td_ajax_get_views';//page_type = page
        if(page_type == "post") {
            page_type_action = 'td_ajax_update_views';
        }

        jQuery.ajax({
            type: 'POST',
            url: td_ajax_url,
            cache:true,

            data: {
                action: page_type_action,
                td_post_ids: array_ids
            },
            success: function(data, textStatus, XMLHttpRequest){
                var td_ajax_post_counts = jQuery.parseJSON(data);//get the return dara

                //check the return var to be object
                if (td_ajax_post_counts instanceof Object) {
                    //alert('value is Object!');

                    //itinerate thru the object
                    jQuery.each(td_ajax_post_counts, function(id_post, value) {
                        //alert(id_post + ": " + value);

                        //this is the count placeholder in witch we write the post count
                        var current_post_count = ".td-nr-views-" + id_post;

                        jQuery(current_post_count).html(value);
                        //console.log(current_post_count + ': ' + value);
                    });
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){
                //console.log(errorThrown);
            }
        });

    }
};