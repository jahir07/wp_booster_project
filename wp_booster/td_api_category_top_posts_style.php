<?php
/**
 * Created by ra on 2/13/2015.
 */



/**
 * The theme's category toop loop style api, usable via the td_global_after hook
 * Class td_api_category_top_posts_style
 */
class td_api_category_top_posts_style extends td_api_base {

    /**
     * This method to register a new category template
     *
     * @param $id string The category template id. It must be unique
     * @param $params_array array The category template array
     *
     *      $params_array = array(
     *          'file' => '',           - Where we can find the file
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($id, $params_array) {

	    // put a default image if we don't have any image, useful when developing a new module
	    if (empty($params_array['img'])) {
		    $params_array['img'] = td_global::$get_template_directory_uri . '/includes/wp_booster/wp-admin/images/panel/panel-placeholders/no_module_image.png';
	    }

        parent::add_component(__CLASS__, $id, $params_array);
    }


	static function update($id, $params_array) {
		parent::update_component(__CLASS__, $id, $params_array);
	}


	static function get_all($group = '') {
		$components = parent::get_all_components_metadata(__CLASS__);
		foreach ($components as $component_key => $component_value) {
			if (array_key_exists('group', $component_value) && $component_value['group'] !== $group) {
				unset($components[$component_key]);
			}
		}
		return $components;
	}



    static function _helper_get_active_id() {

	    $tdc_option_key = 'tdc_category_top_posts_style';
	    $tds_option_key = 'tds_category_top_posts_style';

	    $template_id = td_util::get_category_option(td_global::$current_category_obj->cat_ID, $tdc_option_key); // first check the category setting

        if (empty($template_id)) { // now check the global setting - we have "default" selected on the category, the theme now looks for the global setting
            $template_id = td_util::get_option($tds_option_key);
        }

        if (empty($template_id)) { // nothing is set, check the default value
            $template_id = parent::get_default_component_id(__CLASS__);
        }

        return $template_id;
    }


    /**
     * get the category template, this function has to look at the global theme setting and at the category setting
     */
    static function _helper_show_category_top_posts_style() {
        $template_id = self::_helper_get_active_id();

        if (class_exists($template_id)) {

            /** @var $td_category_template td_category_top_posts_style */
            $td_category_template = new $template_id();
            $td_category_template->show_top_posts();
        } else {
            td_util::error(__FILE__, "The category template $template_id doesn't exist. Did you disable a tagDiv plugin?");
        }

    }


    static function _helper_get_posts_shown_in_the_loop() {

        $template_id = self::_helper_get_active_id(); //@todo we may need a 'better' error checking here
        return parent::get_key($template_id, 'posts_shown_in_the_loop');
    }



    static function _helper_to_panel_values($view_name = 'get_all') {
        $buffy_array = array();

        switch ($view_name) {
            case 'default+get_all':

                //add default style
                $buffy_array[] = array(
                    'text' => 'Default',
                    'title' => 'This category will use the site wide category top post style setting.',
                    'val' => '',
                    'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/module-default.png'
                );

                // add the rest
                foreach (self::get_all() as $id => $config) {
                    $buffy_array[] = array(
                        'text' => $config['text'],
                        'title' => self::_display_file_path($id),
                        'val' => $id,
                        'img' => $config['img']
                    );
                }
                break;

            case 'get_all':

                //get all the top post styles, the first one is with an empty value
                foreach (self::get_all() as $id => $config) {
                    $buffy_array[] = array(
                        'text' => $config['text'],
                        'title' => self::_display_file_path($id),
                        'val' => $id,
                        'img' => $config['img']
                    );
                }

                // the first template is the default one, ex: it has no value in the database
                $buffy_array[0]['val'] = '';
                break;
        }

        return $buffy_array;
    }
}






