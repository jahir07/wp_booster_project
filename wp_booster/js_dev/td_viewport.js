/**
 * Created by tagdiv on 13.05.2015.
 */


"use strict";


var td_viewport = {



    /**
     * - initial (default) value of the _current_interval_index
     * - it's used by third part libraries
     * - it used just as constant value
     */
    INTERVAL_INITIAL_INDEX: -1,



    /**
     * - keep the current interval index
     * - it should be modified/taken just by setter/getter methods
     * - after computing, it should not be a negative value
     */
    _current_interval_index : this.INTERVAL_INITIAL_INDEX,



    /**
     * - it keeps the interval index
     * - it should be modified/taken just by setter/getter methods
     * - it must be a crescendo positive values
     */
    _interval_list : [],



    /**
     *
     */
    init: function init() {
        if ((typeof window.td_viewport_interval_list !== 'undefined') && (window.td_viewport_interval_list.constructor === Array)) {

            for (var i = 0; i < window.td_viewport_interval_list.length; i++) {
                var item = new td_viewport.item();

                var current_val = window.td_viewport_interval_list[i];

                // the check is done to be sure that the intervals are well formatted
                if (!current_val.hasOwnProperty('limit_bottom') || !current_val.hasOwnProperty('sidebar_width')) {
                    break;
                }

                item.limit_bottom = current_val['limit_bottom'];
                item.sidebar_width = current_val['sidebar_width'];

                td_viewport._items.push(item);
            }

            td_viewport.detect_changes();
        }
    },



    /**
     * - getter of the _current_interval_index
     * - it should be used by outsiders libraries
     * @returns {*}
     */
    get_current_interval_index : function get_current_interval_index() {
        return td_viewport._current_interval_index;
    },



    /**
     * - setter of the _interval_list
      - it should be used by outsiders libraries
     * @param value
     */
    set_interval_list : function set_interval_list(value) {
        td_viewport._interval_list = value;
    },



    /**
     * - getter of the _interval_list
     * - it should be used by outsiders libraries
     * @returns {*}
     */
    get_interval_list : function get_interval_list() {
        return td_viewport._interval_list;
    },



    /**
     * - getter of the td_viewport current item
     * - it should be used by outsiders libraries
     * @returns {*}
     */
    get_current_interval_item : function get_current_interval_item() {

        if (td_viewport._current_interval_index == td_viewport.INTERVAL_INITIAL_INDEX ||
            td_viewport._current_interval_index == 0) {

            return null;
        }

        return td_viewport._items[td_viewport._current_interval_index - 1];
    },





    _items : [],




    item : function item() {
        this.limit_bottom = undefined;
        this.sidebar_width = undefined;
    },





    /**
     * - detect view port changes
     * - it returns true if the change view port has changed, false otherwise
     * - it also sets the _current_interval_index
     * @returns {boolean} True when viewport has changed
     */
    detect_changes: function detect_changes() {
        var result = false;

        var real_view_port_width = 0;
        var local_current_interval_index = 0;

        if (td_detect.is_safari === true) {
            real_view_port_width = this._safari_view_port_width.get_real_width();
        } else {
            real_view_port_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        }

        for (var i = 0; i < td_viewport._items.length; i++) {

            if (real_view_port_width <= td_viewport._items[i].limit_bottom) {

                if (local_current_interval_index != td_viewport._current_interval_index) {
                    td_viewport._current_interval_index = local_current_interval_index;
                    result = true;

                    td_viewport.log('changing viewport ' + td_viewport._current_interval_index + ' ~ ' + real_view_port_width);
                }

                break;
            }
            local_current_interval_index++;
        }

        if ((result == false) && (local_current_interval_index != td_viewport._current_interval_index)) {
            td_viewport._current_interval_index = local_current_interval_index;
            result = true;

            td_viewport.log('changing viewport ' + td_viewport._current_interval_index + ' ~ ' + real_view_port_width);
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

        get_real_width : function get_real_widht () {
            if (this.div_added === false) {
                // we don't have a div present
                this.div_jquery_object = jQuery('<div>')
                    .css({

                        "height": "1px",
                        "position": "absolute",
                        "top": "-1px",
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
        //console.log(msg);
    }
};

td_viewport.init();
