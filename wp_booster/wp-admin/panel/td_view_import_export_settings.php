<?php
$show_update_msg = 0;

if(!empty($_REQUEST['action_import']) and $_REQUEST['action_import'] == 'import_theme_settings') {

    if(!empty($_POST['td_update_theme_options']['tds_update_theme_options'])) {
        if(update_option(TD_THEME_OPTIONS_NAME, @unserialize(@base64_decode($_POST['td_update_theme_options']['tds_update_theme_options'])))) {
            $show_update_msg = 1;
        }
    }

}
?>
<form id="td_panel_import_export_settings" name="td_panel_import_export_settings" action="?page=td_theme_panel&td_page=td_view_import_export_settings&action_import=import_theme_settings" method="post" onsubmit="return confirm('Are you sure you want to import this settings?\nIt will overwrite the one that you have now!');">
<input type="hidden" name="action" value="td_ajax_update_panel">
<div class="td_displaying_saving"></div>
<div class="td_wrapper_saving_gifs">
    <img class="td_displaying_saving_gif" src="<?php echo get_template_directory_uri();?>/includes/wp_booster/wp-admin/images/panel/loading.gif">
    <img class="td_displaying_ok_gif" src="<?php echo get_template_directory_uri()?>/includes/wp_booster/wp-admin/images/panel/saved.gif">
</div>


<div class="wrap">

<div class="td-container-wrap">

<div class="td-panel-main-header">
    <img src="<?php echo get_template_directory_uri() . '/includes/wp_booster/wp-admin/images/panel/panel-wrap/panel-logo.png'?>" alt=""/>
    <span><?php echo sprintf('%s - Theme panel', strtoupper(TD_THEME_NAME)) ?></span>
</div>


<div id="td-container-left">
    <div id="td-container-right">
        <div id="td-col-left">
            <ul class="td-panel-menu">
                <li class="td-welcome-menu">
                    <a data-td-is-back="yes" class="td-panel-menu-active" href="?page=td_theme_panel">
                        <span class="td-sp-nav-icon td-ico-welcome"></span>
                        THEME SETTINGS
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
        <div id="td-col-rigth" class="td-panel-content" style="min-height: 900px">

            <!-- Export theme settings -->
            <div id="td-panel-welcome" class="td-panel-active td-panel">

                <?php echo td_panel_generator::box_start('Importing / exporting theme settings'); ?>

                <div class="td-box-row">
                    <div class="td-box-description td-box-full">
                        <span class="td-box-title">EXPORT THEME SETTINGS</span>
                        <p>
                            This box contains all the panel options encoded as a string so you can easily copy them and move them to another server.
                        </p>
                    </div>
                    <div class="td-box-control-full">
                        <?php
                        echo td_panel_generator::textarea(array(
                            'ds' => 'td_read_theme_options',
                            'option_id' => 'tds_read_theme_options',
                            'value' => base64_encode(serialize(get_option(TD_THEME_OPTIONS_NAME)))
                        ));
                        ?>
                    </div>
                    <div class="td-box-row-margin-bottom"></div>
                </div>



                <div class="td-box-row">
                    <div class="td-box-description td-box-full">
                        <span class="td-box-title">IMPORT THEME SETTINGS</span>
                        <p>Paste your theme settings string here and the theme will load them into the database</p>
                    </div>
                    <div class="td-box-control-full">
                        <?php
                        echo td_panel_generator::textarea(array(
                            'ds' => 'td_update_theme_options',
                            'option_id' => 'tds_update_theme_options'
                        ));
                        ?>
                    </div>
                    <div class="td-box-row-margin-bottom"></div>
                </div>

                <div class="td-box-row">
                    <input type="submit" class="td-big-button td-button-remove-border" value="Import theme settings">
                </div>

                <?php echo td_panel_generator::box_end();?>
            </div>


        </div>
    </div>
</div>

<div class="td-clear"></div>

</div>

<div class="td-clear"></div>
</form>
</div>
<?php if($show_update_msg == 1){?><script type="text/javascript">alert('Import is done!');</script><?php }?>
<br><br><br><br><br><br><br>
