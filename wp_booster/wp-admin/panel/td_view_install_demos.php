<?php
require_once "td_view_header.php";

/*
td_demo_content::add_post(array(
    'title' => 'saf11111sas jhtsj jh sjrjr',
    'file' => td_global::$get_template_directory . '/includes/demos/fashion/posts/post1.txt',
    'categories_id_array' => array(),
    'featured_image_td_id' => 'td_pic_10',
    'template' => 'single_template_10'
));
*/
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


