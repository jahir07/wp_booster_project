<!-- CUSTOM Javascript -->
<?php echo td_panel_generator::box_start('Custom Javascript'); ?>

    <!-- YOUR CUSTOM Javascript -->
    <div class="td-box-row td-custom-javascript">
        <div class="td-box-description">
            <span class="td-box-title">YOUR CUSTOM JAVASCRIPT</span>
            <p>Paste here your custom Javascript</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'tds_custom_javascript',
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>