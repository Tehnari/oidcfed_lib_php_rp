<?php

/**
 * OIDCFED Library for PHP
 *
 * @abstract OIDCFED Library for PHP
 *
 *  PHP version 5
 *
 * @category  PHP
 * @package   OIDCFED_Lib_PHP_RP
 * @author    Constantin Sclifos <sclifcon@gmail.com>
 * @copyright 2017 Constantin Sclifos
 * @license   https://opensource.org/licenses/MIT MIT
 * @version   "GIT:f23edba8"
 * @link      https://github.com/Tehnari/oidcfed_lib_php_rp
 * Copyright MIT <2017> Constantin Sclifos <sclifcon@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *  - The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace oidcfed;

/**
 * Description of oidcfed
 *
 * @author constantin
 */
class oidcfed {

    public static function get_oidc_config($url_oidc_config = false,
                                           $show_config = false,
                                           $filename = false,
                                           $return_pretty = true) {
        if (($url_oidc_config === false || \is_string($url_oidc_config) === false)
                && $filename === false && $return_pretty === false) {
            return false;
        }
//----------
        $curl        = \curl_init($url_oidc_config);
        \curl_setopt($curl, \CURLOPT_FAILONERROR, true);
        \curl_setopt($curl, \CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, \CURLOPT_SSL_VERIFYHOST, false);
        \curl_setopt($curl, \CURLOPT_SSL_VERIFYPEER, false);
        $result_curl = \curl_exec($curl);
        if ($return_pretty === true) {
            $result_output = \json_encode(\json_decode($result_curl),
                                                       \JSON_PRETTY_PRINT);
        }
        else {
            $result_output = \json_encode(\json_decode($result_curl),
                                                       \JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        if ($show_config !== false) {
            echo "<pre>";
//echo $result_curl;
            echo $result_output;
            echo "</pre>";
        }
        if ($filename !== false && \is_string($filename) === true) {
            \file_put_contents($filename, $result_output);
        }
        unset($curl);
        unset($result_curl);
        return $result_output;
//----------
    }

    public static function get_webfinger_data_op($base_url, $param = null,
                                                 $default = null) {
        $check00 = (\is_string($base_url) === true && \is_array(\pathinfo($base_url)) === true
                && \count(\pathinfo($base_url)) > 0 );
        if ($check00 === false) {
            throw new Exception("Failed to get data. Bad url.");
        }
        $well_known_config_url = rtrim($base_url, "/") . "/.well-known/openid-configuration";
        $wf_json_data          = \oidcfed\configure::getUrlContent($well_known_config_url);
        //Get OIDC web finger data
        $wellKnown             = \json_decode($wf_json_data, true); //We will use (internal) associative arrays.
        $check01               = (\is_array($wellKnown) === true || \is_object($wellKnown) === true);
        $check02               = ($check01 === true && \count((array) $wellKnown)
                > 0);
        if ($check02 === false) {
            throw new Exception("Failed to get data. Bad data received.");
        }
        $check03 = (isset($param) === true && \is_string($param)===true && ((array) isset($wellKnown[$param]) === true));
        $check04 = (isset($default) === true );
        if($check03 ===true) {
            return $wellKnown[$param];
        } else if($check04===true){
            return $default;
        } else {
            return $wellKnown;
        }
    }

}
