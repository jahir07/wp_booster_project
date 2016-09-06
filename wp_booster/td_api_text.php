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
		'text_smart_sidebar_widget_support' => '<p>From here you can enable and disable the smart sidebar on all the templates. The smart sidebar is an affix (sticky) sidebar that has auto resize and it scrolls with the content. The smart sidebar reverts back to a normal sidebar on iOS (iPad) and on mobile devices. The following widgets are not supported in the smart sidebar:</p>',


		// fast start notification on welcome page
		// *overwritten in 012 for tagDiv composer
		'welcome_fast_start' => 'Install visual composer plugin and also install the social counter plugin if you want to add the counters on your sidebar - from our <a href="admin.php?page=td_theme_plugins">plugins panel</a>',

		// welcome and support panels
		'welcome_support_forum' => '',
		'welcome_docs' => '',
		'welcome_video_tutorials' => '',

		// supported plugins list
		'supported_plugins_list' => '
				<div class="td-supported-plugin">WP Super Cache <span> - caching plugin</span></div>
				<div class="td-supported-plugin">Contact form 7 <span>- used to make contact forms</span></div>
				<div class="td-supported-plugin">bbPress <span>- forum plugin</span></div>
				<div class="td-supported-plugin">BuddyPress<span>- social network plugin</span></div>
				<div class="td-supported-plugin">Font Awesome 4 Menus<span>- icon pack, supported in the theme menus</span></div>
				<div class="td-supported-plugin">Jetpack  <span>- plugin with lots of features *it may slow down your site</span></div>
				<div class="td-supported-plugin">WooCommerce <span>- eCommerce solution</span></div>
				<div class="td-supported-plugin">WordPress SEO <span> - SEO plugin</span></div>
				<div class="td-supported-plugin">Wp User Avatar <span> - Change users avatars</span></div>',

		// existing content documentation url
		// *overwritten in 012
		'panel_existing_content_url' => '<a href="http://forum.tagdiv.com/existing-content/" target="_blank">read more</a>'
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