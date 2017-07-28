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
 $passphrase, $configargs, $client_id, $private_key, $public_key;

$path_dataDir      = \oidcfed\configure::path_dataDir();
$privateKeyName    = \oidcfed\configure::privateKeyName();
$publicKeyName     = \oidcfed\configure::publicKeyName();
$path_dataDir_real = \oidcfed\configure::path_dataDir_real();
$private_key_path  = \oidcfed\configure::private_key_path();
$public_key_path   = \oidcfed\configure::public_key_path();
$passphrase        = \oidcfed\configure::passphrase();
$configargs        = \oidcfed\configure::configargs();
// CLIENT ID is below:
$client_id         = \oidcfed\configure::client_id();
$private_key       = \oidcfed\configure::private_key();
$public_key        = \oidcfed\configure::public_key();