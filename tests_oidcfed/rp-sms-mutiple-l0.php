<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * Level 0
 *
 * Test ID:
 * rp-sms-single-l0
 *
 * Description
 * Make a OpenID Provider Issuer Discovery and interpret the received Signed Metadata Statement. The response will be directly signed by a federation without any intermediate.
 *
 * Info
 * Correctly verified the signature and interpreted the provider information received.
 *
 */

require '../parameters.php';

$base_url = 'https://agaton-sax.com:8080';

$tester_id = '/oidcfed-lib-php';

$test_id = '/rp-sms-single-l0';

$full_url = $base_url . $tester_id . $test_id;

echo "Full url:<br>";
echo $full_url;

try {
    $openid_known = \oidcfed\oidcfed::get_webfinger_data_op($full_url);
    echo $openid_known;
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

//print_r($openid_known);
//var_dump($openid_known);
//$oidc = new OpenIDConnectClient($full_url);

