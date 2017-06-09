<?php

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
