/*
 td_video_playlist.js
 v1.1
 */


/* global jQuery:{} */
/* global YT:{} */
/* global tdDetect:{} */
/* global $f:{} */


var tdYoutubePlayer = {};
var tdVimeoPlaylistObj = {};
var tdPlaylistGeneralFunctions = {};


// ??? @todo What is td_youtube_list_ids ???
// ??? @todo What is td_vimeo_list_ids ???

// @todo this ready hook function must be moved from here
// jQuery(window).load(function() {//
jQuery().ready(function() {

    'use strict';

    //click on a youtube movie
    jQuery( '.td_click_video_youtube' ).click(function(){

        //this flag is check to see if to start the movie
        tdYoutubePlayer.tdPlaylistVideoAutoplayYoutube = 1;

        //add pause to playlist control
        tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_youtube_control' );

        //create  and play the clicked video
        var tdYoutubeVideo = jQuery( this ).attr( 'id' ).substring( 3 );
        if ( '' !== tdYoutubeVideo ) {
            tdYoutubePlayer.playVideo( tdYoutubeVideo );
        }
    });



    //click on youtube play control
    jQuery( '.td_youtube_control' ).click(function(){

        //click to play
        if ( jQuery( this ).hasClass( 'td-sp-video-play' ) ){
            //this is to enable video playing
            tdYoutubePlayer.tdPlaylistVideoAutoplayYoutube = 1;

            //play the video
            tdYoutubePlayer.tdPlaylistYoutubePlayVideo();

        } else {

            //put pause to the player
            tdYoutubePlayer.tdPlaylistYoutubePauseVideo();
        }
    });



    //check for youtube wrapper and add api code to create the player
    if ( jQuery( '.td_wrapper_playlist_player_youtube' ).length > 0) {

        if ( '1' === jQuery( '.td_wrapper_playlist_player_youtube').data( 'autoplay' ) ) {
            tdYoutubePlayer.tdPlaylistVideoAutoplayYoutube = 1;
        }

        var firstVideo = jQuery( '.td_wrapper_playlist_player_youtube' ).data( 'first-video' );

        if ( '' !== firstVideo ) {
            tdYoutubePlayer.tdPlaylistIdYoutubeVideoRunning = firstVideo;

            tdYoutubePlayer.playVideo( firstVideo );
        }
    }



    //check autoplay vimeo
    if ( '1' === jQuery( '.td_wrapper_playlist_player_vimeo' ).data( 'autoplay' ) ) {
        tdVimeoPlaylistObj.tdPlaylistVideoAutoplayVimeo = 1;
    }

    //click on a vimeo
    jQuery( '.td_click_video_vimeo' ).click(function(){

        //this flag is check to see if to start the movie
        tdVimeoPlaylistObj.tdPlaylistVideoAutoplayVimeo = 1;

        //add pause to playlist control
        tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_vimeo_control' );

        //create  and play the clicked video
        tdVimeoPlaylistObj.createPlayer( jQuery( this ).attr( 'id' ).substring( 3 ) );
    });





    //check for vimeo wrapper and add api code to create the player
    if ( jQuery( '.td_wrapper_playlist_player_vimeo' ).length > 0 ) {

        //add play to playlist control
        tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_vimeo_control' );

        //create the iframe with the video
        tdVimeoPlaylistObj.createPlayer( jQuery( '.td_wrapper_playlist_player_vimeo' ).data('first-video' ) );
    }




    //click on youtube play control
    jQuery( '.td_vimeo_control' ).click(function(){

        //click to play
        if ( jQuery( this ).hasClass( 'td-sp-video-play' ) ) {
            //this is to enable video playing
            tdVimeoPlaylistObj.tdPlaylistVideoAutoplayVimeo = 1;

            //play the video
            tdVimeoPlaylistObj.tdPlaylistPlayerVimeo.api( 'play' );

        } else {

            //put pause to the player
            tdVimeoPlaylistObj.tdPlaylistPlayerVimeo.api( 'pause' );
        }
    });

});


(function() {
    'use strict';

    tdYoutubePlayer = {
        tdYtPlayer: '',

        tdPlayerContainer: 'player_youtube',

        tdPlaylistVideoAutoplayYoutube: 0,

        tdPlaylistIdYoutubeVideoRunning: '',


        playVideo: function( videoId ) {
            if ( 'undefined' === typeof( YT ) || 'undefined' === typeof( YT.Player ) ) {
                window.onYouTubePlayerAPIReady = function() {
                    tdYoutubePlayer.loadPlayer( tdYoutubePlayer.tdPlayerContainer, videoId );
                };
                jQuery.getScript( 'https://www.youtube.com/player_api' );
            } else {
                tdYoutubePlayer.loadPlayer( tdYoutubePlayer.tdPlayerContainer, videoId );
            }
        },


        loadPlayer: function( container, videoId ) {
            //container is here in case we need to add multiple players on page
            tdYoutubePlayer.tdPlaylistIdYoutubeVideoRunning = videoId;

            var current_video_name = td_youtube_list_ids['td_' + tdYoutubePlayer.tdPlaylistIdYoutubeVideoRunning]['title'];
            var current_video_time = td_youtube_list_ids['td_' + tdYoutubePlayer.tdPlaylistIdYoutubeVideoRunning]['time'];

            //remove focus from all videos from playlist
            tdPlaylistGeneralFunctions.tdVideoPlaylistRemoveFocused( '.td_click_video_youtube' );

            //add focus class on current playing video
            jQuery( '#td_' + videoId ).addClass( 'td_video_currently_playing' );

            //ading the current video playing title and time to the control area
            jQuery( '#td_current_video_play_title_youtube' ).html( current_video_name );
            jQuery( '#td_current_video_play_time_youtube' ).html( current_video_time );

            tdYoutubePlayer.tdYtPlayer = '';
            jQuery( '.td_wrapper_playlist_player_youtube' ).html( '<div id=' + tdYoutubePlayer.tdPlayerContainer + '></div>' );

            tdYoutubePlayer.tdYtPlayer = new YT.Player( container, {//window.myPlayer = new YT.Player(container, {
                playerVars: {
                    //modestbranding: 1,
                    //rel: 0,
                    //showinfo: 0,
                    autoplay: tdYoutubePlayer.tdPlaylistVideoAutoplayYoutube
                },
                height: '100%',
                width: '100%',
                videoId: videoId,
                events: {
                    'onReady': tdYoutubePlayer.onPlayerReady,
                    'onStateChange': tdYoutubePlayer.onPlayerStateChange
                }
            });
        },


        onPlayerStateChange: function( event ) {
            if ( event.data === YT.PlayerState.PLAYING ) {

                //add pause to playlist control
                tdPlaylistGeneralFunctions.tdPlaylistAddPauseControl( '.td_youtube_control' );

            } else if ( event.data === YT.PlayerState.ENDED ) {
                //video_events_js.on_stop('youtube');

                //add play to playlist control
                tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_youtube_control' );

                //if a video has ended then make auto play = 1; This is the case when the user set autoplay = 0 but start watching videos
                tdYoutubePlayer.tdPlaylistVideoAutoplayYoutube = 1;

                //get the next video
                var nextVideoId = tdPlaylistGeneralFunctions.tdPlaylistChooseNextVideo( [ td_youtube_list_ids, tdYoutubePlayer.tdPlaylistIdYoutubeVideoRunning ] );
                if ( '' !== nextVideoId ) {
                    tdYoutubePlayer.playVideo( nextVideoId );
                }

            } else if ( YT.PlayerState.PAUSED ) {
                //add play to playlist control
                tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_youtube_control' );
            }
        },

        tdPlaylistYoutubeStopVideo: function() {
            tdYoutubePlayer.tdYtPlayer.stopVideo();
        },

        tdPlaylistYoutubePlayVideo: function() {
            if ( ! tdDetect.isMobileDevice ) {
                tdYoutubePlayer.tdYtPlayer.playVideo();
            }
        },

        tdPlaylistYoutubePauseVideo: function() {
            tdYoutubePlayer.tdYtPlayer.pauseVideo();
        }
    };



    //VIMEO
    tdVimeoPlaylistObj = {

        currentVideoPlaying : '',

        tdPlaylistPlayerVimeo: '',//a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control

        tdPlaylistVideoAutoplayVimeo: '',//autoplay

        createPlayer: function ( videoId ) {
            if ( '' !== videoId ) {

                var vimeo_iframe_autoplay = '';

                this.currentVideoPlaying = videoId;

                //remove focus class
                tdPlaylistGeneralFunctions.tdVideoPlaylistRemoveFocused( '.td_click_video_vimeo' );

                //add focus clas on play movie
                jQuery( '#td_' + videoId ).addClass( 'td_video_currently_playing' );

                //put movie data to control box
                this.putMovieDataToControlBox( videoId );

                //check autoplay
                if ( 0 !== this.tdPlaylistVideoAutoplayVimeo ) {
                    vimeo_iframe_autoplay = '&autoplay=1';
                }


                jQuery( '.td_wrapper_playlist_player_vimeo' ).html( '' );
                jQuery( '.td_wrapper_playlist_player_vimeo' ).html( '<iframe id="player_vimeo_1" src="https://player.vimeo.com/video/' + videoId + '?api=1&player_id=player_vimeo_1' + vimeo_iframe_autoplay + '"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' );//width="100%" height="100%"

                this.createVimeoObjectPlayer( jQuery );
            }

        },

        putMovieDataToControlBox: function( videoId ){
            jQuery( '#td_current_video_play_title_vimeo' ).html( td_vimeo_list_ids['td_' + videoId]['title'] );
            jQuery( '#td_current_video_play_time_vimeo' ).html( td_vimeo_list_ids['td_' + videoId]['time'] );
        },

        createVimeoObjectPlayer : function( $ ) {
            var iframe = '';
            var player = '';

            iframe = $( '#player_vimeo_1' )[0];
            player = $f( iframe );

            //a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control
            this.tdPlaylistPlayerVimeo = player;

            // When the player is ready, add listeners for pause, finish, and playProgress
            player.addEvent( 'ready', function() {
                //status.text('ready');

                player.addEvent( 'play', tdVimeoPlaylistObj.onPlay );
                player.addEvent( 'pause', tdVimeoPlaylistObj.onPause );
                player.addEvent( 'finish', tdVimeoPlaylistObj.onFinish );
                player.addEvent( 'playProgress', tdVimeoPlaylistObj.onPlayProgress );
            });
        },

        onPlay : function( id ) {
            tdPlaylistGeneralFunctions.tdPlaylistAddPauseControl( '.td_vimeo_control' );

            tdVimeoPlaylistObj.tdPlaylistVideoAutoplayVimeo = 1;
        },

        onPause : function( id ) {
            tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_vimeo_control' );
        },

        onFinish : function( id ) {
            //status.text('finished');

            //add play to playlist control
            tdPlaylistGeneralFunctions.tdPlaylistAddPlayControl( '.td_vimeo_control' );

            //if a video has ended then make auto play = 1; This is the case when the user set autoplay = 0 but start watching videos
            tdVimeoPlaylistObj.tdPlaylistVideoAutoplayVimeo = 1;

            if ( ! tdDetect.isMobileDevice || ! tdDetect.isAndroid ) {

                //get the next video
                var nextVideoId = tdPlaylistGeneralFunctions.tdPlaylistChooseNextVideo( [td_vimeo_list_ids, tdVimeoPlaylistObj.currentVideoPlaying] );
                if ( '' !== nextVideoId ) {
                    tdVimeoPlaylistObj.createPlayer( nextVideoId );
                }
            }
        },

        onPlayProgress : function onPlayProgress( data, id ) {
            //status.text(data.seconds + 's played');
        }
    };




    //this object holds some functions used by both the youtube and vimeo
    tdPlaylistGeneralFunctions = {
        tdVideoPlaylistRemoveFocused: function( objClass ) {
            //remove focus class
            jQuery( objClass ).each(function() {
                jQuery( this ).removeClass( 'td_video_currently_playing' );
            });
        },


        /*
         parram_array = array [
         video_list,
         current_video_id_playing
         ]
         */
        tdPlaylistChooseNextVideo: function( parramArray ){
            //alert('get next');

            var videoList = parramArray[0];
            var currentVideoIdPlaying = 'td_' + parramArray[1];

            //get next video id
            var nextVideoId = '';
            var foundCurrent = '';
            for ( var video in videoList ) {
                if ( videoList.hasOwnProperty( video ) ) {
                    if ( 'found' === foundCurrent ) {
                        nextVideoId = video;
                        foundCurrent = '';
                        break;//found , now exit
                    }
                    if ( video === currentVideoIdPlaying ) {
                        foundCurrent = 'found';
                    }
                }
            }

            //play the next video
            if ( '' !== nextVideoId ) {

                //remove 'td_' from the beginning of the string if necessary
                if ( 'td_' === nextVideoId.substring( 0, 3 ) ) {
                    nextVideoId = nextVideoId.substring( 3 );
                }

                return nextVideoId;
            }

            return '';
        },



        //add pause button playlist control
        tdPlaylistAddPauseControl: function( wrapperClass ){
            jQuery( wrapperClass ).removeClass( 'td-sp-video-play' ).addClass( 'td-sp-video-pause' );
        },

        //add play button playlist control
        tdPlaylistAddPlayControl: function( wrapperClass ){
            jQuery( wrapperClass ).removeClass( 'td-sp-video-pause' ).addClass( 'td-sp-video-play' );
        }
    };
})();










