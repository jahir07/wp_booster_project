/*
 td_video_playlist.js
 v1.1
 */

"use strict";
// jQuery(window).load(function() {//
jQuery().ready(function() {

    //click on a youtube movie
    jQuery('.td_click_video_youtube').click(function(){

        //this flag is check to see if to start the movie
        td_youtube_player.td_playlist_video_autoplay_youtube = 1;

        //add pause to playlist control
        td_playlist_general_functions.td_playlist_add_play_control('.td_youtube_control');

        //create  and play the clicked video
        var td_youtube_video = jQuery(this).attr("id").substring(3);
        if(td_youtube_video != '') {
            td_youtube_player.playVideo(td_youtube_video);
        }
    });



    //click on youtube play control
    jQuery('.td_youtube_control').click(function(){

        //click to play
        if(jQuery(this).hasClass('td-sp-video-play')){
            //this is to enable video playing
            td_youtube_player.td_playlist_video_autoplay_youtube = 1;

            //play the video
            td_youtube_player.td_playlist_youtube_play_video();

        } else {

            //put pause to the player
            td_youtube_player.td_playlist_youtube_pause_video();
        }
    });



    //check for youtube wrapper and add api code to create the player
    if(jQuery('.td_wrapper_playlist_player_youtube').length > 0) {

        if(jQuery('.td_wrapper_playlist_player_youtube').data("autoplay") == "1") {
            td_youtube_player.td_playlist_video_autoplay_youtube = 1;
        }

        var first_video = jQuery('.td_wrapper_playlist_player_youtube').data('first-video');

        if(first_video != '') {
            td_youtube_player.td_playlist_id_youtube_video_running = first_video;

            td_youtube_player.playVideo(first_video);
        }
    }



    //check autoplay vimeo
    if(jQuery('.td_wrapper_playlist_player_vimeo').data("autoplay") == "1") {
        td_vimeo_playlist_obj.td_playlist_video_autoplay_vimeo = 1;
    }

    //click on a vimeo
    jQuery('.td_click_video_vimeo').click(function(){

        //this flag is check to see if to start the movie
        td_vimeo_playlist_obj.td_playlist_video_autoplay_vimeo = 1;

        //add pause to playlist control
        td_playlist_general_functions.td_playlist_add_play_control('.td_vimeo_control');

        //create  and play the clicked video
        td_vimeo_playlist_obj.create_player(jQuery(this).attr("id").substring(3));
    });





    //check for vimeo wrapper and add api code to create the player
    if(jQuery('.td_wrapper_playlist_player_vimeo').length > 0) {

        //add play to playlist control
        td_playlist_general_functions.td_playlist_add_play_control('.td_vimeo_control');

        //create the iframe with the video
        td_vimeo_playlist_obj.create_player(jQuery('.td_wrapper_playlist_player_vimeo').data("first-video"));
    }




    //click on youtube play control
    jQuery('.td_vimeo_control').click(function(){

        //click to play
        if(jQuery(this).hasClass('td-sp-video-play')){
            //this is to enable video playing
            td_vimeo_playlist_obj.td_playlist_video_autoplay_vimeo = 1;

            //play the video
            td_vimeo_playlist_obj.td_playlisty_player_vimeo.api("play");

        } else {

            //put pause to the player
            td_vimeo_playlist_obj.td_playlisty_player_vimeo.api("pause");
        }
    });

});


var td_youtube_player = {
    td_yt_player: '',

    td_player_container: 'player_youtube',

    td_playlist_video_autoplay_youtube: 0,

    td_playlist_id_youtube_video_running: '',


    playVideo: function(videoId) {
        if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
            window.onYouTubePlayerAPIReady = function() {
                td_youtube_player.loadPlayer(td_youtube_player.td_player_container, videoId);
            };
            jQuery.getScript('//www.youtube.com/player_api');
        } else {
            td_youtube_player.loadPlayer(td_youtube_player.td_player_container, videoId);
        }
    },


    loadPlayer: function(container, videoId) {
        //container is here in case we need to add multiple players on page
        td_youtube_player.td_playlist_id_youtube_video_running = videoId;

        var current_video_name = td_youtube_list_ids['td_' + td_youtube_player.td_playlist_id_youtube_video_running]['title'];
        var current_video_time = td_youtube_list_ids['td_' + td_youtube_player.td_playlist_id_youtube_video_running]['time'];

        //remove focus from all videos from playlist
        td_playlist_general_functions.td_video_playlist_remove_focused('.td_click_video_youtube');

        //add focus class on current playing video
        jQuery('#td_' + videoId).addClass('td_video_currently_playing');

        //ading the current video playing title and time to the control area
        jQuery('#td_current_video_play_title_youtube').html(current_video_name);
        jQuery('#td_current_video_play_time_youtube').html(current_video_time);

        td_youtube_player.td_yt_player = '';
        jQuery(".td_wrapper_playlist_player_youtube").html("<div id="+ td_youtube_player.td_player_container +"></div>");

        td_youtube_player.td_yt_player = new YT.Player(container, {//window.myPlayer = new YT.Player(container, {
            playerVars: {
                //modestbranding: 1,
                //rel: 0,
                //showinfo: 0,
                autoplay: td_youtube_player.td_playlist_video_autoplay_youtube
            },
            height: '100%',
            width: '100%',
            videoId: videoId,
            events: {
                'onReady': td_youtube_player.onPlayerReady,
                'onStateChange': td_youtube_player.onPlayerStateChange
            }
        });
    },


    onPlayerStateChange: function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {

            //add pause to playlist control
            td_playlist_general_functions.td_playlist_add_pause_control('.td_youtube_control');

        } else if (event.data == YT.PlayerState.ENDED) {
            //video_events_js.on_stop('youtube');

            //add play to playlist control
            td_playlist_general_functions.td_playlist_add_play_control('.td_youtube_control');

            //if a video has ended then make auto play = 1; This is the case when the user set autoplay = 0 but start watching videos
            td_youtube_player.td_playlist_video_autoplay_youtube = 1;

            //get the next video
            var next_video_id = td_playlist_general_functions.td_playlist_choose_next_video([td_youtube_list_ids, td_youtube_player.td_playlist_id_youtube_video_running]);
            if(next_video_id != '') {
                td_youtube_player.playVideo(next_video_id);
            }

        } else if (YT.PlayerState.PAUSED) {
            //add play to playlist control
            td_playlist_general_functions.td_playlist_add_play_control('.td_youtube_control');
        }
    },

    td_playlist_youtube_stopVideo: function td_playlist_youtube_stopVideo() {
        td_youtube_player.td_yt_player.stopVideo();
    },

    td_playlist_youtube_play_video: function td_playlist_youtube_play_video() {
        if(td_detect.is_mobile_device) {
            //alert('mobile');
        } else {
            td_youtube_player.td_yt_player.playVideo();
        }
    },

    td_playlist_youtube_pause_video: function td_playlist_youtube_pause_video() {
        td_youtube_player.td_yt_player.pauseVideo();
    }
};





//VIMEO
var td_vimeo_playlist_obj = {

    current_video_playing : '',

    td_playlisty_player_vimeo: '',//a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control

    td_playlist_video_autoplay_vimeo: '',//autoplay

    create_player: function (video_id){
        if(video_id != '') {

            var vimeo_iframe_autoplay = '';

            this.current_video_playing = video_id;

            //remove focus class
            td_playlist_general_functions.td_video_playlist_remove_focused('.td_click_video_vimeo');

            //add focus clas on play movie
            jQuery('#td_' + video_id).addClass('td_video_currently_playing');

            //put movie data to control box
            this.put_movie_data_to_control_box(video_id);

            //check autoplay
            if(this.td_playlist_video_autoplay_vimeo != 0) {
                vimeo_iframe_autoplay = '&autoplay=1';
            }


            jQuery('.td_wrapper_playlist_player_vimeo').html('');
            jQuery('.td_wrapper_playlist_player_vimeo').html('<iframe id="player_vimeo_1" src="//player.vimeo.com/video/' + video_id + '?api=1&player_id=player_vimeo_1' + vimeo_iframe_autoplay + '"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');//width="100%" height="100%"

            this.create_vimeo_object_player(jQuery);
        }

    },

    put_movie_data_to_control_box: function (video_id){
        jQuery('#td_current_video_play_title_vimeo').html(td_vimeo_list_ids['td_' + video_id]['title']);
        jQuery('#td_current_video_play_time_vimeo').html(td_vimeo_list_ids['td_' + video_id]['time']);
    },

    create_vimeo_object_player : function ($) {
        var iframe = '';
        var player = '';

        iframe = $('#player_vimeo_1')[0];
        player = $f(iframe);

        //a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control
        this.td_playlisty_player_vimeo = player;

        // When the player is ready, add listeners for pause, finish, and playProgress
        player.addEvent('ready', function() {
            //status.text('ready');

            player.addEvent('play', td_vimeo_playlist_obj.onPlay);
            player.addEvent('pause', td_vimeo_playlist_obj.onPause);
            player.addEvent('finish', td_vimeo_playlist_obj.onFinish);
            player.addEvent('playProgress', td_vimeo_playlist_obj.onPlayProgress);
        });
    },

    onPlay : function onPlay(id) {
        td_playlist_general_functions.td_playlist_add_pause_control('.td_vimeo_control');

        td_vimeo_playlist_obj.td_playlist_video_autoplay_vimeo = 1;
    },

    onPause : function onPause(id) {
        td_playlist_general_functions.td_playlist_add_play_control('.td_vimeo_control');
    },

    onFinish : function onFinish(id) {
        //status.text('finished');

        //add play to playlist control
        td_playlist_general_functions.td_playlist_add_play_control('.td_vimeo_control');

        //if a video has ended then make auto play = 1; This is the case when the user set autoplay = 0 but start watching videos
        td_vimeo_playlist_obj.td_playlist_video_autoplay_vimeo = 1;

        if(td_detect.is_mobile_device && td_detect.is_android) {
            //alert('is android');
        } else {

            //get the next video
            var next_video_id = td_playlist_general_functions.td_playlist_choose_next_video([td_vimeo_list_ids, td_vimeo_playlist_obj.current_video_playing]);
            if(next_video_id != '') {
                td_vimeo_playlist_obj.create_player(next_video_id);
            }
        }
    },

    onPlayProgress : function onPlayProgress(data, id) {
        //status.text(data.seconds + 's played');
    }
};


//this object holds some functions used by both the youtube and vimeo
var td_playlist_general_functions = {
    td_video_playlist_remove_focused: function td_video_playlist_remove_focused(obj_class) {
        //remove focus class
        jQuery( obj_class).each(function(){
            jQuery(this).removeClass('td_video_currently_playing');
        });
    },


    /*
     parram_array = array [
     video_list,
     current_video_id_playing
     ]
     */
    td_playlist_choose_next_video: function td_playlist_choose_next_video(parram_array){
        //alert('get next');

        var video_list = parram_array[0];
        var current_video_id_playing = 'td_' + parram_array[1];

        //get next video id
        var next_video_id = '';
        var found_current = '';
        for(var video in video_list){
            if(found_current == 'found') {
                next_video_id = video;
                found_current = '';
                break;//found , now exit
            }
            if(video == current_video_id_playing) {
                found_current = 'found';
            }
        }

        //play the next video
        if(next_video_id != '') {

            //remove 'td_' from the beginning of the string if necessary
            if(next_video_id.substring(0, 3) == 'td_') {
                next_video_id = next_video_id.substring(3);
            }

            return next_video_id;
        }

        return '';
    },



    //add pause button playlist control
    td_playlist_add_pause_control: function td_playlist_add_pause_control(wrapper_class){
        jQuery(wrapper_class).removeClass('td-sp-video-play').addClass('td-sp-video-pause');
    },

    //add play button playlist control
    td_playlist_add_play_control: function td_playlist_add_play_control(wrapper_class){
        jQuery(wrapper_class).removeClass('td-sp-video-pause').addClass('td-sp-video-play');
    }
};