<?php

class td_instagram
{

    private static $caching_time = 10800;  // 3 hours

    public static function render_generic($atts, $block_id) {
        // prepare the data
        $instragram_data = array(
            'user' => '',
        );

        // get instagram data
        $instagram_data_status = self::get_instagram_data($atts, $instragram_data);

        // check if we have an error and return that
        if ($instagram_data_status != 'instagram_fail_cache' and $instagram_data_status != 'instagram_cache_updated' and $instagram_data_status != 'instagram_cache') {
            return $instagram_data_status;
        }

        // render the HTML
        $buffy = '<!-- td instagram source: ' . $instagram_data_status . ' -->';

        // renders the block template
        $buffy .= self::render_block_template($atts, $instragram_data);

        return $buffy;
    }

    private static function render_block_template($atts, $instagram_data){

        // stop render when no data is received
        if ($instagram_data['user'] == ''){
            return self::error('Render failed - no data is received: ' . $atts['instagram_id']);
        }

//        // debugging
//        echo '<pre>';
//        print_r($instagram_data);
//        echo '</pre>';

        ob_start();

        // number of images - by default display 5 images
        $images_number = 5;
        if ($atts['instagram_number_of_images'] != '') {
            $images_number = $atts['instagram_number_of_images'];
        }
        ?>
        <!-- user name and profile image -->
        <div class="td-instagram-header">
        <div class="td-instagram-user"><?php echo $instagram_data['user']['full_name'] ?></div>
        <div class="td-instagram-profile-image"><img src="<?php echo $instagram_data['user']['profile_pic_url'] ?>" /></div>
        </div>

        <!-- user shared images -->
        <?php
        $image_count = 0;
        foreach ($instagram_data['user']['media']['nodes'] as $image) {
            ?>
            <div class="td-instagram-element">

            <!-- image -->
            <a href="https://www.instagram.com/p/<?php echo $image['code'] ?>" >
            <img class="td-instagram-image" src="<?php echo $image['thumbnail_src'] ?>" />
            </a>

            <!-- video icon -->
            <?php
            if ($image->is_video == 1) {
                ?>
                <div class="td-instagram-video"><img src="http://www.iconsdb.com/icons/preview/gray/video-play-3-xxl.png" /></div>
            <?php
            }
            ?>

            </div>

            <!-- number of images to display -->
            <?php
            $image_count++;
            if ($image_count == $images_number) {
                break;
            }
        }

        return ob_get_clean();
    }

    /**
     * @param $atts
     * @param $instragram_data - the precomputed instagram data
     * @return bool|string
     *  - bool:true - we have the $instragram_data (from cache or from a real request)
     *  - string - error message
     */
    private static function get_instagram_data($atts, &$instragram_data) {

        $cache_key = 'td_instragram_' . strtolower($atts['instagram_id']);
        if (td_remote_cache::is_expired(__CLASS__, $cache_key) === true) {
            // cache is expired - do a request
            $instagram_get_data = self::instagram_get_data($atts, $instragram_data);
            // check the api call response
            if ($instagram_get_data !== true) {
                // we have an error in the data retrieval process
                $instragram_data = td_remote_cache::get(__CLASS__, $cache_key);
                if ($instragram_data === false) {    // miss and io error... shit / die
                    return self::error('Instagram data error: ' . $instagram_get_data);
                }

                td_remote_cache::extend(__CLASS__, $cache_key, self::$caching_time);
                return 'instagram_fail_cache';
            }

            td_remote_cache::set(__CLASS__, $cache_key, $instragram_data, self::$caching_time); //we have a reply and we set it
            return 'instagram_cache_updated';

        } else {
            // cache is valid
            $instragram_data = td_remote_cache::get(__CLASS__, $cache_key);
            return 'instagram_cache';
        }
    }

    private static function instagram_get_data($atts, &$instagram_data){

        $instagram_html_data = self::parse_instagram_html($atts['instagram_id']);

        if ($instagram_html_data === false) {
            td_log::log(__FILE__, __FUNCTION__, 'Instagram html data cannot be retrieved', $atts['instagram_id']);
            return 'Instagram html data cannot be retrieved';
        }

        // try to decode the json
        $instagram_json = json_decode($instagram_html_data, true);
        if ($instagram_json === null and json_last_error() !== JSON_ERROR_NONE) {
            td_log::log(__FILE__, __FUNCTION__, 'Error decoding the instagram json', $instagram_json);
            return 'Error decoding the instagram json';
        }

        // current instagram data
        if (isset($instagram_json['entry_data']['ProfilePage'][0]['user'])) {
            $instagram_data['user'] = $instagram_json['entry_data']['ProfilePage'][0]['user'];
        }

        return true;
    }


    /**
     * @param $instagram_id
     * @return bool|string
     */
    private static function parse_instagram_html($instagram_id) {
        $data = self::get_html_data('https://www.instagram.com/' . $instagram_id);

        if ($data === false) {
            td_log::log(__FILE__, __FUNCTION__, 'The instagram_get_data method FAILED');
            return false;
        } else {
            $pattern = '/window\._sharedData = (.*);<\/script>/';
            preg_match($pattern, $data, $matches);

            if (!empty($matches)) {
                // other checks?
                return $matches[1];
            } else {
                td_log::log(__FILE__, __FUNCTION__, 'No images are available or Cannot find any match on the page content');
                return false;
            }
        }
    }


    /**
     * @param $url
     * @return bool|string
     */
    private static function get_html_data($url){

        // get remote HTML file
        $response = wp_remote_get($url, array(
            'timeout' => 10,
            'sslverify' => false,
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0'
        ));
        // check for wordpress error
        if (is_wp_error($response)) {
            td_log::log(__FILE__, __FUNCTION__, 'got wp_error, get_error_message: ' . $response->get_error_message());
            return false;
        }
        // do not kill at response code != 200, it may still work
        if (wp_remote_retrieve_response_code($response) != 200) {
            td_log::log(__FILE__, __FUNCTION__, 'Response code != 200: ' . wp_remote_retrieve_response_code($response));
        }

        // parse remote HTML file
        $td_request_result = wp_remote_retrieve_body($response);
        // check for wordpress error
        if (is_wp_error($td_request_result)) {
            td_log::log(__FILE__, __FUNCTION__, 'got wp_error, get_error_message: ' . $td_request_result->get_error_message());
            return false;
        }
        // empty result
        if ($td_request_result == '') {
            td_log::log(__FILE__, __FUNCTION__, 'Empty response via wp_remote_retrieve_body, Quitting.');
            return false;
        }

        return $td_request_result;
    }


    /**
     * Show an error if the user is logged in. It does not check for admin
     * @param $msg
     * @return string
     */
    private static function error($msg) {
        if (is_user_logged_in()) {
            return $msg;
        }
        return '';
    }
}