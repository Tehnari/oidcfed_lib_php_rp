<?php

/**
 *
 * @author Constantin Sclifos
 * @copyright (c) 2017, Constantin Sclifos
 * @license    https://opensource.org/licenses/MIT
 *
 * Copyright MIT <2017> Constantin Sclifos <sclifcon@gmail.com>

    Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

    - The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 */


require 'vendor/autoload.php';
require 'classes/autoloader.php';
//Loading classes
autoloader::init();

// First testing dynaming registration ...
$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
$url_oidc_config = 'https://rp.certification.openid.net:8080/oidcfed_php_rp/rp-response_type-code/.well-known/openid-configuration';

$oidc_dyn = \oidcfed\oidc_dyn::init($issuer);
$cid = $oidc_dyn->getClientID();
$secret = $oidc_dyn->getClientSecret();
$oidc_response_obj = new stdClass();
$oidc_response_obj->rp_id = "oidcfed_lib_php_rp";
$oidc_response_obj->test_id = "rp-response_type-code";
$oidc_response_obj->client_id = $cid;
$oidc_response_obj->client_secret = $secret;
$result_json = json_encode($oidc_response_obj, JSON_PARTIAL_OUTPUT_ON_ERROR);
try {
    file_put_contents('../oidc_dynamic_response.json', $result_json);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

$result_json_pretty = json_encode($oidc_response_obj, JSON_PRETTY_PRINT);
try {
    file_put_contents('../oidc_dynamic_response_pretty.txt', $result_json_pretty);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
unset($result_json);
unset($result_json_pretty);
//----------
//file_put_contents('../oidc-config_test-rp-code.txt', $result_pretty_print);
$oidc_config = \oidcfed\oidcfed::get_oidc_config($url_oidc_config, false, false, true);
//----------
//----------
// Next test is rp-response_type-code
$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
$oidc = new OpenIDConnectClient($issuer, $cid, $secret);
$oidc->addAuthParam(["response_type"=>'code']);
$oidc->addScope(["openid"]);
$oidc->getTokenResponse();
//$oidc->authenticate();
//$oidc->requestUserInfo('sub');
//$session = [];
//foreach ($oidc->getUserInfo() as $key=>$value) {
//if(is_array($value)) {
//        $v = implode(', ', $value);
//}else{
//    $v = $value;
//}
//    $session[$key] = $v;
//}

//session_start();
//$_SESSION['attributes'] = $session;

//$oidc_response_obj->test_id = 'rp-token_endpoint-client_secret_basic';
//$oidc_response_obj->test_id = 'rp-response_type-code';
//$oidc_response_obj->session_attrib = $session;
//$result_json = json_encode($oidc_response_obj, JSON_PARTIAL_OUTPUT_ON_ERROR);
//try {
////    file_put_contents('../rp-token_endpoint-client_secret_basic.json', $result_json);
//    file_put_contents('../rp-response_type-code.json', $result_json);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
//
//$result_json_pretty = json_encode($oidc_response_obj, JSON_PRETTY_PRINT);
//try {
////    file_put_contents('../rp-token_endpoint-client_secret_basic.txt', $result_json_pretty);
//    file_put_contents('../rp-response_type-code.txt', $result_json_pretty);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
//unset($result_json);
//unset($result_json_pretty);
//----------
$oidc_config = \oidcfed\oidcfed::get_oidc_config($url_oidc_config, false, false, true);
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
