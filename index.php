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
echo "";


echo "<form action=\"index.php\" method=\"post\">";
//echo "Provider url: <input type=\"text\" name=\"provider_url\"><br>";
echo "Provider url (List): <input type=\"text\" name=\"provider_url_list\"><br>";
echo "<datalist id=\"provider_url_list\">";
echo "  <option></option>";
echo "  <option></option>";
echo "</datalist>";
echo "Client ID: <input type=\"text\" name=\"client_id\"><br>";
echo "Client Secret: <input type=\"password\" name=\"client_secret\"><br>";
echo "<input type=\"submit\">";
echo "</form>";
//use \OpenIdConnectClient\OpenIdConnectClient;
//
//$oidc_site_url = "https://rp.certification.openid.net:8080/$client_id/rp-response_type-code";
//$oidc = new OpenIDConnectClient($oidc_site_url);
//
//try {
//    $oidc->register();
//    $client_id     = $oidc->getClientID();
//    $client_secret = $oidc->getClientSecret();
//}
//catch (Exception $exc) {
//    echo "<pre>";
//    echo $exc->getTraceAsString();
//    echo "</pre>";
//}



//$oidc = new OpenIDConnectClient('https://rp.certification.openid.net:8080',
//                                $client_id,
//                                'ClientSecretHere');
//$oidc->setCertPath('/path/to/my.cert');
//$oidc->authenticate();
//$name = $oidc->requestUserInfo('given_name');