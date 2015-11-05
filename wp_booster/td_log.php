<?php
/**
 * Log class, used by the theme. Is loaded only when needed.
 * The key used to store the log is: TD_THEME_OPTIONS_NAME . '_log'  (ex: td_011_log)
 */
class td_log {
	private static $log_cache = array();
	private static $is_shutdown_hooked = false;


	//


	static function log($file, $function, $msg, $more_data = '') {

		// read the cache from db if needed
		if (empty(self::$log_cache)) {
			self::$log_cache = get_option(TD_THEME_OPTIONS_NAME . '_log');
		}

		// limit the log size
		if (count(self::$log_cache) > 20) {
			array_shift(self::$log_cache); //remove first element
		}

		self::$log_cache []= array(
			'file' => $file,
			'function' => $function,
			'msg' => $msg,
			'more_data' => $more_data,
			'timestamp' => time()  //date('j/n/Y G:i:s')
		);

		// make sure that we hook only once
		if (self::$is_shutdown_hooked === false) {
			add_action('shutdown', array(__CLASS__, 'on_shutdown_save_log'));
			self::$is_shutdown_hooked = true;
		}

	}




	// save the log if needed
	static function on_shutdown_save_log() {
		update_option(TD_THEME_OPTIONS_NAME . '_log', self::$log_cache);
	}

}