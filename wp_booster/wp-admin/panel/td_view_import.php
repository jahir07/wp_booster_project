<?php

/**
 * The import demo view
 */


?><form id="td_panel_big_form" action="?page=td_theme_panel" method="post">
<input type="hidden" name="action" value="td_ajax_update_panel">
<div class="td_displaying_saving_gif"><img src="<?php echo get_template_directory_uri()?>/includes/wp_booster/wp-admin/images/panel/loading.gif"></div>
<img class="td_displaying_ok_gif" src="<?php echo get_template_directory_uri()?>/includes/wp_booster/wp-admin/images/panel/saved.gif">

<div class="wrap">

<div class="td-container-wrap">

<div class="td-panel-main-header">
    <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/panel-wrap/panel-logo.png'?>" alt=""/>
    <span>NEWSMAG - Theme panel</span>
</div>


<div id="td-container-left">
    <div id="td-container-right">
        <div id="td-col-left">
            <ul class="td-panel-menu">
                <li class="td-welcome-menu">
                    <a data-td-is-back="yes" class="td-panel-menu-active" href="?page=td_theme_panel">
                        <span class="td-sp-nav-icon td-ico-welcome"></span>
                        IMPORT DEMO DATA
                        <span class="td-no-arrow"></span>
                    </a>
                </li>

                <li>
                    <a data-td-is-back="yes" href="?page=td_theme_panel">
                        <span class="td-sp-nav-icon td-ico-back"></span>
                        Back
                        <span class="td-no-arrow"></span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="td-col-rigth" class="td-panel-content">

            <!-- import data -->
            <div id="td-panel-import" class="td-panel-active td-panel">

                <!-- One click demo install -->
                <?php echo td_panel_generator::box_start('ONE CLICK DEMO INSTALL'); ?>

                <!-- Install demo data -->
                <div class="td-box-row">


                    <script>
                        function td_progressbar_step(step_to_percent) {
                            if (step_to_percent >= 100) {
                                jQuery('.td_progress_bar').hide();
                                jQuery('.td-loading').hide();
                                jQuery('.td-complete').show();
                                jQuery('.td-progress-show-details').show();
                            } else {
                                jQuery('.td_progress_bar div').css('width', step_to_percent + '%');
                            }
                        }


                        jQuery().ready(function() {
                            jQuery('.td-progress-show-details').click(function(){
                                jQuery(this).hide();
                                jQuery('.td-demo-msg').show('fast', function() {
                                    //jQuery('#wpwrap').backstretch("resize");
                                });

                            });
                        });
                    </script>



                    <div class="td-section td-loading">
                        <div class="td-section-title">Loading the demo... </div>
                        <p>Please wait until the demo is loading. It may take one or two minutes.</p>
                    </div>

                    <div class="td-section td-complete" style="display:none">
                        <div class="td-section-title">The demo is live! :)</div>
                        <p>That's it. Remember that you can always recreate the demo by just pressing the load demo button from this admin. It will not create duplicates, it will just rebuild the demo pages.</p>
                    </div>

                    <div class="td_progress_bar_wrap">
                        <div class="td_progress_bar">
                            <div></div>
                        </div>
                        <div><a href="#" class="td-progress-show-details">Show details</a></div>


                        <?php

                        //return;
                        //new class
                        $td_demo_site = new td_demo_site();
                        $td_demo_site->total_progress_steps = 113; //used for loading bar


                        // enable all thumbnails
                        td_util::update_option('tds_thumb_td_640x0', 'yes');
                        td_util::update_option('tds_thumb_td_0x420', 'yes');
                        td_util::update_option('tds_thumb_td_100x75', 'yes');
                        td_util::update_option('tds_thumb_td_80x60', 'yes');
                        td_util::update_option('tds_thumb_td_341x220', 'yes');
                        td_util::update_option('tds_thumb_td_300x160', 'yes');
                        td_util::update_option('tds_thumb_td_300x194', 'yes');
                        td_util::update_option('tds_thumb_td_300x350', 'yes');
                        td_util::update_option('tds_thumb_td_681x0', 'yes');
                        td_util::update_option('tds_thumb_td_1021x580', 'yes');
                        td_util::update_option('tds_thumb_td_180x135', 'yes');
                        td_util::update_option('tds_thumb_td_238x178', 'yes');
                        td_util::update_option('tds_thumb_td_537x360', 'yes');
                        td_util::update_option('tds_thumb_td_640x350', 'yes');


                        /*  ----------------------------------------------------------------------------
                            top menu
                        */
                        $td_demo_site->create_menu('Top menu');
                        $td_demo_site->add_top_menu('Our blog');
                        $td_demo_site->add_top_menu('Advertise');
                        $td_demo_site->add_top_menu('Contact');
                        $td_demo_site->activate_menu('top-menu');

                        td_util::update_option('tds_data_top_menu', 'show'); // enable data on top menu
                        td_util::update_option('tds_login_sign_in_widget', 'show'); // enable login widget on top menu
                        td_util::update_option('tds_snap_menu', 'smart_snap_always'); // enable snap menu always
                        td_util::update_option('tds_logo_on_sticky', 'show'); // show logo on smart menu

                        $td_demo_site->add_socials(); //add social icons



                        /*  ----------------------------------------------------------------------------
                            logo + mobile logo + ad
                        */
                        $td_demo_site->update_logo(get_template_directory_uri() . '/images/demo/newsmag.png', get_template_directory_uri() . '/images/demo/newsmag@2x.png', get_template_directory_uri() . '/images/demo/newsmag-mobile.png');
                        $td_demo_site->add_ad_spot('header',
	                        '<div class="td-visible-desktop">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec728.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-tablet-portrait">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec468.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-phone">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec320.jpg') . '" alt="" /></a>
</div>'
                        );



                        /*  ----------------------------------------------------------------------------
                            custom ad 1
                        */
                        $td_demo_site->add_ad_spot('custom_ad_1',
	                        '<div class="td-visible-desktop">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec468.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-tablet-portrait">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec468.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-phone">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec300.png') . '" alt="" /></a>
</div>'
                        );


                        /*  ----------------------------------------------------------------------------
                            custom ad 2
                        */
                        $td_demo_site->add_ad_spot('custom_ad_2',
	                        '<div class="td-visible-desktop">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec728.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-tablet-portrait">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec468.jpg') . '" alt="" /></a>
</div>
<div class="td-visible-phone">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_top/rec300.png') . '" alt="" /></a>
</div>'
                        );



                        /*  ----------------------------------------------------------------------------
                            default sidebar
                        */
                        $td_demo_site->remove_widgets_from_sidebar('default');

                        //ad widget + adspot
                        $td_demo_site->add_ad_spot('sidebar',
	                        '<div class="td-visible-desktop">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_sidebar/rec300.png') . '" alt="" /></a>
</div>
<div class="td-visible-tablet-portrait">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_sidebar/rec200.png') . '" alt="" /></a>
</div>
<div class="td-visible-phone">
    <a href="#"><img src="' . $td_demo_site->get_demo_image('ad_sidebar/rec300.png') . '" alt="" /></a>
</div>'
                        );

                        //social counter widget
                        $td_demo_site->add_widget_to_sidebar('default', 'td_block_social_counter_widget', array(
	                        'custom_title' => 'STAY CONNECTED',
	                        'facebook' => 'themeforest',
	                        'twitter' => 'envato',
	                        'youtube' => 'ChilloutLoungeMusic',
	                        'open_in_new_window' => 'y',
	                        'border_top' => 'no_border_top'
                        ));

                        //ad box widget
                        $td_demo_site->add_widget_to_sidebar('default', 'td_block_ad_box_widget', array(
	                        'spot_id' => 'sidebar'
                        ));

						//block 9 widget
                        $td_demo_site->add_widget_to_sidebar('default', 'td_block_9_widget', array(
	                        'sort' => 'featured',
	                        'custom_title' => 'EDITOR PICKS',
	                        'limit' => '4',
	                        'header_color' => '#f24b4b'
                        ));

                        //footer setup
                        $td_demo_site->set_footer($td_demo_site->get_demo_image('newsmag-footer.png'),
	                        $td_demo_site->get_demo_image('newsmag-footer@2x.png'),
	                        'Newsmag is your news, entertainment, music fashion website. We provide you with the latest breaking news and videos straight from the entertainment industry.',
	                        'contact@yoursite.com');

                        //sub-footer setup
                        td_util::update_option('tds_footer_copyright', 'Copyright 2014 - Newsmag by TagDiv'); // sub-footer copyright

                        //sub-footer menu
                        $td_demo_site->create_menu('Footer menu');
                        $td_demo_site->add_top_menu('Disclaimer');
                        $td_demo_site->add_top_menu('Privacy');
                        $td_demo_site->add_top_menu('Advertisement');
                        $td_demo_site->add_top_menu('Contact us');
                        $td_demo_site->activate_menu('footer-menu');



                        /*  ----------------------------------------------------------------------------
                            create categories

                            $td_demo_site->add_category has an array parameter
                            @param[0] : name of category
                            @param[1] : parent category
                         */
                        $td_demo_site->add_category(array('Fashion'));
                        $td_demo_site->add_category(array('Chicago show', 'Fashion'));
                        $td_demo_site->add_category(array('Cosmopolitan', 'Fashion'));
                        $td_demo_site->add_category(array('Fashion week', 'Fashion'));
                        $td_demo_site->add_category(array('New York 2014', 'Fashion'));


                        /*  ----------------------------------------------------------------------------
                            header menu - make the main menu
                        */
                        $td_demo_site->create_menu('Main menu');
                        $td_demo_site->activate_menu('header-menu');

                        /*  ----------------------------------------------------------------------------
                            homepage
                        */
                        // add xxx_all_categ_xxx where you want to add the pulldown with the categories
		                $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjUiXVt0ZF9ibG9ja19iaWdfZ3JpZCBzb3J0PSJmZWF0dXJlZCJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja18xIGxpbWl0PSI1IiBjdXN0b21fdGl0bGU9IkZBU0hJT04gV0VFSyIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2IiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiBoZWFkZXJfY29sb3I9IiNlMjljMDQiXVt0ZF9ibG9ja18xNSBsaW1pdD0iOCIgY3VzdG9tX3RpdGxlPSJHQURHRVQgV09STEQiIGhlYWRlcl9jb2xvcj0iIzBiOGQ1ZCIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bdmNfcm93X2lubmVyXVt2Y19jb2x1bW5faW5uZXIgd2lkdGg9IjEvMiJdW3RkX2Jsb2NrXzIgbGltaXQ9IjEiIGN1c3RvbV90aXRsZT0iQkVTVCBTbWFydHBob25lcyIgaGVhZGVyX2NvbG9yPSIjNGRiMmVjIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVsvdmNfY29sdW1uX2lubmVyXVt2Y19jb2x1bW5faW5uZXIgd2lkdGg9IjEvMiJdW3RkX2Jsb2NrXzEwIGN1c3RvbV90aXRsZT0iRE9OJ1QgTUlTUyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGxpbWl0PSIzIiBzb3J0PSJyYW5kb21fcG9zdHMiXVsvdmNfY29sdW1uX2lubmVyXVsvdmNfcm93X2lubmVyXVsvdmNfY29sdW1uXVt2Y19jb2x1bW4gd2lkdGg9IjEvMyJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJzaWRlYmFyIl1bdGRfYmxvY2tfNiBsaW1pdD0iMiIgY3VzdG9tX3RpdGxlPSJQT1BVTEFSIFZJREVPIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgaGVhZGVyX2NvbG9yPSIjZWQ1ODFjIl1bdGRfYmxvY2tfOCBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJIT0xJREFZIFJFQ0lQRVMiIGhlYWRlcl9jb2xvcj0iIzAxNTJhOSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3ddW3ZjX2NvbHVtbiB3aWR0aD0iMS8xIl1bdGRfYmxvY2tfMTQgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iRVZFTiBNT1JFIE5FV1MiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgaGVhZGVyX2NvbG9yPSIjMjg4YWJmIl1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';

                        //$td_demo_site->create_page('page', 'Homepage', base64_decode($new_demo_page_64), 'page-pagebuilder-latest.php');
                        $td_demo_site->create_page('page', 'Homepage', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->add_top_page('News'); //add item in main menu
                        $td_demo_site->set_homepage();

                        //print_r(implode(',', $td_demo_site->category_array));



                        //category pages
                        //main category menu + homepage submenu
                        $td_demo_site->add_top_mega_menu('Fashion', 0);


						//homepages + add in menu
                        $td_demo_site->add_top_menu('Homepages');

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgY3NzPSIudGQtdG9wLWJvcmRlcntib3JkZXItdG9wLXdpZHRoOiAxcHggIWltcG9ydGFudDt9IiBlbF9jbGFzcz0idGQtc3Mtcm93Il1bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja19zbGlkZSBsaW1pdD0iMSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVt0ZF9ibG9ja18xMSBsaW1pdD0iMyIgb2Zmc2V0PSIxIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJjdXN0b21fYWRfMSJdW3RkX2Jsb2NrX3NsaWRlIGxpbWl0PSIxIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdW3RkX2Jsb2NrXzExIGxpbWl0PSIzIiBvZmZzZXQ9IjEiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIl1bdGRfYmxvY2tfYWRfYm94IHNwb3RfaWQ9ImN1c3RvbV9hZF8xIl1bdGRfYmxvY2tfc2xpZGUgbGltaXQ9IjEiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIl1bdGRfYmxvY2tfMTEgbGltaXQ9IjMiIG9mZnNldD0iMSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVsvdmNfY29sdW1uXVt2Y19jb2x1bW4gd2lkdGg9IjEvMyJdW3RkX2Jsb2NrX3NvY2lhbF9jb3VudGVyIGN1c3RvbV90aXRsZT0iU1RBWSBDT05ORUNURUQiIGZhY2Vib29rPSJ0aGVtZWZvcmVzdCIgdHdpdHRlcj0iZW52YXRvIiB5b3V0dWJlPSJFbnZhdG8iIG9wZW5faW5fbmV3X3dpbmRvdz0ieSIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJzaWRlYmFyIl1bdGRfYmxvY2tfOSBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJGRUFUVVJFRCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bdGRfYmxvY2tfMiBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJNT1NUIFBPUFVMQVIiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdW3RkX2Jsb2NrXzYgbGltaXQ9IjIiIGN1c3RvbV90aXRsZT0iTEFURVNUIFJFVklFV1MiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3JvdyBjc3M9Ii50ZC10b3AtYm9yZGVye2JvcmRlci10b3Atd2lkdGg6IDFweCAhaW1wb3J0YW50O30iXVt2Y19jb2x1bW4gd2lkdGg9IjEvMSJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJjdXN0b21fYWRfMiJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja18zIGxpbWl0PSI2IiBjdXN0b21fdGl0bGU9IkxBVEVTVCBBUlRJQ0xFUyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0iaW5maW5pdGUiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - loop', base64_decode($new_demo_page_64), 'page.php');
                        $td_demo_site->update_post_meta('td_page', 'td_sidebar_position', 'no_sidebar');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVt0ZF9ibG9ja19zbGlkZSBsaW1pdD0iMyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgZWxfY2xhc3M9InRkLXNzLXJvdyJdW3ZjX2NvbHVtbiB3aWR0aD0iMi8zIl1bdGRfYmxvY2tfMiBsaW1pdD0iNiIgY3VzdG9tX3RpdGxlPSJET04nVCBNSVNTIiBoZWFkZXJfY29sb3I9IiM0ZGIyZWMiIHRkX2FqYXhfZmlsdGVyX3R5cGU9InRkX2NhdGVnb3J5X2lkc19maWx0ZXIiIHRkX2FqYXhfZmlsdGVyX2lkcz0ieHh4X2FsbF9jYXRlZ194eHgiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGRfYmxvY2tfc29jaWFsX2NvdW50ZXIgY3VzdG9tX3RpdGxlPSJTVEFZIENPTk5FQ1RFRCIgZmFjZWJvb2s9InRoZW1lZm9yZXN0IiB0d2l0dGVyPSJlbnZhdG8iIHlvdXR1YmU9IlVDcWdsZ3lrOGc4NENNTHpQdVpwenhoUSIgb3Blbl9pbl9uZXdfd2luZG93PSJ5IiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIl1bdGRfYmxvY2tfOSBsaW1pdD0iMiIgY3VzdG9tX3RpdGxlPSJNT1NUIFBPUFVMQVIiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja18xNiBsaW1pdD0iNSIgY3VzdG9tX3RpdGxlPSJMQVRFU1QgVklERU9TIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiIGNvbG9yX3ByZXNldD0idGQtYmxvY2stY29sb3Itc3R5bGUtMiIgaGVhZGVyX2NvbG9yPSIjZmZmZmZmIiBoZWFkZXJfdGV4dF9jb2xvcj0iIzAwMDAwMCJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja18yIGxpbWl0PSIyIiBjdXN0b21fdGl0bGU9IlRSQVZFTCBHVUlERVMiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCIgaGVhZGVyX2NvbG9yPSIjYzcyNzJmIl1bdGRfYmxvY2tfMiBsaW1pdD0iMiIgY3VzdG9tX3RpdGxlPSJNT0JJTEUgQU5EIFBIT05FUyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2IiBvZmZzZXQ9IjEiIGhlYWRlcl9jb2xvcj0iIzEwN2E1NiJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJjdXN0b21fYWRfMSJdW3RkX2Jsb2NrXzIgbGltaXQ9IjIiIGN1c3RvbV90aXRsZT0iTkVXIFlPUksgMjAxNCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2IiBvZmZzZXQ9IjEiIGhlYWRlcl9jb2xvcj0iI2U4M2U5ZSJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGRfYmxvY2tfOSBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJURUNIIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVt0ZF9ibG9ja18xNSBsaW1pdD0iNCIgY3VzdG9tX3RpdGxlPSJGQVNISU9OIiBjb2xvcl9wcmVzZXQ9InRkLWJsb2NrLWNvbG9yLXN0eWxlLTIiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdW3RkX2Jsb2NrXzIgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iTEFURVNUIFJFVklFV1MiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja18xNCBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJFTlRFUlRBSU5NRU5UIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - big slide', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVt0ZF9ibG9ja19iaWdfZ3JpZF1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd11bdmNfcm93XVt2Y19jb2x1bW4gd2lkdGg9IjIvMyJdW3RkX2Jsb2NrXzEgbGltaXQ9IjUiIGN1c3RvbV90aXRsZT0iVFJBVkVMIEdVSURFIiB0ZF9hamF4X2ZpbHRlcl90eXBlPSJ0ZF9jYXRlZ29yeV9pZHNfZmlsdGVyIiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVt0ZF9ibG9ja18xNiBsaW1pdD0iNiIgY3VzdG9tX3RpdGxlPSJMQVRFU1QgVklERU9TIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJjdXN0b21fYWRfMSJdW3RkX2Jsb2NrXzEgbGltaXQ9IjUiIGN1c3RvbV90aXRsZT0iR0FER0VUUyBXT1JMRCIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt0ZF9ibG9ja19zb2NpYWxfY291bnRlciBjdXN0b21fdGl0bGU9IlNUQVkgQ09OTkVDVEVEIiBmYWNlYm9vaz0idGhlbWVmb3Jlc3QiIHR3aXR0ZXI9ImVudmF0byIgeW91dHViZT0iRW52YXRvIiBvcGVuX2luX25ld193aW5kb3c9InkiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVt0ZF9ibG9ja185IGxpbWl0PSIyIiBjdXN0b21fdGl0bGU9IkxJRkVTVFlMRSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVt0ZF9ibG9ja18yIGxpbWl0PSIzIiBjdXN0b21fdGl0bGU9Ik5VVFJJVElPTiIgY29sb3JfcHJlc2V0PSJ0ZC1ibG9jay1jb2xvci1zdHlsZS0yIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgaGVhZGVyX3RleHRfY29sb3I9IiMwMDAwMDAiIGhlYWRlcl9jb2xvcj0iI2ZmZmZmZiIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVt0ZF9ibG9ja185IGxpbWl0PSI0IiBjdXN0b21fdGl0bGU9IkZBU0hJT04gV0VFSyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - random', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgZWxfY2xhc3M9InRkLXNzLXJvdyJdW3ZjX2NvbHVtbiB3aWR0aD0iMi8zIl1bdGRfYmxvY2tfMiBsaW1pdD0iMiIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bdGRfYmxvY2tfOSBsaW1pdD0iNiIgY3VzdG9tX3RpdGxlPSJGQVNISU9OIFdFRUsiIHRkX2FqYXhfZmlsdGVyX3R5cGU9InRkX2NhdGVnb3J5X2lkc19maWx0ZXIiIHRkX2FqYXhfZmlsdGVyX2lkcz0ieHh4X2FsbF9jYXRlZ194eHgiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdW3RkX2Jsb2NrXzkgbGltaXQ9IjYiIGN1c3RvbV90aXRsZT0iRE9OJ1QgTUlTUyIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGhlYWRlcl9jb2xvcj0iIzRkYjJlYyIgY29sb3JfcHJlc2V0PSJ0ZC1ibG9jay1jb2xvci1zdHlsZS0yIl1bdGRfYmxvY2tfYWRfYm94IHNwb3RfaWQ9ImN1c3RvbV9hZF8xIl1bdGRfYmxvY2tfMSBsaW1pdD0iNSIgY3VzdG9tX3RpdGxlPSJHQURHRVQgV09STEQiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIl1bdmNfcm93X2lubmVyXVt2Y19jb2x1bW5faW5uZXIgd2lkdGg9IjEvMiJdW3RkX2Jsb2NrXzEwIGxpbWl0PSIzIiBjdXN0b21fdGl0bGU9IkxJRkVTVFlMRSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bL3ZjX2NvbHVtbl9pbm5lcl1bdmNfY29sdW1uX2lubmVyIHdpZHRoPSIxLzIiXVt0ZF9ibG9ja18xMCBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJNT0JJTEUgQU5EIFBIT05FUyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bL3ZjX2NvbHVtbl9pbm5lcl1bL3ZjX3Jvd19pbm5lcl1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt0ZF9ibG9ja19zb2NpYWxfY291bnRlciBjdXN0b21fdGl0bGU9IlNUQVkgQ09OTkVDVEVEIiBmYWNlYm9vaz0idGhlbWVmb3Jlc3QiIHR3aXR0ZXI9ImVudmF0byIgeW91dHViZT0iRW52YXRvIiBvcGVuX2luX25ld193aW5kb3c9InkiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0ic2lkZWJhciJdW3ZjX3dwX3JlY2VudGNvbW1lbnRzIHRpdGxlPSJSRUNFTlQgQ09NTUVOVFMiIG51bWJlcj0iMyJdW3RkX2Jsb2NrXzIgbGltaXQ9IjEiIGN1c3RvbV90aXRsZT0iTEFURVNUIFJFVklFV1MiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdW3ZjX3dwX3Bvc3RzIHNob3dfZGF0ZT0iMSIgdGl0bGU9IlJFQ0VOVCBQT1NUUyIgbnVtYmVyPSIzIl1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';
                        $td_demo_site->create_page('page', 'Homepage - less images', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page.php');
                        $td_demo_site->update_post_meta('td_page', 'td_sidebar_position', 'no_sidebar');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgc29ydD0icmFuZG9tX3Bvc3RzIiBsaW1pdD0iNSJdW3RkX2Jsb2NrX2JpZ19ncmlkXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgZWxfY2xhc3M9InRkLXNzLXJvdyJdW3ZjX2NvbHVtbiB3aWR0aD0iMi8zIl1bdGRfYmxvY2tfMiBsaW1pdD0iNiIgY3VzdG9tX3RpdGxlPSJQT1BVTEFSIE5FV1MiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiIHRkX2FqYXhfZmlsdGVyX3R5cGU9InRkX2NhdGVnb3J5X2lkc19maWx0ZXIiIHRkX2FqYXhfZmlsdGVyX2lkcz0ieHh4X2FsbF9jYXRlZ194eHgiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdW3RkX2Jsb2NrXzEgbGltaXQ9IjUiIGN1c3RvbV90aXRsZT0iVFJBVkVMIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgb2Zmc2V0PSIxIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGRfYmxvY2tfc29jaWFsX2NvdW50ZXIgZmFjZWJvb2s9InRoZW1lZm9yZXN0IiB0d2l0dGVyPSJlbnZhdG8iIHlvdXR1YmU9IlVDcWdsZ3lrOGc4NENNTHpQdVpwenhoUSJdW3RkX2Jsb2NrXzkgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iRk9PRCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVt0ZF9ibG9ja18xNSBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJGQVNISU9OIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3ddW3ZjX2NvbHVtbiB3aWR0aD0iMS8xIl1bdGRfYmxvY2tfdmlkZW9feW91dHViZSBwbGF5bGlzdF95dD0iUEVMbEhzbGxsazAsIGdXTC1yNzJ0R09FLCBhWkpTTXhzamltUSwgdWpmT3lhZTFld3csIF9rQ19rd1dQVHg0LCBCZEVPcTdYQXlyQSwgLVM5TDM4WnFIdzgsIEZTTXhZUzZoMnR3LCB3Nm5YRFBFSTc2OCwgM2pUX3E3ZHQtY00iIHBsYXlsaXN0X2F1dG9fcGxheT0iMCIgcGxheWxpc3RfdGl0bGU9IlZpZGVvIHBsYXlsaXN0Il1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';
                        $td_demo_site->create_page('page', 'Homepage - sport', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '11');
                        $td_demo_site->update_post_meta('td_homepage_loop_filter', 'limit', '6');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjUiXVt0ZF9ibG9ja18xNCBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJGRUFUVVJFRCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgZWxfY2xhc3M9InRkLXNzLXJvdyJdW3ZjX2NvbHVtbiB3aWR0aD0iMi8zIl1bdGRfYmxvY2tfMSBsaW1pdD0iNSIgY3VzdG9tX3RpdGxlPSJXSEFUJ1MgTkVXIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrXzIgbGltaXQ9IjYiIGN1c3RvbV90aXRsZT0iQUNDRVNTT1JJRVMiIGhlYWRlcl9jb2xvcj0iIzBhOWUwMSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0ic2lkZWJhciJdW3RkX2Jsb2NrXzYgbGltaXQ9IjEiIGN1c3RvbV90aXRsZT0iV0lORE9XUyBQSE9ORSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGhlYWRlcl9jb2xvcj0iIzU1YTRmZiJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja19iaWdfZ3JpZF1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';
                        $td_demo_site->create_page('page', 'Homepage - tech', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '12');
                        $td_demo_site->update_post_meta('td_homepage_loop_filter', 'limit', '8');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja19ob21lcGFnZV9mdWxsXzFdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3JvdyBlbF9jbGFzcz0idGQtc3Mtcm93Il1bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja18yIGxpbWl0PSI2IiBjdXN0b21fdGl0bGU9IkRPTidUIE1JU1MiIHRkX2FqYXhfZmlsdGVyX3R5cGU9InRkX2NhdGVnb3J5X2lkc19maWx0ZXIiIHRkX2FqYXhfZmlsdGVyX2lkcz0ieHh4X2FsbF9jYXRlZ194eHgiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrXzE1IGxpbWl0PSI2IiBjdXN0b21fdGl0bGU9IkxpZmVzdHlsZSIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2Il1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0ic2lkZWJhciJdW3RkX2Jsb2NrXzEgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iRm9vZCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgY3NzPSIudGQtdG9wLWJvcmRlcntib3JkZXItdG9wLXdpZHRoOiAxcHggIWltcG9ydGFudDt9Il1bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0iY3VzdG9tX2FkXzIiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - full post featured', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '2');
                        $td_demo_site->update_post_meta('td_homepage_loop_filter', 'limit', '8');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjUiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - blog', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '15');
                        $td_demo_site->update_post_meta('td_homepage_loop_filter', 'limit', '8');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVt0ZF9ibG9ja19iaWdfZ3JpZF1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd11bdmNfcm93XVt2Y19jb2x1bW4gd2lkdGg9IjIvMyJdW3RkX2Jsb2NrXzIgbGltaXQ9IjYiIGN1c3RvbV90aXRsZT0iRE9OJ1QgTUlTUyIgaGVhZGVyX2NvbG9yPSIjNGRiMmVjIiB0ZF9hamF4X2ZpbHRlcl90eXBlPSJ0ZF9jYXRlZ29yeV9pZHNfZmlsdGVyIiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVt0ZF9ibG9ja18xIGxpbWl0PSI1IiBjdXN0b21fdGl0bGU9IkdBREdFVCBXT1JMRCIgaGVhZGVyX2NvbG9yPSIjMGI4ZDVkIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJjdXN0b21fYWRfMSJdW3RkX2Jsb2NrXzIgbGltaXQ9IjYiIGN1c3RvbV90aXRsZT0iVFJBVkVMIEdVSURFUyIgaGVhZGVyX2NvbG9yPSIjZjI0YjRiIiB0ZF9hamF4X2ZpbHRlcl90eXBlPSJ0ZF9jYXRlZ29yeV9pZHNfZmlsdGVyIiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVsvdmNfY29sdW1uXVt2Y19jb2x1bW4gd2lkdGg9IjEvMyJdW3RkX2Jsb2NrX3NvY2lhbF9jb3VudGVyIGN1c3RvbV90aXRsZT0iU1RBWSBDT05ORUNURUQiIGZhY2Vib29rPSJ0aGVtZWZvcmVzdCIgdHdpdHRlcj0iZW52YXRvIiB5b3V0dWJlPSJFbnZhdG8iIG9wZW5faW5fbmV3X3dpbmRvdz0ieSIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJzaWRlYmFyIl1bdGRfYmxvY2tfMiBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJMQVRFU1QgUkVWSUVXUyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVt0ZF9ibG9ja19zbGlkZSBsaW1pdD0iMyIgY3VzdG9tX3RpdGxlPSJQT1BVTEFSIFZJREVPIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdW3RkX2Jsb2NrXzkgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iQ0hJQ0FHTyBTSE9XIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdWy92Y19jb2x1bW5dWy92Y19yb3ddW3ZjX3JvdyBlbF9jbGFzcz0idGQtc3Mtcm93Il1bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja18yIGxpbWl0PSI2IiBjdXN0b21fdGl0bGU9IkZBU0hJT04gQU5EIFRSRU5EUyIgaGVhZGVyX2NvbG9yPSIjZmYzZTlmIiB0ZF9hamF4X2ZpbHRlcl90eXBlPSJ0ZF9jYXRlZ29yeV9pZHNfZmlsdGVyIiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdGRfYmxvY2tfNyBsaW1pdD0iMSIgY3VzdG9tX3RpdGxlPSJFRElUT1IgUElDS1MiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIl1bdGRfYmxvY2tfYWRfYm94IHNwb3RfaWQ9InNpZGViYXIiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgY3NzPSIudGQtdG9wLWJvcmRlcntib3JkZXItdG9wLXdpZHRoOiAxcHggIWltcG9ydGFudDt9Il1bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0iY3VzdG9tX2FkXzIiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - newspaper', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVt0ZF9ibG9ja19iaWdfZ3JpZF1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd11bdmNfcm93XVt2Y19jb2x1bW4gd2lkdGg9IjEvMSJdW3RkX2Jsb2NrXzIgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iRE9OJ1QgTUlTUyIgdGRfYWpheF9maWx0ZXJfdHlwZT0idGRfY2F0ZWdvcnlfaWRzX2ZpbHRlciIgdGRfYWpheF9maWx0ZXJfaWRzPSJ4eHhfYWxsX2NhdGVnX3h4eCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGFqYXhfcGFnaW5hdGlvbj0ibmV4dF9wcmV2IiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIl1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd11bdmNfcm93IGNzcz0iLnRkLXRvcC1ib3JkZXJ7Ym9yZGVyLXRvcC13aWR0aDogMXB4ICFpbXBvcnRhbnQ7fSIgZWxfY2xhc3M9InRkLXNzLXJvdyJdW3ZjX2NvbHVtbiB3aWR0aD0iMi8zIl1bdGRfYmxvY2tfMyBsaW1pdD0iNiIgY3VzdG9tX3RpdGxlPSJMQVRFU1QgQVJUSUNMRVMiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249ImluZmluaXRlIiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIiBhamF4X3BhZ2luYXRpb25faW5maW5pdGVfc3RvcD0iMyJdWy92Y19jb2x1bW5dW3ZjX2NvbHVtbiB3aWR0aD0iMS8zIl1bdmNfd2lkZ2V0X3NpZGViYXIgc2lkZWJhcl9pZD0idGQtZGVmYXVsdCJdWy92Y19jb2x1bW5dWy92Y19yb3dd';
                        $td_demo_site->create_page('page', 'Homepage - infinite scroll', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page.php');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVsvdmNfY29sdW1uXVsvdmNfcm93XVt2Y19yb3cgY3NzPSIudGQtdG9wLWJvcmRlcntib3JkZXItdG9wLXdpZHRoOiAxcHggIWltcG9ydGFudDt9Il1bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt0ZF9ibG9ja19zbGlkZSBsaW1pdD0iMyIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiXVt0ZF9ibG9ja18yIGxpbWl0PSI2IiBjdXN0b21fdGl0bGU9IkRPTidUIE1JU1MiIHRkX2FqYXhfZmlsdGVyX3R5cGU9InRkX2NhdGVnb3J5X2lkc19maWx0ZXIiIHRkX2FqYXhfZmlsdGVyX2lkcz0ieHh4X2FsbF9jYXRlZ194eHgiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrXzEgbGltaXQ9IjUiIGN1c3RvbV90aXRsZT0iR0FER0VUUyBXT1JMRCIgdGRfZmlsdGVyX2RlZmF1bHRfdHh0PSJBbGwiIGJvcmRlcl90b3A9Im5vX2JvcmRlcl90b3AiXVt0ZF9ibG9ja19hZF9ib3ggc3BvdF9pZD0iY3VzdG9tX2FkXzEiXVt0ZF9ibG9ja18xNSBsaW1pdD0iOCIgY3VzdG9tX3RpdGxlPSJMSUZFU1RZTEUiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIl1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt0ZF9ibG9ja19zb2NpYWxfY291bnRlciBmYWNlYm9vaz0idGhlbWVmb3Jlc3QiIHR3aXR0ZXI9ImVudmF0byIgeW91dHViZT0iRW52YXRvIiBjdXN0b21fdGl0bGU9IlNUQVkgQ09OTkVDVEVEIiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIl1bdGRfYmxvY2tfOSBsaW1pdD0iNSIgY3VzdG9tX3RpdGxlPSJMSUZFU1RZTEUiIHRkX2ZpbHRlcl9kZWZhdWx0X3R4dD0iQWxsIiBhamF4X3BhZ2luYXRpb249Im5leHRfcHJldiIgYm9yZGVyX3RvcD0ibm9fYm9yZGVyX3RvcCJdW3RkX2Jsb2NrX2FkX2JveCBzcG90X2lkPSJzaWRlYmFyIl1bdmNfd3BfcG9zdHMgc2hvd19kYXRlPSIxIiB0aXRsZT0iUkVDRU5UIFBPU1RTIiBudW1iZXI9IjUiXVt0ZF9ibG9ja18yIGxpbWl0PSIzIiBjdXN0b21fdGl0bGU9IkxBVEVTVCBSRVZJRVdTIiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCJdWy92Y19jb2x1bW5dWy92Y19yb3dd';
                        $td_demo_site->create_page('page', 'Homepage - magazine', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page.php');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja19ob21lcGFnZV9mdWxsXzFdW3RkX2Jsb2NrXzIgbGltaXQ9IjMiIGN1c3RvbV90aXRsZT0iTU9TVCBQT1BVTEFSIiBib3JkZXJfdG9wPSJub19ib3JkZXJfdG9wIiB0ZF9hamF4X2ZpbHRlcl90eXBlPSJ0ZF9jYXRlZ29yeV9pZHNfZmlsdGVyIiB0ZF9hamF4X2ZpbHRlcl9pZHM9Inh4eF9hbGxfY2F0ZWdfeHh4IiB0ZF9maWx0ZXJfZGVmYXVsdF90eHQ9IkFsbCIgYWpheF9wYWdpbmF0aW9uPSJuZXh0X3ByZXYiXVsvdmNfY29sdW1uXVsvdmNfcm93XQ==';
                        $td_demo_site->create_page('page', 'Homepage - fashion', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '14');
                        $td_demo_site->update_post_meta('td_homepage_loop_filter', 'limit', '6');
                        $td_demo_site->add_sub_page();

                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIxLzEiXVt0ZF9ibG9ja190cmVuZGluZ19ub3cgbGltaXQ9IjMiXVt0ZF9ibG9ja19iaWdfZ3JpZF1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';
                        $td_demo_site->create_page('page', 'Homepage - clean', str_replace('xxx_all_categ_xxx', implode(',', $td_demo_site->category_array), base64_decode($new_demo_page_64)), 'page-pagebuilder-latest.php');
                        $td_demo_site->update_post_meta('td_homepage_loop', 'td_layout', '2');
                        $td_demo_site->add_sub_page();



						//contact page + add in menu
                        $new_demo_page_64 = 'W3ZjX3Jvd11bdmNfY29sdW1uIHdpZHRoPSIyLzMiXVt2Y19jb2x1bW5fdGV4dF1Mb3JlbSBpcHN1bSBkb2xvciBzaXQgYW1ldCwgY29uc2VjdGV0dXIgYWRpcGlzY2luZyBlbGl0LiBTdXNwZW5kaXNzZSBub24gbnVuYyBhYyBxdWFtIGNvbmd1ZSBmZXJtZW50dW0gZXQgdmVsIG1hc3NhLiBQcm9pbiBpbXBlcmRpZXQgcHVsdmluYXIgcmhvbmN1cy4gSW50ZWdlciBpbiBlbGl0IGFjY3Vtc2FuLCB1bGxhbWNvcnBlciBhbnRlIG5vbiwgY29tbW9kbyB2ZWxpdC4gTnVuYyBsdWN0dXMgc2NlbGVyaXNxdWUgZHVpLCB2aXRhZSBsdWN0dXMgZXN0IGF1Y3RvciBldS5bL3ZjX2NvbHVtbl90ZXh0XVt2Y19yb3dfaW5uZXJdW3ZjX2NvbHVtbl9pbm5lciB3aWR0aD0iMS8yIl1bdGRfYmxvY2tfdGV4dF93aXRoX3RpdGxlIGN1c3RvbV90aXRsZT0iQ29udGFjdCBEZXRhaWxzIl08c3Ryb25nPk5ld3NtYWcgQ29tdW5pY2F0aW9uIFNlcnZpY2U8L3N0cm9uZz4NCjQyNSBTYW50YSBUZXJlc2EgU3QuIFN0YW5mb3JkDQoNCig2NTApIDcyMy0yNTU4IChtYWluIG51bWJlcikNCig2NTApIDcyNS0wMjQ3IChmYXgpDQoNCkVtYWlsOiA8c3Ryb25nPmNvbnRhY3RAbmV3c21hZy5jb208L3N0cm9uZz5bL3RkX2Jsb2NrX3RleHRfd2l0aF90aXRsZV1bL3ZjX2NvbHVtbl9pbm5lcl1bdmNfY29sdW1uX2lubmVyIHdpZHRoPSIxLzIiIGNzcz0iLnRkLW5vLWxlZnQtYm9yZGVye2JvcmRlci1sZWZ0LXdpZHRoOiAwcHggIWltcG9ydGFudDt9Il1bdGRfYmxvY2tfdGV4dF93aXRoX3RpdGxlIGN1c3RvbV90aXRsZT0iQWJvdXQgdXMiXU5ld3NtYWcgaXMgeW91ciBuZXdzLCBlbnRlcnRhaW5tZW50LCBtdXNpYyBmYXNoaW9uIHdlYnNpdGUuIFdlIHByb3ZpZGUgeW91IHdpdGggdGhlIGxhdGVzdCBicmVha2luZyBuZXdzIGFuZCB2aWRlb3Mgc3RyYWlnaHQgZnJvbSB0aGUgZW50ZXJ0YWlubWVudCBpbmR1c3RyeS5bL3RkX2Jsb2NrX3RleHRfd2l0aF90aXRsZV1bL3ZjX2NvbHVtbl9pbm5lcl1bL3ZjX3Jvd19pbm5lcl1bL3ZjX2NvbHVtbl1bdmNfY29sdW1uIHdpZHRoPSIxLzMiXVt2Y193aWRnZXRfc2lkZWJhciBzaWRlYmFyX2lkPSJ0ZC1kZWZhdWx0Il1bL3ZjX2NvbHVtbl1bL3ZjX3Jvd10=';
                        $td_demo_site->create_page('page', 'Contact us', base64_decode($new_demo_page_64), 'page-pagebuilder-title.php');
                        $td_demo_site->add_top_page('Contact us');
                        $td_demo_site->update_post_meta('td_page', 'td_sidebar_position', 'no_sidebar');


                        //post content - no images path
                        $post_content_NO_images_path = 'PGRpdiBjbGFzcz0idGQtcGFyYWdyYXBoLXBhZGRpbmctMSI+DQoNCkFsbCByaWdodC4gV2VsbCwgdGFrZSBjYXJlIHlvdXJzZWxmLiBJIGd1ZXNzIHRoYXQncyB3aGF0IHlvdSdyZSBiZXN0LCBwcmVzZW5jZSBvbGQgbWFzdGVyPyBBIHRyZW1vciBpbiB0aGUgRm9yY2UuIFRoZSBsYXN0IHRpbWUgZmVsdCBpdCB3YXMgaW4gdGhlIHByZXNlbmNlIG9mIG15IG9sZCBtYXN0ZXIuIEkgaGF2ZSB0cmFjZWQgdGhlIFJlYmVsIHNwaWVzIHRvIGhlci4gTm93IHNoZSBpcyBteSBvbmx5IGxpbmsgdG8gZmluZGluZyB0aGVpciBzZWNyZXQgYmFzZS4gQSB0cmVtb3IgaW4gdGhlIEZvcmNlLiBUaGUgbGFzdCB0aW1lIEkgZmVsdCBpdCB3YXMgaW4gdGhlIHByZXNlbmNlIG9mIG15IG9sZCBtYXN0ZXIuDQoNClJlbWVtYmVyLCBhIEplZGkgY2FuIGZlZWwgdGhlIEZvcmNlIGZsb3dpbmcgdGhyb3VnaCBoaW0uIEkgY2FuJ3QgZ2V0IGludm9sdmVkISBJJ3ZlIGdvdCB3b3JrIHRvIGRvISBJdCdzIG5vdCB0aGF0IEkgbGlrZSB0aGUgRW1waXJlLCBJIGhhdGUgaXQsIGJ1dCB0aGVyZSdzIG5vdGhpbmcgSSBjYW4gZG8gYWJvdXQgaXQgcmlnaHQgbm93LiBJdCdzIHN1Y2ggYSBsb25nIHdheSBmcm9tIGhlcmUuIEkgY2FsbCBpdCBsdWNrLiBZb3UgYXJlIGEgcGFydCBvZiB0aGUgUmViZWwgQWxsaWFuY2UgYW5kIGEgdHJhaXRvciEgVGFrZSBoZXIgYXdheSENCjxibG9ja3F1b3RlPkRlc2lnbiBpcyBub3QganVzdCB3aGF0IGl0IGxvb2tzIGxpa2UgYW5kIGZlZWxzIGxpa2UuIERlc2lnbiBpcyBob3cgaXQgd29ya3MuPC9ibG9ja3F1b3RlPg0KVGhlIHBsYW5zIHlvdSByZWZlciB0byB3aWxsIHNvb24gYmUgYmFjayBpbiBvdXIgaGFuZHMuIFRoZSBwbGFucyB5b3UgcmVmZXIgdG8gd2lsbCBzb29uIGJlIGJhY2sgaW4gb3VyIGhhbmRzLiBMZWF2ZSB0aGF0IHRvIG1lLiBTZW5kIGEgZGlzdHJlc3Mgc2lnbmFsLCBhbmQgaW5mb3JtIHRoZSBTZW5hdGUgdGhhdCBhbGwgb24gYm9hcmQgd2VyZSBraWxsZWQuIFJlZCBGaXZlIHN0YW5kaW5nIGJ5Lg0KDQpJIGZpbmQgeW91ciBsYWNrIG9mIGZhaXRoIGRpc3R1cmJpbmcuIEEgdHJlbW9yIGluIHRoZSBGb3JjZS4gVGhlIGxhc3QgdGltZSBJIGZlbHQgaXQgd2FzIGluIHRoZSBwcmVzZW5jZSBvZiBteSBvbGQgbWFzdGVyLiBIZXksIEx1a2UhIE1heSB0aGUgRm9yY2UgYmUgd2l0aCB5b3UuIFJlZCBGaXZlIHN0YW5kaW5nIGJ5LiBSZWQgRml2ZSBzdGFuZGluZyBieS4gWW91ciBleWVzIGNhbiBkZWNlaXZlIHlvdS4gRG9uJ3QgdHJ1c3QgdGhlbS4NCg0KPC9kaXY+DQoNCltjYXB0aW9uIGlkPSJhdHRhY2htZW50XzQxMjYiIGFsaWduPSJhbGlnbmNlbnRlciIgd2lkdGg9IjcwMCJdPGEgaHJlZj0ieHh4X3BhdGhfdG9fZmlsZV94eHgvaW1hZ2VzL2RlbW8vcDEuanBnIiByZWw9ImF0dGFjaG1lbnQgd3AtYXR0LTQxMjYiPjxpbWcgY2xhc3M9IiB0ZC1tb2RhbC1pbWFnZSB3cC1pbWFnZS00MTI2IiBzcmM9Inh4eF9wYXRoX3RvX2ZpbGVfeHh4L2ltYWdlcy9kZW1vL3AxLmpwZyIgYWx0PSIiIC8+PC9hPiBUaGUgbmV3IGNvbmNlcHQgc3B5ZGVyWy9jYXB0aW9uXQ0KDQo8ZGl2IGNsYXNzPSJ0ZC1wYXJhZ3JhcGgtcGFkZGluZy0xIj4NCg0KSG9rZXkgcmVsaWdpb25zIGFuZCBhbmNpZW50IHdlYXBvbnMgYXJlIG5vIG1hdGNoIGZvciBhIGdvb2QgYmxhc3RlciBhdCB5b3VyIHNpZGUsIGtpZC4gSSBmaW5kIHlvdXIgbGFjayBvZiBmYWl0aCBkaXN0dXJiaW5nLiBUaGUgbW9yZSB5b3UgdGlnaHRlbiB5b3VyIGdyaXAsIFRhcmtpbiwgdGhlIG1vcmUgc3RhciBzeXN0ZW1zIHdpbGwgc2xpcCB0aHJvdWdoIHlvdXIgZmluZ2Vycy4gQXMgeW91IHdpc2guDQoNClRoZSBtb3JlIHlvdSB0aWdodGVuIHlvdXIgZ3JpcCwgVGFya2luLCB0aGUgbW9yZSBzdGFyIHN5c3RlbXMgd2lsbCBzbGlwIHRocm91Z2ggeW91ciBmaW5nZXJzLiBJbiBteSBleHBlcmllbmNlLCB0aGVyZSBpcyBubyBzdWNoIHRoaW5nIGFzIGx1Y2suIFJlZCBGaXZlIHN0YW5kaW5nIGJ5Lg0KPGJsb2NrcXVvdGU+VGhhbmtzIGZvciBsb29raW5nIGF0IG91ciB0aGVtZS48L2Jsb2NrcXVvdGU+DQpJIG5lZWQgeW91ciBoZWxwLCBMdWtlLiBTaGUgbmVlZHMgeW91ciBoZWxwLiBJJ20gZ2V0dGluZyB0b28gb2xkIGZvciB0aGlzIHNvcnQgb2YgdGhpbmcuIE9oIEdvZCwgbXkgdW5jbGUuIEhvdyBhbSBJIGV2ZXIgZ29ubmEgZXhwbGFpbiB0aGlzPyBBcyB5b3Ugd2lzaC4gRXNjYXBlIGlzIG5vdCBoaXMgcGxhbi4gSSBtdXN0IGZhY2UgaGltLCBhbG9uZS4gWW91IG1lYW4gaXQgY29udHJvbHMgeW91ciBhY3Rpb25zPw0KDQpSZW1lbWJlciwgYSBKZWRpIGNhbiBmZWVsIHRoZSBGb3JjZSBmbG93aW5nIHRocm91Z2ggaGltLiBUaGUgcGxhbnMgeW91IHJlZmVyIHRvIHdpbGwgc29vbiBiZSBiYWNrIGluIG91ciBoYW5kcy4gWWUtaGEhIFRoZSBwbGFucyB5b3UgcmVmZXIgdG8gd2lsbCBzb29uIGJlIGJhY2sgaW4gb3VyIGhhbmRzLg0KDQo8L2Rpdj4NCjxkaXYgY2xhc3M9InRkLXBhcmFncmFwaC1wYWRkaW5nLTAiPg0KDQpbY2FwdGlvbiBpZD0iYXR0YWNobWVudF80MTIzIiBhbGlnbj0iYWxpZ25sZWZ0IiB3aWR0aD0iMjUxIl08YSBocmVmPSJ4eHhfcGF0aF90b19maWxlX3h4eC9pbWFnZXMvZGVtby9wMi5qcGciIHJlbD0iYXR0YWNobWVudCB3cC1hdHQtNDEyMyI+PGltZyBjbGFzcz0iIHRkLW1vZGFsLWltYWdlIHdwLWltYWdlLTQxMjMiIHNyYz0ieHh4X3BhdGhfdG9fZmlsZV94eHgvaW1hZ2VzL2RlbW8vcDIuanBnIiBhbHQ9IiIgd2lkdGg9IjI1MSIgaGVpZ2h0PSIzNzUiIC8+PC9hPiBQaG90b3Nob290IHBvcnRyYWl0IG91dGRvb3JbL2NhcHRpb25dDQoNCkhva2V5IHJlbGlnaW9ucyBhbmQgYW5jaWVudCB3ZWFwb25zIGFyZSBubyBtYXRjaCBmb3IgYSBnb29kIGJsYXN0ZXIgYXQgeW91ciBzaWRlLCBraWQuIEkgZmluZCB5b3VyIGxhY2sgb2YgZmFpdGggZGlzdHVyYmluZy4NCg0KVGhlIG1vcmUgeW91IHRpZ2h0ZW4geW91ciBncmlwLCBUYXJraW4sIHRoZSBtb3JlIHN0YXIgc3lzdGVtcyB3aWxsIHNsaXAgdGhyb3VnaCB5b3VyIGZpbmdlcnMuIEFzIHlvdSB3aXNoLg0KDQpSZW1lbWJlciwgYSBKZWRpIGNhbiBmZWVsIHRoZSBGb3JjZSBmbG93aW5nIHRocm91Z2ggaGltLiBUaGUgcGxhbnMgeW91IHJlZmVyIHRvIHdpbGwgc29vbiBiZSBiYWNrIGluIG91ciBoYW5kcy4gWWUtaGEhIFRoZSBwbGFucyB5b3UgcmVmZXIgdG8gd2lsbCBzb29uIGJlIGJhY2sgaW4gb3VyIGhhbmRzLg0KDQpUaGUgbW9yZSB5b3UgdGlnaHRlbiB5b3VyIGdyaXAsIFRhcmtpbiwgdGhlIG1vcmUgc3RhciBzeXN0ZW1zIHdpbGwgc2xpcCB0aHJvdWdoIHlvdXIgZmluZ2Vycy4gSW4gbXkgZXhwZXJpZW5jZSwgdGhlcmUgaXMgbm8gc3VjaCB0aGluZyBhcyBsdWNrLiBSZWQgRml2ZSBzdGFuZGluZyBieS4NCg0KSSBuZWVkIHlvdXIgaGVscCwgTHVrZS4gU2hlIG5lZWRzIHlvdXIgaGVscC4gSSdtIGdldHRpbmcgdG9vIG9sZCBmb3IgdGhpcyBzb3J0IG9mIHRoaW5nLiBPaCBHb2QsIG15IHVuY2xlLg0KDQo8L2Rpdj4NCjxkaXYgY2xhc3M9InRkLXBhcmFncmFwaC1wYWRkaW5nLTEiPg0KDQpBIHRyZW1vciBpbiB0aGUgRm9yY2UuIFRoZSBsYXN0IHRpbWUgSSBmZWx0IGl0IHdhcyBpbiB0aGUgcHJlc2VuY2Ugb2YgbXkgb2xkIG1hc3Rlci4gSSBoYXZlIHRyYWNlZCB0aGUgUmViZWwgc3BpZXMgdG8gaGVyLiBOb3cgc2hlIGlzIG15IG9ubHkgbGluayB0byBmaW5kaW5nIHRoZWlyIHNlY3JldCBiYXNlLiBSZW1lbWJlciwgYSBKZWRpIGNhbiBmZWVsIHRoZSBGb3JjZSBmbG93aW5nIHRocm91Z2ggaGltLg0KDQpJIG5lZWQgeW91ciBoZWxwLCBMdWtlLiBTaGUgbmVlZHMgeW91ciBoZWxwLiBJJ20gZ2V0dGluZyB0b28gb2xkIGZvciB0aGlzIHNvcnQgb2YgdGhpbmcuIFJlZCBGaXZlIHN0YW5kaW5nIGJ5LiBEb24ndCBiZSB0b28gcHJvdWQgb2YgdGhpcyB0ZWNobm9sb2dpY2FsIHRlcnJvciB5b3UndmUgY29uc3RydWN0ZWQuIFRoZSBhYmlsaXR5IHRvIGRlc3Ryb3kgYSBwbGFuZXQgaXMgaW5zaWduaWZpY2FudCBuZXh0IHRvIHRoZSBwb3dlciBvZiB0aGUgRm9yY2UuIFRoZSBwbGFucyB5b3UgcmVmZXIgdG8gd2lsbCBzb29uIGJlIGJhY2sgaW4gb3VyIGhhbmRzLiBUaGUgcGxhbnMgeW91IHJlZmVyIHRvIHdpbGwgc29vbiBiZSBiYWNrIGluIG91ciBoYW5kcy4NCg0KPC9kaXY+DQo8YSBocmVmPSJ4eHhfcGF0aF90b19maWxlX3h4eC9pbWFnZXMvZGVtby9wMy5qcGciIHJlbD0iYXR0YWNobWVudCB3cC1hdHQtNDEyOCI+PGltZyBjbGFzcz0iIHRkLW1vZGFsLWltYWdlIGFsaWduY2VudGVyIHdwLWltYWdlLTQxMjgiIHNyYz0ieHh4X3BhdGhfdG9fZmlsZV94eHgvaW1hZ2VzL2RlbW8vcDMuanBnIiBhbHQ9IiIgLz48L2E+DQo8ZGl2IGNsYXNzPSJ0ZC1wYXJhZ3JhcGgtcGFkZGluZy0xIj4NCg0KQWxsIHJpZ2h0LiBXZWxsLCB0YWtlIGNhcmUgb2YgeW91cnNlbGYsIEhhbi4gSSBndWVzcyB0aGF0J3Mgd2hhdCB5b3UncmUgYmVzdCBhdCwgYWluJ3QgaXQ/IEEgdHJlbW9yIGluIHRoZSBGb3JjZS4gVGhlIGxhc3QgdGltZSBJIGZlbHQgaXQgd2FzIGluIHRoZSBwcmVzZW5jZSBvZiBteSBvbGQgbWFzdGVyLiBJIGhhdmUgdHJhY2VkIHRoZSBSZWJlbCBzcGllcyB0byBoZXIuIE5vdyBzaGUgaXMgbXkgb25seSBsaW5rIHRvIGZpbmRpbmcgdGhlaXIgc2VjcmV0IGJhc2UuIEEgdHJlbW9yIGluIHRoZSBGb3JjZS4gVGhlIGxhc3QgdGltZSBJIGZlbHQgaXQgd2FzIGluIHRoZSBwcmVzZW5jZSBvZiBteSBvbGQgbWFzdGVyLg0KDQpSZW1lbWJlciwgYSBKZWRpIGNhbiBmZWVsIHRoZSBGb3JjZSBmbG93aW5nIHRocm91Z2ggaGltLiBJIGNhbid0IGdldCBpbnZvbHZlZCEgSSd2ZSBnb3Qgd29yayB0byBkbyEgSXQncyBub3QgdGhhdCBJIGxpa2UgdGhlIEVtcGlyZSwgSSBoYXRlIGl0LCBidXQgdGhlcmUncyBub3RoaW5nIEkgY2FuIGRvIGFib3V0IGl0IHJpZ2h0IG5vdy4gSXQncyBzdWNoIGEgbG9uZyB3YXkgZnJvbSBoZXJlLiBJIGNhbGwgaXQgbHVjay4gWW91IGFyZSBhIHBhcnQgb2YgdGhlIFJlYmVsIEFsbGlhbmNlIGFuZCBhIHRyYWl0b3IhIFRha2UgaGVyIGF3YXkhDQoNCkhva2V5IHJlbGlnaW9ucyBhbmQgYW5jaWVudCB3ZWFwb25zIGFyZSBubyBtYXRjaCBmb3IgYSBnb29kIGJsYXN0ZXIgYXQgeW91ciBzaWRlLCBraWQuIEkgZmluZCB5b3VyIGxhY2sgb2YgZmFpdGggZGlzdHVyYmluZy4gVGhlIG1vcmUgeW91IHRpZ2h0ZW4geW91ciBncmlwLCBUYXJraW4sIHRoZSBtb3JlIHN0YXIgc3lzdGVtcyB3aWxsIHNsaXAgdGhyb3VnaCB5b3VyIGZpbmdlcnMuIEFzIHlvdSB3aXNoLg0KDQpUaGUgbW9yZSB5b3UgdGlnaHRlbiB5b3VyIGdyaXAsIFRhcmtpbiwgdGhlIG1vcmUgc3RhciBzeXN0ZW1zIHdpbGwgc2xpcCB0aHJvdWdoIHlvdXIgZmluZ2Vycy4gSW4gbXkgZXhwZXJpZW5jZSwgdGhlcmUgaXMgbm8gc3VjaCB0aGluZyBhcyBsdWNrLiBSZWQgRml2ZSBzdGFuZGluZyBieS4NCg0KPC9kaXY+DQoNCltjYXB0aW9uIGlkPSJhdHRhY2htZW50XzQxMjIiIGFsaWduPSJhbGlnbmNlbnRlciJdPGEgaHJlZj0ieHh4X3BhdGhfdG9fZmlsZV94eHgvaW1hZ2VzL2RlbW8vcDQuanBnIiByZWw9ImF0dGFjaG1lbnQgd3AtYXR0LTQxMjIiPjxpbWcgY2xhc3M9IiB0ZC1tb2RhbC1pbWFnZSB3cC1pbWFnZS00MTIyIHNpemUtZnVsbCIgc3JjPSJ4eHhfcGF0aF90b19maWxlX3h4eC9pbWFnZXMvZGVtby9wNC5qcGciIGFsdD0iIiAvPjwvYT4gTmV3IGlQaG9uZSA2IGFycml2ZWQgdG9kYXlbL2NhcHRpb25dDQoNCllvdSBhcmUgYSBwYXJ0IG9mIHRoZSBSZWJlbCBBbGxpYW5jZSBhbmQgYSB0cmFpdG9yISBUYWtlIGhlciBhd2F5ISBSZWQgRml2ZSBzdGFuZGluZyBieS4gQWxsIHJpZ2h0LiBXZWxsLCB0YWtlIGNhcmUgb2YgeW91cnNlbGYsIEhhbi4gSSBndWVzcyB0aGF0J3Mgd2hhdCB5b3UncmUgYmVzdCBhdCwgYWluJ3QgaXQ/IEFsZGVyYWFuPyBJJ20gbm90IGdvaW5nIHRvIEFsZGVyYWFuLiBJJ3ZlIGdvdCB0byBnbyBob21lLiBJdCdzIGxhdGUsIEknbSBpbiBmb3IgaXQgYXMgaXQgaXMuDQo8YmxvY2txdW90ZT5EZXNpZ24gaXMgbm90IGp1c3Qgd2hhdCBpdCBsb29rcyBsaWtlIGFuZCBmZWVscyBsaWtlLiBEZXNpZ24gaXMgaG93IGl0IHdvcmtzLjwvYmxvY2txdW90ZT4NCjxkaXYgY2xhc3M9InRkLXBhcmFncmFwaC1wYWRkaW5nLTQiPg0KDQpbY2FwdGlvbiBpZD0iYXR0YWNobWVudF80MTMxIiBhbGlnbj0iYWxpZ25yaWdodCIgd2lkdGg9IjIwNyJdPGEgaHJlZj0ieHh4X3BhdGhfdG9fZmlsZV94eHgvaW1hZ2VzL2RlbW8vcDUuanBnIiByZWw9ImF0dGFjaG1lbnQgd3AtYXR0LTQxMzEiPjxpbWcgY2xhc3M9IiB0ZC1tb2RhbC1pbWFnZSB3cC1pbWFnZS00MTMxIHNpemUtbWVkaXVtIiBzcmM9Inh4eF9wYXRoX3RvX2ZpbGVfeHh4L2ltYWdlcy9kZW1vL3A1LmpwZyIgYWx0PSIiIHdpZHRoPSIyMDciIGhlaWdodD0iMzAwIiAvPjwvYT4gSnVzdCBnb2luZyBkb3duIHRoZSBzdHJlZXRbL2NhcHRpb25dDQoNCllvdSBhcmUgYSBwYXJ0IG9mIHRoZSBSZWJlbCBBbGxpYW5jZSBhbmQgYSB0cmFpdG9yISBUYWtlIGhlciBhd2F5ISBTdGlsbCwgc2hlJ3MgZ290IGEgbG90IG9mIHNwaXJpdC4gSSBkb24ndCBrbm93LCB3aGF0IGRvIHlvdSB0aGluaz8gQSB0cmVtb3IgaW4gdGhlIEZvcmNlLiBUaGUgbGFzdCB0aW1lIEkgZmVsdCBpdCB3YXMgaW4gdGhlIHByZXNlbmNlIG9mIG15IG9sZCBtYXN0ZXIuRXNjYXBlIGlzIG5vdCBoaXMgcGxhbi4gSSBtdXN0IGZhY2UgaGltLCBhbG9uZS4gT2ggR29kLCBteSB1bmNsZS4gSG93IGFtIEkgZXZlciBnb25uYSBleHBsYWluIHRoaXM/IEkgZmluZCB5b3VyIGxhY2sgb2YgZmFpdGggZGlzdHVyYmluZy4NCg0KU3RpbGwsIHNoZSdzIGdvdCBhIGxvdCBvZiBzcGlyaXQuIEkgZG9uJ3Qga25vdywgd2hhdCBkbyB5b3UgdGhpbms/IEluIG15IGV4cGVyaWVuY2UsIHRoZXJlIGlzIG5vIHN1Y2ggdGhpbmcgYXMgbHVjay4NCg0KT2ggR29kLCBteSB1bmNsZS4gSG93IGFtIEkgZXZlciBnb25uYSBleHBsYWluIHRoaXM/IEhleSwgTHVrZSEgTWF5IHRoZSBGb3JjZSBiZSB3aXRoIHlvdS4gSSBmaW5kIHlvdXIgbGFjayBvZiBmYWl0aCBkaXN0dXJiaW5nLg0KDQo8L2Rpdj4=';

                        //post content - With images path
                        $post_content_WITH_images_path = str_replace('xxx_path_to_file_xxx', get_template_directory_uri(), base64_decode($post_content_NO_images_path));


                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path, '', 'featured');
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path, '', 'featured');
                        $td_demo_site->add_featured_image();
                        $td_demo_site->update_post_meta('td_post_theme_settings', 'td_post_template', 'single_template_2');

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path, '', 'featured');
                        $td_demo_site->add_featured_image();
                        $td_demo_site->update_post_meta('td_post_theme_settings', 'td_post_template', 'single_template_4');

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path, '', 'featured');
                        $td_demo_site->add_featured_image();
                        $td_demo_site->update_post_meta('td_post_theme_settings', 'td_post_template', 'single_template_5');

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path, '', 'featured');
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();

                        $td_demo_site->create_page('post', '', $post_content_WITH_images_path);
                        $td_demo_site->add_featured_image();


                        /**
                         *
                         * creating a post with gallery slide
                         */
                        //get some picture id's from `wp_postmeta` table to use with the gallery slide
                        global $wpdb;

                        // get 10 attachments - no need to escape because it dosn't use any user input
                        $result = $wpdb->get_results("SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' ORDER BY meta_id DESC LIMIT 10");

                        //itinerate thru the resultset and, if any, creating an string of id's for the gallery slide
                        $string_of_atth_ids = '';
                        if(!empty($result)) {
	                        foreach( $result as $results ) {
		                        if(!empty($string_of_atth_ids)) {
			                        $string_of_atth_ids .= ',';
		                        }
		                        $string_of_atth_ids .= $results->post_id;
	                        }

	                        $gallery_content = 'W2dhbGxlcnkgdGRfc2VsZWN0X2dhbGxlcnlfc2xpZGU9InNsaWRlIiB0ZF9nYWxsZXJ5X3RpdGxlX2lucHV0PSJNeSBHYWxsZXJ5IiBpZHM9Inh4eF9nYWxsZXJ5X3NsaWRlX2lkc194eHgiXQ0KPGRpdiBjbGFzcz0idGQtcGFyYWdyYXBoLXBhZGRpbmctMSI+DQoNCkFsbCByaWdodC4gV2VsbCwgdGFrZSBjYXJlIHlvdXJzZWxmLiBJIGd1ZXNzIHRoYXQncyB3aGF0IHlvdSdyZSBiZXN0LCBwcmVzZW5jZSBvbGQgbWFzdGVyPyBBIHRyZW1vciBpbiB0aGUgRm9yY2UuIFRoZSBsYXN0IHRpbWUgZmVsdCBpdCB3YXMgaW4gdGhlIHByZXNlbmNlIG9mIG15IG9sZCBtYXN0ZXIuIEkgaGF2ZSB0cmFjZWQgdGhlIFJlYmVsIHNwaWVzIHRvIGhlci4gTm93IHNoZSBpcyBteSBvbmx5IGxpbmsgdG8gZmluZGluZyB0aGVpciBzZWNyZXQgYmFzZS4gQSB0cmVtb3IgaW4gdGhlIEZvcmNlLiBUaGUgbGFzdCB0aW1lIEkgZmVsdCBpdCB3YXMgaW4gdGhlIHByZXNlbmNlIG9mIG15IG9sZCBtYXN0ZXIuDQoNClJlbWVtYmVyLCBhIEplZGkgY2FuIGZlZWwgdGhlIEZvcmNlIGZsb3dpbmcgdGhyb3VnaCBoaW0uIEkgY2FuJ3QgZ2V0IGludm9sdmVkISBJJ3ZlIGdvdCB3b3JrIHRvIGRvISBJdCdzIG5vdCB0aGF0IEkgbGlrZSB0aGUgRW1waXJlLCBJIGhhdGUgaXQsIGJ1dCB0aGVyZSdzIG5vdGhpbmcgSSBjYW4gZG8gYWJvdXQgaXQgcmlnaHQgbm93LiBJdCdzIHN1Y2ggYSBsb25nIHdheSBmcm9tIGhlcmUuIEkgY2FsbCBpdCBsdWNrLiBZb3UgYXJlIGEgcGFydCBvZiB0aGUgUmViZWwgQWxsaWFuY2UgYW5kIGEgdHJhaXRvciEgVGFrZSBoZXIgYXdheSENCjxibG9ja3F1b3RlPkRlc2lnbiBpcyBub3QganVzdCB3aGF0IGl0IGxvb2tzIGxpa2UgYW5kIGZlZWxzIGxpa2UuIERlc2lnbiBpcyBob3cgaXQgd29ya3MuPC9ibG9ja3F1b3RlPg0KVGhlIHBsYW5zIHlvdSByZWZlciB0byB3aWxsIHNvb24gYmUgYmFjayBpbiBvdXIgaGFuZHMuIFRoZSBwbGFucyB5b3UgcmVmZXIgdG8gd2lsbCBzb29uIGJlIGJhY2sgaW4gb3VyIGhhbmRzLiBMZWF2ZSB0aGF0IHRvIG1lLiBTZW5kIGEgZGlzdHJlc3Mgc2lnbmFsLCBhbmQgaW5mb3JtIHRoZSBTZW5hdGUgdGhhdCBhbGwgb24gYm9hcmQgd2VyZSBraWxsZWQuIFJlZCBGaXZlIHN0YW5kaW5nIGJ5Lg0KDQpJIGZpbmQgeW91ciBsYWNrIG9mIGZhaXRoIGRpc3R1cmJpbmcuIEEgdHJlbW9yIGluIHRoZSBGb3JjZS4gVGhlIGxhc3QgdGltZSBJIGZlbHQgaXQgd2FzIGluIHRoZSBwcmVzZW5jZSBvZiBteSBvbGQgbWFzdGVyLiBIZXksIEx1a2UhIE1heSB0aGUgRm9yY2UgYmUgd2l0aCB5b3UuIFJlZCBGaXZlIHN0YW5kaW5nIGJ5LiBSZWQgRml2ZSBzdGFuZGluZyBieS4gWW91ciBleWVzIGNhbiBkZWNlaXZlIHlvdS4gRG9uJ3QgdHJ1c3QgdGhlbS4NCg0KPC9kaXY+';

	                        $td_demo_site->create_page('post', 'Image Gallery - Travelling with kids on Queensland', str_replace('xxx_gallery_slide_ids_xxx', $string_of_atth_ids, base64_decode($gallery_content)));
	                        $td_demo_site->add_featured_image();
	                        $td_demo_site->update_post_meta('td_post_theme_settings', 'td_post_template', 'single_template_7');
                        }


                        //save all the themes settings
                        update_option(TD_THEME_OPTIONS_NAME, td_global::$td_options );



                        ?>

                    </div>


                    <div class="td-clear"></div>






                    <!-- end box row -->

                </div>



                <?php echo td_panel_generator::box_end();?>
            </div>



        </div>



    </div>
</div>

<div class="td-clear"></div>

<div class="td-panel-main-footer">

</div>

</div>

<div class="td-clear"></div>
</form>
</div>
