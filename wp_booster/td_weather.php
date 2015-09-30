<?php
/**
 * Created by ra.
 * Date: 9/28/2015
 */


class td_weather {

	private static $caching_time = 10800;  // 3 hours


	static function render_generic($atts) {
		// the default units are metric
		if (empty($atts['w_units'])) {
			$atts['w_units'] = 'metric';
		}



		if (empty($atts['w_location'])) {
			return self::error('<strong>location</strong> is empty. Configure this block/widget and enter a location and we will show the weather from that location :) ');
		}

		if (empty($atts['w_language'])) {
			$atts['w_language'] = 'en';
			$sytem_locale = get_locale();
			$available_locales = array( 'en', 'es', 'sp', 'fr', 'it', 'de', 'pt', 'ro', 'pl', 'ru', 'uk', 'ua', 'fi', 'nl', 'bg', 'sv', 'se', 'ca', 'tr', 'hr', 'zh', 'zh_tw', 'zh_cn', 'hu' );

			// CHECK FOR LOCALE
			if( in_array( $sytem_locale , $available_locales ) ) {
				$atts['w_language'] = $sytem_locale;
			}
			// CHECK FOR LOCALE BY FIRST TWO DIGITS
			if( in_array(substr($sytem_locale, 0, 2), $available_locales ) ) {
				$atts['w_language'] = substr($sytem_locale, 0, 2);
			}
		}


		$cache_key = strtolower($atts['w_location']);
		if (td_remote_cache::is_expired(__CLASS__, $cache_key) === true) {
			// cache is expired

			// prepare the data and do an api call
			$weather_data = array (
				'today_icon' => '',
				'today_icon_text' => '',
				'today_temp' => '',
				'today_humidity' => '',
				'today_wind_speed' => '',
				'today_wind_direction' => '',
				'today_min' => '',
				'today_max' => '',
				'forecast' => array()
			);
			$today_api_data = self::owm_get_today_data($atts, $weather_data);
			$forecast_api_data = self::owm_get_five_days_data($atts, $weather_data);


			// check the api call response
			if ($today_api_data !== true or $forecast_api_data !== true) {
				// we have an error on one of the apis
				$weather_data = td_remote_cache::get(__CLASS__, $cache_key);

				if ($weather_data === false) {
					// miss and io error... shit / die
					td_remote_cache::extend(__CLASS__, $cache_key, self::$caching_time);
					self::error('Weather API error: ' . $today_api_data . ' ' . $forecast_api_data);
				}
				td_remote_cache::set(__CLASS__, $cache_key, $weather_data, self::$caching_time); //we have a reply and we set it
			}

			td_remote_cache::set(__CLASS__, $cache_key, $weather_data, self::$caching_time); //we have a reply and we set it

		} else {
			// cache is valid
			$weather_data = td_remote_cache::get(__CLASS__, $cache_key);
		}


		return $weather_data;
//		ob_start();
//		print_r($weather_data);
//		return ob_get_clean();

	}


	static function owm_get_today_data($atts, &$weather_data) {
		$today_weather_url = 'http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($atts['w_location']) . '&lang=' . $atts['w_language'] . '&units=' . $atts['w_units'];
		$json_api_response = td_remote_http::get_page($today_weather_url, __CLASS__);

		// fail
		if ($json_api_response === false) {
			return 'Error getting remote data for today forecast. Please check your server configuration';
		}

		// try to decode the json
		$api_response = @json_decode($json_api_response, true);
		if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
			return 'Error decoding the json from OpenWeatherMap';
		}

		// min max current temperature
		if (isset($api_response['main']['temp'])) {
			$weather_data['today_temp'] = $api_response['main']['temp'];
		}
		if (isset($api_response['main']['temp_min'])) {
			$weather_data['today_min'] = $api_response['main']['temp_min'];
		}
		if (isset($api_response['main']['temp_max'])) {
			$weather_data['today_max'] = $api_response['main']['temp_max'];
		}


		// humidity
		if (isset($api_response['main']['humidity'])) {
			$weather_data['today_humidity'] = $api_response['main']['humidity'];
		}


		// wind speed and direction
		if (isset($api_response['wind']['speed'])) {
			if ($atts['w_units'] == 'metric') {
				$wind_speed_text = 'km/h';
			} else {
				$wind_speed_text = 'mph';
			}

			$weather_data['today_wind_speed'] = $api_response['wind']['speed'];
		}

		if (isset($api_response['wind']['deg'])) { // may be missing
			$wind_directions = array ('N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW');
			$weather_data['today_wind_direction'] = $wind_directions[fmod((($api_response['wind']['deg'] + 11) / 22.5), 16)];
		}


		// forecast description
		if (isset($api_response['weather'][0]['description'])) {
			$weather_data['today_icon_text'] = $api_response['weather'][0]['description'];
		}

		// icon
		if (isset($api_response['weather'][0]['icon'])) {
			$icons = array (
				// day
				'01d' => 'clear-sky-d',
				'02d' => 'few-clouds-d',
				'03d' => 'scattered-clouds-d',
				'04d' => 'broken-clouds-d',
				'09d' => 'shower-rain-d',   // ploaie hardcore
				'10d' => 'rain-d',          // ploaie light
				'11d' => 'thunderstorm-d',
				'13d' => 'snow-d',
				'50d' => 'mist-d',

				//night
				'01n' => 'clear-sky-n',
				'02n' => 'few-clouds-n',
				'03n' => 'scattered-clouds-n',
				'04n' => 'broken-clouds-n',
				'09n' => 'shower-rain-n',   // ploaie hardcore
				'10n' => 'rain-n',          // ploaie light
				'11n' => 'thunderstorm-n',
				'13n' => 'snow-n',
				'50n' => 'mist-n',
			);

			$weather_data['today_icon'] = 'clear-sky-d'; // the default icon :) if we get an error or strange icons as a reply
			if (isset($icons[$api_response['weather'][0]['icon']])) {
				$weather_data['today_icon'] = $icons[$api_response['weather'][0]['icon']];
			}
		}

		return true;
	}


	private static function owm_get_five_days_data ($atts, &$weather_data) {
		$today_weather_url = 'http://api.openweathermap.org/data/2.5/forecast/daily?q=' . urlencode($atts['w_location']) . '&lang=' . $atts['w_language'] . '&units=' . $atts['w_units'] . '&cnt=7';
		$json_api_response = td_remote_http::get_page($today_weather_url, __CLASS__);


		// fail
		if ($json_api_response === false) {
			return 'Error getting remote data for 5 days forecast. Please check your server configuration';
		}

		// try to decode the json
		$api_response = @json_decode($json_api_response, true);
		if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
			return 'Error decoding the json from OpenWeatherMap';
		}

		$today_date = date( 'Ymd', current_time( 'timestamp', 0 ) );


		if (!empty($api_response['list']) and is_array($api_response['list'])) {
			foreach ($api_response['list'] as $index => $day_forecast) {
				if (
					!empty($day_forecast['dt'])
					and !empty($day_forecast['temp']['day'])
					and $today_date < date('Ymd', $day_forecast['dt'])
				) {
					$weather_data['forecast'][] = array (
						'timestamp' => $day_forecast['dt'],
						//'timestamp_readable' => date('Ymd', $day_forecast['dt']),
						'day_temp' => $day_forecast['temp']['day'],
						'day_name' => date_i18n('D', $day_forecast['dt'])
					);
				}
				if ($index > 4) {
					break;
				}
			}
		}
		return true;
	}







	private static function error($msg) {
		if (is_user_logged_in()) {
			return $msg;
		}
		return '';
	}
}