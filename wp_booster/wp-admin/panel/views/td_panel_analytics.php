<!-- Analitycs -->
<?php echo td_panel_generator::box_start('Analitycs'); ?>

    <!-- GOOGLE ASYNCHRONOUS ADS -->
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <span class="td-box-title">GOOGLE ANALYTICS CODE</span>
            <p>Google analytics heps track your site traffic</p>
        </div>
    </div>


    <!-- paste your code here -->
    <div class="td-box-row">
        <div class="td-box-description">
            <span class="td-box-title">PASTE YOUR CODE HERE</span>
            <p>Google Analitycs code</p>
        </div>
        <div class="td-box-control-full">
            <?php
            echo td_panel_generator::textarea(array(
                'ds' => 'td_option',
                'option_id' => 'td_analytics',
            ));
            ?>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>