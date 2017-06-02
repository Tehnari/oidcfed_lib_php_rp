<?php
require 'vendor/autoload.php';
// First testing
$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
$oidc = new OpenIDConnectClient($issuer);
$oidc->register();
$cid = $oidc->getClientID();
$secret = $oidc->getClientSecret();
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