<?php
class td_api_base {


    // flag marked by get_by_id and get_key function. It's used just for debugging
    const USED_ON_PAGE = 'used_on_page';

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
            td_util::error(__FILE__, "td_api_base: $id already present in list");
        }

    }



    /**
     * This method gets the value set for ($class_name) in the main settings array (self::$component_list)
     * This method does not set the self::USED_ON_PAGE flag, as self::get_by_id or self::get_key method does
     * @param $class_name string The array key in the self::$component_list
     * @return mixed The value of the self::$component_list[$class_name]
     */
    static function get_all_components($class_name) {
        $final_array = array();

        foreach (self::$components_list as $component_key => $component_value) {
            if (isset($component_value[self::TYPE])
                and $component_value[self::TYPE] == $class_name) {

                $final_array[$component_key] = $component_value;
            }
        }
        return $final_array;
    }



    /**
     * returns the default component for a particular class. As of now, the default component is the first one that was added
     * we usually use this value when there is no setting in the database
     * Note: it marks the component as used on page
     *
     * @param $class_name
     * @return mixed
     * @throws ErrorException
     */
    private static function get_default_component($class_name) {
        foreach (self::$components_list as $component_id => $component_value) {

            if (isset($component_value[self::TYPE])
                and $component_value[self::TYPE] == $class_name) {

                self::mark_used_on_page($component_id);
                return $component_value;
            }

        }
        td_util::error(__FILE__, "td_api_base::get_default  : no component of type $class_name");
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
        $component = self::get_default_component($class_name);
        return $component[$key];
    }


    protected static function get_default_component_id($class_name) {
        foreach (self::$components_list as $component_id => $component_value) {

            if (isset($component_value[self::TYPE])
                and $component_value[self::TYPE] == $class_name) {

                self::mark_used_on_page($component_id);
                return $component_id;
            }

        }
        td_util::error(__FILE__, "td_api_base::get_default_component_id  : no component of type $class_name");
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
        return self::$components_list[$id][$key];
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
     *
     * @return array with all theme settings
     */
    static function debug_get_components_list() {
        return self::$components_list;
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
            td_util::error(__FILE__, "td_api_base::mark_used_on_page :  a component with the ID: $id is not set.");
        }
        self::$components_list[$id][self::USED_ON_PAGE] = true;
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
        if (array_key_exists(self::USED_ON_PAGE, self::$components_list[$id])) {
            td_util::error(__FILE__, "td_api_base::check_used_on_page: You requested a $requested_operation for ID: $id BUT it's already used on page. This usually means that you are using a wrong hook - you are trying to modify the component after it already rendered / was used.");
        }
    }

}