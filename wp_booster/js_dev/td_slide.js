/*
 td_slide.js
 */

"use strict";

//call function to resize the smartlist on ready (for safary)
jQuery(window).load(function() {
    td_resize_smartlist_sliders_and_update();
});

//call function to resize the smartlist on ready
jQuery().ready(function() {
    td_resize_smartlist_sliders_and_update();
});



//function to resize the height of the smartlist slide
function td_resize_smartlist_slides(args) {
    var slide_displayd = args.currentSlideNumber;


    //console.log(args.sliderObject[0]);
    //console.log(args.data.obj[0]);

    var current_slider = jQuery(args.data.obj[0]).attr("id");

    if(!tdDetect.isIe8) {
        jQuery("#" + current_slider).css("overflow", "none");
        jQuery("#" + current_slider + " .td-item").css("overflow", "visible");
    }

    var setHeight = 0;
    setHeight = jQuery("#" + current_slider + "_item_" + slide_displayd).outerHeight(true);


    jQuery("#" + current_slider + ", #" + current_slider + " .td-slider").css({
        height: setHeight
    });
}





//function to resize and update the height of the smartlist slide
function td_resize_smartlist_sliders_and_update() {
    jQuery(document).find('.td-smart-list-slider').each(function() {
        var current_slider = jQuery(this).attr("id");

        if(!tdDetect.isIe8) {
            jQuery("#" + current_slider).css("overflow", "none");
            jQuery("#" + current_slider + " .td-item").css("overflow", "visible");
        }

        var setHeight = 0;
        setHeight = jQuery("#" + current_slider + "_item_" + td_history.get_current_page("slide")).outerHeight(true);

        jQuery("#" + current_slider + ", #" + current_slider + " .td-slider").css({
            height: setHeight
        });

        if(tdDetect.isAndroid) {
            setTimeout(function () {
                jQuery("#" + current_slider).iosSlider("update");
            }, 2000);
        }
    });
}


//function to resize the height of the normal slide
function td_resize_normal_slide(args) {
    var slide_displayd = 0;//args.currentSlideNumber;

    var current_slider = jQuery(args.data.obj[0]).attr("id");

    //get window width
    var window_wight = td_get_document_width();

    if (!tdDetect.isIe8) {
        jQuery("#" + current_slider).css("overflow", "none");
        jQuery("#" + current_slider + " .td-item").css("overflow", "visible");
    }

    var setHeight = 0;
    var slide_outer_width = jQuery("#" + current_slider + "_item_" + slide_displayd).outerWidth(true);

    //only for android, width of the screen to start changing the height of the slide
    var max_wight_resize = 780;
    if(tdDetect.isAndroid) {
        max_wight_resize = 1000;
    }

    if (window_wight < max_wight_resize && !tdDetect.isIpad) {//problem because we cannot get an accurate page width
        if(slide_outer_width > 300) {
            setHeight = slide_outer_width * 0.5;
        } else {
            setHeight = slide_outer_width;
        }

        //console.log(window_wight);
        jQuery("#" + current_slider + ", #" + current_slider + " .td-slider, #" + current_slider + " .td-slider .td-module-thumb").css({
            height: setHeight
        });
    }

}



//function to resize and update the height of the slide for normal sliders
function td_resize_normal_slide_and_update(args) {


    //console.log('resize 2');
    var slide_displayd = 0;//args.currentSlideNumber;

    var current_slider = jQuery(args.data.obj[0]).attr("id");

    //get window width
    var window_wight = td_get_document_width();

    if(!tdDetect.isIe8) {
        jQuery("#" + current_slider).css("overflow", "none");
        jQuery("#" + current_slider + " .td-item").css("overflow", "visible");
    }

    var setHeight = 0;
    var slide_outer_width = jQuery("#" + current_slider + "_item_" + slide_displayd).outerWidth(true);

    //only for android, width of the screen to start changing the height of the slide
    var max_wight_resize = 780;
    if(tdDetect.isAndroid) {
        max_wight_resize = 1000;
    }

    if (window_wight < max_wight_resize && !tdDetect.isIpad) {//problem because we cannot get an accurate page width
        if(slide_outer_width > 300) {
            setHeight = slide_outer_width * 0.5;
        } else {
            setHeight = slide_outer_width;
        }

        //console.log(window_wight);
        jQuery("#" + current_slider + ", #" + current_slider + " .td-slider, #" + current_slider + " .td-slider .td-module-thumb").css({
            height: setHeight
        });

        setTimeout(function () {
            jQuery("#" + current_slider).iosSlider("update");



        }, 2000);

    }
}