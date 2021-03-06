<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once (dirname(__FILE__) . '/vendor/autoload.php');
require_once (dirname(__FILE__) . '/classes/autoloader.php');

//require '../index.php';
//Loading classes
\oidcfed\autoloader::init();

global $path_dataDir, $privateKeyName, $publicKeyName,
 $path_dataDir_real, $private_key_path, $public_key_path,
 $passphrase, $configargs, $client_id, $private_key, $public_key, $dn, $ndays, $oidc_object;

$path_dataDir = \oidcfed\configure::path_dataDir();
$privateKeyName = \oidcfed\configure::privateKeyName();
$publicKeyName = \oidcfed\configure::publicKeyName();
$path_dataDir_real = \oidcfed\configure::path_dataDir_real();
$private_key_path = \oidcfed\configure::private_key_path();
$public_key_path = \oidcfed\configure::public_key_path();
$passphrase = \oidcfed\configure::passphrase();
$configargs = \oidcfed\configure::configargs();
$clientName = "PHP_RP_Test-OIDC_Simple";
$dn = \oidcfed\configure::dn();
$ndays = \oidcfed\configure::ndays();
$oidc_object = [];
// CLIENT ID is below:
if (!$client_id || empty($client_id))
    {
    $client_id = \oidcfed\configure::client_id();
    }
$client_secret = false;
$private_key = \oidcfed\configure::private_key();
$priv_key_woPass = \oidcfed\security_keys::get_private_key_without_passphrase($private_key,
                                                                              $passphrase);
$public_key = \oidcfed\configure::public_key(
                $priv_key_woPass, $public_key_path, $dn, $ndays,
                $path_dataDir_real . '/keys');
//----------------------------------------------------------------------------
// Below we have list of the OP to connect
//----------------------------------------------------------------------------
$provider_url_list = [];
//$provider_url_list[] = (object)["key"=>"localhost:8777", "value"=>"localhost:8777"];
$provider_url_list[] = (object) ["key" => "https://localhost:8777", "value" => "https://localhost:8777/"];
$provider_url_list[] = (object) ["key" => "oidcfed.inf.um.es:8777", "value" => "https://oidcfed.inf.um.es:8777/"];
echo "";
