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

$base_url  = 'https://agaton-sax.com:8080';
$tester_id = '/oidcfed-lib-php';
$test_id   = '/rp-sms-single-l0';
//$test_id = '/rp-sms-single-l1';
//$test_id = '/rp-sms-single-l2';
$full_url  = $base_url . $tester_id . $test_id;
echo "Full url:<br>";
echo $full_url;
try {
    $openid_known = \oidcfed\oidcfedClient::get_well_known_openid_config_data($full_url, null,
                                                            null, false);
    echo "<pre>";
    echo "<br>=============All Claims=============<br>";
    //We should have an array with data, if not we have a problem cap!
    print_r($openid_known);
    echo "</pre>";
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
    $openid_known = false;
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//Get bundle keys(all of them)
$keys_bundle_url = 'https://agaton-sax.com:8080/bundle';
$sigkey_url      = 'https://agaton-sax.com:8080/bundle/sigkey';
try {
    $keys_bundle   = \oidcfed\configure::getUrlContent($keys_bundle_url, false);
    $sigkey_bundle = \oidcfed\configure::getUrlContent($sigkey, false);
    $jwks_bundle   = \oidcfed\security_jose::create_jwks_from_uri($sigkey_url,
                                                                  true);
    echo "<pre>";
    echo "<br>=============Keys Bundle=============<br>";
    print_r($keys_bundle);
    echo "<br>=============SIGKEY=============<br>";
    print_r($sigkey_bundle);
    echo "<br>=============JWKSet (SigKey)=============<br>";
    print_r($jwks_bundle);
    echo "<br>=============Verify (Keys Bundle) signature result=============<br>";
    $jwks          = \oidcfed\metadata_statements::verify_signature_keys_from_MS($keys_bundle,
                                                                                 false,
                                                                                 $jwks_bundle);
    print_r($jwks);
    if ($jwks instanceof \Jose\Object\JWS) {
        echo "<br>=============Verify (Keys Bundle) signature result=============<br>";
        print_r($jwks->getPayload());
    }
    echo "</pre>";
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
    $openid_known = false;
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
$check00 = (\is_array($openid_known) === true);
$check01 = ($check00 === true && \array_key_exists('metadata_statements',
                                                   $openid_known) === true && \is_array($openid_known['metadata_statements']) === true
        && \count($openid_known['metadata_statements']) > 0);
$check02 = ($check00 === true && \array_key_exists('metadata_statement_uris',
                                                   $openid_known) === true && \is_array($openid_known['metadata_statement_uris']) === true
        && \count($openid_known['metadata_statement_uris']) > 0);
$ms_tmp  = false;
if ($check01 === false && $check02 === true) {
    $ms_tmp = $openid_known['metadata_statement_uris'];
    foreach ($ms_tmp as $ms_tmp_key => $ms_tmp_val) {
        $ms_tmp[$ms_tmp_key] = \oidcfed\configure::getUrlContent($ms_tmp_val,
                                                                 false);
    }
    $openid_known['metadata_statements'] = $ms_tmp;
}
unset($ms_tmp);
echo "=============Metadata Statements=============<br>";
$ms_arr = [];
foreach ($openid_known['metadata_statements'] as $ms_key => $ms_value) {
    $jws_struc = \oidcfed\metadata_statements::unpack_MS($ms_value, null,
                                                         $jwks->getPayload()["bundle"],
                                                         false, false);
    if ($jws_struc) {
        echo "===>>> Verified MS index: $ms_key <<<===";
        echo "<pre>";
        print_r($jws_struc);
        $ms_arr[]=$jws_struc;
        echo "</pre>";
    } else {
        echo "Have some dificulties";
    }
}
echo "<br>=============Register client=============<br>";
$openid = new \oidcfed\oidcfedClient();
//$openid->
echo "";

