<?php
/**
 * Created by ra on 5/15/2015.
 */

if (current_user_can( 'activate_plugins' )) {
    // deactivate a plugin from tgm
    if (isset($_GET['td_deactivate_plugin_slug'])) {
        $td_deactivate_plugin_slug = $_GET['td_deactivate_plugin_slug'];
        if (!empty($td_deactivate_plugin_slug)) {
            $plugins = TGM_Plugin_Activation::$instance->plugins;
            foreach ($plugins as $plugin) {
                if ($plugin['slug'] == $td_deactivate_plugin_slug) {
                    deactivate_plugins($plugin['file_path']);
                    ?>
                    <script type="text/javascript">
                        window.location = "admin.php?page=td_theme_plugins";
                    </script>
                    <?php
                    break;
                }
            }
        }
    }

    // Activate a plugin
    if (isset($_GET['td_activate_plugin_slug'])) {
            $td_activate_plugin_slug = $_GET['td_activate_plugin_slug'];
            if (!empty($td_activate_plugin_slug)) {
                $plugins = TGM_Plugin_Activation::$instance->plugins;

                foreach ($plugins as $plugin) {
                    if ($plugin['slug'] == $td_activate_plugin_slug) {
                        activate_plugins($plugin['file_path']);
                        ?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=td_theme_plugins";
                        </script>
                        <?php
                        break;
                    }
                }
            }
    }
}



require_once "td_view_header.php";


//print_r(get_plugins());



$theme_plugins = TGM_Plugin_Activation::$instance->plugins;

?>



<div class="td-admin-wrap about-wrap theme-browser">
    <h1>Install premium plugins</h1>
    <div class="about-text">
        <p>
            Install the included plugins with ease using this panel. All the plugins are well tested to work with the theme and we keep them up to date.
            The themes comes packed with the following premium plugins:
        </p>
    </div>


   <div class="td-admin-columns">


<?php
$wp_plugin_list = get_plugins();


    //asort($theme_plugins);
    foreach ($theme_plugins as $theme_plugin) {

        $tmp_class = 'td-plugin-not-installed';

        if (is_plugin_active( $theme_plugin['file_path'])) {
            $tmp_class = 'td-plugin-active';
        }
        else if (isset($wp_plugin_list[$theme_plugin['file_path']])) {
            $tmp_class = 'td-plugin-inactive';
        }


        //echo '<br>';
        //echo $theme_plugin['file_path'] . ' ' . is_plugin_inactive( $theme_plugin['file_path'] ) . '<br>';

        //print_r(is_plugin_inactive( $theme_plugin['file_path'] ));

        ?>

        <div class="td-wp-admin-plugin theme <?php echo $tmp_class ?>">

            <!-- Import content -->
            <div class="theme-screenshot">
                <img class="td-demo-thumb" src="<?php echo $theme_plugin['img'] ?>"/>
            </div>

            <div class="td-admin-title">
                <div class="td-progress-bar-wrap"><div class="td-progress-bar"></div></div>
                <h3 class="theme-name"><?php echo $theme_plugin['name'] ?></h3>
            </div>

            <div class="td-admin-checkbox td-small-checkbox">
                <p><?php echo $theme_plugin['text'] ?></p>
            </div>

            <div class="theme-actions">
                <a class="button button-primary td-button-install-plugin" href="<?php
                echo esc_url( wp_nonce_url(
                    add_query_arg(
                        array(
                            'page'		  	=> urlencode(TGM_Plugin_Activation::$instance->menu),
                            'plugin'		=> urlencode($theme_plugin['slug']),
                            'plugin_name'   => urlencode($theme_plugin['name']),
                            'plugin_source' => urlencode($theme_plugin['source']),
                            'tgmpa-install' => 'install-plugin',
                            'return_url' => 'td_theme_plugins'
                        ),
                        admin_url('themes.php')
                    ),
                    'tgmpa-install'
                ));
                ?>">Install</a>
                <a class="button button-secondary td-button-uninstall-plugin" href="<?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'page'		  	            => urlencode('td_theme_plugins'),
                            'td_deactivate_plugin_slug'	=> urlencode($theme_plugin['slug']),
                        ),
                        admin_url('admin.php')
                    ));
                ?>"">Deactivate</a>

                <a class="button button-primary td-button-activate-plugin" href="<?php
                echo esc_url(
                    add_query_arg(
                        array(
                            'page'		  	            => urlencode('td_theme_plugins'),
                            'td_activate_plugin_slug'	=> urlencode($theme_plugin['slug']),
                        ),
                        admin_url('admin.php')
                    ));
                ?>"">Activate</a>
            </div>
        </div>






        <?php
    }
?>

    </div>
    <hr style="clear:left"/>
    <h3>Tested Plugins:</h3>
    <div class="about-text">
        <p>With each theme release we provide a list of fully suported plugins. In order to make the plugins look and work better, the theme may add custom stylesheets and hook custom code to them.
            We manually inspect each plugin periodically. If we missed something, feel free to contact us!</p>
    </div>

    <div class="td-supported-plugin-list">
        <div class="td-supported-plugin">Contact form 7 <span>- used to make contact forms</span></div>
        <div class="td-supported-plugin">bbPress <span>- forum plugin</span></div>
        <div class="td-supported-plugin">BuddyPress<span>- social network plugin</span></div>
        <div class="td-supported-plugin">Font Awesome 4 Menus<span>- icon pack, supported in the theme menus</span></div>
        <div class="td-supported-plugin">Jetpack  <span>- plugin with lots of features *it may slow down your site</span></div>
        <div class="td-supported-plugin">WooCommerce <span>- eCommerce solution</span></div>
        <div class="td-supported-plugin">WordPress SEO <span> - SEO plugin</span></div>
        <div class="td-supported-plugin">Wp User Avatar <span> - Change users avatars</span></div>
    </div>



</div>
