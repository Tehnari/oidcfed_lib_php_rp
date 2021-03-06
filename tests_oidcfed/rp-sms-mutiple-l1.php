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
//$test_id   = '/rp-sms-multiple-l0';
$test_id   = '/rp-sms-multiple-l1';
$full_url  = $base_url . $tester_id . $test_id;
echo "Full url:<br>";
echo $full_url;
try {
    $openid_known = \oidcfed\oidcfedClient::get_well_known_openid_config_data($full_url,
                                                                              null,
                                                                              null,
                                                                              false);
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
    $sigkey_bundle = \oidcfed\configure::getUrlContent($sigkey_url, false);
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
    $jwks_payload = false;
}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
$check00a = (\is_array($openid_known) === true);
$check00b = ($jwks_payload);
$check00  = ($check00a && $check00b);
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
$ms_arr      = [];
$ms_compound = [];
foreach ($openid_known['metadata_statements'] as $ms_key => $ms_value) {
    echo "MS string: <br>";
    echo "<pre>";
    print_r($ms_value);
    echo "</pre>";
    echo "<br>";
    $ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($ms_value);
    echo "<pre>";
    echo "MS Header <br>";
    print_r($ms_header);
    echo "</pre>";
    $jws_struc = \oidcfed\metadata_statements::unpack_MS($ms_value, null,
                                                         $jwks->getPayload()["bundle"],
                                                         false, false);
    if ($jws_struc) {
        echo "===>>> Verified MS index: $ms_key <<<===";
        echo "<pre>";
        print_r($jws_struc);
        $ms_arr[] = $jws_struc;
        echo "</pre>";
    }
    else {
        echo "Have some dificulties";
    }
}
echo "<br>=============Check for policy error=============<br>";
$ms_compound_result = \oidcfed\metadata_statements::get_compound_ms_static($ms_arr,
                                                                           $ms_compound);
echo "Compound MS<br>";
$check_scopes       = null;
//Check if is a claim/parameter: scopes_supported
$check03 = (is_array($ms_compound_result) && count($ms_compound_result)>0);
$check04 = (is_array($ms_arr) && is_array($ms_arr[0]) && count($ms_arr[0])>0);
$check05 = ($check03 && $check04);
if ($check05 && isset($ms_arr[0]["scopes_supported"]) && isset($ms_compound_result["scopes_supported"])) {
    try {
        $check_scopes = \oidcfed\metadata_statements::check_MS_scopes_supported($ms_compound_result,
                                                                                $ms_arr[0]);
        if (!$check_scopes) {
            echo "Problem with scopes checking.";
//            echo $exc->getTraceAsString();
//            throw new Exception("Problem with scopes checking.");
        }
    }
    catch (Exception $exc) {
        echo $exc->getMessage();
//                    echo $exc->getTraceAsString();
//        throw new Exception($exc->getMessage());
    }
}
if (\is_bool($check_scopes) && $check_scopes === true) {
    echo "<pre>";
    print_r($ms_compound_result);
    echo "</pre>";
    echo "<br>";
}
echo "<br>=============Register client=============<br>";

echo "Variable: path_dataDir_real: <br>";
print_r($path_dataDir_real);
echo "Getting or prepare certificate to use with OIDCFED Client...<br>";
$certificateLocal_content = \oidcfed\security_keys::get_csr(false, $dn,
                                                            $priv_key_woPass,
                                                            $ndays,
                                                            $path_dataDir_real);
echo "<br>";
echo "<pre>";
print_r($certificateLocal_content);
echo "<br>";
//print_r(openssl_x509_parse($certificateLocal_content));
echo "</pre>";
$certificateLocal_path    = \oidcfed\security_keys::public_certificateLocal_path();
$openid                   = new \oidcfed\oidcfedClient([
//    'provider_url'  => $openid_known['registration_endpoint'],
    'provider_url'        => $full_url,
    'client_id'           => $openid_known['issuer'],
    'client_secret'       => $passphrase,
    'clientName'          => 'oidcfed_lib_rp',
    'metadata_statements' => $openid_known['metadata_statements']
        ]);
$openid->addScopes(['openid', 'email', 'profile']);
if (!$client_secret) {
    $openid->register();
//Using this  client_id and client_secret
    $client_id     = $openid->getClientID();
    $client_secret = $openid->getClientSecret();
}
if ($client_secret) {
    echo "";
    //If we have defined $client _secret we can continue
    //But for this example we just stop
    echo "Here we have registered client with ID: " . $client_id . "<br>";
    echo "And client secret: " . $client_secret . "<br>";
//    try {
//        /*
//         * "end_session_endpoint"   => $openid_known['end_session_endpoint'],
//          "token_endpoint"         => $openid_known['token_endpoint']
//         */
//        $openid->setProviderConfigParams(["authorization_endpoint" => $openid_known['authorization_endpoint'],
//        ]);
////    $openid->setProviderUrl($client_id);
//        // $openid->setVerifyHost(false);
//        // $openid->setVerifyPeer(false);
//        $openid->setCertPath($certificateLocal_path);
//        //
////    $openid->authenticate();
//        $name = $oidc->requestUserInfo('given_name');
//    }
//    catch (Exception $exc) {
//        echo "<br>" . $exc->getMessage() . "<br>";
//        echo $exc->getTraceAsString();
//    }
}
echo "";

