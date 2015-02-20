<?php


$td_defaultOptions['sidebars'] = '';
$td_defaultOptions['td_ad_spots'] = '';

//add and autoload the option
add_option(TD_THEME_OPTIONS_NAME, $td_defaultOptions, '', 'yes' );

//moved in a new function in td_util.php
//td_global::$td_options = get_option(TD_THEME_OPTIONS_NAME);


function td_on_theme_activate($oldname, $oldtheme=false) {

    //the pagebuilder templates
    $td_pagebuilder_templates = array
    (
        'homepage_3256' => Array (
            'name' => 'Homepage',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="5"][td_block_big_grid sort="featured"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][td_block_1 limit="5" custom_title="FASHION WEEK" border_top="no_border_top"  td_filter_default_txt="All" ajax_pagination="next_prev" header_color="#e29c04"][td_block_15 limit="8" custom_title="GADGET WORLD" header_color="#0b8d5d" td_filter_default_txt="All" ajax_pagination="next_prev"][vc_row_inner][vc_column_inner width="1/2"][td_block_2 limit="1" custom_title="BEST Smartphones" header_color="#4db2ec" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column_inner][vc_column_inner width="1/2"][td_block_10 custom_title="DON\'T MISS" td_filter_default_txt="All" limit="3" sort="random_posts"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][td_block_ad_box spot_id="sidebar"][td_block_6 limit="2" custom_title="POPULAR VIDEO" td_filter_default_txt="All" header_color="#ed581c"][td_block_8 limit="3" custom_title="HOLIDAY RECIPES" header_color="#0152a9" td_filter_default_txt="All"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_14 limit="3" custom_title="EVEN MORE NEWS" td_filter_default_txt="All" ajax_pagination="next_prev" header_color="#288abf"][/vc_column][/vc_row]'
        ),

        'homepage_4160' => Array (
            'name' => 'Homepage - loop',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}" el_class="td-ss-row"][vc_column width="2/3"][td_block_slide limit="1" td_filter_default_txt="All"][td_block_11 limit="3" offset="1" td_filter_default_txt="All" border_top="no_border_top"][td_block_ad_box spot_id="custom_ad_1"][td_block_slide limit="1" td_filter_default_txt="All"][td_block_11 limit="3" offset="1" td_filter_default_txt="All" border_top="no_border_top"][td_block_ad_box spot_id="custom_ad_1"][td_block_slide limit="1" td_filter_default_txt="All"][td_block_11 limit="3" offset="1" td_filter_default_txt="All" border_top="no_border_top"][/vc_column][vc_column width="1/3"][td_block_social_counter custom_title="STAY CONNECTED" facebook="themeforest" twitter="envato" youtube="Envato" open_in_new_window="y" border_top="no_border_top"][td_block_ad_box spot_id="sidebar"][td_block_9 limit="3" custom_title="FEATURED" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_2 limit="3" custom_title="MOST POPULAR" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_6 limit="2" custom_title="LATEST REVIEWS" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}"][vc_column width="1/1"][td_block_ad_box spot_id="custom_ad_2"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_3 limit="6" custom_title="LATEST ARTICLES" td_filter_default_txt="All" ajax_pagination="infinite"][/vc_column][/vc_row]'
        ),

        'homepage_5160' => Array (
            'name' => 'Homepage - big slide',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][td_block_slide limit="3" td_filter_default_txt="All"][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_2 limit="6" custom_title="DON\'T MISS" header_color="#4db2ec" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][/vc_column][vc_column width="1/3"][td_block_social_counter custom_title="STAY CONNECTED" facebook="themeforest" twitter="envato" youtube="UCqglgyk8g84CMLzPuZpzxhQ" open_in_new_window="y" border_top="no_border_top"][td_block_9 limit="2" custom_title="MOST POPULAR" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_16 limit="5" custom_title="LATEST VIDEOS" td_filter_default_txt="All" ajax_pagination="next_prev" color_preset="td-block-color-style-2" header_color="#ffffff" header_text_color="#000000"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][td_block_2 limit="2" custom_title="TRAVEL GUIDES" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top" header_color="#c7272f"][td_block_2 limit="2" custom_title="MOBILE AND PHONES" td_filter_default_txt="All" ajax_pagination="next_prev" offset="1" header_color="#107a56"][td_block_ad_box spot_id="custom_ad_1"][td_block_2 limit="2" custom_title="NEW YORK 2014" td_filter_default_txt="All" ajax_pagination="next_prev" offset="1" header_color="#e83e9e"][/vc_column][vc_column width="1/3"][td_block_9 limit="3" custom_title="TECH" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_15 limit="4" custom_title="FASHION" color_preset="td-block-color-style-2" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_2 limit="3" custom_title="LATEST REVIEWS" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_14 limit="3" custom_title="ENTERTAINMENT" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row]'
        ),

        'homepage_5170' => Array (
            'name' => 'Homepage - random',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][td_block_big_grid][/vc_column][/vc_row][vc_row][vc_column width="2/3"][td_block_1 limit="5" custom_title="TRAVEL GUIDE"  td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_16 limit="6" custom_title="LATEST VIDEOS" td_filter_default_txt="All"][td_block_ad_box spot_id="custom_ad_1"][td_block_1 limit="5" custom_title="GADGETS WORLD" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][vc_column width="1/3"][td_block_social_counter custom_title="STAY CONNECTED" facebook="themeforest" twitter="envato" youtube="Envato" open_in_new_window="y" border_top="no_border_top"][td_block_9 limit="2" custom_title="LIFESTYLE" td_filter_default_txt="All"][td_block_2 limit="3" custom_title="NUTRITION" color_preset="td-block-color-style-2" td_filter_default_txt="All" header_text_color="#000000" header_color="#ffffff" ajax_pagination="next_prev"][td_block_9 limit="4" custom_title="FASHION WEEK" td_filter_default_txt="All"][/vc_column][/vc_row]'
        ),

        'homepage_5180' => Array (
            'name' => 'Homepage - less images',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_2 limit="2" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_9 limit="6" custom_title="FASHION WEEK" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_9 limit="6" custom_title="DON\'T MISS" td_filter_default_txt="All" header_color="#4db2ec" color_preset="td-block-color-style-2"][td_block_ad_box spot_id="custom_ad_1"][td_block_1 limit="5" custom_title="GADGET WORLD" td_filter_default_txt="All"][vc_row_inner][vc_column_inner width="1/2"][td_block_10 limit="3" custom_title="LIFESTYLE" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column_inner][vc_column_inner width="1/2"][td_block_10 limit="3" custom_title="MOBILE AND PHONES" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][td_block_social_counter custom_title="STAY CONNECTED" facebook="themeforest" twitter="envato" youtube="Envato" open_in_new_window="y"][td_block_ad_box spot_id="sidebar"][vc_wp_recentcomments title="RECENT COMMENTS" number="3"][td_block_2 limit="1" custom_title="LATEST REVIEWS" td_filter_default_txt="All" ajax_pagination="next_prev"][vc_wp_posts show_date="1" title="RECENT POSTS" number="3"][/vc_column][/vc_row]'
        ),

        'homepage_5190' => Array (
            'name' => 'Homepage - sport',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now sort="random_posts" limit="5"][td_block_big_grid][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_2 limit="6" custom_title="POPULAR NEWS" border_top="no_border_top" td_filter_default_txt="All" ajax_pagination="next_prev"][td_block_1 limit="5" custom_title="TRAVEL" td_filter_default_txt="All" offset="1" ajax_pagination="next_prev"][/vc_column][vc_column width="1/3"][td_block_social_counter facebook="themeforest" twitter="envato" youtube="UCqglgyk8g84CMLzPuZpzxhQ"][td_block_9 limit="3" custom_title="FOOD" td_filter_default_txt="All"][td_block_15 limit="3" custom_title="FASHION" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_video_youtube playlist_yt="PELlHslllk0, gWL-r72tGOE, aZJSMxsjimQ, ujfOyae1eww, _kC_kwWPTx4, BdEOq7XAyrA, -S9L38ZqHw8, FSMxYS6h2tw, w6nXDPEI768, 3jT_q7dt-cM" playlist_auto_play="0" playlist_title="Video playlist"][/vc_column][/vc_row]'
        ),

        'homepage_5200' => Array (
            'name' => 'Homepage - tech',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="5"][td_block_14 limit="3" custom_title="FEATURED" td_filter_default_txt="All"][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_1 limit="5" custom_title="WHAT\'S NEW" td_filter_default_txt="All" border_top="no_border_top"][td_block_2 limit="6" custom_title="ACCESSORIES" header_color="#0a9e01" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][vc_column width="1/3"][td_block_ad_box spot_id="sidebar"][td_block_6 limit="1" custom_title="WINDOWS PHONE" td_filter_default_txt="All" header_color="#55a4ff"][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_big_grid][/vc_column][/vc_row]'
        ),

        'homepage_5210' => Array (
            'name' => 'Homepage - full post featured',
            'template' => '[vc_row][vc_column width="1/1"][td_block_homepage_full_1][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_2 limit="6" custom_title="DON\'T MISS" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_15 limit="6" custom_title="Lifestyle" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][vc_column width="1/3"][td_block_ad_box spot_id="sidebar"][td_block_1 limit="3" custom_title="Food" td_filter_default_txt="All"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}"][vc_column width="1/1"][td_block_ad_box spot_id="custom_ad_2"][/vc_column][/vc_row]'
        ),

        'homepage_5220' => Array (
            'name' => 'Homepage - blog',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="5"][/vc_column][/vc_row]'
        ),

        'homepage_5230' => Array (
            'name' => 'Homepage - newspaper',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][td_block_big_grid][/vc_column][/vc_row][vc_row][vc_column width="2/3"][td_block_2 limit="6" custom_title="DON\'T MISS" header_color="#4db2ec" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_1 limit="5" custom_title="GADGET WORLD" header_color="#0b8d5d" td_filter_default_txt="All"] [td_block_ad_box spot_id="custom_ad_1"][td_block_2 limit="6" custom_title="TRAVEL GUIDES" header_color="#f24b4b" td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][vc_column width="1/3"][td_block_social_counter custom_title="STAY CONNECTED" facebook="themeforest" twitter="envato" youtube="Envato" open_in_new_window="y" border_top="no_border_top"][td_block_ad_box spot_id="sidebar"][td_block_2 limit="3" custom_title="LATEST REVIEWS" td_filter_default_txt="All"][td_block_slide limit="3" custom_title="POPULAR VIDEO" td_filter_default_txt="All"][td_block_9 limit="3" custom_title="CHICAGO SHOW" td_filter_default_txt="All"][/vc_column][/vc_row][vc_row el_class="td-ss-row"][vc_column width="2/3"][td_block_2 limit="6" custom_title="FASHION AND TRENDS" header_color="#ff3e9f"  td_filter_default_txt="All"][/vc_column][vc_column width="1/3"][td_block_7 limit="1" custom_title="EDITOR PICKS" td_filter_default_txt="All"][td_block_ad_box spot_id="sidebar"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}"][vc_column width="1/1"][td_block_ad_box spot_id="custom_ad_2"][/vc_column][/vc_row]'
        ),

        'homepage_5240' => Array (
            'name' => 'Homepage - infinite scroll',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][td_block_big_grid][/vc_column][/vc_row][vc_row][vc_column width="1/1"][td_block_2 limit="3" custom_title="DON\'T MISS"  td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}" el_class="td-ss-row"][vc_column width="2/3"][td_block_3 limit="6" custom_title="LATEST ARTICLES" td_filter_default_txt="All" ajax_pagination="infinite" border_top="no_border_top" ajax_pagination_infinite_stop="3"][/vc_column][vc_column width="1/3"][vc_widget_sidebar sidebar_id="td-default"][/vc_column][/vc_row]'
        ),

        'homepage_5250' => Array (
            'name' => 'Homepage - magazine',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][/vc_column][/vc_row][vc_row css=".td-top-border{border-top-width: 1px !important;}"][vc_column width="2/3"][td_block_slide limit="3" td_filter_default_txt="All"][td_block_2 limit="6" custom_title="DON\'T MISS" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_1 limit="5" custom_title="GADGETS WORLD" td_filter_default_txt="All" border_top="no_border_top"][td_block_ad_box spot_id="custom_ad_1"][td_block_15 limit="8" custom_title="LIFESTYLE" td_filter_default_txt="All"][/vc_column][vc_column width="1/3"][td_block_social_counter facebook="themeforest" twitter="envato" youtube="Envato" custom_title="STAY CONNECTED" border_top="no_border_top"][td_block_9 limit="5" custom_title="LIFESTYLE" td_filter_default_txt="All" ajax_pagination="next_prev" border_top="no_border_top"][td_block_ad_box spot_id="sidebar"][vc_wp_posts show_date="1" title="RECENT POSTS" number="5"][td_block_2 limit="3" custom_title="LATEST REVIEWS" td_filter_default_txt="All"][/vc_column][/vc_row]'
        ),

        'homepage_5260' => Array (
            'name' => 'Homepage - fashion',
            'template' => '[vc_row][vc_column width="1/1"][td_block_homepage_full_1][td_block_2 limit="3" custom_title="MOST POPULAR" border_top="no_border_top"  td_filter_default_txt="All" ajax_pagination="next_prev"][/vc_column][/vc_row]'
        ),

        'homepage_5270' => Array (
            'name' => 'Homepage - clean',
            'template' => '[vc_row][vc_column width="1/1"][td_block_trending_now limit="3"][td_block_big_grid][/vc_column][/vc_row]'
        ),

        'contact_533' => Array (
            'name' => 'Contact',
            'template' => '[vc_row][vc_column width="2/3"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse non nunc ac quam congue fermentum et vel massa. Proin imperdiet pulvinar rhoncus. Integer in elit accumsan, ullamcorper ante non, commodo velit. Nunc luctus scelerisque dui, vitae luctus est auctor eu.[/vc_column_text][vc_row_inner][vc_column_inner width="1/2"][td_block_text_with_title custom_title="Contact Details"]Newsmag Comunication Service
425 Santa Teresa St. Stanford

(650) 723-2558 (main number)
(650) 725-0247 (fax)

Email: contact@newsmag.com[/td_block_text_with_title][/vc_column_inner][vc_column_inner width="1/2" css=".td-no-left-border{border-left-width: 0px !important;}"][td_block_text_with_title custom_title="About us"]Newsmag is your news, entertainment, music fashion website. We provide you with the latest breaking news and videos straight from the entertainment industry.[/td_block_text_with_title][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3"][vc_widget_sidebar sidebar_id="td-default"][/vc_column][/vc_row]'
        ),

        'sidebar-right_28390' => Array (
            'name' => 'Sidebar right',
            'template' => '[vc_row][vc_column width="2/3"][/vc_column][vc_column width="1/3"][vc_widget_sidebar sidebar_id="td-default"][/vc_column][/vc_row]'
        ),

        'sidebar-left_18719' => Array (
            'name' => 'Sidebar left',
            'template' => '[vc_row][vc_column width="1/3"][vc_widget_sidebar sidebar_id="td-default"][/vc_column][vc_column width="2/3"][/vc_column][/vc_row]'
        )
    );

    update_option('wpb_js_templates',$td_pagebuilder_templates);

    //update the wordpress default time format
    update_option('date_format', 'M j, Y');  // @todo this one may not be needed


}
add_action("after_switch_theme", "td_on_theme_activate", 10 ,  2);



$td_isFirstInstall = td_util::get_option('firstInstall');
if (empty($td_isFirstInstall)) {
    td_util::update_option('firstInstall', 'themeInstalled');

    wp_insert_term('Featured', 'category', array(
        'description' => 'Featured posts',
        'slug' => 'featured',
        'parent' => 0
    ));

    // enable the default thumbs only on the first install
    td_util::update_option('tds_thumb_td_640x0', 'yes');
    td_util::update_option('tds_thumb_td_100x75', 'yes');
    td_util::update_option('tds_thumb_td_300x160', 'yes');
    td_util::update_option('tds_thumb_td_300x194', 'yes');
    td_util::update_option('tds_thumb_td_1021x580', 'yes');
    td_util::update_option('tds_thumb_td_180x135', 'yes');
    td_util::update_option('tds_thumb_td_238x178', 'yes');
    td_util::update_option('tds_thumb_td_537x360', 'yes');
}

