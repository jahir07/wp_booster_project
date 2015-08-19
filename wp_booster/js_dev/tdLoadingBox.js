/* global jQuery:false */
/* global tdUtil:false */

var tdLoadingBox = {};


( function() {
    "use strict";
    tdLoadingBox = {

        //arrayColors: ['#ffffff', '#fafafa', '#ececec', '#dddddd', '#bfbfbf', '#9a9a9a', '#7e7e7e', '#636363'],//whiter -> darker

        speed: 40,

        arrayColorsTemp: [
            'rgba(99, 99, 99, 0)',
            'rgba(99, 99, 99, 0.05)',
            'rgba(99, 99, 99, 0.08)',
            'rgba(99, 99, 99, 0.2)',
            'rgba(99, 99, 99, 0.3)',
            'rgba(99, 99, 99, 0.5)',
            'rgba(99, 99, 99, 0.6)',
            'rgba(99, 99, 99, 1)'
        ],//whiter -> darker

        arrayColors: [],

        statusAnimation: 'stop',

        //stop loading box
        stop : function stop () {
            tdLoadingBox.statusAnimation = 'stop';
            //jQuery('.td-loader-gif').html("");
        },


        //init loading box
        init : function init (color, speed) {

            // set up the speed
            if (false === tdUtil.isUndefined(speed)) {
                tdLoadingBox.speed = speed;
            }

            //console.log('test');
            var tdColorRegExp = /^#[a-zA-Z0-9]{3,6}$/;
            if(color && tdColorRegExp.test(color)) {

                var colRgba = tdLoadingBox.hexToRgb(color);

                var rgbaString = "rgba(" + colRgba.r + ", " + colRgba.g + ", " + colRgba.b + ", ";

                tdLoadingBox.arrayColors[7] = rgbaString + " 1)";
                tdLoadingBox.arrayColors[6] = rgbaString + " 0.6)";
                tdLoadingBox.arrayColors[5] = rgbaString + " 0.5)";
                tdLoadingBox.arrayColors[4] = rgbaString + " 0.3)";
                tdLoadingBox.arrayColors[3] = rgbaString + " 0.2)";
                tdLoadingBox.arrayColors[2] = rgbaString + " 0.08)";
                tdLoadingBox.arrayColors[1] = rgbaString + " 0.05)";
                tdLoadingBox.arrayColors[0] = rgbaString + " 0)";

            } else {
                //default array
                tdLoadingBox.arrayColors = tdLoadingBox.arrayColorsTemp.slice(0);

            }

            if(tdLoadingBox.statusAnimation === 'stop') {
                tdLoadingBox.statusAnimation = 'display';
                this.render();
            }
        },


        //create the animation
        render: function render (color) {

            //call the animationDisplay function
            tdLoadingBox.animationDisplay(
                '<div class="td-lb-box td-lb-box-1" style="background-color:' + tdLoadingBox.arrayColors[0] + '"></div>' +
                '<div class="td-lb-box td-lb-box-2" style="background-color:' + tdLoadingBox.arrayColors[1] + '"></div>' +
                '<div class="td-lb-box td-lb-box-3" style="background-color:' + tdLoadingBox.arrayColors[2] + '"></div>' +
                '<div class="td-lb-box td-lb-box-4" style="background-color:' + tdLoadingBox.arrayColors[3] + '"></div>' +
                '<div class="td-lb-box td-lb-box-5" style="background-color:' + tdLoadingBox.arrayColors[4] + '"></div>' +
                '<div class="td-lb-box td-lb-box-6" style="background-color:' + tdLoadingBox.arrayColors[5] + '"></div>' +
                '<div class="td-lb-box td-lb-box-7" style="background-color:' + tdLoadingBox.arrayColors[6] + '"></div>' +
                '<div class="td-lb-box td-lb-box-8" style="background-color:' + tdLoadingBox.arrayColors[7] + '"></div>'
            );

            //direction right
            var tempColorArray = [
                tdLoadingBox.arrayColors[0],
                tdLoadingBox.arrayColors[1],
                tdLoadingBox.arrayColors[2],
                tdLoadingBox.arrayColors[3],
                tdLoadingBox.arrayColors[4],
                tdLoadingBox.arrayColors[5],
                tdLoadingBox.arrayColors[6],
                tdLoadingBox.arrayColors[7]
            ];

            tdLoadingBox.arrayColors[0] = tempColorArray[7];
            tdLoadingBox.arrayColors[1] = tempColorArray[0];
            tdLoadingBox.arrayColors[2] = tempColorArray[1];
            tdLoadingBox.arrayColors[3] = tempColorArray[2];
            tdLoadingBox.arrayColors[4] = tempColorArray[3];
            tdLoadingBox.arrayColors[5] = tempColorArray[4];
            tdLoadingBox.arrayColors[6] = tempColorArray[5];
            tdLoadingBox.arrayColors[7] = tempColorArray[6];

            if(tdLoadingBox.statusAnimation === 'display') {


                setTimeout(tdLoadingBox.render, tdLoadingBox.speed);
            } else {
                tdLoadingBox.animationDisplay('');
            }
        },


        //display the animation
        animationDisplay: function (animation_str) {
            jQuery('.td-loader-gif').html(animation_str);
        },


        //converts hex to rgba
        hexToRgb: function (hex) {
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }
    }; //tdLoadingBox.init();//tdLoadingBox.stop();
})();





