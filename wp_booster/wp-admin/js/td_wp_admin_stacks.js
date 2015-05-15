/**
 * Created by ra on 5/14/2015.
 */


var td_wp_admin_stacks = {
    init: function () {
        jQuery().ready(function() {
            jQuery('.td-wp-admin-stack .td-big-button').click(function() {
                var td_confirm = confirm('Are you sure? This will import our predefined settings for the stack (background, template layouts, fonts, colors etc...). Please backup your settings to be sure that you don`t lose them by accident.');
                if (td_confirm === true) {
                    alert(jQuery(this).data('stack-id'));
                }
            });
        });
    },


    install_stack: function install_stack() {

        //cache miss - we make a full request! - cache hit - false
        td_block_ajax_loading_start(current_block_obj, false, td_user_action);

        var request_data = {
            action: 'td_ajax_block',
            td_atts: current_block_obj.atts,
            td_block_id:current_block_obj.id,
            td_column_number:current_block_obj.td_column_number,
            td_current_page:current_block_obj.td_current_page,
            block_type:current_block_obj.block_type,
            td_filter_value:current_block_obj.td_filter_value,
            td_filter_ui_uid:current_block_obj.td_filter_ui_uid,
            td_user_action:current_block_obj.td_user_action
        };

        //console.log('td_ajax_do_block_request:');
        //console.log(request_data);

        jQuery.ajax({
            type: 'POST',
            url: td_ajax_url,
            cache:true,
            data: request_data,
            success: function(data, textStatus, XMLHttpRequest){
                td_local_cache.set(current_block_obj_signature, data);
                td_ajax_block_process_response(data, td_user_action);
            },
            error: function(MLHttpRequest, textStatus, errorThrown){
                //console.log(errorThrown);
            }
        });
    },


    progress_bar_show: function show_progress_bar() {

    },
    progress_bar_hide: function hide_progress_bar() {

    },
    progress_bar_change: function progress_bar_change() {

    }
};

td_wp_admin_stacks.init();