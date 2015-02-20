

td_pulldown.init();

jQuery(window).resize(function() {
    td_pulldown.td_events_resize();
});


var jquery_objects = jQuery('.td-category-siblings:first').each(function(index, element) {

    var jquery_object_container = jQuery(this);

    var horizontal_jquery_obj = jquery_object_container.find('.td-category:first');
    var vertical_jquery_obj = jquery_object_container.find('.td-subcat-dropdown:first');

    if (horizontal_jquery_obj.length == 1 && vertical_jquery_obj.length == 1) {

        var item_obj = new td_pulldown.item();

        item_obj.horizontal_jquery_obj = horizontal_jquery_obj;
        item_obj.vertical_jquery_obj = vertical_jquery_obj;

        item_obj.container_jquery_obj = horizontal_jquery_obj.parents('.td-category-siblings:first');

        item_obj.horizontal_element_css_class = 'entry-category';

        td_pulldown.add_item(item_obj);
    }
});



jQuery('body').addClass('td-js-loaded');


