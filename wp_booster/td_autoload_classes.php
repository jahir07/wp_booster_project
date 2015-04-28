<?php

class td_autoload_classes {


    /**
     * register the spl hook
     */
	public function __construct() {
		spl_autoload_register(array($this, 'loading_classes'));
	}

	/**
	 * The callback function used by spl_autoload_register
	 * @param $class_name string - The class name
	 */
	private function loading_classes($class_name) {
        $path_regex = 'td';

        // foreach regex path, the class name is verified for a start matching
        if ((strpos($class_name, $path_regex) !== false) and (strpos($class_name, $path_regex) === 0)) {

            $class_settings = td_api_base::get_by_id($class_name);

            if (!empty($class_settings)) {
                if (array_key_exists('file', $class_settings)) {
                    $class_file_path = $class_settings['file'];

                    if (isset($class_file_path) and !empty($class_file_path)) {
                        //@todo - verificat daca schimbarea asta e ok. Se pierd globalele, ca si arhitectura e mai ok fara globale
                        require_once($class_file_path);
                    }
                } else {
                    td_util::error(__FILE__, "Missing parameter: 'file'");
                }
            }
        }
	}
}

new td_autoload_classes();
