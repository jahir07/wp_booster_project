<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 26.09.2016
 * Time: 16:45
 */

class td_api_ad extends td_api_base {

	static function add($ad_id, $params_array = '') {
		parent::add_component(__CLASS__, $ad_id, $params_array);
	}


	static function update($ad_id, $params_array = '') {
		parent::update_component(__CLASS__, $ad_id, $params_array);
	}


	static function get_all() {
		return parent::get_all_components_metadata(__CLASS__);
	}

	static function helper_display_ads() {

		foreach (td_api_ad::get_all() as $ad_id => $ad_array) {
			$ad_type = self::get_key($ad_id, 'ad_type');
			$ad_text = self::get_key($ad_id, 'text');

			switch ($ad_type) {

//					case 'inline':
//
//						td_global::$current_ad_id = $ad_id;
//
//						echo td_panel_generator::box_start($ad_text, false);
//						load_template(dirname( __FILE__ ) . '/wp-admin/panel/views/ajax_boxes/td_panel_ads/td_get_ad_spot_by_id.php', false);
//						echo td_panel_generator::box_end();
//
//						break;

				case 'ajax':

					echo td_panel_generator::ajax_box($ad_text, array(
							'td_ajax_calling_file' => 'td_panel_ads',
							'td_ajax_box_id' => 'td_get_ad_spot_by_id',
							'ad_spot_id' => $ad_id
						)
					);

				break;
			}
		}
	}
}