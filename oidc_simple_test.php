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
//oidc_simple_test
//require 'vendor/autoload.php';
//require 'classes/autoloader.php';
////Loading classes
//\oidcfed\autoloader::init();
require (dirname(__FILE__) . '/parameters.php');
echo "";
$post_in = NULL;

$client_id_v2 = \oidcfed\oidcfedClient::generateRandString_static();
$client_secret_v2 = \oidcfed\oidcfedClient::generateRandString_static();

$provider_url = "https://op1.test.inacademia.org";
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
try
    {
    $name = $oidc->requestUserInfo('diana');
    }
catch (Exception $exc)
    {
    echo "<pre>";
    echo $exc->getTraceAsString();
    echo "</pre>";
    }

echo "=====";
