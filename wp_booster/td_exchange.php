<?php


class td_exchange {

    private static $caching_time = 10800;  // 3 hours

    /**
     * Used by all the shortcodes + widget to render the exchange. The top bar has a separate function bellow
     * @param $atts
     * @return string
     */
    static function render_generic($atts) {

        // prepare the data and do an api call
        $exchange_data = array (
            'api_base' => '',
            'api_rates' => ''
        );

        $exchange_data_status = self::get_exchange_data($atts, $exchange_data);

        // check if we have an error and return that
        if ($exchange_data_status != 'api_fail_cache' and $exchange_data_status != 'api' and $exchange_data_status != 'cache') {
            return $exchange_data_status;
        }

        // render the HTML
        $buffy = '<!-- td exchange source: ' . $exchange_data_status  . ' -->';

        // renders the block template
        $buffy .= self::render_block_template($atts, $exchange_data);


        return $buffy;
    }



    private static function render_block_template($atts, $exchange_data) {
        // stop render when no data is received
        if ($exchange_data['api_rates'] == ''){
            return self::error('Render failed - no data is received: ' . $atts['e_base']);
        }

        ob_start();
        ?>

        <div class="td-exchange-header">
            <div class="td-exchange-base">Base currency - <?php echo $exchange_data['api_base'] ?></div>
            <i class="td-icon-<?php echo $exchange_data['api_base'] ?>"></i>
        </div>


         <div class="td-exchange-rates">
            <?php

            // check if we have custom rates
            if ($atts['e_rates'] != ''){

                // custom rates
                $custom_exchange_rates = array();

                // retrieve the data for the custom rates
                $e_rates = explode(',',strtoupper($atts['e_rates']));

                foreach ($e_rates as $e_currency) {
                    foreach ($exchange_data['api_rates'] as $exchange_currency => $exchange_rate) {
                        // remove whitespace for custom rates
                        $e_currency = trim($e_currency);

                        if ($e_currency == $exchange_currency){
                            $custom_exchange_rates[$exchange_currency] = $exchange_rate;
                        }
                    }
                }

                // replace default rates with custom rates
                if (!empty($custom_exchange_rates)) {
                    $exchange_data['api_rates'] = $custom_exchange_rates;
                }
            }

            foreach ($exchange_data['api_rates'] as $exchange_currency => $exchange_rate) {
                // use lowercase on classes
                $exchange_currency_class = strtolower($exchange_currency);
                ?>
                <div class="td-rate">
                    <div class="td-rate-<?php echo $exchange_currency_class ?>"><?php echo $exchange_currency . ' - ' . $exchange_rate?></div>
                    <i class="td-icon-<?php echo $exchange_currency_class ?>"></i>
                </div>
            <?php
            }
            ?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @param $atts
     * @param $exchange_data - the precomputed exchange data
     * @return bool|string
     *  - bool:true - we have the $exchange_data (from cache or from a real request)
     *  - string - error message
     */
    private static function get_exchange_data($atts, &$exchange_data) {

        // set the base currency
        $base_currency = 'eur';
        if (!empty($atts['e_base'])) {
            $base_currency = $atts['e_base'];
        }

        $cache_key = strtolower('td_exchange_' . $base_currency);
		if (td_remote_cache::is_expired(__CLASS__, $cache_key) === true) {
            // cache is expired - do a request
            $fixed_api_data = self::fixer_get_data($atts, $exchange_data);
            // check the api call response
            if ($fixed_api_data !== true) {
                // we have an error in the api
                $exchange_data = td_remote_cache::get(__CLASS__, $cache_key);
                if ($exchange_data === false) {    // miss and io error... shit / die
                    return self::error('Exchange API error: ' . $fixed_api_data);
                }

                td_remote_cache::extend(__CLASS__, $cache_key, self::$caching_time);
                return 'api_fail_cache';
            }

            td_remote_cache::set(__CLASS__, $cache_key, $exchange_data, self::$caching_time); //we have a reply and we set it
            return 'api';

        } else {
            // cache is valid
            $exchange_data = td_remote_cache::get(__CLASS__, $cache_key);
            return 'cache';
        }
    }


    /**
     * adds to the &$exchange_data the information from FIXER
     * @param $atts - the shortcode atts
     * @param $exchange_data - BYREF exchange data - this function will add to it
     *
     * @return bool|string
     *   - true: if everything is ok
     *   - string: the error message, if there was an error
     */
    private static function fixer_get_data($atts, &$exchange_data){
        // default base currency is eur and it returns all rates
        $custom_rates = '';
        if (!empty($atts['e_base'])) {
            $custom_rates = '?base=' . $atts['e_base'];
        }

        $api_url = 'https://api.fixer.io/latest' . $custom_rates;
        $json_api_response = td_remote_http::get_page($api_url, __CLASS__);

        // check for a response
        if ($json_api_response === false) {
            td_log::log(__FILE__, __FUNCTION__, 'Api call failed', $api_url);
            return 'Api call failed';
        }

        // try to decode the json
        $api_response = @json_decode($json_api_response, true);
        if ($api_response === null and json_last_error() !== JSON_ERROR_NONE) {
            td_log::log(__FILE__, __FUNCTION__, 'Error decoding the json', $api_response);
            return 'Error decoding the json';
        }

        // current base currency
        if (isset($api_response['base'])) {
            $exchange_data['api_base'] = $api_response['base'];
        }

        // current rates
        if (isset($api_response['rates'])) {
            $exchange_data['api_rates'] = $api_response['rates'];
        }

        return true;
    }


    /**
     * Show an error if the user is logged in. It does not check for admin
     * @param $msg
     * @return string
     */
    private static function error($msg) {
        if (is_user_logged_in()) {
            return $msg;
        }
        return '';
    }

}

