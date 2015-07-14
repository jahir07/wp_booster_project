<?php

include_once get_template_directory()  . '/includes/wp_booster/wp-admin/external/wpalchemy/MetaBox.php';


/*  ----------------------------------------------------------------------------
    load our custom meta
 */

$td_template_settings_path = get_template_directory() . '/includes/wp_booster/wp-admin/content-metaboxes/';




/*  ----------------------------------------------------------------------------
    page meta
 */


//default page
$td_meta_homepage_loop = new WPAlchemy_MetaBox(array(
    'id' => 'td_page',
    'title' => 'Page template settings',
    'types' => array('page'),
    'priority' => 'high',
    'template' => $td_template_settings_path . 'td_set_page.php',
));



//homepage with loop
$td_meta_homepage_loop = new WPAlchemy_MetaBox(array(
    'id' => 'td_homepage_loop',
    'title' => 'Homepage latest articles',
    'types' => array('page'),
    'priority' => 'high',
    'template' => $td_template_settings_path . 'td_set_page_with_loop.php',
));




/*  ----------------------------------------------------------------------------
    post meta
 */

// featured video
$td_meta_video_meta = new WPAlchemy_MetaBox(array(
    'id' => 'td_post_video',
    'title' => 'Featured Video',
    'types' => array('post'),
    'priority' => 'low',
    'context' => 'side',
    'template' => $td_template_settings_path . 'td_set_video_meta.php',
));




//post settings
$td_metabox_theme_settings = new WPAlchemy_MetaBox(array(
    'id' => 'td_post_theme_settings',
    'title' => 'Post settings',
    'types' => array('post'),
    'priority' => 'high',
    'template' => get_template_directory() . '/includes/wp_booster/wp-admin/content-metaboxes/td_set_post_settings.php',
));

