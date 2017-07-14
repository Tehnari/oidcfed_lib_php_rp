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
\oidcfed\autoloader::init();

// First testing dynaming registration ...
//$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
//$url_oidc_config = 'https://rp.certification.openid.net:8080/oidcfed_php_rp/rp-response_type-code/.well-known/openid-configuration';
//$oidc_config = \oidcfed\oidcfed::get_oidc_config($url_oidc_config, false, false, true);
////----------
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
echo "Here is just an example how to use libraries/classes!!!";
echo "<br>";
echo "Docs (and more cleaning) will be later...<br>";
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
// Some parameters are in static variables !!!
// You can just add a new value to static variables in \oidcfed\security_keys
//---------------------->>>>>
$path_dataDir      = \oidcfed\security_keys::$path_dataDir;
$privateKeyName    = \oidcfed\security_keys::$privateKeyName;
$publicKeyName     = \oidcfed\security_keys::$publicKeyName;
$path_dataDir_real = \oidcfed\security_keys::path_dataDir_real($path_dataDir);
$private_key_path  = \oidcfed\security_keys::private_key_path();
$public_key_path   = \oidcfed\security_keys::public_key_path();
$passphrase        = \oidcfed\security_keys::$passphrase;
$configargs        = \oidcfed\security_keys::$configargs;
// CLIENT ID is below:
$client_id         = \oidcfed\security_keys::parameter_kid_build();
$kid               = $client_id;
print_r($kid);
echo "<br>---------------------------------------<br>";
print_r(parse_url($kid));
$jwk_pub_json      = "";
//=============================================================================
//try {
//    mkdir($path_dataDir_real, 0777, true);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
//try {
//    mkdir($path_dataDir_real . '/keys', 0777, true);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
$private_key       = \oidcfed\security_keys::get_private_key($private_key_path,
                                                             $passphrase,
                                                             $configargs,
                                                             $path_dataDir_real . '/keys');
echo "<br><b>Private key</b>:::===>>><br><pre>";
print_r($private_key);
echo "</pre><br><<<===:::End of <b>Private key</b><br>";
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
try {
    $priv_key_res = \oidcfed\security_keys::get_private_key_resource($private_key,
                                                                     $passphrase);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
$priv_key_details = openssl_pkey_get_details($priv_key_res);
//Getting private key without passphrase
openssl_pkey_export($priv_key_res, $priv_key_woPass);
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
try {
    $priv_key_res_woPass = \oidcfed\security_keys::get_private_key_resource($priv_key_woPass);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
//$priv_key_details = openssl_pkey_get_details($private_key);
//=============================================================================
// $private_key_toCheck can be resource or Private Key content (PEM format)
// Should be without passphrase !!!
$private_key_toCheck = $priv_key_woPass;
$public_key          = \oidcfed\security_keys::get_public_key($public_key_path,
                                                              $dn                  =
                [], $ndays               = 365, $private_key_toCheck,
                                                              $path_dataDir_real . '/keys');
echo "<br><b>Public key</b>:::===>>><br><pre>";
print_r($public_key);
echo "</pre><br><<<===:::End of <b>Public key</b><br>";

//=============================================================================
// TODO Work on/with JOSE should be rewrited !!!
//=============================================================================
//Generate JOSE/JWK for Private Key
echo "<pre>";

/*
  use Jose\Factory\JWKFactory;
  use Jose\Object\JWK;
  $jwk_priv = JWKFactory::createFromKey($priv_key_woPass, $passphrase);
  echo "JWK (Private KEY): <br>";
  print_r($jwk_priv);
  $jwk_priv_json = json_encode($jwk_priv, JSON_PARTIAL_OUTPUT_ON_ERROR);
  print_r($jwk_priv_json);
 */
echo "<br>";
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%";
echo "<br>";
//=============================================================================
//Generate JOSE/JWK for Public Key
// Using null as passprase, for an example,
//  and because before we have key without pass
$additional_parameters = [
    'kid' => $kid
];
$jwk_out               = \oidcfed\security_jose::generate_jwk_from_key_with_kid_and_parameter_array(
                $public_key, null, $additional_parameters);
echo "JWK (Public KEY, resource array/object): <br>";
echo "vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv";
echo "<br>";
//print_r($jwk_pub);
print_r($jwk_out);
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%";
echo "<br>";
echo "JWK (Public KEY, JSON format): <br>";
//echo "<br>";
echo "vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv";
echo "<br>";
if (is_array($jwk_out) === true || is_object($jwk_out) === true) {
    $jwk_pub_json = \oidcfed\security_jose::generate_jwk_from_key_with_kid_and_parameter_array(
                    $public_key, null, $additional_parameters, true);
}
print_r($jwk_pub_json);
echo "<br>";
echo "<br>";
echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
echo "<br>";
echo "========================================================================";
use Jose\Factory\JWSFactory;
/*
$jws = JWSFactory::createJWS([
    'iss' => 'My server',
    'aud' => 'Your client',
    'sub' => 'Your resource owner',
    'exp' => time()+3600,
    'iat' => time(),
    'nbf' => time(),
]);
*/
$jws = JWSFactory::createJWS('A JWS with a detached payload', true);
print_r($jws);

echo "</pre>";