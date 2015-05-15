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


            td_wp_progress_bar.progress_bar_element = jQuery('.td-progress-bar-default');

            td_wp_progress_bar.change(50);
        });
    },


    install_stack: function install_stack(stack_id) {

        //cache miss - we make a full request! - cache hit - false
        td_block_ajax_loading_start(current_block_obj, false, td_user_action);

        var request_data = {
            td_stack: stack_id,
            td_view: current_block_obj.atts
        };


        jQuery.ajax({
            type: 'POST',
            url: td_ajax_url,
            cache:false,
            data: request_data,
            success: function(data, textStatus, XMLHttpRequest){
                td_ajax_block_process_response(data, td_user_action);
            },
            error: function(MLHttpRequest, textStatus, errorThrown){
                alert('tagDiv Ajax error. Cannot connect to server, it may be due to a misconfiguration on the server.');
            }
        });
    },



};

td_wp_admin_stacks.init();




var td_wp_progress_bar = {
    progress_bar_element: '',
    current_value: 0,
    goto_value: 0,
    timer:'',


    change: function change(new_progress) {

        if (new_progress == 100) {
            td_wp_progress_bar.progress_bar_element.css('width', '100%');
            return;
        }

        clearInterval(td_wp_progress_bar.timer);

        td_wp_progress_bar.timer = setInterval(function(){


            if (Math.floor((Math.random() * 3) + 1) == 1) {
                tmp_value = Math.floor((Math.random() * 5) + 1) + td_wp_progress_bar.current_value;
                if (tmp_value <= new_progress) {
                    td_wp_progress_bar.progress_bar_element.css('width', td_wp_progress_bar.current_value + '%');
                    td_wp_progress_bar.current_value = tmp_value;
                } else {
                    clearInterval(td_wp_progress_bar.timer);
                    //console.log('STOP');
                }
            } else {
                //alert('ra');
            }

        }, 500);

        console.log(td_wp_progress_bar.progress_bar_element);

    }
};
