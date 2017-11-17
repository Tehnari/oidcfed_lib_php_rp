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
//require 'vendor/autoload.php';
//require 'classes/autoloader.php';
////Loading classes
//\oidcfed\autoloader::init();
require 'parameters.php';
echo "";
$post_in       = NULL;
$oidcFedRp     = NULL;
$oidc_site_url = null;
if (is_array($_POST) && count($_POST) > 0) {
    $post_in = filter_input_array(INPUT_POST);
}


if ($post_in !== null && is_array($post_in) && array_key_exists("provider_url",
                                                                $post_in)) {
    $oidc_site_url = $post_in["provider_url"];
}
$check03                  = (isset($post_in["client_id"]) && is_string($post_in["client_id"])
        && mb_strlen($post_in["client_id"]) > 0);
$check04                  = (isset($post_in["client_secret"]) && is_string($post_in["client_secret"])
        && mb_strlen($post_in["client_secret"]) > 0);
//            echo "Getting or prepare certificate to use with OIDCFED Client...<br>";
$certificateLocal_content = \oidcfed\security_keys::get_csr(false, $dn,
                                                            $priv_key_woPass,
                                                            $ndays,
                                                            $path_dataDir_real);
$certificateLocal_path    = \oidcfed\security_keys::public_certificateLocal_path();
$check05                  = is_string($certificateLocal_path) && is_readable($certificateLocal_path);
if (is_string($oidc_site_url) && mb_strlen($oidc_site_url) > 0) {
    //Static registration TEST
    if ($check03 && $check04 && $check05) {
        $oidcFedRp = new \oidcfed\oidcfedClient($oidc_site_url,
                                                $post_in["client_id"],
                                                $post_in["client_secret"]);
        try {
//            $oidcFedRp->setCertPath('/path/to/my.cert');
            $oidcFedRp->setCertPath($certificateLocal_path);
            $openid_known  = \oidcfed\oidcfedClient::get_well_known_openid_config_data($oidc_site_url,
                                                                                       null,
                                                                                       null,
                                                                                       false);
            $oidcFedRp->authenticate();
            $oidcFedRp->getAuthParams();
            $client_id     = $oidcFedRp->getClientID();
            $client_secret = $oidcFedRp->getClientSecret();
        }
        catch (Exception $exc) {
            echo "<pre>";
            echo $exc->getTraceAsString();
            echo "</pre>";
        }
    }
    //Dynamic registration TEST
//    $oidcFedRp = new \oidcfed\oidcfedClient($oidc_site_url);
//    try {
//        $oidcFedRp->register();
//        $client_id     = $oidcFedRp->getClientID();
//        $client_secret = $oidcFedRp->getClientSecret();
//    }
//    catch (Exception $exc) {
//        echo "<pre>";
//        echo $exc->getTraceAsString();
//        echo "</pre>";
//    }
}



//$oidc = new OpenIDConnectClient('https://rp.certification.openid.net:8080',
//                                $client_id,
//                                'ClientSecretHere');
//$oidc->setCertPath('/path/to/my.cert');
//$oidc->authenticate();
//$name = $oidc->requestUserInfo('given_name');