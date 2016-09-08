<?php
/**
 * Created by ra.
 * Date: 8/18/2016
 */

class td_api_features {
	private static $features = array (
		'require_activation' => true,
		'require_vc' => true,
		'page_mega_menu' => true,
		'video_playlists' => true,
		'tagdiv_slide_gallery' => true
	);


	/**
	 * @param $feature string feature name
	 * @param $new_state boolean new feature state
	 */
	static function set ($feature, $new_state) {
		if (!isset(self::$features[$feature])) {
			td_util::error(__FILE__, 'td_api_features::set This feature: ' . $feature . ' is not defined in td_api_features.php');
		}

		if (!is_bool($new_state)) {
			td_util::error(__FILE__, 'td_api_features::set This feature: ' . $feature . ' was not set to a boolean value');
		}

		self::$features[$feature] = $new_state;
	}

	static function is_enabled ($feature) {
		if (!isset(self::$features[$feature])) {
			td_util::error(__FILE__, 'td_api_features::is_enabled This feature: ' . $feature . ' is not defined');
		}

		return self::$features[$feature];
	}


}