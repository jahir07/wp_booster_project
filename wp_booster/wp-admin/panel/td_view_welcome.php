<?php
/**
 * Created by ra on 5/15/2015.
 */

require_once "td_view_header.php";
?>

<div class="td-admin-wrap">


    <?php if (!is_plugin_active('js_composer/js_composer.php')) { ?>
        <div class="td-admin-box-text td-admin-required-plugins">
            <div class="td-admin-required-plugins-wrap">
                <p><strong>Please install Visual Composer</strong></p>
                <p>Visual Composer is a required plugin for this theme to work best.</p>
                <a class="button button-primary" href="admin.php?page=td_theme_plugins">Go to plugin installer</a>
            </div>
        </div>
    <?php } ?>

    <div class="td-admin-welcome-left">
        <h3>Thanks for installing <?php echo TD_THEME_NAME?>!</h3>
        <p>Passionate and hard working constantly looking for visual perfection, we love designing and coding WordPress themes. Continuously researching the news niche for the latest trends in design and functionality, open to new ideas, we encourage our users to provide constructive feedback.</p>
        <p>We worked very hard and spent a great amount of time, practically thousands of hours, to create this great theme and we will do our absolute best to support it and fix all the issues you may encounter.</p>

    </div>
    <!--
    <div class="td-admin-box-text">
        <p>Thank you for choosing the best theme we have ever build!</p>
		<a class="button button-primary" href="http://demo.tagdiv.com/<?php echo strtolower(TD_THEME_NAME);?>" target="_blank">View demo</a>
		<a class="button button-primary" href="http://themeforest.net/user/tagDiv" target="_blank">Our portfolio</a>
	</div>
	-->
</div>