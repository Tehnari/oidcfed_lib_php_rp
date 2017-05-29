<?php
require 'protected/vendor/autoload.php'; 

$issuer = 'https://rp.certification.openid.net:8080/oidcfed_php_rp/rp-response_type-code/token';
$oidc->register();
$oidc = new OpenIDConnectClient($issuer); 
$cid = $oidc->getClientID();
$secret = $oidc->getClientSecret(); 

$oidc->authenticate(); 
$oidc->requestUserInfo('sub'); 

$session = array(); 
foreach ($oidc->getUserInfo() as $key => $value) {
    if (is_array($value)) {
        $v = implode(', ', $value); 
    }else {
        $v = $value; 
    }
        $session[$key] = $v; 
    }   

session_start(); 
$_SESSION['attributes'] = $session; 

header("Location: ./attributes.php"); 

