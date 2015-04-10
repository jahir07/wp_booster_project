<?php

/*  ----------------------------------------------------------------------------
    tagDiv video playlist support
    - creates the array to be saved in post meta
 */

class td_video_playlist_support {

    static $td_playlist_video_key = 'td_playlist_video';


    //put the filter to get data when the post is saved
    static function init() {
        if(is_admin()) {
            add_filter('save_post', array( __CLASS__, 'save_playlist_hook'));
        }
    }


    //parse the content and id playlist video shortcode is found then save that shortcode data in post_meta as an array of arrays
    static function save_playlist_hook($post_id){

        $td_playlist_video = array();

        //check for saved playlists in post meta
        $td_playlist_video_db = get_post_meta($post_id, self::$td_playlist_video_key, true);

        if(!empty($_POST['post_content'])) {
            $post_content = $_POST['post_content'];


            //HANDLE YOUTUBE
            $youtube_regular_expresion = '/\[td_block_video_youtube(.*?)\]/i';

            if (preg_match($youtube_regular_expresion, $post_content)) {

                //get youtube playlist's (shortcode)
                preg_match_all($youtube_regular_expresion, $post_content, $matches_youtube);

                //split the array to get the string of id's
                $xplode_matches_for_ids = explode('playlist_yt', $matches_youtube[0][0]);
                $xplode_matches_remove_quotes = explode('\"', $xplode_matches_for_ids[1]);

                //get an array of youtube list; array_filter = remove empty arrays
                $youtube_id_array = array_filter(self::get_ids_from_list($xplode_matches_remove_quotes[1]));

	            if(!empty($youtube_id_array)) {

                    //get the info for the videos
                    $td_playlist_video['youtube_ids'] = self::get_video_info_data(array($youtube_id_array, $td_playlist_video_db, 'youtube_ids'));

                    //save title
                    if(strpos($matches_youtube[0][0], 'playlist_title')) {
                        $title_for_save_youtube = self::get_title($matches_youtube[0][0]);
                        if(!empty($title_for_save_youtube)) {
                            $td_playlist_video['youtube_title'] = $title_for_save_youtube;
                        }
                    }

                    //save autoplay
                    $autoplay_youtube = self::get_autoplay($matches_youtube[0][0]);
                    if($autoplay_youtube > 0) {
                        $td_playlist_video['youtube_auto_play'] = 1;
                    }

                }
            }


            //HANDLE VIMEO
            $vimeo_regular_expresion = '/\[td_block_video_vimeo(.*?)\]/i';

            if (preg_match($vimeo_regular_expresion, $post_content)) {

                //get vimeo playlist's (shortcode)
                preg_match_all($vimeo_regular_expresion, $post_content, $matches_vimeo);

                //split the array to get the string of id's
                $xplode_matches_for_ids = explode('playlist_v', $matches_vimeo[0][0]);
                $xplode_matches_remove_quotes = explode('\"', $xplode_matches_for_ids[1]);

                //get an array of vimeo list; array_filter = remove empty arrays
                $vimeo_id_array = array_filter(self::get_ids_from_list($xplode_matches_remove_quotes[1]));

                if(!empty($vimeo_id_array)) {

                    //get the info for the videos
                    $td_playlist_video['vimeo_ids'] = self::get_video_info_data(array($vimeo_id_array, $td_playlist_video_db, 'vimeo_ids'));

                    //save title
                    if(strpos($matches_vimeo[0][0], 'playlist_title')) {
                        $title_for_save_vimeo = self::get_title($matches_vimeo[0][0]);
                        if(!empty($title_for_save_vimeo)) {
                            $td_playlist_video['vimeo_title'] = $title_for_save_vimeo;
                        }
                    }

                    //save autoplay
                    $autoplay_vimeo = self::get_autoplay($matches_vimeo[0][0]);
                    if($autoplay_vimeo > 0) {
                        $td_playlist_video['vimeo_auto_play'] = 1;
                    }

                }
            }

        }

        //add or edit the video playlist for this post
        if(!empty($td_playlist_video) or !empty($td_playlist_video_db)){

            update_post_meta($post_id, self::$td_playlist_video_key, $td_playlist_video);
        }

        //self::write_output_data($td_playlist_video);

    }


    /*
     * return an array of video id's
     * */
    static function get_ids_from_list ($list_video_ids){

        if(empty($list_video_ids)){
            return;
        }

        $buffy = array();

        //this is needed because we could have more space between each movie id
        $remove_spaces = trim(str_replace(array('&nbsp;', ' '),array(''), htmlentities($list_video_ids, ENT_QUOTES)));

        if(!empty($remove_spaces)) {

            $video_id_explode = explode(',', $remove_spaces);
            $video_id_explode_map = array_map('trim',$video_id_explode);//extra trim just in case

            //make an array of video id's'
            foreach($video_id_explode_map as $video_id) {
                $trim_video_id = trim($video_id);

                //check to prevent duplicates id's
                if (!in_array($trim_video_id, $buffy)) {
                    $buffy[] = $trim_video_id;
                }
            }
        }

        return $buffy;
    }


    /*
     * return an array of video id's
     * */
    static function get_video_info_data ($array_param){

        $list_to_parse = $array_param[0];

        $data_from_db = $array_param[1];

        $video_provider = $array_param[2];

        $buffy = array();

        //get the info data for videos
        //foreach($list_to_parse as $array_id_video) {

        foreach($list_to_parse as $id_video) {

            $id_video = trim($id_video);//possible to have spaces

            if(!empty($data_from_db[$video_provider][$id_video])) {
                $buffy[$id_video] = $data_from_db[$video_provider][$id_video];

            } else {

                //get the info data for video
                switch ($video_provider) {
                    case 'youtube_ids':
                        $response = wp_remote_fopen(td_global::$http_or_https . '://gdata.youtube.com/feeds/api/videos/' . $id_video . '?format=5&alt=json');
                        $obj = json_decode($response, true);
                        $buffy[$id_video]['thumb'] = td_global::$http_or_https . '://img.youtube.com/vi/' . $id_video . '/default.jpg';
                        $buffy[$id_video]['title'] = $obj['entry']['media$group']['media$title']['$t']; //@todo htmlentities should be used when the title is displayed, not here
                        $buffy[$id_video]['time'] = gmdate("H:i:s", intval($obj['entry']['media$group']['yt$duration']['seconds']));

//						$response = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id=' . $id_video . '&part=id,contentDetails,snippet&key=AIzaSyCaCpuS5LaTK6vaGja4d_A_Fx_OqyQqXjM');
//
//	                    $obj = json_decode($response, true);
//	                    $buffy[$id_video]['thumb'] = td_global::$http_or_https . '://img.youtube.com/vi/' . $id_video . '/default.jpg';
//						$duration = $obj['items'][0]['contentDetails']['duration'];
//
//		                preg_match('/(\d+)H/', $duration, $match);
//		                $h = $match[0] ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
//
//		                preg_match('/(\d+)M/', $duration, $match);
//		                $m = $match[0] ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
//
//		                preg_match('/(\d+)S/', $duration, $match);
//		                $s = $match[0] ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
//
//		                $buffy[$id_video]['title'] = $obj['items'][0]['snippet']['title'];
//
//	                    $buffy[$id_video]['time'] = gmdate("H:i:s", intval($h * 3600 + $m * 60  + $s));

                        break;

                    case 'vimeo_ids':
                        $html_returned = unserialize(wp_remote_fopen(td_global::$http_or_https . '://vimeo.com/api/v2/video/' . $id_video . '.php'));

                        $buffy[$id_video]['thumb'] = $html_returned[0]['thumbnail_small'];
                        $buffy[$id_video]['title'] = $html_returned[0]['title'];  //@todo htmlentities should be used when the title is displayed, not here
                        $buffy[$id_video]['time'] = gmdate("H:i:s", intval($html_returned[0]['duration']));
                        break;
                }

            }

        }

        //}


        if(!empty($buffy)) {
            return $buffy;
        } else {
            return;
        }
    }


    //get the title for the playlist video
    static function get_title($data_title){

        if(empty($data_title)) {
            return;
        }

        //explode after 'playlist_title'
        $explode_playlist_title = explode('playlist_title', $data_title);

        //get the title
        preg_match('/\"(.*?)\\"/', $explode_playlist_title[1], $maches_title);

        //if the title is empty there will be only an \ at the end
        if(empty($maches_title[1]) or $maches_title[1] == '\\') {
            return '';
        }

        //check the last chart in the title, could be  \
        if(substr($maches_title[1], -1, 1) == '\\') {
            $maches_title[1] = substr($maches_title[1], 0, -1);
        }

        //remove spaces
        if(!empty($maches_title[1])) {
            return trim(str_replace(array('&nbsp;'),array(''), htmlentities($maches_title[1], ENT_QUOTES, 'UTF-8')));//trim just in case
        } else {
            return '';
        }

    }


    static function get_autoplay($text) {

        if(!empty($text)) {

            $explode_autoplay = explode('auto_play=', $text);

            if(!empty($explode_autoplay)) {
                $explode_text = explode('\"', $explode_autoplay[1]);

                if(intval($explode_text[1]) > 0) {
                    return 1;
                }
            }
        }

        return 0;
    }


    /*
    static function write_output_data($data, $write_type = 'w', $file_name = 'testFile.txt'){
        //this is for output; the page is redirected and no output is given by this function
        $myFile = "d:/" . $file_name;
        $fh = fopen($myFile, $write_type) or die("can't open file");
        $stringData = print_r($data, 1);

        fwrite($fh, $stringData);
        fclose($fh);
    }*/

}

td_video_playlist_support::init();