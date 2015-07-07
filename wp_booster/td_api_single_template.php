<?php
/**
 * Created by ra on 2/13/2015.
 */


class td_api_single_template extends td_api_base {

    /**
     * This method to register a new single template
     *
     * @param $id string The single template id. It must be unique
     * @param $params_array array The single_template_parameter array
     *
     *      $params_array = array (
     *          'file' => '',                               - [string] the path to the template file
     *          'text' => '',                               - [string] name text used in the theme panel
     *          'img' => '',                                - [string] the path to the image icon
     *          'show_featured_image_on_all_pages' => ''    - [boolean]
     *      )
     *
     * @throws ErrorException new exception, fatal error if the $id already exists
     */
    static function add($single_template_id, $params_array = '') {
        parent::add_component(__CLASS__, $single_template_id, $params_array);
    }

	static function update($single_template_id, $params_array = '') {
		parent::update_component(__CLASS__, $single_template_id, $params_array);
	}

    static function get_all() {
        return parent::get_all_components_metadata(__CLASS__);
    }

    /**
     * checks the show_featured_image_on_all_pages for a template
     *
     * @internal
     * @param $single_template_id
     * @return bool true if we have to show the featured image on all pages
     */
    static function _check_show_featured_image_on_all_pages($single_template_id) {
        // on the default template, hide the featured image on page 2
        if (empty($single_template_id)) {  //$single_template_id is empty if we're on the default template
            return false;
        }

        // check the show_featured_image_on_all_pages key of each template
        if (self::get_key($single_template_id, 'show_featured_image_on_all_pages') === false) {
            return false;
        }

        return true;
    }


    /**
     *  returns all the single post templates in a format that is usable for the panel
     *
     *  @internal
     *  @return array
     *
     *      array(
     *          array('text' => '', 'title' => '', 'val' => 'single_template_6', 'img' => get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/post-templates/post-templates-icons-6.png'),
     *      )
     */
    static function _helper_td_global_list_to_panel_values() {
        $buffy_array = array();
        foreach (self::get_all() as $template_value => $template_config) {
	        $buffy_array[] = array(
                'text' => '',
                'title' => '',
                'val' => $template_value,
                'img' => $template_config['img']
            );
        }

	    array_unshift (
		    $buffy_array,
		    array(
			    'text' => '',
			    'title' => '',
			    'val' => '',
			    'img' => td_global::$get_template_directory_uri . '/images/panel/single_templates/single_template_default.png'
		    )
	    );

	    return $buffy_array;
    }


	static function _helper_td_global_list_to_metabox_values() {
		$buffy_array = array();
		foreach (self::get_all() as $template_value => $template_config) {
			$buffy_array[] = array(
				'text' => '',
				'title' => '',
				'val' => $template_value,
				'img' => $template_config['img']
			);
		}

		array_unshift (
			$buffy_array,
			array(
				'text' => '',
				'title' => '',
				'val' => 'single_template_default',
				'img' => td_global::$get_template_directory_uri . '/images/panel/single_templates/single_template_default.png'
			)
		);

		array_unshift (
			$buffy_array,
			array(
				'text' => '',
				'title' => '',
				'val' => '',
				'img' => td_global::$get_template_directory_uri . '/images/panel/single_templates/single_template_0.png'
			)
		);

		return $buffy_array;
	}


    /**
     * shows a single template (echos it). NOTE: it also loads the WordPress globals in that template!
     *
     * @internal
     * @param $template_id
     */
    static function _helper_show_single_template($template_id) {
        $template_path = '';

        // try to get the key from the api
        try {
            $template_path = self::get_key($template_id, 'file');
        } catch (ErrorException $ex) {
            td_util::error(__FILE__, "The template $template_id isn't set. Did you disable a tagDiv plugin?"); // this does not stop execution
        }


        // load the template
        if (!empty($template_path) and file_exists($template_path)) {
            load_template($template_path);
        } else {
            td_util::error(__FILE__, "The path $template_path of the $template_id template not found. Did you disable a tagDiv plugin?");  // this does not stop execution
        }


    }
}



