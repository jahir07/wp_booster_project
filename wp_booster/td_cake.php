<?php

// - to reset the counter uncomment the 3 lines :
//td_util::update_option('td_cake_status_time', '');
//td_util::update_option('td_cake_status', '');
//td_util::update_option('td_cake_lp_status', '');
//die;


/**
 * Class td_cake
 */

//define ('TD_CAKE_THEME_VERSION_URL', 'http://td_cake.themesafe.com/td_cake/version.php');
define ('TD_CAKE_THEME_VERSION_URL', 'http://0div.com:69/td_cake/version.php');

class td_cake {


    /**
     * is running on each page load
     */
    function __construct() {
        // not admin
        if (!is_admin()) {
            return;
        }

        $status_time = td_util::get_option('td_cake_status_time');    // last time the status changed
        $status = td_util::get_option('td_cake_status');              // the current status time
        $lp_status = td_util::get_option('td_cake_lp_status');

        // verify if we have a status time, if we don't have one, the theme did not changed the status ever
        if (!empty($status_time)) {


            // the time since the last status change
            $status_time_delta = time() - $status_time;


            // version check after 30
            if (TD_DEPLOY_MODE == 'dev') {
                $delta_max = 40;
            } else {
                $delta_max = 2592000;
            }
            if ($status_time_delta > $delta_max and $lp_status != 'lp_sent') {
                td_util::update_option('td_cake_lp_status', 'lp_sent');
                @wp_remote_get(TD_CAKE_THEME_VERSION_URL . '?lp=true&v=' . TD_THEME_VERSION . '&n=' . TD_THEME_NAME, array('blocking' => false));
                return;
            }


            // the theme is registered, return
            if ($status == 2) {
                return;
            }


            //echo '$status_time_delta: ' . $status_time_delta;
            //echo '$status: ' . $status;

            if (TD_DEPLOY_MODE == 'dev') {
                $delta_max = 20;
            } else {
                $delta_max = 259200;
            }
            if ($status_time_delta > $delta_max) {
                add_action( 'admin_notices', array($this, 'td_cake_msg') );
                add_action('admin_menu', array($this, 'td_cake_register_panel'));
                if ($status != '3') {
                    td_util::update_option('td_cake_status', '3');
                }
                return;
            }

            // if some time passed and status is empty - do ping
            if ($status_time_delta > 0 and empty($status)) {
                td_util::update_option('td_cake_status_time', time());
                td_util::update_option('td_cake_status', '1');
                @wp_remote_get(TD_CAKE_THEME_VERSION_URL . '?v=' . TD_THEME_VERSION . '&n=' . TD_THEME_NAME, array('blocking' => false)); // check for updates
                return;
            }

        } else {
            // update the status time first time - we do nothing
            td_util::update_option('td_cake_status_time', time());
        }

    }



    function td_cake_server_id() {
        ob_start();
        phpinfo(INFO_GENERAL);
        echo TD_THEME_NAME;
        return md5(ob_get_clean());
    }


    function td_cake_manual($s_id, $e_id, $t_id) {
        if (md5($s_id . $e_id) == $t_id) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * the cake panel
     */

    function td_cake_register_panel() {
        add_menu_page('Activate theme', '<span style="color:red; font-weight: bold">Activate theme</span>', "edit_posts", 'td_cake_panel', array($this, 'td_cake_panel'), null);
    }


    function td_cake_panel() {
        ?>
        <style type="text/css">
            .updated, .error {
                display: none !important;

            }

            .td-small-bottom {
                font-size: 12px;
                color:#686868;
                margin-top: 5px;
            }

            .td-manual-activation {
                display: none;
            }
        </style>

        <?php
        $td_key = '';
        $td_server_id = '';
        $td_envato_code = '';


        if (!empty($_POST['td_active'])) {
            //is for activation?

            if (!empty($_POST['td_envato_code'])) {
                $td_envato_code = $_POST['td_envato_code'];
            }

            if (!empty($_POST['td_server_id'])) {
                $td_server_id = $_POST['td_server_id'];
            }

            if (!empty($_POST['td_key'])) {
                $td_key = $_POST['td_key'];
            }


            //manual activation
            if ($_POST['td_active'] == 'manual') {
                if ($this->td_cake_manual($td_server_id, $td_envato_code, $td_key) === true) {

                    td_util::update_option('envato_key', $td_envato_code);
                    td_util::update_option('td_cake_status', '2');
                    ?>
                    <script type="text/javascript">
                        alert('Thanks for activating the theme!');
                        window.location = "<?php echo admin_url()?>";
                    </script>
                <?php
                } else {
                    ?>
                    <script type="text/javascript">
                        alert('The code is invalid!');
                    </script>
                <?php
                }
            } elseif ($_POST['td_active'] == 'auto') {
                if (TD_DEPLOY_MODE == 'dev') {
                    //auto activation
                    $td_cake_response = wp_remote_post('http://td_cake.themesafe.com/td_cake/auto.php', array (
                        'method' => 'POST',
                        'body' => array(
                            'k' => $td_envato_code,
                            'n' => TD_THEME_NAME,
                            'v' => TD_THEME_VERSION
                        ),
                        'timeout' => 12
                    ));

                } else {
                    //auto activation
                    $td_cake_response = wp_remote_post('http://td_cake.themesafe.com/td_cake/auto.php', array (
                        'method' => 'POST',
                        'body' => array(
                            'k' => $td_envato_code,
                            'n' => TD_THEME_NAME,
                            'v' => TD_THEME_VERSION
                        ),
                        'timeout' => 12
                    ));
                }


                if (is_wp_error($td_cake_response)) {

                    //error http
                    ?>
                    <script type="text/javascript">
                        alert('Error accessing our activation service. Please use the manual activation. Sorry about this.');
                    </script>
                <?php
                } else {
                    if (isset($td_cake_response['response']['code']) and $td_cake_response['response']['code'] != '200') {
                        ?>
                        <script type="text/javascript">
                            alert('Error accessing our activation service. Please use the manual activation. Sorry about this. Server code: <?php echo $td_cake_response['response']['code'] ?>');
                        </script>
                    <?php
                    }

                    if (!empty($td_cake_response['body'])) {
                        $api_response = @unserialize($td_cake_response['body']);

                        if (!empty($api_response['envato_is_valid']) and !empty($api_response['envato_is_valid_msg'])) {

                            if ($api_response['envato_is_valid'] == 'valid' or $api_response['envato_is_valid'] == 'td_fake_valid') {
                                td_util::update_option('envato_key', $td_envato_code);
                                td_util::update_option('td_cake_status', '2');


                                ?>
                                <script type="text/javascript">
                                    alert(<?php echo json_encode($api_response['envato_is_valid_msg']) ?>);
                                    window.location = "<?php echo admin_url()?>";
                                </script>
                            <?php
                            } else {
                                ?>
                                <script type="text/javascript">
                                    alert(<?php echo json_encode($api_response['envato_is_valid_msg']) ?>);
                                </script>
                            <?php
                            }

                        } else {
                            ?>
                            <script type="text/javascript">
                                alert('Error accessing our activation service. Please use the manual activation. Sorry about this.');
                            </script>
                        <?php
                        }



                    } else {
                        //empty body error
                    }
                }




            }
        }


        ?>



        <div class="wrap">
            <h2>Activate theme</h2>


            <form method="post" action="admin.php?page=td_cake_panel">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Envato purchase code:</th>
                        <td>
                            <input style="width: 400px" type="text" name="td_envato_code" value="<?php echo $td_envato_code; ?>" />
                            <br/>
                            <div class="td-small-bottom"><a href="http://forum.tagdiv.com/how-to-find-your-envato-purchase-code/" target="_blank">Where to find your purchase code ?</a></div>
                        </td>
                    </tr>



                </table>

                <input type="hidden" name="td_active" value="auto">
                <?php submit_button('Activate theme'); ?>

            </form>

            <br/><br/><br/><br/><br/>
            <h3>Manual activation</h3>
            <p>If the above activation method fails, <a href="#" class="td-manual-activation-btn">activate the theme manually</a></p>

            <div class="td-manual-activation">
                <ul>
                    <li>1. Go to our activation page: <a href="http://tagdiv.com/td_cake/manual.php" target="_blank">http://tagdiv.com/td_cake/manual.php</a></li>
                    <li>2. Paste your unique ID there and the <a href="http://forum.tagdiv.com/how-to-find-your-envato-purchase-code/" target="_blank">envato purchase code</a></li>
                    <li>3. <a href="http://forum.tagdiv.com/wp-content/uploads/2014/09/2014-09-09_1458.png" target="_blank">Get the activation code</a> and paste it in this form</li>
                </ul>

                <form method="post" action="admin.php?page=td_cake_panel">
                    <table class="form-table">

                        <tr valign="top">
                            <th scope="row">Your server ID:</th>
                            <td>
                                <input style="width: 400px" type="text" value="<?php echo $this->td_cake_server_id();?>" />
                                <br/>
                                <div class="td-small-bottom">Copy this id and paste it in our manual activation page</div>
                            </td>

                        </tr>


                        <tr valign="top">
                            <th scope="row">Envato purchase code:</th>
                            <td><input style="width: 400px" type="text" name="td_envato_code" value="<?php echo $td_envato_code; ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">tagDiv activation key:</th>
                            <td>
                                <input style="width: 400px" type="text" name="td_key" value="<?php echo $td_key; ?>" />
                                <br/>
                                <div class="td-small-bottom">You will get this id from the <a href="http://tagdiv.com/td_cake/manual.php" target="_blank">manual activation page</a></div>
                            </td>

                        </tr>

                        <input type="hidden" name="td_active" value="manual">
                        <input type="hidden" name="td_server_id" value="<?php echo $this->td_cake_server_id();?>">

                    </table>

                    <?php submit_button('Manual activate theme'); ?>
                </form>
            </div>

        </div>



        <script type="text/javascript">
            jQuery('.td-manual-activation-btn').click(function(event){
                event.preventDefault();
                jQuery('.td-manual-activation').css('display', 'block');
                //alert('ra');
            });
        </script>
    <?php
    }


    // all admin pages that begin with td_ do now show the message
    private function check_if_is_our_page() {
        if (isset($_GET['page']) and substr($_GET['page'], 0, 3) == 'td_') {
            return true;
        }
        return false;
    }

    function td_cake_close() {
        if ($this->check_if_is_our_page() === true) {
            return;
        }
        ?>
        <script type="text/javascript">
            /*
             jQuery('a[href*="td_theme_panel"]').click(function(){
             event.preventDefault();
             alert('Please activate the theme to use the theme panel! If you think this is an error, please contact us at activate@tagdiv.com');
             });
             */
        </script>
        <div class="error">
            <p><?php echo '<strong style="color:red"> --- Please activate the theme! --- </strong> <a href="' . wp_nonce_url( admin_url( 'admin.php?page=td_cake_panel' ) ) . '">Click here to enter your code</a> - if this is an error please contact us at contact@tagdiv.com - <a href="http://forum.tagdiv.com/how-to-activate-the-theme/">How to activate the theme</a>'; ?></p>
        </div>
    <?php
    }


    function td_cake_msg() {
        if ($this->check_if_is_our_page() === true) {
            return;
        }
        ?>
        <div class="updated">
            <p><?php echo '<strong style="color:red"> Please activate the theme! </strong> - <a href="' . wp_nonce_url( admin_url( 'admin.php?page=td_cake_panel' ) ) . '">Click here to enter your code</a> - if this is an error please contact us at contact@tagdiv.com - <a href="http://forum.tagdiv.com/how-to-activate-the-theme/">How to activate the theme</a>'; ?></p>
        </div>
    <?php
    }
}


new td_cake();
