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

require (dirname(__FILE__) . '/parameters.php');
echo "";
$post_in       = NULL;
$oidcFedRp     = NULL;
$oidc_site_url = null;
if (is_array($_POST) && count($_POST) > 0) {
    $post_in = filter_input_array(INPUT_POST);
}
if (is_array($_REQUEST) && count($_REQUEST) > 0) {
    $request_in = $_REQUEST;
}

if ($post_in !== null && is_array($post_in) && array_key_exists("useAuthType",
                                                                $post_in)) {
    $useAuthType = $post_in["useAuthType"];
}
else {
//    $useAuthType = "authorization_code_static";
//    $useAuthType = "implicit_flow";
//    $useAuthType = "hybrid_flow";
    $useAuthType = "dynamic";
}

if ($post_in !== null && is_array($post_in) && array_key_exists("provider_url",
                                                                $post_in)) {
    $oidc_site_url = $post_in["provider_url"];
} else if($request_in !== null && is_array($request_in) && array_key_exists("iss",
                                                                $request_in)){
   $oidc_site_url = $request_in["iss"];
}
if (is_string($oidc_site_url) && mb_strlen($oidc_site_url) > 0) {
    //TODO All Flows are rewriting know !!!
    switch ($useAuthType) {
        case "dynamic":
            //Dynamic registration TEST
            $oidcFedRp              = new \oidcfed\oidcfedClient($oidc_site_url);
            $verifyCert             = false;
            $oidcFedRp->setVerifyCert($verifyCert);
            $webfinger_data = rtrim(rtrim($oidcFedRp->get_webfinger_data($oidc_site_url)),'/').'/';
            $oidcFedRp->setProviderURL($webfinger_data);
            $oidcFedRp->wellKnown   = \oidcfed\oidcfedClient::get_well_known_openid_config_data($webfinger_data,
                                                                                                null,
                                                                                                null,
                                                                                                false);
            $oidcFedRp->dynamic_registration_and_auth_code($verifyCert,
                                                           $private_key,
                                                           $passphrase);
            break;
        default:
            break;
    }
    echo "";
    $webfinger_data = $oidcFedRp->get_webfinger_data();
    echo "";
}
