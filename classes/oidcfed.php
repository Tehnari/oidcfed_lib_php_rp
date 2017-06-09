<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
        $curl = \curl_init($url_oidc_config);
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

}
