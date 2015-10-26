/**
 * Created by tagdiv on 13.05.2015.
 */

/* global tdDetect: {} */
/* global jQuery: {} */

var tdViewport = {};

(function(){

    "use strict";

    tdViewport = {

        /**
         * - initial (default) value of the _currentIntervalIndex
         * - it's used by third part libraries
         * - it used just as constant value
         */
        INTERVAL_INITIAL_INDEX: -1,



        /**
         * - keep the current interval index
         * - it should be modified/taken just by setter/getter methods
         * - after computing, it should not be a negative value
         */
        _currentIntervalIndex : this.INTERVAL_INITIAL_INDEX,



        /**
         * - it keeps the interval index
         * - it should be modified/taken just by setter/getter methods
         * - it must be a crescendo positive values
         */
        _intervalList : [],



        /**
         *
         */
        init: function() {
            if (('undefined' !== typeof window.td_viewport_interval_list) && (Array === window.td_viewport_interval_list.constructor)) {

                for (var i = 0; i < window.td_viewport_interval_list.length; i++) {
                    var item = new tdViewport.item();

                    var currentVal = window.td_viewport_interval_list[i];

                    // the check is done to be sure that the intervals are well formatted
                    if (!currentVal.hasOwnProperty('limitBottom') || !currentVal.hasOwnProperty('sidebarWidth')) {
                        break;
                    }

                    item.limitBottom = currentVal.limitBottom;
                    item.sidebarWidth = currentVal.sidebarWidth;

                    tdViewport._items.push(item);
                }

                tdViewport.detectChanges();
            }
        },



        /**
         * - getter of the _currentIntervalIndex
         * - it should be used by outsiders libraries
         * @returns {*}
         */
        getCurrentIntervalIndex : function() {
            return tdViewport._currentIntervalIndex;
        },



        /**
         * - setter of the _intervalList
          - it should be used by outsiders libraries
         * @param value
         */
        setIntervalList : function(value) {
            tdViewport._intervalList = value;
        },



        /**
         * - getter of the _intervalList
         * - it should be used by outsiders libraries
         * @returns {*}
         */
        getIntervalList : function() {
            return tdViewport._intervalList;
        },



        /**
         * - getter of the tdViewport current item
         * - it should be used by outsiders libraries
         * @returns {*}
         */
        getCurrentIntervalItem : function() {

            if ((tdViewport.INTERVAL_INITIAL_INDEX === tdViewport._currentIntervalIndex) || (0 === tdViewport._currentIntervalIndex)) {
                return null;
            }
            return tdViewport._items[tdViewport._currentIntervalIndex - 1];
        },



        _items : [],



        item : function() {
            this.limitBottom = undefined;
            this.sidebarWidth = undefined;
        },





        /**
         * - detect view port changes
         * - it returns true if the change view port has changed, false otherwise
         * - it also sets the _currentIntervalIndex
         * @returns {boolean} True when viewport has changed
         */
        detectChanges: function() {
            var result = false;

            var realViewPortWidth = 0;
            var localCurrentIntervalIndex = 0;

            if (true === tdDetect.isSafari) {
                realViewPortWidth = this._safariWiewPortWidth.getRealWidth();
            } else {
                realViewPortWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            }

            for (var i = 0; i < tdViewport._items.length; i++) {

                if (realViewPortWidth <= tdViewport._items[i].limitBottom) {

                    if (localCurrentIntervalIndex !== tdViewport._currentIntervalIndex) {
                        tdViewport._currentIntervalIndex = localCurrentIntervalIndex;
                        result = true;

                        tdViewport.log('changing viewport ' + tdViewport._currentIntervalIndex + ' ~ ' + realViewPortWidth);
                    }
                    break;
                }
                localCurrentIntervalIndex++;
            }

            if ((false === result) && (localCurrentIntervalIndex !== tdViewport._currentIntervalIndex)) {
                tdViewport._currentIntervalIndex = localCurrentIntervalIndex;
                result = true;

                tdViewport.log('changing viewport ' + tdViewport._currentIntervalIndex + ' ~ ' + realViewPortWidth);
            }
            return result;
        },


        /**
         * get the real view port width on safari
         * @type {{divAdded: boolean, divJqueryObject: string, getRealWidth: Function}}
         */
        _safariWiewPortWidth : {
            divAdded : false,
            divJqueryObject : '',

            getRealWidth : function() {
                if (false === this.divAdded) {
                    // we don't have a div present
                    this.divJqueryObject = jQuery('<div>')
                        .css({
                            "height": "1px",
                            "position": "absolute",
                            "top": "-1px",
                            "left": "0",
                            "right": "0",
                            "visibility": "hidden",
                            "z-index": "-1"
                        });
                    this.divJqueryObject.appendTo('body');
                    this.divAdded = true;
                }
                return this.divJqueryObject.width();
            }
        },



        log: function log(msg) {
            //console.log(msg);
        }
    };

    tdViewport.init();

})();
