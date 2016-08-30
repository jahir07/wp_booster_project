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


		//@todo in 012 sa fie overwritten
		'welcome_fast_start' => 'Install visual composer plugin and also install the social counter plugin if you want to add the counters on your sidebar - from our <a href="admin.php?page=td_theme_plugins">plugins panel</a>',

		'welcome_support_forum' => '
			<h2>Support forum</h2>
            <p>We offer outstanding support through our forum. To get support first you need to register (create an account) and open a thread in the ' . TD_THEME_NAME . ' Section.</p>
            <a class="button button-primary" href="#" target="_blank">Open forum</a>',

		'welcome_docs' => '
			<h2>Docs and learning</h2>
            <p>Our online documentation will give you important information about the theme. This is a exceptional resource to start discovering the theme?s true potential.</p>
            <a class="button button-primary" href="#" target="_blank">Open documentation</a>',

		'welcome_video_tutorials' => '
			<h2>Video tutorials</h2>
            <p>We believe that the easiest way to learn is watching a video tutorial. We have a growing library of narrated video tutorials to help you do just that.</p>
            <a class="button button-primary" href="#" target="_blank">View tutorials</a>',

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