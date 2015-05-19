


<!-- Theme information -->
<?php echo td_panel_generator::box_start('Theme information'); ?>

    <!-- Theme Name -->
    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title">Theme Name: </span><span><?php echo TD_THEME_NAME?></span>
        </div>
    </div>

    <!-- VERSION -->
    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title">Version: </span><span><?php echo TD_THEME_VERSION?></span>
        </div>
    </div>

    <!-- AUTHOR -->
    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title">Author: </span><span><a href="http://themeforest.net/user/tagDiv">tagDiv</a> (our portfolio)</span>
        </div>
    </div>



    <!-- Support forum (recommended)  -->
    <br><br>
    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title" style="color:red">Please include your <span style="text-decoration: underline">site url</span> when you report any problems:</span>
        </div>
    </div>
    <br>

    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title">Support forum (recommended): </span><span><a href="http://forum.tagdiv.com">forum.tagdiv.com</a></span>
        </div>
    </div>


    <!-- Documentation URL  -->
    <div class="td-box-row">
        <div class="td-box-description_text">
            <span class="td-box-title">Documentation URL: </span><span><a href="<?php echo TD_THEME_DOC_URL?>"><?php echo TD_THEME_DOC_URL?></a></span>
        </div>
    </div>

    <!-- Demo URL  -->
    <div class="td-box-row td-box-row-end">
        <div class="td-box-description_text">
            <span class="td-box-title">Demo URL: </span><span><a href="<?php echo TD_THEME_DEMO_URL?>"><?php echo TD_THEME_DEMO_URL?></a></span>
        </div>
    </div>



<?php echo td_panel_generator::box_end();?>



<!-- Thanks -->
<?php echo td_panel_generator::box_start('Thanks'); ?>

    <!-- Thanks -->
    <div class="td-box-row">
        <div class="td-box-description td-box-full">
            <p>Thanks for using our theme, we had worked very hard to release a great product and we will do our absolute best to support this theme and fix all the issues.<br><br>Marius, Radu O, Emil G, Lucian and Nicu from tagDiv - <?php echo date("Y");?></p>
        </div>
    </div>

<?php echo td_panel_generator::box_end();?>







<!-- Import / Export Theme Settings -->
<?php echo td_panel_generator::box_start('Import &amp; export theme settings', false); ?>

<div class="td-box-row">
    <div class="td-box-description td-box-full">
            <p>Save and backup all the theme settings using this panel.</p>
    </div>
    <div class="td-box-row-margin-bottom"></div>
</div>

<div class="td-box-row">
        <a class="td-big-button" href="?page=td_theme_panel&td_page=td_view_import_export_settings">Go to: Import &amp; export panel</a>
    </div>

<?php echo td_panel_generator::box_end();?>




