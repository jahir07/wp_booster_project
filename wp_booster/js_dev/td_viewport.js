/**
 * Created by tagdiv on 13.05.2015.
 */


"use strict";


var td_viewport = {


    view_port_flag: -1,


    interval_list: [],


    init: function init() {
        if ((typeof window.td_viewport_interval_list !== undefined) && (window.td_viewport_interval_list.constructor === Array)) {
            td_viewport.interval_list = window.td_viewport_interval_list;
            td_viewport.detect_changes();
        }
    },


    detect_changes: function detect_changes() {
        var result = false;

        var real_view_port_width = 0;
        var local_view_port_flag = 0;

        if (td_detect.is_safari === true) {
            real_view_port_width = this._safari_view_port_width.get_real_width();
        } else {
            real_view_port_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        }

        for (var i = 0; i < td_viewport.interval_list.length; i++) {

            if (real_view_port_width <= td_viewport.interval_list[i]) {

                if (local_view_port_flag != td_viewport.view_port_flag) {
                    td_viewport.view_port_flag = local_view_port_flag;
                    result = true;

                    td_viewport.log('schimbare ' + td_viewport.view_port_flag + ' ~ ' + real_view_port_width);
                }

                break;
            }
            local_view_port_flag++;
        }

        if ((result == false) && (local_view_port_flag != td_viewport.view_port_flag)) {
            td_viewport.view_port_flag = local_view_port_flag;
            result = true;

            td_viewport.log('schimbare ' + td_viewport.view_port_flag + ' ~ ' + real_view_port_width);
        }

        return result;
    },


    /**
     * get the real view port width on safari
     * @type {{div_added: boolean, div_jquery_object: string, get_real_width: Function}}
     */
    _safari_view_port_width : {
        div_added : false,
        div_jquery_object : '',

        get_real_width: function () {
            if (this.div_added === false) {
                // we don't have a div present
                this.div_jquery_object = jQuery('<div>')
                    .css({

                        "height": "1px",
                        "position": "absolute",
                        "top": "-1",
                        "left": "0",
                        "right": "0",
                        "visibility": "hidden",
                        "z-index": "-1"

                    });
                this.div_jquery_object.appendTo('body');
                this.div_added = true;
            }
            return this.div_jquery_object.width();
        }
    },



    log: function log(msg) {
        console.log(msg);
    }
};

td_viewport.init();
