/**
 * Created by tagdiv on 29.09.2015.
 */

/* global tdAnimationScene */

;'use strict';

var tdAnimationScene = {

    animationRunning: false,

    _items: [],

    item: function item() {

        // boolean - an item must be initialized only once
        this._is_initialized = false;

        // object - css properties that will be changed (key - value; ex: 'color' : '#00FFCC')
        this.properties = {};

        // boolean - flag used by the requestAnimationFrame callback to know which items have properties to apply
        this.readyToAnimate = false;

        // the index of the current frame
        this.currentFrame = 1;

        // number - the current interval id set for the animation
        this.interval;

        // the jquery obj whose background will be animated
        this.jqueryObj;

        // the css class selector of the jqueryObj
        this.animationSceneClass;

        // string - default direction for parsing the sprite img
        this._currentDirection = 'right';




        // The followings will be set from the class selector

        // int - number of frames (it must be greater than 1 to allow animation)
        this.frames;

        // the width(px) of a frame
        this.frameWidth;

        // int - the interval time (ms) the animation runs
        this.velocity;

        // boolean - to the right and vice versa
        this.reverse;

        // boolean - running without limit
        this.infinity;



        this.animate = function() {

            var imgSrc = this.jqueryObj.css('background-image');

            var horizontalPosition = -1 * this.currentFrame * this.frameWidth;

            if ( true === this.reverse) {

                if ( 'right' === this._currentDirection ) {

                    this.currentFrame++;

                    if ( this.currentFrame == this.frames - 1 ) {
                        this._currentDirection = 'left';
                    }

                    // complete tour ( once to the right and once to the left ), so we stop
                    if ( ( 1 === this.currentFrame ) && ( false === this.infinity ) ) {
                        clearInterval( this.interval );
                    }

                } else if ( 'left' === this._currentDirection ) {
                    this.currentFrame--;
                    if ( this.currentFrame == 0 ) {
                        this._currentDirection = 'right';
                    }
                }

            } else {
                if ( this.currentFrame == this.frames ) {

                    // complete tour ( once to the right ), so we stop
                    if ( false === this.infinity ) {
                        clearInterval( this.interval );
                        return;
                    }

                    this.currentFrame = 1;
                } else {
                    this.currentFrame++;
                }
            }

            //this.jqueryObj.css('background', imgSrc + ' ' + horizontalPosition + 'px 0');

            this.properties.background = imgSrc + ' ' + horizontalPosition + 'px 0';
            this.readyToAnimate = true;

            window.requestAnimationFrame( tdAnimationScene.animate );
        };
    },

    addItem: function( item ) {

        if ( item.constructor === tdAnimationScene.item ) {
            tdAnimationScene._items.push( item );
            tdAnimationScene._initializeItem( item );
        }
    },

    _initializeItem: function( item ) {
        if ( ( true === item._is_initialized ) ) {
            return;
        }

        var regex = /td_animation_scene\S*/i;
        item.animationSceneClass = regex.exec(item.jqueryObj.attr('class'))[0];

        var sceneParams = item.animationSceneClass.split('-');

        if ( 6 === sceneParams.length ) {

            item.frames = parseInt( sceneParams[1] );
            item.frameWidth = parseInt( sceneParams[2] );
            item.velocity = parseInt( sceneParams[3] );

            if ( 1 === parseInt( sceneParams[4] ) ) {
                item.infinity = true;
            } else {
                item.infinity = false;
            }

            if ( 1 === parseInt( sceneParams[5] ) ) {
                item.reverse = true;
            } else {
                item.reverse = false;
            }

            item._is_initialized = true;
        }
        console.log(sceneParams.length);
    },

    computeAllItems: function() {
        for ( var i = 0; i < tdAnimationScene._items.length; i++ ) {
            tdAnimationScene.computeItem( tdAnimationScene._items[i] );
        }
    },

    recomputeAllItems: function() {
        for ( var i = 0; i < tdAnimationScene._items.length; i++ ) {
            tdAnimationScene.recomputeItem( tdAnimationScene._items[i] );
        }
    },

    computeItem: function( item ) {
        if ( item.frames > 1 ) {
            item.interval = setInterval(function(){

                item.animate();

            }, item.velocity );
        }
    },

    recomputeItem: function( item ) {
        // stop any animation
        clearInterval( item.interval );
        // reset the _is_initialized flag
        item._is_initialized = false;
        // reinitialize item
        tdAnimationScene._initializeItem( item );

        tdAnimationScene.computeItem( item );
    },

    animate: function() {
        var currentItem;

        for ( var i = 0; i < tdAnimationScene._items.length; i++ ) {
            currentItem = tdAnimationScene._items[i];
            if ( true === currentItem.readyToAnimate ) {
                for ( var prop in currentItem.properties ) {
                    currentItem.jqueryObj.css( prop, currentItem.properties[prop] );
                    currentItem.readyToAnimate = false;
                }
            }
        }
    }
}

/*
 <div class="td_animation_scene-10-50-300-1-0"></div>

 .test-animation-scene {
 background-image: url('@{td_css_path}images/sprite/sprite-01.png');
 height: 50px;
 width: 50px;
 float: left;
 }
 */

var tdAnimationSceneElements = jQuery('div[class^="td_animation_scene"]');

for (var i = 0; i < tdAnimationSceneElements.length; i++) {
    var tdAnimationSceneItem = new tdAnimationScene.item();
    tdAnimationSceneItem.jqueryObj = jQuery(tdAnimationSceneElements[i]);

    tdAnimationScene.addItem(tdAnimationSceneItem);
}
tdAnimationScene.computeAllItems();
