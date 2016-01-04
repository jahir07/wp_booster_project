/*
 td_video_playlist.js
 v1.1
 */


/* global jQuery:{} */
/* global YT:{} */
/* global tdDetect:{} */
/* global $f:{} */

/* jshint -W069 */
/* jshint -W116 */

var tdYoutubePlayers = {};
var tdVimeoPlayers = {};

// @todo this ready hook function must be moved from here
jQuery().ready(function() {

    'use strict';

    tdYoutubePlayers.init();
    tdVimeoPlayers.init();
});



(function() {

    'use strict';


    // the youtube list players (the init() method should be called before using the list)
    tdYoutubePlayers = {

        // the part name of the player id (they will be ex 'player_youtube_1', 'player_youtube_1', 'player_youtube_2', ...)
        tdPlayerContainer: 'player_youtube',

        // the internal list
        players: [],


        // the initialization of the youtube list players
        init: function() {

            var jqWrapperPlaylistPlayerYoutube = jQuery( '.td_wrapper_playlist_player_youtube' );

            for ( var i = 0; i < jqWrapperPlaylistPlayerYoutube.length; i++ ) {

                var jqPlayerWrapper = jQuery( jqWrapperPlaylistPlayerYoutube[ i ] ),
                    youtubePlayer = tdYoutubePlayers.addPlayer( jqPlayerWrapper),
                    playerId = youtubePlayer.tdPlayerContainer;

                jqPlayerWrapper.parent().find( '.td_youtube_control').data( 'player-id', playerId );

                var videoYoutubeElements = jqPlayerWrapper.parent().find( '.td_click_video_youtube');
                for ( var j = 0; j < videoYoutubeElements.length; j++ ) {
                    jQuery( videoYoutubeElements[ j ] ).data( 'player-id', playerId );

                    if ( j + 1 < videoYoutubeElements.length) {
                        jQuery( videoYoutubeElements[ j ] ).data( 'next-video-id', jQuery(videoYoutubeElements[ j + 1 ] ).data( 'video-id' ) );
                    } else {
                        jQuery( videoYoutubeElements[ j ] ).data( 'next-video-id', jQuery(videoYoutubeElements[0]).data( 'video-id' ) );
                    }
                }


                if ( '1' == jqPlayerWrapper.data( 'autoplay' ) ) {
                    youtubePlayer.autoplay = 1;
                }

                var firstVideo = jqPlayerWrapper.data( 'first-video' );

                if ( '' !== firstVideo ) {
                    youtubePlayer.tdPlaylistIdYoutubeVideoRunning = firstVideo;
                    youtubePlayer.playVideo( firstVideo );
                }
            }

            //click on a youtube movie
            jQuery( '.td_click_video_youtube' ).click(function(){

                var videoId = jQuery( this ).data( 'video-id' ),
                    playerId = jQuery( this ).data( 'player-id' );

                if ( undefined !== playerId && '' !== playerId && undefined !== videoId && '' !== videoId ) {
                    tdYoutubePlayers.operatePlayer( playerId, 'play', videoId );
                }
            });



            //click on youtube play control
            jQuery( '.td_youtube_control' ).click(function(){

                var playerId = jQuery( this ).data( 'player-id' );

                if ( undefined !== playerId && '' !== playerId ) {
                    if ( jQuery( this ).hasClass( 'td-sp-video-play' ) ){
                        tdYoutubePlayers.operatePlayer( playerId, 'play' );
                    } else {
                        tdYoutubePlayers.operatePlayer( playerId, 'pause' );
                    }
                }
            });
        },


        addPlayer: function( jqPlayerWrapper ) {

            var containerId = tdYoutubePlayers.tdPlayerContainer + '_' + tdYoutubePlayers.players.length,
                tdPlayer = tdYoutubePlayers.createPlayer( containerId, jqPlayerWrapper );

            tdYoutubePlayers.players.push( tdPlayer );

            return tdPlayer;
        },

        operatePlayer: function( playerId, option, videoId ) {
            for ( var i = 0; i < tdYoutubePlayers.players.length; i++ ) {
                if (tdYoutubePlayers.players[i].tdPlayerContainer == playerId ) {

                    var youtubePlayer = tdYoutubePlayers.players[ i ];

                    // This status is necessary just for mobile
                    youtubePlayer.playStatus();

                    if ( 'play' === option ) {

                        youtubePlayer.autoplay = 1;

                        if ( undefined === videoId ) {
                            youtubePlayer.playerPlay();
                        } else {
                            youtubePlayer.playVideo(videoId);
                        }
                    } else if ( 'pause' == option ) {
                        tdYoutubePlayers.players[i].playerPause();
                    }
                    break;
                }
            }
        },


        // create and return the youtube player object
        createPlayer: function( containerId, jqPlayerWrapper ) {

            var youtubePlayer = {

                tdYtPlayer: '',

                tdPlayerContainer: containerId,

                autoplay: 0,

                tdPlaylistIdYoutubeVideoRunning: '',

                jqTDWrapperVideoPlaylist: jqPlayerWrapper.closest( '.td_wrapper_video_playlist' ),

                jqPlayerWrapper: jqPlayerWrapper,

                jqControlPlayer: '',

                _videoId: '',

                playVideo: function( videoId ) {

                    youtubePlayer._videoId = videoId;

                    if ( 'undefined' === typeof( YT ) || 'undefined' === typeof( YT.Player ) ) {

                        window.onYouTubePlayerAPIReady = function () {

                            for ( var i = 0; i < tdYoutubePlayers.players.length; i++ ) {
                                tdYoutubePlayers.players[ i ].loadPlayer( );
                            }
                        };

                        jQuery.getScript('https://www.youtube.com/player_api').done(function( script, textStatus ) {
                            //alert(textStatus);
                        });
                    } else {
                        youtubePlayer.loadPlayer( videoId );
                    }
                },


                loadPlayer: function (videoId) {

                    var videoIdToPlay = youtubePlayer._videoId;

                    if ( undefined !== videoId ) {
                        videoIdToPlay = videoId;
                    }

                    if ( undefined === videoIdToPlay ) {
                        return;
                    }

                    //container is here in case we need to add multiple players on page
                    youtubePlayer.tdPlaylistIdYoutubeVideoRunning = videoIdToPlay;

                    var current_video_name = window['td_' + youtubePlayer.tdPlaylistIdYoutubeVideoRunning]['title'],
                        current_video_time = window['td_' + youtubePlayer.tdPlaylistIdYoutubeVideoRunning]['time'];

                    //remove focus from all videos from playlist
                    youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_click_video_youtube' ).removeClass( 'td_video_currently_playing' );

                    //add focus class on current playing video
                    youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_' + videoIdToPlay ).addClass( 'td_video_currently_playing' );

                    //ading the current video playing title and time to the control area
                    youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_current_video_play_title_youtube' ).html( current_video_name );
                    youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_current_video_play_time_youtube' ).html( current_video_time );

                    youtubePlayer.jqPlayerWrapper.html('<div id=' + youtubePlayer.tdPlayerContainer + '></div>');

                    youtubePlayer.jqControlPlayer = youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_youtube_control' );

                    youtubePlayer.tdYtPlayer = new YT.Player(youtubePlayer.tdPlayerContainer, {//window.myPlayer = new YT.Player(container, {
                        playerVars: {
                            //modestbranding: 1,
                            //rel: 0,
                            //showinfo: 0,
                            autoplay: youtubePlayer.autoplay
                        },
                        height: '100%',
                        width: '100%',
                        videoId: videoIdToPlay,
                        events: {
                            'onStateChange': youtubePlayer.onPlayerStateChange
                        }
                    });
                },


                onPlayerStateChange: function (event) {
                    if (event.data === YT.PlayerState.PLAYING) {

                        //add pause to playlist control
                        youtubePlayer.pauseStatus();

                    } else if (event.data === YT.PlayerState.ENDED) {

                        youtubePlayer.playStatus();

                        //if a video has ended then make auto play = 1; This is the case when the user set autoplay = 0 but start watching videos
                        youtubePlayer.autoplay = 1;


                        //get the next video
                        var nextVideoId = '',
                            tdVideoCurrentlyPlaying = youtubePlayer.jqTDWrapperVideoPlaylist.find( '.td_video_currently_playing' );

                        if ( tdVideoCurrentlyPlaying.length ) {
                            var nextSibling = jQuery( tdVideoCurrentlyPlaying ).next( '.td_click_video_youtube' );
                            if ( nextSibling.length ) {
                                nextVideoId = jQuery( nextSibling ).data( 'video-id' );
                            }
                            //else {
                            //    var firstSibling = jQuery(tdVideoCurrentlyPlaying).siblings( '.td_click_video_youtube:first' );
                            //    if ( firstSibling.length ) {
                            //        nextVideoId = jQuery( firstSibling ).data( 'video-id' );
                            //    }
                            //}
                        }

                        if ('' !== nextVideoId) {
                            youtubePlayer.playVideo(nextVideoId);
                        }

                    } else if (YT.PlayerState.PAUSED) {
                        //add play to playlist control
                        youtubePlayer.playStatus();
                    }
                },

                //tdPlaylistYoutubeStopVideo: function () {
                //    youtubePlayer.tdYtPlayer.stopVideo();
                //},

                playerPlay: function () {
                    youtubePlayer.tdYtPlayer.playVideo();
                },

                playerPause: function () {
                    youtubePlayer.tdYtPlayer.pauseVideo();
                },

                playStatus: function() {
                    youtubePlayer.jqControlPlayer.removeClass( 'td-sp-video-pause' ).addClass( 'td-sp-video-play' );
                },

                pauseStatus: function() {
                    youtubePlayer.jqControlPlayer.removeClass( 'td-sp-video-play' ).addClass( 'td-sp-video-pause' );
                }
            };

            return youtubePlayer;
        }
    };




    // the vimeo list players (to use it, the init() method should be called)
    // !Important. Usually, because of froogaloop implementation, there couldn't be multiple vimeo players running all at once on page.
    tdVimeoPlayers = {

        // the part name of the player id (they will be ex 'player_vimeo_0', 'player_vimeo_1', 'player_vimeo_2', ...)
        tdPlayerContainer: 'player_vimeo',

        // the internal list
        players: [],

        // Set to true at the first autoplayed player created
        // It's used to avoid the autoplay setting of the next players (multiple players can't have autoplay = 1 )
        existingAutoplay: false,


        // init the vimeo list players
        init: function() {
            var jqTDWrapperPlaylistPlayerVimeo = jQuery( '.td_wrapper_playlist_player_vimeo' );

            for ( var i = 0; i < jqTDWrapperPlaylistPlayerVimeo.length; i++ ) {
                var vimeoPlayer = tdVimeoPlayers.addPlayer( jQuery(jqTDWrapperPlaylistPlayerVimeo[i]) );
                if ( 0 !== vimeoPlayer.autoplay ) {
                    tdVimeoPlayers.existingAutoplay = true;
                }
            }


            //click on a vimeo
            jQuery( '.td_click_video_vimeo' ).click(function(){

                var videoId = jQuery( this ).data( 'video-id' ),
                    playerId = jQuery( this ).data( 'player-id' );

                if ( undefined !== playerId && '' !== playerId && undefined !== videoId && '' !== videoId ) {
                    tdVimeoPlayers.operatePlayer( playerId, 'play', videoId );
                }
            });


            //click on vimeo play control
            jQuery( '.td_vimeo_control' ).click(function(){

                var playerId = jQuery( this ).data( 'player-id' );

                if ( undefined !== playerId && '' !== playerId ) {
                    if ( jQuery( this ).hasClass( 'td-sp-video-play' ) ){
                        tdVimeoPlayers.operatePlayer( playerId, 'play' );
                    } else {
                        tdVimeoPlayers.operatePlayer( playerId, 'pause' );
                    }
                }
            });
        },


        // create and add player to the vimeo list players
        addPlayer: function( jqPlayerWrapper ) {
            var playerId = tdVimeoPlayers.tdPlayerContainer + '_' + tdVimeoPlayers.players.length,
                vimeoPlayer = tdVimeoPlayers.createPlayer(  playerId, jqPlayerWrapper );

            jqPlayerWrapper.parent().find( '.td_vimeo_control').data( 'player-id', playerId );

            var vimeoVideoElements = jqPlayerWrapper.parent().find( '.td_click_video_vimeo');
            for ( var j = 0; j < vimeoVideoElements.length; j++ ) {
                jQuery( vimeoVideoElements[ j ] ).data( 'player-id', playerId );

                if ( j + 1 < vimeoVideoElements.length ) {
                    jQuery( vimeoVideoElements[ j ] ).data( 'next-video-id', jQuery( vimeoVideoElements[ j + 1 ] ).data( 'video-id' ) );
                } else {
                    jQuery( vimeoVideoElements[ j ] ).data( 'next-video-id', jQuery( vimeoVideoElements[ 0 ] ).data( 'video-id' ) );
                }
            }

            if ( '1' == jqPlayerWrapper.data( 'autoplay' ) ) {
                vimeoPlayer.autoplay = 1;
            }

            var firstVideo = jqPlayerWrapper.data( 'first-video' );

            if ( undefined !== firstVideo && '' !== firstVideo ) {
                vimeoPlayer.createPlayer( firstVideo );
            }

            tdVimeoPlayers.players.push( vimeoPlayer );

            return vimeoPlayer;
        },


        // play or pause a video or the current (first) video
        operatePlayer: function( playerId, option, videoId ) {
            for ( var i = 0; i < tdVimeoPlayers.players.length; i++ ) {

                if ( tdVimeoPlayers.players[ i ].playerId == playerId ) {

                    var vimeoPlayer = tdVimeoPlayers.players[ i ];

                    if ( 'play' === option ) {

                        vimeoPlayer.autoplay = 1;

                        if ( undefined !== videoId ) {

                            // the existing autoplay is reset to allow autoplay when we have videoId (a video from the playlist was clicked)
                            tdVimeoPlayers.existingAutoplay = false;

                            vimeoPlayer.createPlayer( videoId );
                        } else {
                            vimeoPlayer.playerPlay();
                        }

                    } else if ( 'pause' === option ) {
                        vimeoPlayer.playerPause();
                    }

                    break;
                }
            }
        },


        // create and return the vimeo player object
        createPlayer: function( playerId, jqPlayerWrapper ) {

            var vimeoPlayer = {

                playerId: playerId,

                // the jq td playlist wrapper ( the player and the playlist)
                jqTDWrapperVideoPlaylist: jqPlayerWrapper.closest( '.td_wrapper_video_playlist' ),

                // the jq player wrapper
                jqPlayerWrapper: jqPlayerWrapper,

                currentVideoPlaying : '', // not used for the moment

                player: '',//a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control

                // main control button of the player
                jqControlPlayer: '',

                autoplay: 0,//autoplay

                createPlayer: function ( videoId ) {
                    if ( '' !== videoId ) {

                        this.currentVideoPlaying = videoId;

                        var autoplay = '',
                            current_video_name = window['td_' + videoId]['title'],
                            current_video_time = window['td_' + videoId]['time'];

                        //remove focus from all videos from playlist
                        vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_click_video_vimeo' ).removeClass( 'td_video_currently_playing' );

                        //add focus class on current playing video
                        vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_' + videoId ).addClass( 'td_video_currently_playing' );

                        //ading the current video playing title and time to the control area
                        vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_current_video_play_title_vimeo' ).html( current_video_name );
                        vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_current_video_play_time_vimeo' ).html( current_video_time );

                        vimeoPlayer.jqControlPlayer = vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_vimeo_control' );

                        //check autoplay
                        if ( !tdVimeoPlayers.existingAutoplay && 0 !== vimeoPlayer.autoplay ) {
                            autoplay = '&autoplay=1';

                            if ( tdDetect.isMobileDevice ) {
                                vimeoPlayer.playStatus();
                            } else {
                                vimeoPlayer.pauseStatus();
                            }
                        } else {
                            vimeoPlayer.playStatus();
                        }
                        vimeoPlayer.jqPlayerWrapper.html( '<iframe id="' + vimeoPlayer.playerId + '" src="https://player.vimeo.com/video/' + videoId + '?api=1&player_id=' + vimeoPlayer.playerId + '' + autoplay + '"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' );
                        vimeoPlayer.createVimeoObjectPlayer( jQuery );
                    }
                },

                createVimeoObjectPlayer : function( $ ) {
                    var player = '',
                        iframe = $( '#' + vimeoPlayer.playerId );

                    if ( iframe.length ) {
                        player = $f( iframe[0] );

                        //a copy of the vimeo player : needed when playing or pausing the vimeo pleyer from the playlist control
                        vimeoPlayer.player = player;

                        // When the player is ready, add listeners for pause, finish, and playProgress
                        player.addEvent( 'ready', function() {

                            player.addEvent( 'play', function( data ) {
                                vimeoPlayer.pauseStatus();
                                vimeoPlayer.autoplay = 1;
                            });

                            player.addEvent( 'pause', function( data ) {
                                vimeoPlayer.playStatus();
                            });

                            player.addEvent( 'finish', function( data ) {

                                var nextVideoId = '',
                                    tdVideoCurrentlyPlaying = vimeoPlayer.jqTDWrapperVideoPlaylist.find( '.td_video_currently_playing' );

                                if ( tdVideoCurrentlyPlaying.length ) {
                                    var nextSibling = jQuery( tdVideoCurrentlyPlaying ).next( '.td_click_video_vimeo' );
                                    if ( nextSibling.length ) {
                                        nextVideoId = jQuery( nextSibling ).data( 'video-id' );
                                    }
                                }

                                if ( '' !== nextVideoId ) {
                                    vimeoPlayer.createPlayer( nextVideoId );

                                    if ( tdDetect.isMobileDevice ) {
                                        vimeoPlayer.playStatus();
                                    } else {
                                        vimeoPlayer.pauseStatus();
                                    }
                                } else {
                                    vimeoPlayer.playStatus();
                                }
                            });
                        });
                    }
                },

                // play the current video
                playerPlay: function () {
                    vimeoPlayer.autoplay = 1;
                    vimeoPlayer.player.api( 'play' );
                },

                // pause the current video
                playerPause: function () {
                    vimeoPlayer.player.api( 'pause' );
                },

                // change status to 'play'
                playStatus: function() {
                    vimeoPlayer.jqControlPlayer.removeClass( 'td-sp-video-pause' ).addClass( 'td-sp-video-play' );
                },

                // change status to 'pause'
                pauseStatus: function() {
                    vimeoPlayer.jqControlPlayer.removeClass( 'td-sp-video-play' ).addClass( 'td-sp-video-pause' );
                }
            };

            return vimeoPlayer;
        }
    };

})();