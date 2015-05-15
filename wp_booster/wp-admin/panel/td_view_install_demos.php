<?php

require_once "td_view_header.php";



//$img_id = td_stacks_media::add_image_to_media_gallery('http://demo.tagdiv.com/newsmag/wp-content/uploads/2014/08/photo4.jpg', '');

/*
$cate_id = td_stacks_category::add_category('axra1');
td_stacks_category::add_category('axra2', $cate_id);
$cate_id2 = td_stacks_category::add_category('axra3', $cate_id);
td_stacks_category::add_category('axra4', $cate_id2);

td_stacks_category::remove();

die;

td_stacks_widgets::add_sidebar('td_demo_main');

td_stacks_widgets::add_widget_to_sidebar('td_demo_main', 'td_block_9_widget',
    array (
        'sort' => 'featured',
        'custom_title' => 'EDITOR PICKS',
        'limit' => '4',
        'header_color' => '#f24b4b'
    )
);



td_stacks_widgets::remove();
td_stacks_content::add_post(array(
    'title' => 'my_post',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_post(array(
    'title' => 'my_post2',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));

td_stacks_content::add_post(array(
    'title' => 'my_post3',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));

td_stacks_content::remove();



td_stacks_content::add_page(array(
    'title' => 'test page xx1',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => '',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_page(array(
    'title' => 'test page xx2',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => 'page-pagebuilder-title.php',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));


td_stacks_content::add_page(array(
    'title' => 'test page xx3',
    'file' => td_global::$get_template_directory . '/includes/stacks/default/pages/homepage.txt',
    'page_template' => '',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT))
));
td_stacks_content::remove();
die;

/*
td_stacks_menus::remove();

$td_stack_top_menu_id = td_stacks_menus::create_menu('td_stack_top', 'top-menu');

    $last_menu_item_id = td_stacks_menus::add_link($td_stack_top_menu_id, 'testing', '#');
    td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 1', '#', $last_menu_item_id);
        $last_menu_item_id_2 = td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 2', '#', $last_menu_item_id);
        td_stacks_menus::add_link($td_stack_top_menu_id, 'testing 3', '#', $last_menu_item_id_2);

*/



/**
 * this is the import theme style / stacks view
 */

$td_import_fonts_show_update_msg = false;

/**
 * holds the imported settings
 */
class td_style_imported_settings {




}

/*
if(!empty($_REQUEST['td_option'])) {


    switch ($_REQUEST['td_option']) {

        case 'factory_restore':
            $new_blank_options = array(
                'sidebars' => '',
                'td_ad_spots' => '',
                'firstInstall' => 'yes',
                'envato_key' => td_util::get_option('envato_key'),
                'td_cake_status_time' => td_util::get_option('td_cake_status_time'),
                'td_cake_status' => td_util::get_option('td_cake_status')
            );
            update_option(TD_THEME_OPTIONS_NAME, $new_blank_options);
            $td_import_fonts_show_update_msg = true;
            break;

        case 'default_style':
            foreach (td_style_imported_settings::$td_array_import_settings_from_file as $import_setting_from_file) {
                td_global::$td_options[$import_setting_from_file] = '';
            }

            //typography settings
            td_global::$td_options['td_fonts'] = '';

            //css font files (google) buffer
            td_global::$td_options['td_fonts_css_files'] = '';

            //compile user css if any
            td_global::$td_options['tds_user_compile_css'] = td_css_generator();

            update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);




            $td_import_fonts_show_update_msg = true;
            break;

        default:
            if (array_key_exists($_REQUEST['td_option'], td_global::$stacks_list)) {
                $td_import_fonts_show_update_msg = td_import_demo_style_fonts($_REQUEST['td_option']);
            }
            break;
    }

}
*/
//import demo style fonts only


?>

	<!--    <input type="hidden" name="action" value="td_ajax_update_panel">-->
	<!--    <div class="td_displaying_saving"></div>-->
	<!--    <div class="td_wrapper_saving_gifs">-->
	<!--        <img class="td_displaying_saving_gif" src="--><?php //echo get_template_directory_uri();?><!--/includes/wp_booster/wp-admin/images/panel/loading.gif">-->
	<!--        <img class="td_displaying_ok_gif" src="">-->
	<!--    </div>-->
<div class="td-admin-wrap theme-browser">
	<p>The footer uses sidebars to show information. Here you can customize the number of sidebars and the layout. To add content to the footer head go to the widgets section and drag widget to the Footer 1, Footer 2 and Footer 3 sidebars.</p>

	<div class="td-admin-columns">
		<?php foreach (td_global::$stacks_list as $stack_id => $stack_params) { ?>

			<div class="<?php echo $stack_id ?> td-wp-admin-stack theme">

				<!-- Import content -->
				<div class="theme-screenshot">
					<img src="<?php echo td_global::$stacks_list[$stack_id]['img'] ?>"/>
				</div>
				<div class="td-progress-bar-wrap"><div class="td-progress-bar td-progress-bar-<?php echo $stack_id ?>"></div></div>
				<h3 class="theme-name"><?php echo $stack_params['text'] ?></h3>

				<div class="td-admin-checkbox td-small-checkbox">
					<?php
					echo td_panel_generator::checkbox(array(
						'ds' => 'td_import_theme_styles',
						'option_id' => 'td_import_menus',
						'true_value' => '',
						'false_value' => 'no'
					));
					?>
					<p>Include content</p>
				</div>

				<div class="theme-actions">
					<a class="button button-secondary" href="#" target="_blank">Preview</a>
					<a class="button button-primary button-install-demo" href="#" data-stack-id="<?php echo $stack_id ?>">Install</a>
				</div>
			</div>
		<?php } ?>
	</div>


</div>


<div class="td-admin-wrap">
	<p>This option will delete all settings except license key</p>
	<a onclick="return confirm('Are you sure? This will reset all the theme settings to default!')" href="?page=td_theme_panel&td_page=td_view_import_theme_styles&td_option=factory_restore" class="td-big-button">Factory restore !!! ( RESET ALL SETTINGS )</a>
</div>


<?php if($td_import_fonts_show_update_msg == 1){?><script type="text/javascript">alert('Import is done!');</script><?php }?>