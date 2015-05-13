<?php
function td_js_generator() {
    td_js_buffer::add_variable('td_ajax_url', admin_url('admin-ajax.php?td_theme_name=' . TD_THEME_NAME . '&v=' . TD_THEME_VERSION));
    td_js_buffer::add_variable('td_get_template_directory_uri', get_template_directory_uri());
    td_js_buffer::add_variable('tds_snap_menu', td_util::get_option('tds_snap_menu'));
    td_js_buffer::add_variable('tds_logo_on_sticky', td_util::get_option('tds_logo_on_sticky'));
    td_js_buffer::add_variable('tds_header_style', td_util::get_option('tds_header_style'));


    td_js_buffer::add_variable('td_search_url', get_search_link());

    td_js_buffer::add_variable('td_please_wait', __td('Please wait...', TD_THEME_NAME));
    td_js_buffer::add_variable('td_email_user_pass_incorrect', __td('User or password incorrect!', TD_THEME_NAME));
    td_js_buffer::add_variable('td_email_user_incorrect', __td('Email or username incorrect!', TD_THEME_NAME));
    td_js_buffer::add_variable('td_email_incorrect', __td('Email incorrect!', TD_THEME_NAME));

    //use for more articles on post pages
    td_js_buffer::add_variable('tds_more_articles_on_post_enable', td_util::get_option('tds_more_articles_on_post_pages_enable'));
    td_js_buffer::add_variable('tds_more_articles_on_post_time_to_wait', td_util::get_option('tds_more_articles_on_post_pages_time_to_wait'));
    td_js_buffer::add_variable('tds_more_articles_on_post_pages_distance_from_top', intval(td_util::get_option('tds_more_articles_on_post_pages_distance_from_top')));

    //theme color - used for loading box
    $td_get_db_theme_color = td_util::get_option('tds_theme_color');
    if(!preg_match('/^#[a-f0-9]{6}$/i', $td_get_db_theme_color)) {
        $td_get_db_theme_color = '#4db2ec';//default theme color
    }
    td_js_buffer::add_variable('tds_theme_color_site_wide', $td_get_db_theme_color);

    td_js_buffer::add_variable('tds_smart_sidebar', td_util::get_option('tds_smart_sidebar'));


    // magnific popup translations
    td_js_buffer::add_variable('td_magnific_popup_translation_tPrev', __td('Previous (Left arrow key)', TD_THEME_NAME));
    td_js_buffer::add_variable('td_magnific_popup_translation_tNext', __td('Next (Right arrow key)', TD_THEME_NAME));
    td_js_buffer::add_variable('td_magnific_popup_translation_tCounter', __td('%curr% of %total%', TD_THEME_NAME));
    td_js_buffer::add_variable('td_magnific_popup_translation_ajax_tError', __td('The content from %url% could not be loaded.', TD_THEME_NAME));
    td_js_buffer::add_variable('td_magnific_popup_translation_image_tError', __td('The image #%curr% could not be loaded.', TD_THEME_NAME));


    td_js_buffer::add_to_header("
var td_blocks = []; //here we store all the items for the current page

//td_block class - each ajax block uses a object of this class for requests
function td_block() {
    this.id = '';
    this.block_type = 1; //block type id (1-234 etc)
    this.atts = '';
    this.td_column_number = '';
    this.td_current_page = 1; //
    this.post_count = 0; //from wp
    this.found_posts = 0; //from wp
    this.max_num_pages = 0; //from wp
    this.td_filter_value = ''; //current live filter value
    this.td_filter_ui_uid = ''; //used to select a item from the drop down filter
    this.is_ajax_running = false;
    this.td_user_action = ''; // load more or infinite loader (used by the animation)
    this.header_color = '';
    this.ajax_pagination_infinite_stop = ''; //show load more at page x
}

    ");
}

// we have to call the td_js_generator on "some" hook due to the fact that td_translate is loaded on 'after_setup_theme'
// and we don't have the _td translation function yet
add_action('wp_head', 'td_js_generator', 10);
add_action('admin_head', 'td_js_generator', 10);