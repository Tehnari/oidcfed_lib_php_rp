<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'vendor/autoload.php';
require_once 'classes/autoloader.php';

//require '../index.php';
//Loading classes
\oidcfed\autoloader::init();

global $path_dataDir, $privateKeyName, $publicKeyName,
 $path_dataDir_real, $private_key_path, $public_key_path,
 $passphrase, $configargs, $client_id, $private_key, $public_key, $dn, $ndays;

$path_dataDir      = \oidcfed\configure::path_dataDir();
$privateKeyName    = \oidcfed\configure::privateKeyName();
$publicKeyName     = \oidcfed\configure::publicKeyName();
$path_dataDir_real = \oidcfed\configure::path_dataDir_real();
$private_key_path  = \oidcfed\configure::private_key_path();
$public_key_path   = \oidcfed\configure::public_key_path();
$passphrase        = \oidcfed\configure::passphrase();
$configargs        = \oidcfed\configure::configargs();
$dn                = [];
$ndays             = 365;
// CLIENT ID is below:
if (!$client_id) {
    $client_id = \oidcfed\configure::client_id();
}
$client_secret   = false;
$private_key     = \oidcfed\configure::private_key();
$priv_key_woPass = \oidcfed\security_keys::get_private_key_without_passphrase($private_key,
                                                                              $passphrase);
$public_key      = \oidcfed\configure::public_key(
                $priv_key_woPass, $public_key_path, $dn, $ndays,
                $path_dataDir_real . '/keys');
echo "";
