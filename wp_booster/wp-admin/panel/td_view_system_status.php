<?php

require_once "td_view_header.php";
?>
<div class="about-wrap td-admin-wrap">
    <h1><?php echo TD_THEME_NAME ?> system status</h1>
    <div class="about-text" style="margin-bottom: 32px;">

        <p>
            Here you can check the system status. Yellow status means that the site will work as expected on the front end but it may cause problems in wp-admin.
            <strong>Memory notice:</strong> - the theme is well tested with a limit of 40MB/request but plugins may require more, for example woocommerce requires 64MB.
        </p>


    </div>




    <?php


    /*  ----------------------------------------------------------------------------
        Theme config
     */

    // Theme name
    td_system_status::add('Theme config', array(
        'check_name' => 'Theme name',
        'tooltip' => '',
        'value' =>  TD_THEME_NAME,
        'status' => 'info'
    ));

    // Theme version
    td_system_status::add('Theme config', array(
        'check_name' => 'Theme version',
        'tooltip' => '',
        'value' =>  TD_THEME_VERSION,
        'status' => 'info'
    ));

    // Theme database version
    td_system_status::add('Theme config', array(
        'check_name' => 'Theme database version',
        'tooltip' => '',
        'value' =>  td_util::get_option('td_version'),
        'status' => 'info'
    ));

    // speed booster
    if (defined('TD_SPEED_BOOSTER')) {
        if (defined('TD_SPEED_BOOSTER_INCOMPATIBLE')) {
            td_system_status::add('Theme config', array(
                'check_name' => 'Speed Booster',
                'tooltip' => '',
                'value' =>  TD_SPEED_BOOSTER . ' - Disabled - incompatible plugin detected: <strong>' . TD_SPEED_BOOSTER_INCOMPATIBLE . '</strong>',
                'status' => 'yellow'
            ));
        } else {
            if (version_compare(TD_SPEED_BOOSTER, 'v4.0', '<')) {
                td_system_status::add('Theme config', array(
                    'check_name' => 'Speed Booster',
                    'tooltip' => '',
                    'value' =>  TD_SPEED_BOOSTER . ' - Old version of speed booster detected. Please uninstall it!',
                    'status' => 'red'
                ));
            } else {
                td_system_status::add('Theme config', array(
                    'check_name' => 'Speed Booster',
                    'tooltip' => '',
                    'value' =>  TD_SPEED_BOOSTER . ' - Active',
                    'status' => 'info'
                ));
            }


        }


    }



    /*  ----------------------------------------------------------------------------
        Server status
     */

    // server info
    td_system_status::add('php.ini configuration', array(
        'check_name' => 'Server software',
        'tooltip' => '',
        'value' =>  esc_html( $_SERVER['SERVER_SOFTWARE'] ),
        'status' => 'info'
    ));

    // php version
    td_system_status::add('php.ini configuration', array(
        'check_name' => 'PHP Version',
        'tooltip' => '',
        'value' => phpversion(),
        'status' => 'info'
    ));

    // post_max_size
    td_system_status::add('php.ini configuration', array(
        'check_name' => 'post_max_size',
        'tooltip' => '',
        'value' =>  ini_get('post_max_size') . '<span class="td-status-small-text"> - You cannot upload images, themes and plugins that have a size bigger than this value.</span>',
        'status' => 'info'
    ));

    // php time limit
    $max_execution_time = ini_get('max_execution_time');
    if ($max_execution_time == 0 or $max_execution_time >= 60) {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'max_execution_time',
            'tooltip' => '',
            'value' =>  $max_execution_time,
            'status' => 'green'
        ));
    } else {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'max_execution_time',
            'tooltip' => '',
            'value' =>  $max_execution_time . '<span class="td-status-small-text"> - the execution time should be bigger than 60 if you plan to use the demos</span>',
            'status' => 'yellow'
        ));
    }


    // php max input vars
    $max_input_vars = ini_get('max_input_vars');
    if ($max_input_vars == 0 or $max_input_vars >= 2000) {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'max_input_vars',
            'tooltip' => '',
            'value' =>  $max_input_vars,
            'status' => 'green'
        ));
    } else {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'max_input_vars',
            'tooltip' => '',
            'value' =>  $max_input_vars . '<span class="td-status-small-text"> - the max_input_vars should be bigger than 2000, otherwise it can cause incomplete saves in the menu panel in WordPress</span>',
            'status' => 'yellow'
        ));
    }

    // suhosin
    if (extension_loaded('suhosin') !== true) {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'SUHOSIN Installed',
            'tooltip' => '',
            'value' => 'False',
            'status' => 'green'
        ));
    } else {
        td_system_status::add('php.ini configuration', array(
            'check_name' => 'SUHOSIN Installed',
            'tooltip' => '',
            'value' =>  'SUHOSIN is installed <span class="td-status-small-text"> - it may cause problems with saving the theme panel if it\'s not properly configured</span>',
            'status' => 'yellow'
        ));
    }







    /*  ----------------------------------------------------------------------------
        WordPress
    */
    // home url
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'WP Home URL',
        'tooltip' => 'test tooltip',
        'value' => home_url(),
        'status' => 'info'
    ));

    // site url
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'WP Site URL',
        'tooltip' => 'test tooltip',
        'value' => site_url(),
        'status' => 'info'
    ));

    // home_url == site_url
    if (home_url() != site_url()) {
        td_system_status::add('WordPress and plugins', array(
            'check_name' => 'Home URL - Site URL',
            'tooltip' => 'Home URL not equal to Site URL, this may indicate a problem with your WordPress configuration.',
            'value' => 'Home URL != Site URL <span class="td-status-small-text">Home URL not equal to Site URL, this may indicate a problem with your WordPress configuration.</span>',
            'status' => 'yellow'
        ));
    }

    // version
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'WP version',
        'tooltip' => '',
        'value' => get_bloginfo('version'),
        'status' => 'info'
    ));


    // is_multisite
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'WP multisite enabled',
        'tooltip' => '',
        'value' => is_multisite() ? 'Yes' : 'No',
        'status' => 'info'
    ));


    // language
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'WP Language',
        'tooltip' => '',
        'value' => get_locale(),
        'status' => 'info'
    ));



    // memory limit
    $memory_limit = td_system_status::wp_memory_notation_to_number(WP_MEMORY_LIMIT);
    if ( $memory_limit < 67108864 ) {
        td_system_status::add('WordPress and plugins', array(
            'check_name' => 'WP Memory Limit',
            'tooltip' => '',
            'value' => size_format( $memory_limit ) . '/request <span class="td-status-small-text">- We recommend setting memory to at least 64MB. The theme is well tested with a 40MB/request limit, but if you are using multiple plugins that may not be enough. See: <a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">Increasing memory allocated to PHP</a></span>',
            'status' => 'yellow'
        ));
    } else {
        td_system_status::add('WordPress and plugins', array(
            'check_name' => 'WP Memory Limit',
            'tooltip' => '',
            'value' => size_format( $memory_limit ) . '/request',
            'status' => 'green'
        ));
    }


    // wp debug
    if (defined('WP_DEBUG') and WP_DEBUG === true) {
        td_system_status::add('WordPress and plugins', array(
            'check_name' => 'WP_DEBUG',
            'tooltip' => '',
            'value' => 'WP_DEBUG is enabled',
            'status' => 'yellow'
        ));
    } else {
        td_system_status::add('WordPress and plugins', array(
            'check_name' => 'WP_DEBUG',
            'tooltip' => '',
            'value' => 'False',
            'status' => 'green'
        ));
    }






    // caching
    $caching_plugin_list = array(
        'wp-super-cache/wp-cache.php' => array(
            'name' => 'WP super cache',
            'status' => 'green',
        ),
        'w3-total-cache/w3-total-cache.php' => array(
            'name' => 'W3 total cache (we recommend WP super cache)',
            'status' => 'yellow',
        ),
        'wp-fastest-cache/wpFastestCache.php' => array(
            'name' => 'WP Fastest Cache (we recommend WP super cache)',
            'status' => 'yellow',
        ),
    );
    $active_plugins = get_option('active_plugins');
    $caching_plugin = 'No caching plugin detected';
    $caching_plugin_status = 'yellow';
    foreach ($active_plugins as $active_plugin) {
        if (isset($caching_plugin_list[$active_plugin])) {
            $caching_plugin = $caching_plugin_list[$active_plugin]['name'];
            $caching_plugin_status = $caching_plugin_list[$active_plugin]['status'];
            break;
        }
    }
    td_system_status::add('WordPress and plugins', array(
        'check_name' => 'Caching plugin',
        'tooltip' => '',
        'value' =>  $caching_plugin,
        'status' => $caching_plugin_status
    ));

    td_system_status::render_tables();



    // social counter cache
    $cache_content = get_option('td_social_api_v3_last_val', '');
    td_system_status::render_social_cache($cache_content);







    ?>




</div>



<?php
   class td_system_status {
       static $system_status = array();
       static function add($section, $status_array) {
           self::$system_status[$section] []= $status_array;
       }


       static function render_tables() {
           foreach (self::$system_status as $section_name => $section_statuses) {
                ?>
                <table class="widefat td-system-status-table" cellspacing="0">
                    <thead>
                        <tr>
                           <th colspan="4"><?php echo $section_name ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php

                    foreach ($section_statuses as $status_params) {
                        ?>
                        <tr>
                            <td class="td-system-status-name"><?php echo $status_params['check_name'] ?></td>
                            <td class="td-system-status-help"><!--<a href="#" class="help_tip">[?]</a>--></td>
                            <td class="td-system-status-status">
                                <?php
                                    switch ($status_params['status']) {
                                        case 'green':
                                            echo '<div class="td-system-status-led td-system-status-green td-tooltip" data-position="right" title="Green status: this check passed our system status test!"></div>';
                                            break;
                                        case 'yellow':
                                            echo '<div class="td-system-status-led td-system-status-yellow td-tooltip" data-position="right" title="Yellow status: this setting may affect the backend of the site. The front end should still run as expected. We recommend that you fix this."></div>';
                                            break;
                                        case 'red' :
                                            echo '<div class="td-system-status-led td-system-status-red td-tooltip" data-position="right" title="Red status: the site may not work as expected with this option."></div>';
                                            break;
                                        case 'info':
                                            echo '<div class="td-system-status-led td-system-status-info td-tooltip" data-position="right" title="Info status: this is just for information purposes and easier debug if a problem appears">i</div>';
                                            break;

                                    }


                                ?>
                            </td>
                            <td class="td-system-status-value"><?php echo $status_params['value'] ?></td>
                        </tr>
                        <?php
                    }

                ?>
                    </tbody>
                </table>
                <?php
           }
       }


       static function render_social_cache($cache_entries) {
           if (!empty($cache_entries) and is_array($cache_entries)) {
                ?>
                <table class="widefat td-system-status-table" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Social network cache status:</th>
                            <th>Last request count:</th>
                            <th>Last good count:</th>
                            <th>Timestamp - (h:m:s) ago:</th>
                            <th>Expires:</th>
                            <th>SN User:</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($cache_entries as $social_network_id => $cache_params) {
                        if (empty($cache_params['count'])) {
                            $cache_params['count'] = '';
                        }

                        if (empty($cache_params['ok_count'])) {
                            $cache_params['ok_count'] = '';
                        }

                        if (empty($cache_params['timestamp'])) {
                            $cache_params['timestamp'] = '';
                        }

                        if (empty($cache_params['expires'])) {
                            $cache_params['expires'] = '';
                        }

                        if (empty($cache_params['uid'])) {
                            $cache_params['uid'] = '';
                        }
                        ?>
                        <tr>
                            <td class="td-system-status-name"><?php echo $social_network_id ?></td>
                            <td><?php echo $cache_params['count'] ?></td>
                            <td><?php echo $cache_params['ok_count'] ?></td>
                            <td><?php echo $cache_params['timestamp'] . ' - ' . gmdate("H:i:s", time() - $cache_params['timestamp'])?> ago</td>
                            <td><?php echo $cache_params['expires'] ?></td>
                            <td><?php echo $cache_params['uid'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>


                    </tbody>
                </table>
                <?php
           }
       }


       static function render_diagnostics() {

       }

       static function wp_memory_notation_to_number( $size ) {
           $l   = substr( $size, -1 );
           $ret = substr( $size, 0, -1 );
           switch ( strtoupper( $l ) ) {
               case 'P':
                   $ret *= 1024;
               case 'T':
                   $ret *= 1024;
               case 'G':
                   $ret *= 1024;
               case 'M':
                   $ret *= 1024;
               case 'K':
                   $ret *= 1024;
           }
           return $ret;
       }
   }
?>