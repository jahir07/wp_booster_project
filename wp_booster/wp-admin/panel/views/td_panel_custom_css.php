<!-- CUSTOM CSS -->
<?php echo td_panel_generator::box_start('Custom CSS'); ?>

    <!-- YOUR CUSTOM CSS -->
    <div class="td-box-row td-custom-css">
        <div class="td-box-description">
            <span class="td-box-title">YOUR CUSTOM CSS</span>
            <p>Paste here your custom CSS</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_custom_css',
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>



<!-- ADVANCED CSS -->
<?php echo td_panel_generator::box_start('Advanced CSS', false); ?>

    <!-- Responsive css -->
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <span class="td-box-title">RESPONSIVE CSS</span>
            <p>Paste your custom css in the appropriate box, to run only on a specific device
            </p>
        </div>
    </div>


    <!-- Desktop CSS -->
    <div class="td-box-row">
        <div class="td-box-description">
            <div class="td-display-inline-block">
                <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/resp-desktop.png';?>">
            </div>
            <div class="td-display-inline-block">
                <span class="td-box-title">DESKTOP</span>
                <p>1024px +</p>
            </div>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_responsive_css_desktop',
            ));
            ?>
        </div>
    </div>


    <!-- iPad portrait CSS -->
    <div class="td-box-row">
        <div class="td-box-description">
            <div class="td-display-inline-block">
                <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/resp-ipadv.png';?>">
            </div>
            <div class="td-display-inline-block">
                <span class="td-box-title">IPAD PORTRAIT</span>
                <p>768 - 1023px</p>
            </div>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_responsive_css_ipad_portrait',
            ));
            ?>
        </div>
    </div>


    <!-- PHONES CSS -->
    <div class="td-box-row">
        <div class="td-box-description">
            <div class="td-display-inline-block">
                <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/resp-phone.png';?>">
            </div>
            <div class="td-display-inline-block">
                <span class="td-box-title">PHONES</span>
                <p>0 - 767px</p>
            </div>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_responsive_css_phone',
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>


<!-- Add custom body class -->
<?php echo td_panel_generator::box_start('Custom Body Class(s)', false); ?>

<!-- Add custom body class -->
<div class="td-box-row">
    <div class="td-box-description">
        <span class="td-box-title">CUSTOM BODY CLASS(s)</span>
        <p>You can add one or more classes on theme body element. If you need more then one class, add them with a space between them.</p><p>Ex: class-test-1 class-test-2 </p>
    </div>
    <div class="td-box-control-full">
        <?php
        echo td_panel_generator::input(array(
            'ds' => 'td_option',
            'option_id' => 'td_body_classes'
        ));
        ?>
    </div>
</div>

<?php echo td_panel_generator::box_end();