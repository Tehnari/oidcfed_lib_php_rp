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
//Loading classes
//require '../vendor/autoload.php';
//require '../classes/autoloader.php';
//\oidcfed\autoloader::init();
require_once '../parameters.php';

//global $path_dataDir, $privateKeyName, $publicKeyName,
// $path_dataDir_real, $private_key_path, $public_key_path,
// $passphrase, $configargs, $client_id, $private_key, $public_key;

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
$jwk_out               = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array(
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
    $jwk_pub_json = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array(
                    $public_key, null, $additional_parameters, true);
}
print_r($jwk_pub_json);
echo "<br>";
echo "<br>";
echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
echo "<br>";
echo "========================================================================<br>";
/*
  echo "Trying to work with JWKS:<br>";
  use Jose\Factory\JWKFactory;
  $jwk_set = JWKFactory::createFromJKU('https://www.googleapis.com/oauth2/v2/certs');
  print($jwk_set->count());
  echo "<br>";
  $jwks_serialized = $jwk_set->jsonSerialize();
  echo "<br>Current Key for Key Set<br>";
  print_r($jwk_set->current());
  $jwks_allkeys = $jwk_set->getKeys();
  foreach ($jwks_allkeys as $jwks_akey => $jwks_avalue) {
  //    $key_current = $jwks_allkeys->get($jwks_akey);
  //    $key_current = $jwks_allkeys->get($jwks_avalue);
  print_r($jwks_avalue);
  //    $key_current = \oidcfed\security_jose::create_jwk_from_values($jwks_avalue);
  //    print_r($key_current);
  }
  echo "<br>jsonSerialize:<br>";
  print($jwks_serialized);
  var_dump($jwk_set);
 */
echo "========================================================================<br>";
echo "========================================================================<br>";
$jwk1      = \oidcfed\security_jose::create_jwk_from_values([
            "kty" => "RSA",
            "n"   => "oahUIoWw0K0usKNuOR6H4wkf4oBUXHTxRvgb48E-BVvxkeDNjbC4he8rUWcJoZmds2h7M70imEVhRU5djINXtqllXI4DFqcI1DgjT9LewND8MW2Krf3Spsk_ZkoFnilakGygTwpZ3uesH-PFABNIUYpOiN15dsQRkgr0vEhxN92i2asbOenSZeyaxziK72UwxrrKoExv6kc5twXTq4h-QChLOln0_mtUZwfsRaMStPs6mS6XrgxnxbWhojf663tuEQueGC-FCMfra36C9knDFGzKsNa7LZK2djYgyD3JR_MB_4NUJW_TqOQtwHYbxevoJArm-L5StowjzGy-_bq6Gw",
            "e"   => "AQAB"], true);
echo "JWK from values >>>";
var_dump($jwk1);
echo "========================================================================<br>";
echo "========================================================================<br>";

