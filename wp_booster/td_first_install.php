<?php

function td_first_install_setup() {
    $td_isFirstInstall = td_util::get_option('firstInstall');
    if (empty($td_isFirstInstall)) {
        td_util::update_option('firstInstall', 'themeInstalled');

        wp_insert_term('Featured', 'category', array(
            'description' => 'Featured posts',
            'slug' => 'featured',
            'parent' => 0
        ));

        // bulk enable all the theme thumbs!
        $td_theme_thumbs = td_api_thumb::get_all();
        foreach ($td_theme_thumbs as $td_theme_thumb_id => $td_theme_thumb_params) {
            td_global::$td_options['tds_thumb_' . $td_theme_thumb_id] = 'yes';
        }
        update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options); // force an update of the options ()

    }
}
td_first_install_setup();



function td_after_theme_is_activated() {
    global $pagenow;
    if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
        wp_redirect(admin_url('admin.php?page=td_theme_welcome'));
        exit;
    }
}
td_after_theme_is_activated();


