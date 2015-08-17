<!-- FOOTER SETTINGS -->
<?php echo td_panel_generator::box_start('Footer settings', true); ?>

<div class="td-box-row">
    <div class="td-box-description td-box-full">
        <span class="td-box-title">More information:</span>
        <p>The footer uses sidebars to show information. Here you can customize the number of sidebars and the layout. To add content to the footer head go to the widgets section and drag widget to the Footer 1, Footer 2 and Footer 3 sidebars.</p>
        <p>Some footer templates contain predefined content, like <strong>Info content</strong> and can be set from <strong>Footer info content</strong> section.</p>
    </div>
    <div class="td-box-row-margin-bottom"></div>
</div>


<!-- Enable footer -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">SHOW FOOTER</span>
        <p>Show or hide the footer</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::checkbox(array(
            'ds' => 'td_option',
            'option_id' => 'tds_footer',
            'true_value' => '',
            'false_value' => 'no'
        ));
        ?>
    </div>
</div>



<!-- LAYOUT -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">Footer templates</span>
        <p>Set the footer template</p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::visual_select_o(array(
            'ds' => 'td_option',
            'option_id' => 'tds_footer_template',
            'values' => td_api_footer_template::_helper_to_panel_values()
        ));
        ?>
    </div>
</div>

<?php echo td_panel_generator::box_end();?>




<!-- FOOTER PREDEFINED CONTENT -->
<?php echo td_panel_generator::box_start('Footer info content', false); ?>

    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <ul>
                <li>Footer logo - different one from the header logo. If footer logo is not specified, the site will load the default normal logo.</li>
                <li>Footer text - usually it's a text about your sites topic</li>
                <li>Your contact email address</li>
                <li>Social icons - to customize what social icons appear in the footer, go to <strong>Social Networks</strong> section.</li>
            </ul>
        </div>
        <div class="td-box-row-margin-bottom"></div>
    </div>

    <!-- logo -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER LOGO</span>
            <p>Upload your logo</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::upload_image(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_logo_upload'
            ));
            ?>
        </div>
    </div>

    <!-- logo retina -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER RETINA LOGO</span>
            <p>Upload your retina logo (double size)</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::upload_image(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_retina_logo_upload'
            ));
            ?>
        </div>
    </div>

    <!-- footer text -->
    <div class="td-box-row td-custom-css">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER TEXT</span>
            <p>Write here your footer text</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_text',
            ));
            ?>
        </div>
    </div>


    <!-- Footer contact email -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">YOUR EMAIL ADDRESS</span>
            <p>Your email address</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::input(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_email'
            ));
            ?>
        </div>
    </div>


    <!-- Enable social icons -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">SHOW SOCIAL ICONS</span>
            <p>Show or hide the social icons, to setup the Social icons go to <strong>Social Networks</strong></p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::checkbox(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_social',
                'true_value' => '',
                'false_value' => 'no'
            ));
            ?>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>


<!-- FOOTER BACKGROUND -->
<?php echo td_panel_generator::box_start('Footer background', false); ?>

    <!-- BACKGROUND UPLOAD -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER BACKGROUND</span>
            <p>Upload a footer background image</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::upload_image(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_background_image'
            ));
            ?>
        </div>
    </div>

    <!-- Background Repeat -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">REPEAT</span>
            <p>How the background image will be displayed</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::radio_button_control(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_background_repeat',
                'values' => array(
                    array('text' => 'No Repeat', 'val' => ''),
                    array('text' => 'Tile', 'val' => 'repeat'),
                    array('text' => 'Tile Horizontally', 'val' => 'repeat-x'),
                    array('text' => 'Tile Vertically', 'val' => 'repeat-y')
                )
            ));
            ?>
        </div>
    </div>

    <!-- Background Size -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">SIZE</span>
            <p>Set the background image size</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::radio_button_control(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_background_size',
                'values' => array(
                    array('text' => 'Auto', 'val' => ''),
                    array('text' => 'Full Width', 'val' => '100% auto'),
                    array('text' => 'Full Height', 'val' => 'auto 100%'),
                    array('text' => 'Cover', 'val' => 'cover'),
                    array('text' => 'Contain', 'val' => 'contain')
                )
            ));
            ?>
        </div>
    </div>

    <!-- Background position -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">POSITION</span>
            <p>Position your background image</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::radio_button_control(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_background_position',
                'values' => array(
                    array('text' => 'Bottom', 'val' => ''),
                    array('text' => 'Center', 'val' => 'center center'),
                    array('text' => 'Top', 'val' => 'center top')
                )
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>


<!-- SUB-FOOTER SETTINGS -->
<?php echo td_panel_generator::box_start('Sub footer settings', false); ?>


    <!-- text -->
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <span class="td-box-title">More information:</span>
            <p>The sub footer section is the content under the main footer. It usually includes a copyright text and a menu spot on the right</p>
        </div>
        <div class="td-box-row-margin-bottom"></div>
    </div>

    <!-- Enable sub-footer -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">SHOW SUB-FOOTER</span>
            <p>Show or hide the sub-footer</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::checkbox(array(
                'ds' => 'td_option',
                'option_id' => 'tds_sub_footer',
                'true_value' => '',
                'false_value' => 'no'
            ));
            ?>
        </div>
    </div>

    <!-- Footer copyright text -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER COPYRIGHT TEXT</span>
            <p>Set footer copyright text</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::input(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_copyright'
            ));
            ?>
        </div>
    </div>


    <!-- Copyright symbol -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">COPYRIGHT SYMBOL</span>
            <p>Show or hide the footer copyright symbol</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::checkbox(array(
                'ds' => 'td_option',
                'option_id' => 'tds_footer_copy_symbol',
                'true_value' => '',
                'false_value' => 'no'
            ));
            ?>
        </div>
    </div>

    <!-- Footer menu -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">FOOTER MENU</span>
            <p>Select a menu for the sub footer</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::dropdown(array(
                'ds' => 'wp_theme_menu_spot',
                'option_id' => 'footer-menu',
                'values' => td_panel_generator::get_user_created_menus()
            ));
            ?>
        </div>
    </div>
<?php echo td_panel_generator::box_end();?>