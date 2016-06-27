/**
 * Created by ra on 9/30/2015.
 */

/*
 tdWeather.js
 v1.0
 */
/* global jQuery:false */
/* global tdDetect:false */
/* global tdUtil:false */
/* global alert:false */
/* global tdLocalCache:false */




var tdWeather = {};

( function(){
    "use strict";

    tdWeather = {

        // used to translate the OWM code to icon
        _icons: {
            // day
            '01d' : 'clear-sky-d',
            '02d' : 'few-clouds-d',
            '03d' : 'scattered-clouds-d',
            '04d' : 'broken-clouds-d',
            '09d' : 'shower-rain-d',   // ploaie hardcore
            '10d' : 'rain-d',          // ploaie light
            '11d' : 'thunderstorm-d',
            '13d' : 'snow-d',
            '50d' : 'mist-d',

            //night:
            '01n' : 'clear-sky-n',
            '02n' : 'few-clouds-n',
            '03n' : 'scattered-clouds-n',
            '04n' : 'broken-clouds-n',
            '09n' : 'shower-rain-n',   // ploaie hardcore
            '10n' : 'rain-n',          // ploaie light
            '11n' : 'thunderstorm-n',
            '13n' : 'snow-n',
            '50n' : 'mist-n'
        },

        _currentRequestInProgress: false, // prevent multiple parallel requests
        _currentItem: '',  // current weather object, it is set on click and after we modify it, it will be displayed

        // latitude and longitude position, used in callback hell
        _currentLatitude: 0,
        _currentLongitude: 0,
        _currentPositionCacheKey: '',
        _currentLocationCacheKey: '',

        // all the weather items
        items: [],  /** an item is json encoded from this in PHP: @see td_weather::$weather_data */

        // location set filed open
        _is_location_open: false,



        /**
         * Init the class, we hook the click event
         */
        init: function () {

            // weather location button click
            jQuery('.td-icons-location').click(function() {
                if (tdWeather._currentRequestInProgress === true) {
                    return;
                }
                tdWeather._currentRequestInProgress = true;

                // get the block id
                tdWeather._currentItem = tdWeather._getItemByBlockID(jQuery(this).data('block-uid'));

                // get the position + callback
                var timeoutVal = 10 * 1000 * 1000;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        tdWeather._updateLocationCallback,
                        tdWeather._displayLocationApiError,
                        {enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 600000});
                }

                tdWeather._currentRequestInProgress = false;

            });

            jQuery('.td-weather-now').click(function(){
                if (tdWeather._currentRequestInProgress === true) {
                    return;
                }
                tdWeather._currentRequestInProgress = true;

                // get the block id
                tdWeather._currentItem = tdWeather._getItemByBlockID(jQuery(this).data('block-uid'));
                //console.log(tdWeather._currentItem);

                if (tdWeather._currentItem.current_unit === 1) {
                    tdWeather._currentItem.current_unit = 0;
                } else {
                    tdWeather._currentItem.current_unit = 1;
                }
                tdWeather._renderCurrentItem();
            });

            /**
             *  set manual location
             *  */

            jQuery('#location_form').submit( function(event){
                event.preventDefault();

                if (tdWeather._currentRequestInProgress === true) {
                    return;
                }

                tdWeather._currentRequestInProgress = true;

                //console.log(tdWeather._currentItem.block_uid);

                tdWeather._currentItem = tdWeather._getItemByBlockID(tdWeather._currentItem.block_uid);

                //tdWeather._currentItem = tdWeather._getItemByBlockID(jQuery(this).data('block-uid'));

                //console.log(tdWeather._currentItem);

                var location = jQuery('#location').val();
                //console.log('location: ' + location);
                //alert('form submitted!!');

                tdWeather._updateLocationCallback2(location);

                tdWeather._currentRequestInProgress = false;
                tdWeather._hide_location();
            });


            jQuery(document).click(function(ev) {
                //console.log(jQuery(ev.target));
                //console.log('+++' + ev.target.className);
                if ( tdWeather._is_location_open === true
                    && jQuery(ev.target).hasClass('td-location-set-input') !== true
                    && jQuery(ev.target).hasClass('td-location-set-button') !== true ) {
                    tdWeather._hide_location();
                }

            });
        },


        /**
         * adds a new weather item
         * item.block_uid is REQUIERED, based on that id, we delete the item from the array *as of 27.4.2016 the id is not used
         * @param item object - an item is json encoded from this in PHP: @see td_weather::$weather_data
         */
        addItem: function (item) {
            tdWeather.items.push(item);
        },

        ///
        // For now it's not needed because td_weater.php does not add js if it detects td-composer
        // **
        // * Deletes an item base on blockUid.
        // * Make sure that you add block_uid to items that you expect to be deleted
        // * @param blockUid
        // */
        //deleteItem: function(blockUid) {
        //    for (var cnt = 0; cnt < tdWeather.items.length; cnt++) {
        //        if (tdWeather.items[cnt].block_uid === blockUid) {
        //            tdWeather.items.splice(cnt, 1); // remove the item from the "array"
        //            return true;
        //        }
        //    }
        //    return false;
        //},


        /**
         * 1. LOCATION api - position callback
         * @param position
         * @private
         */
        _updateLocationCallback: function(position) {
            tdWeather._currentLatitude = position.coords.latitude;
            tdWeather._currentLongitude = position.coords.longitude;
            tdWeather._currentPositionCacheKey = position.coords.latitude + '_' + position.coords.longitude; //  update the cache key for current position

            // check the cache first and avoid doing the same ajax request again
            if (tdLocalCache.exist(tdWeather._currentPositionCacheKey + '_today')) {
                tdWeather._owmGetTodayDataCallback(tdLocalCache.get(tdWeather._currentPositionCacheKey + '_today'));
            } else {
                var weather = 'http://api.openweathermap.org/data/2.5/weather?lat=' + tdWeather._currentLatitude + '&lon=' + tdWeather._currentLongitude + '&units=metric&lang=' + tdWeather._currentItem.api_language + '&appid=' + tdWeather._currentItem.api_key;
                jQuery.ajax({
                    dataType: "jsonp",
                    url: weather,
                    success: tdWeather._owmGetTodayDataCallback,
                    cache: true
                });
            }

            //alert(position.coords.latitude + ' ' + position.coords.longitude);

        },


        /**
         * 2. AJAX callback for today forecast, this also makes a call to ajax 5 days forecast
         * @param data - OWM api response - NOTICE: We don't check anything if it's correct :)
         * @private
         */
        _owmGetTodayDataCallback: function (data) {
            // save the data to localCache
            tdLocalCache.set(tdWeather._currentPositionCacheKey + '_today', data);


            // prepare the tdWeather._currentItem object, notice that tdWeather._currentItem is a reference to an object stored in tdWeather.items
            tdWeather._currentItem.api_location = data.name;
            tdWeather._currentItem.today_clouds = tdUtil.round(data.clouds.all);
            tdWeather._currentItem.today_humidity = tdUtil.round(data.main.humidity);
            tdWeather._currentItem.today_icon = tdWeather._icons[data.weather[0].icon];
            tdWeather._currentItem.today_icon_text = data.weather[0].description;
            tdWeather._currentItem.today_max[0] = tdUtil.round(data.main.temp_max, 1);                                  //celsius
            tdWeather._currentItem.today_max[1] = tdWeather._celsiusToFahrenheit(data.main.temp_max);                   //imperial
            tdWeather._currentItem.today_min[0] = tdUtil.round(data.main.temp_min, 1);                                  //celsius
            tdWeather._currentItem.today_min[1] = tdWeather._celsiusToFahrenheit(data.main.temp_min);                   //imperial
            tdWeather._currentItem.today_temp[0] = tdUtil.round(data.main.temp, 1);                                     //celsius
            tdWeather._currentItem.today_temp[1] = tdWeather._celsiusToFahrenheit(data.main.temp);                      //imperial
            tdWeather._currentItem.today_wind_speed[0] = tdUtil.round(data.wind.speed, 1);                              //metric
            tdWeather._currentItem.today_wind_speed[1] = tdWeather._kmphToMph(data.wind.speed);                         //imperial

            //console.log(tdWeather._currentItem);
            //console.log(data);

            // check the cache first and avoid doing the same ajax request again
            if (tdLocalCache.exist(tdWeather._currentPositionCacheKey)) {
                tdWeather._owmGetFiveDaysData(tdLocalCache.get(tdWeather._currentPositionCacheKey));
            } else {
                var weather = 'http://api.openweathermap.org/data/2.5/forecast/daily?lat=' + tdWeather._currentLatitude + '&lon=' + tdWeather._currentLongitude + '&units=metric&lang=' + tdWeather._currentItem.api_language + '&appid=' + tdWeather._currentItem.api_key;
                //console.log('forecast: ' + weather);
                jQuery.ajax({
                    dataType: "jsonp",
                    url: weather,
                    success: tdWeather._owmGetFiveDaysData,
                    cache:true
                });
            }

        },


        /**
         * 3. AJAX callback for the 5 days forecast
         * @param data - OWM api response NOTICE: We don't check anything if it's correct :)
         * @private
         */
        _owmGetFiveDaysData: function (data) {
            // save the data to localCache
            tdLocalCache.set(tdWeather._currentPositionCacheKey, data);

            // process the data
            for (var item_index = 0; item_index < tdWeather._currentItem.forecast.length ; item_index++) {
                var current_forecast = tdWeather._currentItem.forecast[item_index];
                current_forecast.day_temp[0] = tdUtil.round(data.list[current_forecast.owm_day_index].temp.day);        //celsius
                current_forecast.day_temp[1] = tdWeather._celsiusToFahrenheit(current_forecast.day_temp[0]);            //imperial
            }
            tdWeather._renderCurrentItem();
        },


        /**
         * 4. Here we render the global tdWeather._currentItem object to the screen. The object already contains all the needed information
         * about where and what we have to render.
         * @private
         */
        _renderCurrentItem: function () {

            //console.log('.' + tdWeather._currentItem.block_uid + ' .td-weather-city');

            var blockInner = jQuery('#' + tdWeather._currentItem.block_uid);

            // city
            blockInner.find('.td-weather-city').html(tdWeather._currentItem.api_location);

            // conditions
            blockInner.find('.td-weather-condition').html(tdWeather._currentItem.today_icon_text);

            // animation
            // we remove all the classes! including the animation ones
            var icon_el = blockInner.find('.td-w-today-icon');
            icon_el.removeClass();
            icon_el.addClass('td-w-today-icon');
            icon_el.addClass(tdWeather._currentItem.today_icon);

            var currentTempUnit = tdWeather._currentItem.current_unit;
            var currentSpeedLabel = 'kmh';
            var currentTempLabel = 'C';

            // preapare the labels
            if (currentTempUnit === 1) {
                currentSpeedLabel = 'mph';
                currentTempLabel = 'F';
            }


            // main temp
            blockInner.find('.td-big-degrees').html(tdWeather._currentItem.today_temp[currentTempUnit]);

            // main temp units
            blockInner.find('.td-weather-unit').html(currentTempLabel);


            // high
            blockInner.find('.td-w-high-temp').html(tdWeather._currentItem.today_max[currentTempUnit]);

            // low
            blockInner.find('.td-w-low-temp').html(tdWeather._currentItem.today_min[currentTempUnit]);

            // humidity
            blockInner.find('.td-w-today-humidity').html(tdWeather._currentItem.today_humidity + '%');

            // wind speed
            blockInner.find('.td-w-today-wind-speed').html(tdWeather._currentItem.today_wind_speed[currentTempUnit] + currentSpeedLabel);

            // clouds
            blockInner.find('.td-w-today-clouds').html(tdWeather._currentItem.today_clouds + '%');

            // full list of items! - just the temperature
            for (var item_index = 0; item_index < tdWeather._currentItem.forecast.length ; item_index++) {
                blockInner.find('.td-degrees-' + item_index).html(tdWeather._currentItem.forecast[item_index].day_temp[currentTempUnit]);
            }


            tdWeather._currentRequestInProgress = false; // allow other requests to take place
        },


        /**
         * gets a weather item based on block_uid
         * @param block_uid
         * @returns {*}
         * @private
         */
        _getItemByBlockID: function (block_uid) {
            for (var item_index = 0; item_index < tdWeather.items.length; item_index++) {
                if (tdWeather.items[item_index].block_uid === block_uid) {
                    return tdWeather.items[item_index];
                }
            }
            return false;
        },


        /**
         * Displays a friendly error when the location api fails
         * @param error - a location api error object?
         * @private
         */
        _displayLocationApiError: function (error) {

            if (error.code === 1) {
                if (tdDetect.isAndroid) {
                    alert('Please enable your gps and reload the page.');
                    return;
                }

                else if (tdDetect.isIos) {
                    alert("Please enable Location services for Safari Websites and reload the page. \n ---------------------- \nSettings > Privacy > Location Services");
                    return;
                }

                //alert("Permission denied. Enable GPS or Location services and reload the page");

                /**
                 * manual location field
                 * */
                tdWeather._show_location();

                //alert('the js rounded value is: '+tdUtil.round(27.82));

                return;

            }

            // the rest of the errors
            var errors = {
                2: 'Position unavailable',
                3: 'Request timeout'
            };
            alert("Error: " + errors[error.code]);
        },


        /**
         * C to F converter. It rounds on big F numbers because we don't have space on the UI.
         * @param celsiusDegrees
         * @returns {*}
         * @private
         */
        _celsiusToFahrenheit: function (celsiusDegrees) {
            var f_degrees = celsiusDegrees * 9 / 5 + 32;

            var rounded_val = tdUtil.round(f_degrees, 1);
            if (rounded_val > 99.9) {  // if the value is bigger than 100, round it
                return tdUtil.round(f_degrees);
            }

            return rounded_val;
        },

        /**
         * converter for KMH -> MPH  ex: 2.3
         * @param $kmph
         * @returns {*}
         * @private
         */
        _kmphToMph: function ($kmph) {
            return tdUtil.round($kmph * 0.621371192, 1);
        },

        /*************************************************************************************************************************************
         * weather manual
         * *************************************************************************************************************************************/

        /**
         * show location
         */

        _show_location: function (){

            //console.log(jQuery(this).data('block-uid'));
            //tdWeather._currentItem = tdWeather._getItemByBlockID(jQuery(this).data('block-uid'));
            tdWeather._currentItem = tdWeather._getItemByBlockID(tdWeather._currentItem.block_uid);

            jQuery('#' + tdWeather._currentItem.block_uid).find('.td-weather-set-location').addClass( 'td-show-location' );

            tdWeather._is_location_open = true;

        },

        /**
         * hide location
         */

        _hide_location: function (){

            jQuery('#' + tdWeather._currentItem.block_uid).find('.td-weather-set-location').removeClass('td-show-location');
            tdWeather._is_location_open = false;
        },

        /**
         *  Location API - position callback 2 - used on chrome or other browsers that do not allow current position retrieving
         * @param location
         */

        _updateLocationCallback2: function(location){

            //console.log('se seteaza aici.. ');
            //console.log('se seteaza aici - inainte: ' + tdWeather._currentLocationCacheKey);

            tdWeather._currentLocationCacheKey = location;

            //console.log('se seteaza aici - dupa: ' + tdWeather._currentLocationCacheKey);

            console.log(tdLocalCache.exist(tdWeather._currentLocationCacheKey));

            // check the cache first and avoid doing the same ajax request again
            if (tdLocalCache.exist(tdWeather._currentLocationCacheKey + '_today')) {

                //console.log('ajunge aici - city weather cache!! ');
                //console.log(tdLocalCache.get(tdWeather._currentLocationCacheKey + '_today'));
                tdWeather._owmGetTodayDataCallback2(tdLocalCache.get(tdWeather._currentLocationCacheKey + '_today'));

            } else {

                console.log('se face request la api!! ');

                var weather = 'http://api.openweathermap.org/data/2.5/weather?q=' + encodeURIComponent(location) + '&lang=' + tdWeather._currentItem.api_language + '&units=metric&appid=' + tdWeather._currentItem.api_key;

                console.log('dupa request city weather: ' + weather);

                jQuery.ajax({
                    dataType: "jsonp",
                    url: weather,
                    success: tdWeather._owmGetTodayDataCallback2,
                    cache: true
                });
            }
        },


        /**
         * AJAX callback for forecast and 5 days forecast for city location api request
         * @param data - OWM api response
         *
         */

        _owmGetTodayDataCallback2: function (data) {
            // save the data to localCache

            //console.log('before cache ' + tdWeather._currentLocationCacheKey + '_today');
            tdLocalCache.set(tdWeather._currentLocationCacheKey + '_today', data);

            //console.log('wafter cache ' + tdWeather._currentLocationCacheKey + '_today');

            //console.log('data:');
            //console.log(data);


            // prepare the tdWeather._currentItem object, notice that tdWeather._currentItem is a reference to an object stored in tdWeather.items
            tdWeather._currentItem.api_location = data.name;
            tdWeather._currentItem.today_clouds = tdUtil.round(data.clouds.all);
            tdWeather._currentItem.today_humidity = tdUtil.round(data.main.humidity);
            tdWeather._currentItem.today_icon = tdWeather._icons[data.weather[0].icon];
            tdWeather._currentItem.today_icon_text = data.weather[0].description;
            tdWeather._currentItem.today_max[0] = tdUtil.round(data.main.temp_max, 1);                                  //celsius
            tdWeather._currentItem.today_max[1] = tdWeather._celsiusToFahrenheit(data.main.temp_max);                   //imperial
            tdWeather._currentItem.today_min[0] = tdUtil.round(data.main.temp_min, 1);                                  //celsius
            tdWeather._currentItem.today_min[1] = tdWeather._celsiusToFahrenheit(data.main.temp_min);                   //imperial
            tdWeather._currentItem.today_temp[0] = tdUtil.round(data.main.temp, 1);                                     //celsius
            tdWeather._currentItem.today_temp[1] = tdWeather._celsiusToFahrenheit(data.main.temp);                      //imperial
            tdWeather._currentItem.today_wind_speed[0] = tdUtil.round(data.wind.speed, 1);                              //metric
            tdWeather._currentItem.today_wind_speed[1] = tdWeather._kmphToMph(data.wind.speed);                         //imperial

            //console.log('current itemm: ');
            //console.log(tdWeather._currentItem);
            //console.log(data);

            console.log(tdLocalCache.exist(tdWeather._currentLocationCacheKey));

            // check the cache first and avoid doing the same ajax request again
            if (tdLocalCache.exist(tdWeather._currentLocationCacheKey)) {
                console.log('forecast cache key: ' + tdWeather._currentLocationCacheKey);

                //console.log('forecast cache!!! ');
                tdWeather._owmGetFiveDaysData2(tdLocalCache.get(tdWeather._currentLocationCacheKey));

                //console.log('forecast data object:');
                //console.log(tdLocalCache.get(tdWeather._currentLocationCacheKey));

            } else {

                console.log('se face request la api forecast!! ');
                var weather = 'http://api.openweathermap.org/data/2.5/forecast/daily?q=' + tdWeather._currentItem.api_location + '&lang=' + tdWeather._currentItem.api_language + '&units=metric&cnt=7&appid=' + tdWeather._currentItem.api_key;

                console.log('dupa request city forecast: ' + weather);
                jQuery.ajax({
                    dataType: "jsonp",
                    url: weather,
                    success: tdWeather._owmGetFiveDaysData2,
                    cache:true
                });
            }

        },


        _owmGetFiveDaysData2: function (data) {
            // save the data to localCache
            tdLocalCache.set(tdWeather._currentLocationCacheKey, data);

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            if(dd<10) {
                dd='0'+dd
            }

            if(mm<10) {
                mm='0'+mm
            }

            today = yyyy+mm+dd;
            console.log(today);
            console.log(tdWeather._currentItem.forecast.length);

            // process the data
            for (var item_index = 0; item_index < tdWeather._currentItem.forecast.length ; item_index++) {
                var current_forecast = tdWeather._currentItem.forecast[item_index];

                var timestamp = data.list[current_forecast.owm_day_index].dt;

                var date = new Date(timestamp * 1000);
                var month = date.getMonth()+1;
                var day = date.getDate();
                var year = date.getFullYear();

                if(day<10) {
                    day='0'+day
                }

                if(mm<10) {
                    month='0'+month
                }

                var forecast_day = year+month+day;
                console.log(forecast_day);

                if (today < forecast_day){
                    console.log('false');
                    //return;
                }

                //problema cu zilele de forecast decalate vine de la setarea initiala de forecast care vine din php..
                //..daca din cauza la time zone trebuie excluse primele 2 zile din manual forecast api request data..
                //..atunci orice manual request de acest gen nu o sa stie de asta si o sa reporteze la datele de forecast setate din php
                //..la fel si daca avem owm indexul setat pe 2 initial si cu manual forecast setam o locatei care nu are nevoie de decalaj
                //.. daca faci reuquest manual pt o locatie care nu are nevoie de delay se intoarce cu delay pe baza owm index-lui si din api se aduce cu o zi mai tarziu


                current_forecast.day_temp[0] = tdUtil.round(data.list[current_forecast.owm_day_index].temp.day);        //celsius


                console.log(data);

                current_forecast.day_temp[1] = tdWeather._celsiusToFahrenheit(current_forecast.day_temp[0]);            //imperial
            }
            tdWeather._renderCurrentItem();
        },







    };  // end tdWeather
})();

tdWeather.init(); //init the class