<?php
require 'protected/vendor/autoload.php'; 

$issuer = 'https://rp.certification.openid.net:8080';
$cid = 'YOUR_CLIENT_ID'; 
$secret = 'YOUR_CLIENT_SECRET'; 
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

session_start(); 
$_SESSION['attributes'] = $session; 

header("Location: ./attributes.php"); 

