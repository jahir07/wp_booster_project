<?php
/**
 * Created by ra on 9/22/2016.
 */


class td_options {

	/**
	 * @var bool flag used to hook the shutdown action only once
	 */
	private static $is_shutdown_hooked = false;

	/**
	 * @var null keep a local copy of all the settings
	 */
	static $td_options = NULL ;



	static function get($optionName, $default_value = '') {

		self::read_from_db();

		if (!empty(self::$td_options[$optionName])) {
			return self::$td_options[$optionName];
		} else {
			if (!empty($default_value)) {
				return $default_value;
			} else {
				return '';
			}
		}

	}

	static function update($optionName, $newValue) {
		self::$td_options[$optionName] = $newValue;
		self::schedule_save();
	}




	/**
	 * This method is used to port the OLD global reading and updating to this new class so we don't have to refactor all the code at once.
	 * @deprecated It's marked as deprecated to discourage future uses
	 * @return mixed
	 */
	static function &update_by_ref() {
		self::read_from_db();
		self::schedule_save();
		return self::$td_options;
	}

	/**
	 *
	 * @return mixed
	 */
	static function get_all() {
		self::read_from_db();
		return self::$td_options;
	}


	/**
	 * read the setting from db only once
	 */
	static private function read_from_db() {
		if (is_null(self::$td_options)) {
			self::$td_options = get_option(TD_THEME_OPTIONS_NAME);
		}
	}

	/**
	 *
	 */
	static private function schedule_save() {
		// make sure that we hook only once
		if (self::$is_shutdown_hooked === false) {
			add_action('shutdown', array(__CLASS__, 'on_shutdown_save_options'));
			self::$is_shutdown_hooked = true;
		}
	}


	/**
	 * @internal
	 * save the options hook
	 */
	static function on_shutdown_save_options() {
		update_option( TD_THEME_OPTIONS_NAME, self::$td_options );
	}

}