/*  ----------------------------------------------------------------------------
    Menu script
 */

/* global jQuery:{} */
/* global tdDetect:{} */

var tdMenu = {};
(function(){
    'use strict';

    tdMenu = {

        //submenu items (used on unbind)
        _itemsWithSubmenu: null,
        //main menu (used on unbind)
        _mainMenu: null,

        //on touch - when you click outside the menu it will close all menus
        _outsideClickArea: null,
        _outsideClickExcludedAreas: '#td-header-menu .sf-menu, #td-header-menu .sf-menu *, .menu-top-container, .menu-top-container *',

        //added when menu is open
        _openMenuClass: 'sfHover',
        _openMenuBodyClass: 'td-open-menu',



        /*
         * initialize menu
         */
        init: function() {
            //get menu items
            var mainMenu = jQuery('#td-header-menu .sf-menu'),
                menus = jQuery('#td-header-menu .sf-menu, .top-header-menu'),
                menuLinks = menus.find('.menu-item-has-children > a, .td-mega-menu > a');

            //add dropdown arrow on items with submenu
            menuLinks.append('<i class="td-icon-menu-down"></i>');

            //main menu width adjustment (top menu will use css)
            mainMenu.supersubs({
                minWidth: 10, // minimum width of sub-menus in em units
                maxWidth: 20, // maximum width of sub-menus in em units
                extraWidth: 1 // extra width can ensure lines don't sometimes turn over
            });

            //add sf-with-ul class to all anchors
            menuLinks.addClass('sf-with-ul');
            //add sf-js-enabled class
            menus.addClass('sf-js-enabled');
            //hide all submenus
            menuLinks.parent().find('ul').first().css('display', 'none');

            //set unbind items
            tdMenu._mainMenu = mainMenu;
            tdMenu._itemsWithSubmenu = menuLinks;
            tdMenu._outsideClickArea = jQuery(window).not(tdMenu._outsideClickExcludedAreas);
            //initialize menu
            tdMenu._setHover(menuLinks, mainMenu);
        },




        /**
         * adjust submenu position - if it goes out of window move it to the left
         * @param item - submenu item
         * @private
         */
        _getSubmenuPosition: function(item) {
            var windowWidth = jQuery(window).width(),
                submenuElement = item.children("ul").first();
            if (submenuElement.length > 0) {
                var submenuOffsetWidth = submenuElement.offset().left + submenuElement.width();
                if (submenuOffsetWidth > windowWidth) {
                    if (submenuElement.parent().parent().hasClass("sf-menu")) {
                        //main menu
                        submenuElement.css("left", "-" + (submenuOffsetWidth - windowWidth) + "px");
                    } else {
                        //submenu
                        submenuElement.addClass("reversed").css("left", "-" + (submenuElement.width() + 0) + "px");
                    }
                }
            }
        },



        /**
         * calculate mouse direction
         * @param x1 - old x position
         * @param y1 - old y position
         * @param x2 - current x position
         * @param y2 - current y position
         * @returns {number}
         * @private
         */
        _getMouseAngleDirection: function(x1, y1, x2, y2) {
            var dx = x2 - x1,
                dy = y2 - y1;

            return Math.atan2(dx, dy) / Math.PI * 180;
        },



        /**
         * set menu functionality for desktop and touch devices
         * @param menuLinks - submenu links (anchors)
         * @param mainMenu - main menu
         * @private
         */
        _setHover: function(menuLinks, mainMenu) {

            /* TOUCH DEVICES */
            if (tdDetect.isTouchDevice) {

                //close menu when you tap outside of it
                jQuery(document).on('touchstart', 'body', function(e) {
                    var menuItems = menuLinks.parent(),
                        pageBody = jQuery('body');
                        //check if a menu is open and if the target is outside the menu
                        if (pageBody.hasClass(tdMenu._openMenuBodyClass) && !menuItems.is(e.target) && menuItems.has(e.target).length === 0) {
                            menuItems.removeClass(tdMenu._openMenuClass);
                            menuItems.children('ul').hide();
                            //remove open menu class from <body>
                            pageBody.removeClass(tdMenu._openMenuBodyClass);
                        }
                });

                //open-close the menu on touch
                menuLinks.on('touchstart',
                    function(event){
                        event.preventDefault();
                        event.stopPropagation();
                        var currentMenuLink = jQuery(this),
                            currentMenu = currentMenuLink.parent(),
                            pageBody = jQuery('body');

                        //menu is open
                        if (currentMenu.hasClass(tdMenu._openMenuClass)) {
                            //has a link, open it
                            if (currentMenuLink.attr('href') !== null && currentMenuLink.attr('href') !== '#') {
                                window.location.href = currentMenuLink.attr('href');

                            //no link - close it
                            } else {
                                //if it's a main menu remove the body class
                                if (currentMenu.parent().hasClass('sf-menu') || currentMenu.parent().hasClass('top-header-menu')) {
                                    pageBody.removeClass(tdMenu._openMenuBodyClass);
                                }
                                currentMenu.removeClass(tdMenu._openMenuClass);
                                //close submenus
                                currentMenu.find('ul').hide();
                                currentMenu.find('li').removeClass(tdMenu._openMenuClass);
                            }

                        //menu is not open
                        } else {
                            //a sibling may be open and we have to close it
                            if (currentMenu.parent().hasClass('sf-menu') || currentMenu.parent().hasClass('top-header-menu')) {
                                //main menu - close all menus
                                menuLinks.parent().removeClass(tdMenu._openMenuClass);
                                menuLinks.parent().children('ul').hide();
                            } else {
                                //submenu - close all siblings-submenus and open the current one
                                var currentMenuSiblings = currentMenu.siblings();
                                currentMenuSiblings.removeClass(tdMenu._openMenuClass);
                                //close siblings
                                currentMenuSiblings.find('ul').hide();
                                currentMenuSiblings.find('li').removeClass(tdMenu._openMenuClass);
                            }
                            //open current
                            currentMenu.addClass(tdMenu._openMenuClass);
                            currentMenu.children('ul').show();
                            //adjust menu position
                            tdMenu._getSubmenuPosition(currentMenu);
                            //add body class
                            pageBody.addClass(tdMenu._openMenuBodyClass);
                        }
                    }
                );

             /* DESKTOP */
            } else {

                var lastMenuOpen = {},
                    newMenuTimeout,
                    timeoutCleared = true;

                mainMenu.on('mouseleave', function() {
                    //close all menus
                    menuLinks.parent().removeClass(tdMenu._openMenuClass);
                    menuLinks.parent().children('ul').hide();
                    //reset last menu
                    lastMenuOpen = {};

                });

                //apply hover only to main menu (top menu uses css)
                mainMenu.find('.menu-item').hover(
                    function(){

                        //open the new menu element
                        var currentMenu = jQuery(this),
                            currentMenuSiblings = '',
                            sensitivity = 5, //measure direction after x pixels
                            pixelCount,
                            oldX,
                            oldY,
                            mouseDirection;

                        //menu has submenus
                        if (currentMenu.hasClass('menu-item-has-children') ||  currentMenu.hasClass('td-mega-menu')) {

                            //main menu
                            if (currentMenu.parent().hasClass('sf-menu')) {
                                //no menu is open - instantly open the current one
                                if (jQuery.isEmptyObject(lastMenuOpen)) {
                                    currentMenu.addClass(tdMenu._openMenuClass);
                                    currentMenu.children('ul').show();
                                    //set the last open menu
                                    lastMenuOpen = currentMenu;

                                //menu is open
                                } else {

                                    //execute only if it's a new menu
                                    if (currentMenu[0] !== lastMenuOpen[0]) {

                                        //initialize variables used for calculating mouse direction
                                        pixelCount = 0;
                                        oldX = 0;
                                        oldY = 0;
                                        mouseDirection = null;

                                        //add timeout - when you enter a new menu
                                        if (timeoutCleared === true) {
                                            timeoutCleared = false;
                                            newMenuTimeout = setTimeout(function() {
                                                //close previous menus
                                                menuLinks.parent().removeClass(tdMenu._openMenuClass);
                                                menuLinks.parent().children('ul').hide();
                                                //open current menu
                                                currentMenu.addClass(tdMenu._openMenuClass);
                                                currentMenu.children('ul').show();
                                                //set the last open menu
                                                lastMenuOpen = currentMenu;
                                            }, 400);
                                        }

                                        currentMenu.on('mousemove', function(e) {
                                            //reset pixeCount, calculate direction and define old x and y
                                            if (pixelCount >= sensitivity) {
                                                pixelCount = 0;
                                                mouseDirection = tdMenu._getMouseAngleDirection(oldX, oldY, e.pageX, e.pageY);
                                                oldX = e.pageX;
                                                oldY = e.pageY;
                                            } else {
                                                pixelCount++;
                                                //set the first x and y
                                                if (oldX === 0 && oldY === 0) {
                                                    oldX = e.pageX;
                                                    oldY = e.pageY;
                                                }
                                            }

                                            //debug mouse direction
                                            //console.log(mouseDirection);

                                            //current menu is different than the last one
                                            if (mouseDirection !== null && (mouseDirection > 85 || mouseDirection < -85)) {
                                                //close previous menus
                                                menuLinks.parent().removeClass(tdMenu._openMenuClass);
                                                menuLinks.parent().children('ul').hide();
                                                //open current menu
                                                currentMenu.addClass(tdMenu._openMenuClass);
                                                currentMenu.children('ul').show();

                                                //unbind mousemove event - menu is open, there's no need for it
                                                currentMenu.off('mousemove');
                                                //clear timeout - menu is open
                                                clearTimeout(newMenuTimeout);
                                                timeoutCleared = true;
                                                //set the last open menu
                                                lastMenuOpen = currentMenu;
                                            }
                                        });
                                    }
                                }

                            //submenu
                            } else {
                                //submenu - close all siblings-submenus
                                currentMenuSiblings = currentMenu.siblings();
                                currentMenuSiblings.removeClass(tdMenu._openMenuClass);
                                //close submenus
                                currentMenuSiblings.find('ul').hide();
                                currentMenuSiblings.find('li').removeClass(tdMenu._openMenuClass);
                                //open current menu
                                currentMenu.addClass(tdMenu._openMenuClass);
                                currentMenu.children('ul').show();
                                //adjust menu position
                                tdMenu._getSubmenuPosition(currentMenu);
                            }

                        //menu item doesn't have submenu
                        } else {
                            //main menu
                            if (currentMenu.parent().hasClass('sf-menu') || currentMenu.parent().hasClass('top-header-menu')) {
                                //execute only if another menu is open
                                if (!jQuery.isEmptyObject(lastMenuOpen)) {

                                    //initialize variables used for calculating mouse direction
                                    pixelCount = 0;
                                    oldX = 0;
                                    oldY = 0;
                                    mouseDirection = null;

                                    //add timeout - when you enter a new menu
                                    if (timeoutCleared === true) {
                                        timeoutCleared = false;
                                        newMenuTimeout = setTimeout(function() {
                                            //close previous menus
                                            menuLinks.parent().removeClass(tdMenu._openMenuClass);
                                            menuLinks.parent().children('ul').hide();
                                            lastMenuOpen = {};
                                        }, 400);
                                    }

                                    currentMenu.on('mousemove', function(e) {
                                        //reset pixeCount, calculate direction and define old x and y
                                        if (pixelCount >= sensitivity) {
                                            pixelCount = 0;
                                            mouseDirection = tdMenu._getMouseAngleDirection(oldX, oldY, e.pageX, e.pageY);
                                            oldX = e.pageX;
                                            oldY = e.pageY;
                                        } else {
                                            pixelCount++;
                                            //set the first x and y
                                            if (oldX === 0 && oldY === 0) {
                                                oldX = e.pageX;
                                                oldY = e.pageY;
                                            }
                                        }

                                        //current menu is different than the last one
                                        if (mouseDirection !== null && (mouseDirection > 85 || mouseDirection < -85)) {
                                            //close previous menus
                                            menuLinks.parent().removeClass(tdMenu._openMenuClass);
                                            menuLinks.parent().children('ul').hide();
                                            //unbind mousemove event - menu is open, there's no need for it
                                            currentMenu.off('mousemove');
                                            //clear timeout - menu is open
                                            clearTimeout(newMenuTimeout);
                                            timeoutCleared = true;
                                            //set the last open menu
                                            lastMenuOpen = {};
                                        }
                                    });
                                }
                            //submenu
                            } else {
                                //close all siblings-submenus
                                lastMenuOpen = currentMenu.parent();
                                currentMenuSiblings = currentMenu.siblings();
                                currentMenuSiblings.removeClass(tdMenu._openMenuClass);
                                //close siblings submenus
                                currentMenuSiblings.find('ul').hide();
                                currentMenuSiblings.find('li').removeClass(tdMenu._openMenuClass);
                            }
                        }
                    },


                    //mouseleave
                    function(){

                        var currentMenu = jQuery(this);

                        //clear menu timeout
                        if (timeoutCleared === false) {
                            clearTimeout(newMenuTimeout);
                            timeoutCleared = true;
                        }
                        //unbind mousemove event
                        currentMenu.off('mousemove');
                    }
                );
            }

        },



        /**
         * unbind menu events
         */
        unsetHover: function() {
            if (tdMenu._itemsWithSubmenu !== null) {
                tdMenu._itemsWithSubmenu.off();
            }
            //unbind outside click area events
            if (tdMenu._outsideClickArea !== null) {
                tdMenu._outsideClickArea.off();
            }
        }

    };
})();


//initialize menu
tdMenu.init();










// // the old code
// (function(){
//     'use strict';
//
//     // top menu
//
//     if (tdDetect.isTouchDevice) {
//         //touch
//         jQuery('.td-header-sp-top-menu .top-header-menu').superfish({
//             delay:300,
//             speed:'fast',
//             useClick:true
//         });
//
//     } else {
//
//         //not touch
//         jQuery('.td-header-sp-top-menu .top-header-menu').superfish({
//             delay:600,
//             speed:200,
//             useClick:false
//         });
//     }
//
// /*  ----------------------------------------------------------------------------
//  On load
//  */
//
//     // header menu
//     jQuery('#td-header-menu .sf-menu').supersubs({
//         minWidth: 10, // minimum width of sub-menus in em units
//         maxWidth: 40, // maximum width of sub-menus in em units
//         extraWidth: 1 // extra width can ensure lines don't sometimes turn over
//     });
//
//
//
//     if (tdDetect.isTouchDevice) {
//         //touch
//         jQuery('#td-header-menu .sf-menu').superfish({
//             delay:300,
//             speed:'fast',
//             useClick:true
//         });
//
//     } else {
//
//         //not touch
//         jQuery('#td-header-menu .sf-menu').superfish({
//             delay:600,
//             speed:200,
//             useClick:false
//         });
//     }
// })();






