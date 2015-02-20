<?php

class td_social_icons {
    static $td_social_icons_array = array(
        'behance' => 'Behance',
        'blogger' => 'Blogger',
        'delicious' => 'Delicious',
        'deviantart' => 'Deviantart',
        'digg' => 'Digg',
        'dribbble' => 'Dribbble',
        'evernote' => 'Evernote',
        'facebook' => 'Facebook',
        'flickr' => 'Flickr',
        'forrst' => 'Forrst',
        'googleplus' => 'Google Plus',
        'grooveshark' => 'Grooveshark',
        'html5' => 'Html5',
        'instagram' => 'Instagram',
        'lastfm' => 'Lastfm',
        'linkedin' => 'Linkedin',
        'mail-1' => 'Mail',
        'myspace' => 'Myspace',
        'path' => 'Path',
        'paypal' => 'Paypal',
        'picasa' => 'Picasa',
        'pinterest' => 'Pinterest',
        'posterous' => 'Posterous',
        'reddit' => 'Reddit',
        'rss' => 'Rss',
        'share' => 'Share',
        'skype' => 'Skype',
        'soundcloud' => 'Soundcloud',
        'spotify' => 'Spotify',
        'stackoverflow' => 'Stackoverflow',
        'steam' => 'Steam',
        'stumbleupon' => 'StumbleUpon',
        'tumblr' => 'Tumblr',
        'twitter' => 'Twitter',
        'vimeo' => 'Vimeo',
        'vk' => 'VKontakte',
        'windows' => 'Windows',
        'wordpress' => 'Woordpress',
        'yahoo' => 'Yahoo',
        'youtube' => 'Youtube'
    );


    static function get_service_name($serviceId) {
        return self::$td_social_icons_array['$serviceId'];
    }


    static function get_icon($url, $icon_id, $open_in_new_window = false) {
        if ($open_in_new_window !== false) {
            $td_a_target = ' target="_blank"';
        } else {
            $td_a_target = '';
        }
        return '<span class="td-social-icon-wrap"><a' . $td_a_target . ' href="' . $url . '" title="' . self::$td_social_icons_array[$icon_id] . '"><i class="td-icon-font td-icon-' . $icon_id . '"></i></a></span>';
    }

}
