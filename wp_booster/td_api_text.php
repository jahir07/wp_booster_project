<?php
/**
 * Created by ra.
 * Date: 8/18/2016
 */


class td_api_text {
	private static $text_keys = array (

		// the text for wp-admin -> new post -> featured video box. Usually is the text that tells what post templates support video
		'text_featured_video' => '',

		// admin panel - header
		'text_header_logo' => '',
		'text_header_logo_description' => '',
		'text_header_logo_mobile' => '',
		'text_header_logo_mobile_image' => '',
		'text_header_logo_mobile_image_retina' => '',

		// what widgets do not work on the smart sidebar
		'text_smart_sidebar_widget_support' => '',


		//@todo in 012 sa fie overwritten
		'welcome_fast_start' => 'Install visual composer plugin and also install the social counter plugin if you want to add the counters on your sidebar - from our <a href="admin.php?page=td_theme_plugins">plugins panel</a>'
	);


	static function set($text_key, $text) {
		if (!isset(self::$text_keys[$text_key])) {
			td_util::error(__FILE__, 'td_api_text::set This text key: ' . $text_key . ' is not defined in td_api_text.php');
		}
		self::$text_keys[$text_key] = $text;
	}


	static function get($text_key) {
		if (!isset(self::$text_keys[$text_key])) {
			td_util::error(__FILE__, 'td_api_text::set This text key: ' . $text_key . ' is not defined in td_api_text.php');
		}

		return self::$text_keys[$text_key];
	}
}