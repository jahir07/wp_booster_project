<?php
class td_api_base {


    // flag marked by get_by_id and get_key function. It's used just for debugging
    const USED_ON_PAGE = 'used_on_page';

    const CLASS_AUTOLOADED = 'class_autoloaded'; // flag for marking autoloaded classes

    const TYPE = 'type';

    // the main array settings
    private static $components_list = array();



    /**
     * This method adds settings in the main settings array (self::$component_list)
     * An array of settings is set for the ($class_name, $id) key.
     * If there already exists the ($class_name, $id) key in the main settings array, an error exception is thrown. The update
     * method must be used instead, which ensures the settings are not previously loaded using self::get_by_id or self::get_key
     * method.
     *
     * @param $class_name string The array key in the self::$component_list
     * @param $id string string The array key in the self::$component_list[$class_name]
     * @param $params_array array The value set for the self::$component_list[$class_name][$id]
     * @throws ErrorException The exception thrown if the self::$component_list[$class_name][$id] is already set
     */
    protected static function add_component($class_name, $id, $params_array) {
        if (!isset(self::$components_list[$id])) {

            $params_array[self::TYPE] = $class_name;
	        self::$components_list[$id] = $params_array;

        } else {
            td_util::error(__FILE__, "td_api_base: A component with the ID: $id it's already registered in td_api_base", self::$components_list[$id]);
        }
    }



    /**
     * This method gets the value set for ($class_name) in the main settings array (self::$component_list)
     * This method does not set the self::USED_ON_PAGE flag, as self::get_by_id or self::get_key method does
     *
     * Important! As the flag self::USED_ON_PAGE is not marked, the 'file' parameter is removed to ensure that nobody can use (require) the component
     *
     * @param $class_name string The array key in the self::$component_list
     * @return mixed The value of the self::$component_list[$class_name]
     */
    static function get_all_components_metadata($class_name) {
        $final_array = array();

        foreach (self::$components_list as $component_key => $component_value) {
            if (isset($component_value[self::TYPE])
                and $component_value[self::TYPE] == $class_name) {

            	// to get the actual final file :) you need to use get_key 'file'. This is because a child theme can overwrite the file
	            if (isset($component_value['file'])) {
		            unset($component_value['file']);
	            }

                $final_array[$component_key] = $component_value;
            }
        }
        return $final_array;
    }



    /**
     * returns the default component key value for a particular class. As of now, the default component is the first one that was added
     * we usually use this value when there is no setting in the database
     * Note: it marks the component as used on page
     *
     * @param $class_name
     * @param $key
     * @return mixed
     * @throws ErrorException
     */
    protected static function get_default_component_key($class_name, $key) {
	    foreach (self::$components_list as $component_id => $component_value) {

		    if (isset($component_value[self::TYPE])
		        and $component_value[self::TYPE] == $class_name) {

			    self::mark_used_on_page($component_id);

			    if ($key == 'file') {
				    self::locate_the_file($component_id);
			    }
			    return $component_value[$key];
		    }
	    }
	    td_util::error(__FILE__, "td_api_base::get_default_component_key : no component of type $class_name . Wp booster tried to get
        the default component (the first registered component) but there are no components registered.");
    }



	/**
	 * - returns the id of the default component for a particular class.
	 *
	 * @param $class_name - the class name of the component @see self::$components_list
	 *
	 * @return int|string - the id of the component @see self::$components_list
	 */
    protected static function get_default_component_id($class_name) {
        foreach (self::$components_list as $component_id => $component_value) {

            if (isset($component_value[self::TYPE])
                and $component_value[self::TYPE] == $class_name) {

                self::mark_used_on_page($component_id);
                return $component_id;
            }
        }
        td_util::error(__FILE__, "td_api_base::get_default_component_id  : no component of type $class_name . Wp booster tried to get
        the default component (the first registered component) but there are no components registered.");
    }



    /**
     * This method gets the value set for ($class_name, $id) in the main settings array (self::$component_list)
     * The self::USED_ON_PAGE flag is set accordingly, as updating and deleting operations using the same ($class_name, $id, $key) key
     * know about it and do not fulfill operations.
     * Updating or deleting must be done prior of this method or self::get_key method usage.
     *
     * @param $class_name string The array key in the self::$component_list
     * @param $id string string The array key in the self::$component_list[$class_name]
     * @return mixed The value of the self::$component_list[$class_name][$id]
     */
    static function get_by_id($id) {
        self::mark_used_on_page($id);
	    self::locate_the_file($id);
        return self::$components_list[$id];
    }



    /**
     * This method gets the value set for the ($class_name, $id, $key) key in the main array settings (self::$component_list)
     * The self::USED_ON_PAGE flag is set accordingly, as updating and deleting operations using the same ($class_name, $id, $key) key
     * know about it and do not fulfill operations.
     * Updating or deleting must be done prior of this method or self::get_key method usage.
     *
     * @param $class_name string The array key in the self::$component_list
     * @param $id string The array key in the self::$component_list[$class_name]
     * @param $key string The array key in the self::$component_list[$class_name][$id]
     * @return mixed mixed The value of the self::$component_list[$class_name][$id][$key]
     * @throws ErrorException The error exception thrown by check_used_on_page method call
     */
    static function get_key($id, $key) {
        self::mark_used_on_page($id);

	    if ($key == 'file') {
		    self::locate_the_file($id);
	    }

	    return self::$components_list[$id][$key];
    }


	/**
	 * @internal Use only for display-ing the file path of a component by id. It's used all over the panel to show a nice
	 * path for a component
	 * @param $id
	 * @return mixed
	 */
    static function _display_file_path($id) {
       return 'file path: ' . str_replace(td_global::$get_template_directory, '', self::get_key($id, 'file'));
    }


    /**
     * This method update the value for ($class_name, $id) in the main array settings (self::$component_list)
     * Updating and deleting a key value in the main settings array ensures that the value of the key is not already loaded by the theme.
     * Loaded by the theme means that is's used to set or to build some components.
     * So, the $id and the $key parameter must no be used previously by self::get_by_id or by self::get_key
     * method, otherwise it means that the settings are already loaded to build a component, and an error exception is thrown
     * informing the end user about it.
     *
     * @param $class_name string The array key in the self::$component_list
     * @param $id string The array key in the self::$component_list[$class_name]
     * @param $params_array array The array value set for the self::$component_list[$class_name][$id]
     * @throws ErrorException The error exception thrown by check_used_on_page method call
     */
    static function update_component($class_name, $id, $params_array) {
        self::check_used_on_page($id, 'update');
	    $params_array[self::TYPE] = $class_name;
        self::$components_list[$id] = $params_array;
    }



    /**
     * This method updates the value for the ($class_name, $id, $key) key in the main settings array (self::$component_list).
     * Updating and deleting a key value in the main settings array ensures that the value of the key is not already loaded by the theme.
     * Loaded by the theme means that is's used to set or to build some components.
     * So, the $id and the $key parameter must no be used previously by self::get_by_id or by self::get_key
     * method, otherwise it means that the settings are already loaded to build a component, and an error exception is thrown
     * informing the end user about it.
     *
     * @param $class_name string The array key in self::$component_list
     * @param $id string The array key in the self::$component_list[$class_name]
     * @param $key string The array key in the self::$component_list[$class_name][$id]
     * @param $value mixed The value set for the specified $key
     * @throws ErrorException The error exception thrown by check_used_on_page method call
     */
    static function update_key($id, $key, $value) {
        self::check_used_on_page($id, 'update_key');
        self::$components_list[$id][$key] = $value;
    }



    /**
     * This method unset value for the ($class_name, $id) key in the main settings array (self::$component_list).
     * Updating and deleting a key value in the main settings array ensures that the value of the key is not already loaded by the theme.
     * Loaded by the theme means that is's used to set or to build some components.
     * So, the $id and the $key parameter must no be used previously by self::get_by_id or by self::get_key
     * method, otherwise it means that the settings are already loaded to build a component, and an error exception is thrown
     * informing the end user about it.
     *
     * @param $class_name string The array key in self::$component_list
     * @param $id string The array key in the self::$component_list[$class_name]
     * @throws ErrorException The error exception thrown by check_used_on_page method call
     */
    static function delete($id) {
        self::check_used_on_page($id, 'delete');
        unset(self::$components_list[$id]);
    }



    /**
     * This is an internal function used just for debugging
     * @internal
     * @return array with all theme settings
     */
    static function _debug_get_components_list() {
        return self::$components_list;
    }




    /**
     * returns only the used on page component - useful for debug
     * @return array
     */
    static function _debug_show_autoloaded_components() {
        $buffy_array = array();
        foreach (self::$components_list as $component_id => $component) {

            if (isset($component[self::CLASS_AUTOLOADED]) and $component[self::CLASS_AUTOLOADED] === true) {
                $buffy_array [$component_id]= $component;
            }
        }


        ob_start();
        ?>

	    <script>
		    console.log('_debug_show_autoloaded_components is called');
            <?php
            foreach ($buffy_array as $component_id => $component) {
	            ?>
			    console.log(<?php echo json_encode(str_pad($component_id, 20)) ?>);
	            <?php
            }
            ?>
	    </script>

        <?php
        echo ob_get_clean();



        return $buffy_array;
    }



    /**
     * sets the component's td_api_base::CLASS_AUTOLOADED key to true at runtime.
     * @param $component_id
     */
    static function _debug_set_class_is_autoloaded($component_id) {
        self::$components_list[$component_id][td_api_base::CLASS_AUTOLOADED] = true;
    }







    /**
     * This method sets the self::USED_ON_PAGE flag for the ($class_name, $id) key.
     * It's used by the get_by_id and get_key methods to mark settings as being loaded on page.
     * The main purpose of using this flag is for debugging the loaded components.
     *
     * @param $class_name string The array key in self::$component_list
     * @param $id string The array key in the self::$component_list[$class_name]
     * @throws ErrorException The error thrown when the ($class_name, id) key is not already set
     */
    private static function mark_used_on_page($id) {
        if (!isset(self::$components_list[$id])) {


            /**
             * @deprecated @todo should be removed in v2  compatiblity for social counter old old
             */

            if (($id == 'td_social_counter' or $id == 'td_block_social_counter')) {
                if (is_user_logged_in()) {
                    td_util::error('', "Please update your [tagDiv social counter] Plugin!");
                }
                return;
            }

            /**
             * show a soft error if
             * - the user is logged in
             * - the user is on the login page / register
             * - the user tries to log in via wp-admin (that is why is_admin() is required)
             */
            td_util::error(__FILE__, "td_api_base::mark_used_on_page : a component with the ID: $id is not set.");
        }
        self::$components_list[$id][self::USED_ON_PAGE] = true;
    }


	/**
	 * - it replaces the theme path with the child path, if the file api registered exists in the child theme
	 * - it tries to find the file in the child theme and if it's found, the 'located_in_child' is set
	 * - the check is done only when the child theme is activated and the 'located_in_child' hasn't set yet
	 * - the check is done only for the theme registered paths (those having TEMPLATEPATH), letting the plugins to register themselves paths
	 *
	 * @param string $id - the id of the component
	 * @param string $component - the component value
	 */
	private static function locate_the_file($id = '') {
		if (!is_child_theme()) {
			return;
		}

		$the_component = null;

		if (!empty($id)) {
			$the_component = &self::$components_list[$id];
		}

		if (($the_component != null)
		    and !empty($the_component['file'])
            and (stripos($the_component['file'], TEMPLATEPATH) == 0)
		    and !isset($the_component['located_in_child'])) {

			$child_path = STYLESHEETPATH . str_replace(TEMPLATEPATH, '', $the_component['file']);

			if (file_exists($child_path)) {
				$the_component['file'] = $child_path;
			}
			$the_component['located_in_child'] = true;
		}
	}




    /**
     * This method check the self::USED_ON_PAGE flag for the ($class_name, $id) key and it throws an exception
     * if it's already set, that means the settings are already used to build a component in the user interface.
     *
     * @param $id string The array key in the self::$component_list[$class_name]
     * @param $requested_operation string (delete|update|update_key)
     * @internal param string $class_name The array key in self::$component_list
     */
    private static function check_used_on_page($id, $requested_operation) {
        if ( array_key_exists( $id, self::$components_list ) and array_key_exists( self::USED_ON_PAGE, self::$components_list[$id] ) ) {
            td_util::error(__FILE__, "td_api_base::check_used_on_page: You requested a $requested_operation for ID: $id BUT it's already used on page. This usually means that you are using a wrong hook - you are trying to modify the component after it already rendered / was used.", self::$components_list[$id]);
        }
    }

}