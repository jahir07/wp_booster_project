/**
 * Created by tagdiv on 29.09.2015.
 */

/* global tdAnimationScene */

;'use strict';

var tdAnimationScene = {

    animationRunning: false,

    items: [],



    item: function item() {

        // offset from the top of the item, to the top
        // it's set at the initialization item
        this._offsetTop = undefined;


        // offset from the bottom of the item, to the top
        // it's set at the initialization item
        this._offsetBottomToTop = undefined;
        

        // boolean - an item must be initialized only once
        this._isInitialized = false;

        // object - css properties that will be changed (key - value; ex: 'color' : '#00FFCC')
        this.properties = {};

        // boolean - flag used by the requestAnimationFrame callback to know which items have properties to apply
        this.readyToAnimate = false;

        // the index of the current frame
        this.currentFrame = 1;

        // number - the current interval id set for the animation
        this.interval = undefined;

        // the jquery obj whose background will be animated
        this.jqueryObj = undefined;

        // the css class selector of the jqueryObj
        this.animationSceneClass = undefined;

        // string - default direction for parsing the sprite img
        this._currentDirection = 'right';

        // number - the executed cycles
        this._executedCycles = 0;
        



        // The followings will be set from the class selector

        // int - number of frames (it must be greater than 1 to allow animation)
        this.frames = undefined;

        // the width(px) of a frame
        this.frameWidth = undefined;

        // int - the interval time (ms) the animation runs
        this.velocity = undefined;

        // boolean - to the right and vice versa
        this.reverse = undefined;

        // number - number of cycles to animate
        this.cycles = undefined;



        this.animate = function() {

            var imgSrc = this.jqueryObj.css('background-image');

            var horizontalPosition = -1 * this.currentFrame * this.frameWidth;

            this.properties.background = imgSrc + ' ' + horizontalPosition + 'px 0';
            this.readyToAnimate = true;

            // the currentFrame is computed for next frame
            if ( true === this.reverse) {

                if ( 'right' === this._currentDirection ) {

                    this.currentFrame++;

                    if ( this.currentFrame == this.frames - 1 ) {
                        this._currentDirection = 'left';
                    }

                    // complete tour ( once to the right and once to the left ), so we stop
                    if ( ( 1 === this.currentFrame ) && ( 0 !== this.cycles ) && ( this._executedCycles == this.cycles ) ) {
                        clearInterval( this.interval );
                    }

                } else if ( 'left' === this._currentDirection ) {
                    this.currentFrame--;
                    if ( this.currentFrame == 0 ) {
                        this._currentDirection = 'right';
                        this._executedCycles++;
                    }
                }

            } else {
                if ( this.currentFrame == this.frames ) {

                    this._executedCycles++;

                    // complete tour ( once to the right ), so we stop
                    if ( ( 0 !== this.cycles ) && ( this._executedCycles == this.cycles ) ) {
                        clearInterval( this.interval );
                        return;
                    }

                    this.currentFrame = 1;
                } else {
                    this.currentFrame++;
                }
            }

            //this.jqueryObj.css('background', imgSrc + ' ' + horizontalPosition + 'px 0');
            window.requestAnimationFrame( tdAnimationScene.animateAllItems );
        };
    },

    /**
     * The css class selector must be like 'td_animation_scene-10-50-500-0-1'
     * It must start with 'td_animation_scene'
     * Fields order:
     * - number of frames
     * - width of a frame
     * - velocity in ms
     * - infinity (number) : reload the animation cycle at infinity or specify the number of cycles
     * - cycle (0 or 1) : have a simple cycle from left to the right, or add the vice versa, from right to the left
     *
     * @param item
     * @private
     */
    _initializeItem: function( item ) {
        if ( ( true === item._isInitialized ) ) {
            return;
        }

        // get all strings containing 'td_animation_scene'
        var regex = /(td_animation_scene\S*)/gi;

        // resultMatch is an array of matches, or null if there's no matching
        var resultMatch = item.jqueryObj.attr('class').match( regex );

        if ( null != resultMatch ) {

            item.offsetTop = item.jqueryObj.offset().top;
            item.offsetBottomToTop = item.offsetTop + item.jqueryObj.height();

            // the last matching is considered, because new css classes that matches, can be added before recomputing an item
            item.animationSceneClass = resultMatch[ resultMatch.length - 1 ];

            var sceneParams = item.animationSceneClass.split('-');

            if ( 6 === sceneParams.length ) {

                item.frames = parseInt( sceneParams[1] );
                item.frameWidth = parseInt( sceneParams[2] );
                item.velocity = parseInt( sceneParams[3] );
                item.cycles = parseInt( sceneParams[4] );

                if ( 1 === parseInt( sceneParams[5] ) ) {
                    item.reverse = true;
                } else {
                    item.reverse = false;
                }

                item._isInitialized = true;
            }
        }
    },

    addItem: function( item ) {

        if ( item.constructor === tdAnimationScene.item ) {
            tdAnimationScene.items.push( item );
            tdAnimationScene._initializeItem( item );
        }
    },

    computeItem: function( item ) {

        // set interval just for frames greater than 1
        if ( item.frames > 1 ) {

            item.interval = setInterval(function(){
                item.animate();
            }, item.velocity );
        }
    },

    recomputeItem: function( item ) {

        // stop any animation
        clearInterval( item.interval );

        // reset the _isInitialized flag
        item._isInitialized = false;

        // reinitialize item
        tdAnimationScene._initializeItem( item );

        // compute the item again
        tdAnimationScene.computeItem( item );
    },

    // Clear the interval set on an item.
    stopItem: function( item ) {
        if ( ( item.constructor === tdAnimationScene.item ) && ( true === item._isInitialized ) ) {
            clearInterval( item.interval );
        }
    },




    computeAllItems: function() {
        for ( var i = 0; i < tdAnimationScene.items.length; i++ ) {
            tdAnimationScene.computeItem( tdAnimationScene.items[i] );
        }
    },

    recomputeAllItems: function() {
        for ( var i = 0; i < tdAnimationScene.items.length; i++ ) {
            tdAnimationScene.recomputeItem( tdAnimationScene.items[i] );
        }
    },

    stopAllItems: function() {
        for ( var i = 0; i < tdAnimationScene.items.length; i++ ) {
            tdAnimationScene.stopItem( tdAnimationScene.items[i] );
        }
    },

    // The requestAnimationFrame callback function.
    // The properties of an item, are applied over it as css properties, and then the readyToAnimate is set
    animateAllItems: function() {
        var currentItem;

        for ( var i = 0; i < tdAnimationScene.items.length; i++ ) {
            currentItem = tdAnimationScene.items[i];
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

//var tdAnimationSceneElements = jQuery('div[class^="td_animation_scene"]');
//
//for (var i = 0; i < tdAnimationSceneElements.length; i++) {
//    var tdAnimationSceneItem = new tdAnimationScene.item();
//    tdAnimationSceneItem.jqueryObj = jQuery(tdAnimationSceneElements[i]);
//
//    tdAnimationScene.addItem(tdAnimationSceneItem);
//}
//tdAnimationScene.computeAllItems();

//setTimeout(function() {
//    //tdAnimationSceneElements.addClass('td_animation_scene-10-50-500-0-0');
//    //tdAnimationScene.recomputeAllItems();
//
//    tdAnimationScene.stopAllItems();
//}, 3000);
