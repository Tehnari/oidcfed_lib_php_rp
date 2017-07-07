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
//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
//---------------------->>>>>
$path_dataDir = __DIR__ . '/../oidcfed_data';
$path_dataDir_real = realpath($path_dataDir);
//$pass_phrase = '1234';
$pass_phrase = '';
try {
    mkdir($path_dataDir_real, 0777, true);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
try {
    mkdir($path_dataDir_real . '/keys', 0777, true);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
$private_key = \oidcfed\security_keys::get_private_key($path_dataDir_real . '/keys',
                                                       $pass_phrase,
                                                       $path_dataDir_real . '/keys');
echo "<br><b>Private key</b>:::===>>><br><pre>";
print_r($private_key);
echo "</pre><br><<<===:::End of <b>Private key</b><br>";
//=============================================================================
$public_key = \oidcfed\security_keys::get_public_key($path_dataDir_real . '/keys',
                                                     $dn = [], $ndays = 365,
                                                     $private_key,
                                                     $path_dataDir_real . '/keys');
echo "<br><b>Public key</b>:::===>>><br><pre>";
print_r($public_key);
echo "</pre><br><<<===:::End of <b>Public key</b><br>";
//=============================================================================
// TODO Work on/with JOSE should be rewrited !!!
//=============================================================================
//Generate JOSE/JWK for Private Key
//$private_key_JWK = new phpseclib\Crypt\RSA();
//if (is_string($pass_phrase) === true && mb_strlen($pass_phrase) > 0) {
//    $private_key_JWK->setPassword($pass_phrase);
//} # skip if not encrypted
//$private_key_JWK->loadKey($private_key);
//try {
//    $jose_jwk_private = JOSE_JWK::encode($private_key_JWK);
//    print_r($jose_jwk_private);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
//=============================================================================
//Generate JOSE/JWK for Public Key
//$public_key_JWK = new phpseclib\Crypt\RSA();
//$public_key_JWK->loadKey($public_key);
//try {
//    $jose_jwk_public = JOSE_JWK::encode($public_key_JWK);
//    print_r($jose_jwk_public);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
