<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 30.12.2014
 * Time: 13:26
 */

class td_video_playlist_render {

    static function render_generic($atts, $list_type){

        $block_uid = td_global::td_generate_unique_id(); //update unique id on each render
        $buffy = ''; //output buffer

        $buffy .= '<div class="td_block_wrap td_block_video_playlist">';

        $buffy .= '<div id=' . $block_uid . ' class="td_block_inner">';

        //inner content of the block
        $buffy .= self::inner($list_type);
        $buffy .= '</div>';

        $buffy .= '</div> <!-- ./block_video_playlist -->';
        return $buffy;
    }


    static function inner($list_type) {
        global $post;

        //get the playlists in post meta if any
        $playlist_video_db = '';
        $playlist_video_db = get_post_meta($post->ID, td_video_playlist_support::$td_playlist_video_key, true);

        $buffy = '';

        $td_block_layout = new td_block_layout();


        if(is_single()) {
            //get the column number on single post page
            if(td_global::$cur_single_template_sidebar_pos == 'no_sidebar'){
                $td_column_number = 3;
            } else {
                $td_column_number = 2;
            }
        } else {
            //page
            $td_column_number = td_util::vc_get_column_number(); // get the column width of the block
        }

        $td_current_column = 1; //the current column

        $vimeo_js_player_placeholder = '';//use only for vimeo to hold the js for the player
        if($list_type == 'youtube') {
            //array key for youtube in the pos meta db array
            $list_name = 'youtube_ids';
        } else {
            //array key for vimeo in the pos meta db array
            $list_name = 'vimeo_ids';
        }

        if(!empty($playlist_video_db) and !empty($playlist_video_db[$list_name])){

            $first_video_id = '';
            $contor_first_video = 0;
            $js_object = '';
            $click_video_container = '';

            foreach($playlist_video_db[$list_name] as $video_id => $video_data) {

                //take the id of first video
                if($contor_first_video == 0) {$first_video_id = $video_id;}
                $contor_first_video++;

                //add comma (,) for next value
                if(!empty($js_object)) {$js_object .= ',';}
                $js_object .= "\n'td_".$video_id."':{";

                $video_data_propeties = '';

                //get thumb
                $playlist_structure_thumb = '';
                if(!empty($video_data['thumb'])){
                    $playlist_structure_thumb = '<div class="td_video_thumb"><img src="' . $video_data['thumb'] . '" alt="" /></div>';
                    //$video_data_propeties .= 'thumb:"' . $video_data['thumb'] . '",';
                }

                //get title
                $playlist_structure_title = '<div class="td_video_title_and_time">';
                if(!empty($video_data['title'])){
                    $playlist_structure_title .= '<div class="td_video_title">' . $video_data['title'] . '</div>';
                    $video_data_propeties .= 'title:"' . $video_data['title'] . '",';
                }

                //get time
                $playlist_structure_time = '';
                if(!empty($video_data['time'])){

                    $get_video_time = '';
                    if(substr($video_data['time'], 0, 3) == '00:') {
                        $get_video_time = substr($video_data['time'], 3);
                    } else {
                        $get_video_time = $video_data['time'];
                    }

                    $playlist_structure_title .= '<div class="td_video_time">' . $get_video_time . '</div>';
                    $video_data_propeties .= 'time:"' . $get_video_time . '"';
                }
                $playlist_structure_title .= '</div>';

                //creating click-able playlist video
                $click_video_container .= '<a id="td_' . $video_id . '" class="td_click_video td_click_video_' . $list_type . '"> ' . $playlist_structure_thumb . $playlist_structure_title . '</a>';

                $js_object .= $video_data_propeties . "}";
            }



            if(!empty($js_object)) {
                $js_object = 'var td_' . $list_type . '_list_ids = {' .$js_object. '}';
            }

            //creating column number classes
            $column_number_class = 'td_video_playlist_column_2';

            if($td_column_number == 1) {
                $column_number_class = 'td_video_playlist_column_1';
            }

            if($td_column_number == 3) {
                $column_number_class = 'td_video_playlist_column_3';
            }

            //creating title wrapper if any
            $td_video_title = '';
            if(!empty($playlist_video_db[$list_type . '_title'])) {
                $td_video_title = '<div class="td_video_playlist_title"><div class="td_video_title_text">' . $playlist_video_db[$list_type . '_title'] . '</div></div>';
            }


            //autoplay
            $td_playlist_autoplay = 0;
            $td_class_autoplay_control = 'td-sp-video-play';
            if(!empty($playlist_video_db[$list_type . '_auto_play']) and intval($playlist_video_db[$list_type . '_auto_play']) > 0) {
                $td_playlist_autoplay = 1;

                //$td_class_autoplay_control = 'td-sp-video-pause';
            }

            //check how many video ids we have; if there are more then 5 then add a class that is used on chrome to add the playlist scroll bar
            $td_class_number_video_ids = '';
            $td_playlist_video_count = count($playlist_video_db[$list_name]);

            if(intval($td_playlist_video_count) > 4) {
                $td_class_number_video_ids = 'td_add_scrollbar_to_playlist_for_mobile';
            }

            if(intval($td_playlist_video_count) > 5) {
                $td_class_number_video_ids = 'td_add_scrollbar_to_playlist';
            }

            //$js_object is there so we can take the string and parsit as json to create an object in jQuery
            return '<div class="' . $column_number_class . '">' . $td_video_title . '<div class="td_wrapper_video_playlist"><div class="td_wrapper_player td_wrapper_playlist_player_' . $list_type . '" data-first-video="' . esc_attr($first_video_id) . '" data-autoplay="' . $td_playlist_autoplay . '">
                            <div id="player_' . $list_type . '"></div>
                       </div><div class="td_container_video_playlist " >
                                                <div class="td_video_controls_playlist_wrapper"><div class="td_video_stop_play_control"><a class="' . $td_class_autoplay_control . ' td-sp td_' . $list_type . '_control"></a></div><div id="td_current_video_play_title_' . $list_type . '" class="td_video_title_playing"></div><div id="td_current_video_play_time_' . $list_type . '" class="td_video_time_playing"></div></div>
                                                <div id="td_' . $list_type . '_playlist_video" class="td_playlist_clickable ' . $td_class_number_video_ids . '">' . $click_video_container . '</div>
                       </div>
                    </div>
                    </div>
                    <script>' . $js_object . '</script>';
        }


        //current column
        if ($td_current_column == $td_column_number) {
            $td_current_column = 1;
        } else {
            $td_current_column++;
        }

        $buffy .= $td_block_layout->close_all_tags();
        return $buffy;
    }
}//end td_playlist_render