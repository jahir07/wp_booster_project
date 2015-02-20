var td_loading_box = {

    //array_colors: ['#ffffff', '#fafafa', '#ececec', '#dddddd', '#bfbfbf', '#9a9a9a', '#7e7e7e', '#636363'],//whiter -> darker

    array_colors_temp: ['rgba(99, 99, 99, 0)', 'rgba(99, 99, 99, 0.05)', 'rgba(99, 99, 99, 0.08)', 'rgba(99, 99, 99, 0.2)', 'rgba(99, 99, 99, 0.3)', 'rgba(99, 99, 99, 0.5)', 'rgba(99, 99, 99, 0.6)', 'rgba(99, 99, 99, 1)'],//whiter -> darker

    array_colors: [],

    status_animation: 'stop',

    //stop loading box
    stop : function stop () {
        td_loading_box.status_animation = 'stop';
        //jQuery('.td-loader-gif').html("");
    },


    //init loading box
    init : function init (color) {

        var td_color_reg_exp = /^#[a-zA-Z0-9]{3,6}$/;
        if(color && td_color_reg_exp.test(color)) {

            var col_rgba = td_loading_box.hexToRgb(color);

            var rgba_string = "rgba(" + col_rgba.r + ", " + col_rgba.g + ", " + col_rgba.b + ", ";

            td_loading_box.array_colors[7] = rgba_string + " 1)";
            td_loading_box.array_colors[6] = rgba_string + " 0.6)";
            td_loading_box.array_colors[5] = rgba_string + " 0.5)";
            td_loading_box.array_colors[4] = rgba_string + " 0.3)";
            td_loading_box.array_colors[3] = rgba_string + " 0.2)";
            td_loading_box.array_colors[2] = rgba_string + " 0.08)";
            td_loading_box.array_colors[1] = rgba_string + " 0.05)";
            td_loading_box.array_colors[0] = rgba_string + " 0)";

        } else {
            //default array
            td_loading_box.array_colors = td_loading_box.array_colors_temp.slice(0);

        }

        if(td_loading_box.status_animation == 'stop') {
            td_loading_box.status_animation = 'display';
            this.render();
        }
    },


    //create the animation
    render: function render (color) {

        //call the animation_display function
        td_loading_box.animation_display('<div class="td-lb-box td-lb-box-1" style="background-color:' + td_loading_box.array_colors[0] + '"></div><div class="td-lb-box td-lb-box-2" style="background-color:' + td_loading_box.array_colors[1] + '"></div><div class="td-lb-box td-lb-box-3" style="background-color:' + td_loading_box.array_colors[2] + '"></div><div class="td-lb-box td-lb-box-4" style="background-color:' + td_loading_box.array_colors[3] + '"></div><div class="td-lb-box td-lb-box-5" style="background-color:' + td_loading_box.array_colors[4] + '"></div><div class="td-lb-box td-lb-box-6" style="background-color:' + td_loading_box.array_colors[5] + '"></div><div class="td-lb-box td-lb-box-7" style="background-color:' + td_loading_box.array_colors[6] + '"></div><div class="td-lb-box td-lb-box-8" style="background-color:' + td_loading_box.array_colors[7] + '"></div>');

        //direction right
        var temp_color_array = [td_loading_box.array_colors[0], td_loading_box.array_colors[1], td_loading_box.array_colors[2], td_loading_box.array_colors[3], td_loading_box.array_colors[4], td_loading_box.array_colors[5], td_loading_box.array_colors[6], td_loading_box.array_colors[7]];

        td_loading_box.array_colors[0] = temp_color_array[7];
        td_loading_box.array_colors[1] = temp_color_array[0];
        td_loading_box.array_colors[2] = temp_color_array[1];
        td_loading_box.array_colors[3] = temp_color_array[2];
        td_loading_box.array_colors[4] = temp_color_array[3];
        td_loading_box.array_colors[5] = temp_color_array[4];
        td_loading_box.array_colors[6] = temp_color_array[5];
        td_loading_box.array_colors[7] = temp_color_array[6];

        if(td_loading_box.status_animation == 'display') {


            setTimeout(td_loading_box.render, 40);
        } else {
            td_loading_box.animation_display('');
        }
    },


    //display the animation
    animation_display: function animation_display (animation_str) {
        jQuery('.td-loader-gif').html(animation_str);
    },


    //converts hex to rgba
    hexToRgb: function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
}; //td_loading_box.init();//td_loading_box.stop();