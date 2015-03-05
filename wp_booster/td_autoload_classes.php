<?php
/**
 * Date: 24.12.2014
 * Time: 11:41
 *
 * It's used to auto load a class by its name
 * The class is designed to register spl_autoload_register function per settings [path - class name]
 * The class can be used just when it's instantiated. The params of the constructor is the theme path and the a regex start class name
 * The path is where spl_autoload_register is looking for when a new unregistered class is used
 *
 * The class uses td_global::$plugin_list to check all registered plugins
 * Depending of the plugin settings, the spl_autoload_register functions are registered to check the plugins' path before or after the theme path
 * Default, the paths of a plugin without such settings are checked after. This feature can be used to overwrite classes by plugins
 */

class td_autoload_classes {

	private static $arr_path_regex = array('td');

	/**
	 * Be sure to uniquely call td_autoload_classes.init function to ensure a unique spl_autoload_register.
	 */
	public static function init() {

		spl_autoload_register(array('td_autoload_classes', 'loading_classes'));
	}

	/**
	 * The callback function used by spl_autoload_register
	 * @param $class_name The class name
	 */
	private static function loading_classes($class_name) {

		foreach (self::$arr_path_regex as $path_regex) {

			// foreach regex path, the class name is verified for a start matching
			if ((strpos($class_name, $path_regex) !== false) and (strpos($class_name, $path_regex) === 0)) {
				$class_settings = td_api_base::get_by_id($class_name);

				if (isset($class_settings) and !empty($class_settings)) {
					if (array_key_exists('file', $class_settings)) {
						$class_file_path = $class_settings['file'];

						if (isset($class_file_path) and !empty($class_file_path)) {
							load_template($class_file_path, true);
						}
					} else {
						td_util::error(__FILE__, "Missing parameter: 'file'");
					}
				}
			}
		}
	}
}

td_autoload_classes::init();
