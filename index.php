<?php

require 'vendor/autoload.php';
// First testing dynaming registration ...
$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
$oidc_dyn = new OpenIDConnectClient($issuer);
$oidc_dyn->register();
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
//----------
// Nest test is rp-response_type-code$oidc = new OpenIDConnectClient($issuer, $cid, $secret);

$oidc = new OpenIDConnectClient($issuer, $cid, $secret);
$oidc->authenticate();
$oidc->requestUserInfo('sub');

$session = array();
foreach ($oidc->getUserInfo() as $key=>$value) {
if(is_array($value)) {
        $v = implode(', ', $value);
}else{
    $v = $value;
}
    $session[$key] = $v;
}

//session_start();
//$_SESSION['attributes'] = $session;

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
