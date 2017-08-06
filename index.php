<?php

/**
 * OIDCFED Library for PHP
 *
 * @abstract OIDCFED Library for PHP
 *
 *  PHP version 5
 *
 * @category  PHP
 * @package   OIDCFED_Lib_PHP_RP
 * @author    Constantin Sclifos <sclifcon@gmail.com>
 * @copyright 2017 Constantin Sclifos
 * @license   https://opensource.org/licenses/MIT MIT
 * @version   "GIT:f23edba8"
 * @link      https://github.com/Tehnari/oidcfed_lib_php_rp
 * Copyright MIT <2017> Constantin Sclifos <sclifcon@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *  - The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */
//require 'vendor/autoload.php';
//require 'classes/autoloader.php';
////Loading classes
//\oidcfed\autoloader::init();
require 'parameters.php';
//
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
// Some parameters are in static variables and hold in class \oidcfed\configure !!!
// In class \oidcfed\security_keys we have default value for some parameters.
// You can just add a new value to static variables in \oidcfed\security_keys
//---------------------->>>>>
//global $path_dataDir, $privateKeyName, $publicKeyName,
// $path_dataDir_real, $private_key_path, $public_key_path,
// $passphrase, $configargs, $client_id, $private_key, $public_key, $dn, $ndays;

$kid             = $client_id;
$jwk_pub_json    = "";
//=============================================================================
$dn              = [];
$ndays           = 365;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$priv_key_woPass = \oidcfed\security_keys::get_private_key_without_passphrase($private_key,
                                                                              $passphrase);
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
try {
    $priv_key_res_woPass = \oidcfed\security_keys::get_private_key_resource(
                    $priv_key_woPass);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
//$priv_key_details = openssl_pkey_get_details($private_key);
//=============================================================================
// $private_key_toCheck can be resource or Private Key content (PEM format)
// Should be without passphrase !!!
//$private_key_toCheck            = $priv_key_woPass;
$public_key = \oidcfed\security_keys::get_public_key($public_key_path, $dn,
                                                     $ndays, $priv_key_woPass,
                                                     $path_dataDir_real . '/keys'
);
//=============================================================================
// TODO Work on/with JOSE should be rewrited !!!
//=============================================================================
echo "<br>";
echo "========================================================================<br>";
echo "</pre>";
echo "Examples:<br>";
echo "<a href='./examples/keys_1.php' target='_blank'> - example for key generation ...</a><br> ";
echo "<a href='./examples/keys_2.php' target='_blank'> - example for convertion from RSAKey (object) to PEM format (for RSA type) ...</a><br> ";
echo "<a href='./examples/jose_jwk.php' target='_blank'> - JOSE JWK (Example 1) ...</a><br> ";
echo "<a href='./examples/jose_jwt_jws_1.php' target='_blank'> - JOSE JWT/JWS (Example 1) ...</a><br> ";
