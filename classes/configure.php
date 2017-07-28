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

namespace oidcfed;

require_once 'autoloader.php';
\oidcfed\autoloader::init();

/**
 * Just class for basic configuration of this library (why not? :) )
 *
 * @author constantin
 */
class configure {

    public static function path_dataDir() {
        global $path_dataDir;
        $path_dataDir = \oidcfed\security_keys::$path_dataDir;
        return $path_dataDir;
    }

    public static function privateKeyName() {
        global $privateKeyName;
        $privateKeyName = \oidcfed\security_keys::$privateKeyName;
        return $privateKeyName;
    }

    public static function publicKeyName() {
        global $publicKeyName;
        $publicKeyName = \oidcfed\security_keys::$publicKeyName;
        return $publicKeyName;
    }

    public static function path_dataDir_real() {
        global $path_dataDir_real;
//        $path_dataDir = self::path_dataDir();
        $path_dataDir_real = \oidcfed\security_keys::path_dataDir_real(self::path_dataDir());
        return $path_dataDir_real;
    }

    public static function private_key_path() {
        global $private_key_path;
        $private_key_path = \oidcfed\security_keys::private_key_path();
        return $private_key_path;
    }

    public static function public_key_path() {
        global $public_key_path;
        $public_key_path = \oidcfed\security_keys::public_key_path();
        return $public_key_path;
    }

    public static function passphrase() {
        global $passphrase;
        $passphrase = \oidcfed\security_keys::$passphrase;
        return $passphrase;
    }

    public static function configargs() {
        global $configargs;
        $configargs = \oidcfed\security_keys::$configargs;
        return $configargs;
    }

    public static function client_id() {
        global $client_id;
        $client_id = \oidcfed\security_keys::parameter_kid_build();
        return $client_id;
    }

    public static function dn() {
        global $dn;
        $dn = [];
        return $dn;
    }

    public static function ndays() {
        global $ndays;
        $ndays = 365;
        return $ndays;
    }

    public static function private_key($private_key_path = false,
                                       $passphrase = false, $configargs = false,
                                       $path_dataDir_real = false) {
        global $private_key;

        if ($private_key_path === false || $passphrase === false || $configargs === false
                || $path_dataDir_real === false) {
            $private_key_path  = self::private_key_path();
            $passphrase        = self::passphrase();
            $configargs        = self::configargs();
            $path_dataDir_real = self::path_dataDir_real();
        }
        if ($private_key_path !== false && $passphrase !== false && $configargs !== false
                && $path_dataDir_real !== false) {
            $private_key = \oidcfed\security_keys::get_private_key(
                            $private_key_path, $passphrase, $configargs,
                            $path_dataDir_real . '/keys'
            );
        }
        return $private_key;
    }

    public static function public_key($private_key_woPass = false,
                                      $public_key_path = false, $dn = false,
                                      $ndays = false, $path_dataDir_real = false) {
        global $public_key;
        $check00 = (\is_string($private_key_woPass) === true && \mb_strlen($private_key_woPass)
                > 0);
        if (empty($public_key) === true && ($private_key_woPass === false || ($check00 === false))) {
//            return null;
            throw new Exception('Private key (content) without passphrase wasn\'t provided');
        }
        //TODO Need to check if private key content was provided
        if ($public_key_path === false || $dn === false || $ndays === false || $path_dataDir_real === false) {
            $public_key_path   = self::public_key_path();
            $dn                = self::dn();
            $ndays             = self::ndays();
            $path_dataDir_real = self::path_dataDir_real();
        }
        if ($public_key_path !== false || $dn !== false || $ndays !== false || $private_key_woPass !== false
                || $path_dataDir_real !== false) {
            $public_key = \oidcfed\security_keys::get_public_key(
                            $public_key_path, $dn, $ndays, $private_key_woPass,
                            $path_dataDir_real . '/keys'
            );
        }

        return $public_key;
    }

    public static $config_template = [
        "countryName"            => 'XX',
        "stateOrProvinceName"    => 'State',
        "localityName"           => 'SomewhereCity',
        "organizationName"       => 'MySelf',
        "organizationalUnitName" => 'Whatever',
        "commonName"             => 'mySelf',
        "emailAddress"           => 'user@domain.com',
        "privkeypass"            => '',
        "numberofdays"           => 365,
        "path_data"              => "",
        "path_keys"              => "/keys",
        "configure_filename"     => 'oidcfed_lib_configure.json'
    ];

    /**
     * This function will get configure date for this lib
     * @param string $path_data
     * @param string $path_keys
     * @param string $path_tmp
     * @return boolean || object
     */
    static function get_oidcfed_lib_configure($path_data = false,
                                              $path_keys = false,
                                              $path_tmp = false) {
        if ($path_data === false) {
            return false;
        }
        if ($path_keys === false) {
            $path_keys = $path_data . "/keys";
        }
        if ($path_tmp === false) {
            $path_tmp = \sys_get_temp_dir();
        }

        $filename_lib_configure = "oidcfed_lib_configure.json";
        $configure_raw          = false;
        if (is_readable($path_data . "/" . $filename_lib_configure)) {
            $configure_raw = file_get_contents($path_data . "/" . $filename_lib_configure);
        }
        if (\mb_strlen($configure_raw) > 0) {
            try {
                $configure = \json_decode($configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $configure = false;
            }
        }
        else {
            return false;
        }
        return $configure;
    }

    /**
     * This function will set configure date for this lib
     * @param string $path_data
     * @param json $configure_raw
     * @param string $path_keys
     * @param string $path_tmp
     * @return boolean
     */
    static function set_oidcfed_lib_configure($path_data = false,
                                              $configure_raw = false,
                                              $path_keys = false,
                                              $path_tmp = false) {
        if ($path_data === false) {
            return false;
        }
        if ($path_keys === false) {
            $path_keys = $path_data . "/keys";
        }
        if ($path_tmp === false) {
            $path_tmp = \sys_get_temp_dir();
        }
        if ($configure_raw === false || \is_string($configure_raw) === false || (\is_string($configure_raw) === true
                && \mb_strlen($configure_raw) === 0)) {
            return false;
        }
        if (\mb_strlen($configure_raw) > 0) {
            try {
                $configure = \json_decode($configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $configure = false;
            }
        }
// Check if we can save configure
        $filename_lib_configure = "oidcfed_lib_configure.json";
        if (is_writable($path_data . "/" . $filename_lib_configure) === false) {
            return false;
        }
// If we have configure_raw in json format, we proceed to save configure.
        if ($configure !== false) {
            try {
                \file_put_contents($filename_lib_configure, $configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                return false;
            }
        }
        return true;
    }

}
