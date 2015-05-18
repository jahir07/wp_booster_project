/**
 * Created by ra on 5/14/2015.
 */


var td_wp_admin_demos = {




    init: function init() {

        jQuery().ready(function() {

            jQuery('.td-wp-admin-stack.default .button-install-demo').click(function() {
                var td_confirm = confirm('Are you sure? This will import our predefined settings for the stack (background, template layouts, fonts, colors etc...). Please backup your settings to be sure that you don`t lose them by accident.');
                if (td_confirm === true) {
                    td_wp_admin_demos.install_stack(jQuery(this).data('stack-id'));
                }
            });


            td_wp_progress_bar.progress_bar_element = jQuery('.td-progress-bar-default');


        });
    },


    install_stack: function install_stack(stack_id) {
        td_wp_admin_demos.install_start_animation();



        /* ---------------------------------------------------------------------------------------
           Uninstall
         */
        td_wp_progress_bar.change(33);
        var request_data = {
            action: 'td_ajax_demo_install',
            td_demo: 'uninstall_all',
            td_demo_view: ''
        };
        jQuery.ajax({
            type: 'POST',
            url: td_ajax_url,
            cache:false,
            data: request_data,
            success: function(data, textStatus, XMLHttpRequest) {
                //td_ajax_block_process_response(data, td_user_action);

                /* ---------------------------------------------------------------------------------------
                   td_media_1
                 */
                td_wp_progress_bar.change(66);
                var request_data = {
                    action: 'td_ajax_demo_install',
                    td_demo: stack_id,
                    td_demo_view: 'td_media_1'
                };
                jQuery.ajax({
                    type: 'POST',
                    url: td_ajax_url,
                    cache:false,
                    data: request_data,
                    success: function(data, textStatus, XMLHttpRequest){
                        //td_ajax_block_process_response(data, td_user_action);

                        /* ---------------------------------------------------------------------------------------
                         td_media_1
                         */
                        td_wp_progress_bar.change(99);
                        var request_data = {
                            action: 'td_ajax_demo_install',
                            td_demo: stack_id,
                            td_demo_view: 'td_import'
                        };
                        jQuery.ajax({
                            type: 'POST',
                            url: td_ajax_url,
                            cache:false,
                            data: request_data,
                            success: function(data, textStatus, XMLHttpRequest){
                                //td_ajax_block_process_response(data, td_user_action);

                                td_wp_admin_demos.install_end_animation();
                                td_wp_progress_bar.change(100);
                            },
                            error: function(MLHttpRequest, textStatus, errorThrown){
                                alert('tagDiv Ajax error. Cannot connect to server, it may be due to a misconfiguration on the server.');
                            }
                        });
                        //td_wp_admin_demos.install_end_animation();
                    },
                    error: function(MLHttpRequest, textStatus, errorThrown){
                        alert('tagDiv Ajax error. Cannot connect to server, it may be due to a misconfiguration on the server.');
                    }
                });



            },
            error: function(MLHttpRequest, textStatus, errorThrown){
                alert('tagDiv Ajax error. Cannot connect to server, it may be due to a misconfiguration on the server.');
            }
        });






    },

    install_start_animation:function () {

    },

    install_end_animation: function () {

    }



};

td_wp_admin_demos.init();




var td_wp_progress_bar = {
    progress_bar_element: '',
    current_value: 0,
    goto_value: 0,
    timer:'',
    last_goto_value:0,


    change: function change(new_progress) {

        clearInterval(td_wp_progress_bar.timer);


        td_wp_progress_bar.progress_bar_element.css('width', td_wp_progress_bar.last_goto_value + '%');
        td_wp_progress_bar.current_value = td_wp_progress_bar.last_goto_value;


        if (new_progress == 100) {
            td_wp_progress_bar.progress_bar_element.css('width', '100%');
            return;
        }


        td_wp_progress_bar.timer = setInterval(function(){


            if (Math.floor((Math.random() * 5) + 1) == 1) {
                tmp_value = Math.floor((Math.random() * 5) + 1) + td_wp_progress_bar.current_value;
                if (tmp_value <= new_progress) {
                    td_wp_progress_bar.progress_bar_element.css('width', td_wp_progress_bar.current_value + '%');
                    td_wp_progress_bar.current_value = tmp_value;
                } else {
                    clearInterval(td_wp_progress_bar.timer);
                }
            } else {
                //alert('ra');
            }

        }, 200);

        td_wp_progress_bar.last_goto_value = new_progress;

    }
};
