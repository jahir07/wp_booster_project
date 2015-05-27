<?php
require_once "td_view_header.php";

/*
td_demo_content::remove();
td_demo_content::add_post(array(
    'title' => 'Are You Already Wearing the Hottest Brands in Your City?',
    'file' => td_global::$get_template_directory . '/includes/demos/video/pages/post_default.txt',
    'categories_id_array' => array(get_cat_ID(TD_FEATURED_CAT)),
    'featured_image_td_id' => 'td_pic_1',
    'featured_video_url' => 'https://www.youtube.com/watch?v=rVeMiVU77wo&list=RD1FH-q0I1fJY&index=4',
    'template' => 'single_template_10',
    'post_format' => 'video'
));
*/
/*
td_demo_widgets::remove();

td_demo_widgets::add_widget_to_sidebar('default', 'td_block_ad_box_widget',
    array (
        'spot_title' => '- Advertisement -',
        'spot_id' => 'sidebar'
    )
);


die;
/*
td_demo_content::remove();
$td_homepage_id = td_demo_content::add_page(array(
    'title' => 'Newseeeeeeeexxxx',
    'file' => td_global::$get_template_directory . '/includes/demos/fashion/pages/homepage.txt',
    'template' => 'page-pagebuilder-latest.php',   // the page template full file name with .php
    'homepage' => true,
    'td_layout' => 5
));

die;
*/

if (isset($_GET['puiu_test']) and TD_DEPLOY_MODE == 'dev') {
    // clean the user settings
    //td_demo_media::remove();
    td_demo_content::remove();
    td_demo_category::remove();
    td_demo_menus::remove();
    td_demo_widgets::remove();


    $td_demo_installer = new td_demo_installer();

    // remove panel settings and recompile the css as empty
    foreach (td_global::$td_options as $option_id => $option_value) {
        td_global::$td_options[$option_id] = '';
    }
    //typography settings
    td_global::$td_options['td_fonts'] = '';
    //css font files (google) buffer
    td_global::$td_options['td_fonts_css_files'] = '';
    //compile user css if any
    td_global::$td_options['tds_user_compile_css'] = td_css_generator();
    update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options);

    td_demo_state::update_state($_GET['puiu_test'], 'full');

    // load panel settings
    $td_demo_installer->import_panel_settings(td_global::$demo_list[$_GET['puiu_test']]['folder'] . 'td_panel_settings.txt');
    // load the media import script
    //require_once(td_global::$demo_list[$td_demo_id]['folder'] . 'td_media_1.php');
    require_once(td_global::$demo_list[$_GET['puiu_test']]['folder'] . 'td_import.php');

}

?>

<div class="td-admin-wrap theme-browser">
	<p>The footer uses sidebars to show information. Here you can customize the number of sidebars and the layout. To add content to the footer head go to the widgets section and drag widget to the Footer 1, Footer 2 and Footer 3 sidebars.</p>

	<div class="td-admin-columns">
		<?php

        $installed_demo = td_demo_state::get_installed_demo();

        foreach (td_global::$demo_list as $demo_id => $stack_params) {
            $tmp_class = '';
            if ($installed_demo !== false and $installed_demo['demo_id'] == $demo_id) {
                $tmp_class = 'td-demo-installed';
            }
            ?>

			<div class="td-demo-<?php echo $demo_id ?> td-wp-admin-demo theme <?php echo $tmp_class ?>">

				<!-- Import content -->
				<div class="theme-screenshot">
					<img class="td-demo-thumb" src="<?php echo td_global::$demo_list[$demo_id]['img'] ?>"/>
                </div>

				<div class="td-admin-title">
					<div class="td-progress-bar-wrap"><div class="td-progress-bar"></div></div>
					<h3 class="theme-name"><?php echo $stack_params['text'] ?></h3>
				</div>

				<div class="td-admin-checkbox td-small-checkbox">
                    <div class="td-demo-install-content">
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
                    <p class="td-installed-text">Demo installed!</p>
				</div>

				<div class="theme-actions">
					<a class="button button-secondary td-button-demo-preview" href="<?php echo td_global::$demo_list[$demo_id]['demo_url'] ?>" target="_blank">Preview</a>
					<a class="button button-secondary td-button-install-demo" href="#" data-demo-id="<?php echo $demo_id ?>">Install</a>
                    <a class="button button-primary td-button-uninstall-demo" href="#" data-demo-id="<?php echo $demo_id ?>">Uninstall</a>
                    <a class="button button-primary disabled td-button-installing-demo" href="#" data-demo-id="<?php echo $demo_id ?>">Installing...</a>
                    <a class="button button-secondary disabled td-button-demo-disabled" href="#"">Install</a>


                    <a class="button button-primary disabled td-button-uninstalling-demo" href="#" data-demo-id="<?php echo $demo_id ?>">Uninstalling...</a>
				</div>
			</div>
		<?php } ?>
	</div>
</div>


