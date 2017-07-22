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

require 'vendor/autoload.php';
// First testing
$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
$oidc_dyn = new OpenIDConnectClient($issuer);
$oidc_dyn->register();
$cid = $oidc_dyn->getClientID();
$secret = $oidc_dyn->getClientSecret();
//----------
$curl = curl_init('https://rp.certification.openid.net:8080/oidcfed_php_rp/rp-response_type-code/.well-known/openid-configuration');
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$result_curl = curl_exec($curl);
$result_pretty_print = json_encode(json_decode($result_curl), JSON_PRETTY_PRINT);
echo "<pre>";
//echo $result_curl;
echo $result_pretty_print;
echo "</pre>";
//----------
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
//$oidc->authenticate();
//$oidc->requestUserInfo('sub');
//
//$session = array();
//foreach ($oidc->getUserInfo() as $key => $value) {
//    if (is_array($value)) {
//        $v = implode(', ', $value);
//    }else {
//        $v = $value;
//    }
//        $session[$key] = $v;
//    }
//
//session_start();
//$_SESSION['attributes'] = $session;

//header("Location: ./attributes.php");