<?php

/**
 * OIDCFED Library for PHP
 *
 * @abstract OIDCFED Library for PHP
 *
 *  PHP version 7
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
//oidc_simple_test
//require 'vendor/autoload.php';
//require 'classes/autoloader.php';
////Loading classes
//\oidcfed\autoloader::init();
require (dirname(__FILE__) . '/parameters.php');
echo "";
$post_in = NULL;

//$client_id_v2 = \oidcfed\oidcfedClient::generateRandString_static();
//$client_secret_v2 = \oidcfed\oidcfedClient::generateRandString_static();


$provider_url = "https://op1.test.inacademia.org";
//$clientName = "PHP_RP_Test-OIDC_Simple";
//
//  USING $path_dataDir_real from parameters
//
//if (isset($_REQUEST['code']) && isset($_REQUEST['state']))
//    {
//    echo "";
//
//    //Get id_token_and token
////        $oidc = new \Jumbojett\OpenIDConnectClient($provider_url, $client_id,                                                   $client_secret);
//
//    echo "";
//    }
//else
//    {
try
    {
    $clientData = \oidcfed\oidcfedClient::get_clientName_id_secret($path_dataDir_real,
                                                                   $clientName,
                                                                   $provider_url);
    reset($clientData);
    $clientDataArrVal = current($clientData);
    }
catch (Exception $exc)
    {
//    echo $exc->getTraceAsString();
    $clientDataArrVal = null;
    echo "";
    }
if (\is_array($clientDataArrVal) && \array_key_exists("client_id",
                                                      $clientDataArrVal) && \array_key_exists("client_secret",
                                                                                              $clientDataArrVal))
    {
    $client_id = $clientDataArrVal["client_id"];
    $client_secret = $clientDataArrVal["client_secret"];
    }
else
    {
    $client_id = null;
    $client_secret = null;
    }

if (!(\is_string($client_secret) && \mb_strlen($client_secret)) || (!\is_string($client_id)
        && \mb_strlen($client_id)))
    {
//Dynamic registration for this client
    $oidc_dyn = new \Jumbojett\OpenIDConnectClient($provider_url);

    $oidc_dyn->register();
    $client_id = $oidc_dyn->getClientID();
    $client_secret = $oidc_dyn->getClientSecret();
    $dataToSave = ["provider_url" => $provider_url, "client_id" => $client_id,
        "client_secret" => $client_secret, "client_name" => $clientName];
    \oidcfed\oidcfedClient::save_clientName_id_secret($path_dataDir_real,
                                                      $dataToSave);
    }
//    $GLOBALS["oidc_object"]["client_id"] = $client_id;
//    $GLOBALS["oidc_object"]["client_secret"] = $client_secret;
//$provider_url = "https://op1.test.inacademia.org";
try
    {
    $certificateLocal_content = \oidcfed\security_keys::get_csr(false, $dn,
                                                                $priv_key_woPass,
                                                                $ndays,
                                                                $path_dataDir_real);
    $certificateLocal_path = \oidcfed\security_keys::public_certificateLocal_path();
    }
catch (Exception $exc)
    {
    echo "<pre>";
    echo $exc->getTraceAsString();
    echo "</pre>";
    }

//$oidc = new OpenIDConnectClient('https://id.provider.com',
//                                'ClientIDHere',
//                                'ClientSecretHere');
echo "";
try
    {
    $oidc = new \Jumbojett\OpenIDConnectClient($provider_url, $client_id,
                                               $client_secret);
//$oidc->setCertPath('/path/to/my.cert');
    $oidc->setCertPath($certificateLocal_path);
    }
catch (Exception $exc)
    {
    echo "<pre>";
    echo $exc->getTraceAsString();
    echo "</pre>";
    }
$oidc->setClientName($clientName);

try
    {
    $oidc->authenticate();
    }
catch (Exception $exc)
    {
    echo "<pre>";
    echo $exc->getTraceAsString();
    echo "</pre>";
    }

//$name = $oidc->requestUserInfo('given_name');
//if (!$_REQUEST["code"])
//    {
try
    {
//        $name = $oidc->requestUserInfo('diana');
    $name = $oidc->requestUserInfo();
    echo "<pre>";
    var_dump($name);
    echo "</pre>";
    }
catch (Exception $exc)
    {
    echo "<pre>";
    echo $exc->getTraceAsString();
    echo "</pre>";
    }
//    }
//    }
echo " === == ";
