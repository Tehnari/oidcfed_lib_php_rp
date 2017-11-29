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

/**
 * This class security_keys should help on generating new keys
 * or on getting existing one...
 *
 * @author constantin
 */
class security_keys
    {

//=========================================================================

    public static $passphrase = '1234';
    public static $configargs = ["digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
//        "encrypt_key" => ''
    ];
//=========================================================================

    public static $path_dataDir = __DIR__ . '/../../oidcfed_data';

    public static function path_dataDir_real($path_dataDir = false)
        {
        if ($path_dataDir === false)
            {
            $path_dataDir = self::$path_dataDir;
            }
        $pathinfo = \pathinfo($path_dataDir);
        if (\is_array($pathinfo) === true)
            {
            $path_dataDir_real = \realpath($path_dataDir);
            }
        //=============================================================================
        //TODO Something is wrong here!!!

        try
            {
            if (\is_dir($path_dataDir_real) === false)
                {
                if (!\mkdir($path_dataDir_real, 0777, true))
                    {
                    die('Failed to create folders...');
                    }
                }
            }
        catch (Exception $exc)
            {
            echo $exc->getTraceAsString();
            }
        try
            {
            if (\is_dir($path_dataDir_real . '/keys') === false)
                {
                if (!\mkdir($path_dataDir_real . '/keys', 0777, true))
                    {
                    die('Failed to create folders...');
                    }
                }
            }
        catch (Exception $exc)
            {
            echo $exc->getTraceAsString();
            }
        return $path_dataDir_real;
        }

    public static $privateKeyName = "privateKey.pem";
    public static $publicKeyName = "publicKey.pem";
    public static $certificateLocalName = "certificateLocal.crt";

    public static function keys_path_real($path, $filename)
        {
        $path_dataDir_real = \realpath($path);
        $key_path = $path_dataDir_real . '/keys/' . $filename;
        return $key_path;
        }

    public static function private_key_path()
        {
        $path_data = self::$path_dataDir;
        $filename = self::$privateKeyName;
        return self::keys_path_real($path_data, $filename);
        }

    public static function public_key_path()
        {
        $path_data = self::$path_dataDir;
        $filename = self::$publicKeyName;
        return self::keys_path_real($path_data, $filename);
        }

    public static function public_certificateLocal_path()
        {
        $path_data = self::$path_dataDir;
        $filename = self::$certificateLocalName;
        return self::keys_path_real($path_data, $filename);
        }

//=========================================================================
    /**
     * This function will help to create/generate jose/jwk/kid parameter
     * This can be user as client_id for our scope
     * @param type $library_path
     * @return string
     * @throws Exception
     */
    public static function parameter_kid_build($library_path = false)
        {
        $server_filtered = filter_input_array(INPUT_SERVER);
        $script_name_pathinfo = \pathinfo($server_filtered['SCRIPT_NAME']);
        if (\is_string($library_path) === true && \mb_strlen($library_path) > 0)
            {
            $library_path_parsered = \parse_url($library_path);
            if (\is_array($library_path_parsered) === true && \array_key_exists('path',
                                                                                $library_path_parsered) === true)
                {
                $script_name_filtered = $script_name_filtered['path'];
                }
            else
                {
                throw new Exception('Failed to build jose/jwk/kid parameter.');
//                return false;
                }
            }
        else
            {
            $script_name_filtered = '';
            if (\is_array($script_name_pathinfo) === true && array_key_exists('dirname',
                                                                              $script_name_pathinfo))
                {
                $script_name_filtered = $script_name_pathinfo['dirname'];
                }
            else
                {
                throw new Exception('Failed to build jose/jwk/kid parameter.');
//                return false;
                }
            }
        $kid = $server_filtered['REQUEST_SCHEME'] . "://" . $server_filtered['SERVER_NAME'] . $script_name_filtered;
        return $kid;
        }

//=========================================================================
    public static $supported_algs = [
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];

//=========================================================================
    /*
      if (empty(static::$supported_algs[$alg])) {
      throw new DomainException('Algorithm not supported');
      }
     */

    public static function generate_private_key(
    $key = '', $passphrase = "",
    $configargs = ["digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
//        "encrypt_key" => ''
    ])
        {
        $privateKey = "";
        $check00 = (\is_string($key) === true && \mb_strlen($key) > 0);
        $check00a = (\is_string($passphrase) === true && \mb_strlen($passphrase)
                > 0);
        if ($check00a === true)
            {
            $configargs["encrypt_key"] = $passphrase;
            }
        $path_parts = \pathinfo($key);
        $check01 = ((\is_array($path_parts) === true && \count($path_parts) >= 3));
        if ($check00 === true && $check01 === true)
            {
            $key_contents = self::get_filekey_contents($key);
            }
        else
            {
            $key_contents = \openssl_pkey_new($configargs);
            }
        if ($key_contents === false)
            {
//            die('Failed to generate key pair.' . "\n");
//            throw new Exception('Failed to generate key pair.');
            return false;
            }
//    if (!openssl_pkey_export($keys, $privateKey)) die('Failed to retrieve private key.'."\n");
        if (!openssl_pkey_export($key_contents, $privateKey, $passphrase,
                                 $configargs))
            {
//            die('Failed to retrieve private key.' . "\n");
//            throw new Exception('Failed to retrieve private key.');
            return false;
            }
//        $private_key = \openssl_pkey_get_private($key_contents, $passphrase); //TODO But where is generating part???
        return $privateKey; // We generating private key, but not saving (in this function) !!!
        }

//=========================================================================
    /**
     * This will generate certificate
     */
    public static function generate_csr($dn = [], $res_privkey = '',
                                        $ndays = false, $path_to_save = false)
        {
        $check00 = (\is_array($dn) === true);
        $check01 = (\is_string($res_privkey) === true && \mb_strlen($res_privkey)
                > 0);
        $str_cert = "";
        if ($check00 === true && $check01 === true)
            {
            $res_csr = \openssl_csr_new($dn, $res_privkey);
            }
        else
            {
            $res_csr = false;
            }
        $check02 = (\is_array(\pathinfo($path_to_save)) === true && \count(\pathinfo($path_to_save))
                > 3);
        if ($res_csr !== false && ($ndays !== false && \is_numeric($ndays) === true))
            {
            $res_cert = \openssl_csr_sign($res_csr, null, $res_privkey, $ndays);
            if ($check02)
                {
                $path_arr = \pathinfo($path_to_save);
                $filename_and_ext = $path_arr["filename"] . "." . $path_arr["extension"];
                \openssl_x509_export($res_cert, $str_cert);
                \file_put_contents($filename_and_ext, $str_cert);
                }
            return $res_cert;
            }
        else
            {
            return $res_csr;
            }
        }

//=========================================================================
    public static function generate_public_key($dn = [], $ndays = 365,
                                               $res_privkey = false)
        {
//        $dn = array();  // use defaults
        $pubKey_details = false;
        $str_cert = ""; // Here we will save public key
//        $res_privkey = \openssl_pkey_new();
        $check01 = (\is_string($res_privkey) === true && \mb_strlen($res_privkey)
                > 0);
        $check02 = (\is_resource($res_privkey) === true);
        if ($check02 === true)
            {
            $priv_key_details = \openssl_pkey_get_details($res_privkey);
            $check03 = (\is_array($priv_key_details) === true && \array_key_exists('key',
                                                                                   $priv_key_details));
            if ($check03 === false)
                {
                throw new Exception('Failed to retrieve private key.');
//            return false;
                }
            else
                {
//               $pub_key = $priv_key_details['key'];
//               $res_pubkey = \openssl_pkey_get_public($pub_key);
                return $priv_key_details;
                }
            }
        else if ($check01 === true)
            {
            $res_cert = self::generate_csr($dn, $res_privkey, $ndays);
            \openssl_x509_export($res_cert, $str_cert);
            $res_pubkey = \openssl_pkey_get_public($str_cert);
            $pubKey_details = \openssl_pkey_get_details($res_pubkey);
            }
        else
            {
            throw new Exception('Failed to retrieve private key.');
//            return false;
            }
        return $pubKey_details;
// We generating public key (returning all in one object), but not saving (in this function) !!!
        }

//=========================================================================
    public static function get_private_key($key_data = '', $passphrase = '',
                                           $configargs = ["digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
//        "encrypt_key" => ''
    ], $path_save_key = '')
        {
        $check00 = (\is_string($key_data) === true && \mb_strlen($key_data) > 0);
//        $check01 = (\is_string($passphrase) === true || \is_numeric($passphrase) === true);
        $check02 = (\is_string($path_save_key) === true && \mb_strlen($path_save_key)
                > 0);
        $path_parts = \pathinfo($key_data);
        $check03 = ((\is_array($path_parts) === true && \count($path_parts) > 3));
        $path_parts_sk = \pathinfo($path_save_key);
        $check04 = ((\is_array($path_parts_sk) === true && \count($path_parts_sk) >= 3));
//        $check05 = ($check02 === true && $check03 === true );
        $check06 = ($check00 === true && $check03 === false); //TODO Need to check if it's a key !!!
//        if (($check00 === true && $check01 === true) && $check03 === true) {
        if ($check00 === true && $check03 === true)
            {
            $key_contents = self::get_filekey_contents($key_data);
            }
        else if ($check06 === false)
            {
            $key_contents = $key_data;
            }
//        else {
//            return false;
//        }
//If we have privateKey on file or as parameter (should be string!!!)
//        if ($check05 === true && $check04 === true && \count($path_parts) > 3 && $key_contents !== false) {
//            self::save_filekey_contents($path_save_key, $key_contents);
//        }
//Here we should try to check key and passphrase
        if (\is_string($key_contents) === true && \mb_strlen($key_contents) > 0)
            {
            $private_key_pem_string = $key_contents;
            }
        else
            {
            $private_key_pem_string = self::generate_private_key($key_contents,
                                                                 $passphrase,
                                                                 $configargs);
            }
//TODO Need to check if it's key here
        if ($private_key_pem_string !== false && \is_string($private_key_pem_string) === true
                && \mb_strlen($private_key_pem_string) > 0 && $check02 === true)
            {
//Saving private key to file...
            if (\is_dir($path_save_key) === true)
                {
                $pk_filename = $path_save_key . '/privateKey.pem';
                }
            else if (\is_file($path_save_key) === true && \is_readable($path_save_key) === true)
                {
                $pk_filename = $path_save_key;
                }
            else
                {
                $pk_filename = false;
                }

            if ($check04 === true && $pk_filename !== false)
                {
                self::save_filekey_contents($pk_filename,
                                            $private_key_pem_string);
                }
            }

        return $private_key_pem_string;
        }

//=========================================================================
    public static function get_private_key_without_passphrase($private_key,
                                                              $passphrase)
        {
        $priv_key_woPass = '';
        try
            {
            $priv_key_res = \oidcfed\security_keys::get_private_key_resource(
                            $private_key, $passphrase
            );
            }
        catch (Exception $exc)
            {
            echo $exc->getTraceAsString();
            }
//        $priv_key_details = openssl_pkey_get_details($priv_key_res);
//Getting private key without passphrase
        \openssl_pkey_export($priv_key_res, $priv_key_woPass);
        return $priv_key_woPass;
        }

//=========================================================================
    public static function get_private_key_resource($key_data = '',
                                                    $passphrase = '')
        {
        $check00 = (\is_string($key_data) === true && \mb_strlen($key_data) > 0);
//        $check01 = (\is_string($passphrase) === true || \is_numeric($passphrase) === true);
        $path_parts = \pathinfo($key_data);
        $check03 = ((\is_array($path_parts) === true && \count($path_parts) > 3));
//        if (($check00 === true && $check01 === true) && $check03 === true) {
        if ($check00 === true && $check03 === true)
            {
            $key_contents = self::get_filekey_contents($key_data);
            }
        else if ($check00 === true && $check03 === false)
            {
            $key_contents = $key_data;
            }
        else
            {
            return false;
            }
        $private_key_resource = \openssl_pkey_get_private($key_contents,
                                                          $passphrase);
        return $private_key_resource;
        }

//=========================================================================
    public static function get_csr($key_data = false, $dn = [],
                                   $res_privkey = false, $ndays = 365,
                                   $path_save_key = false)
        {
        $check00 = (\is_string($key_data) === true && \mb_strlen($key_data) > 0 && ($res_privkey !== false));
        $check01 = (\is_string($path_save_key) === true && \mb_strlen($path_save_key)
                > 0);
        $path_parts = \pathinfo($key_data);
        $check02 = ((\is_array($path_parts) === true && \count($path_parts) >= 3));
        $path_parts_sk = \pathinfo($path_save_key);
        $check03 = (\is_array($path_parts_sk) === true);
        $check03a = ($check03 === true && \count($path_parts_sk) > 3);
        $check03b = ($check03 === true && \count($path_parts_sk) <= 3);
//        $check03c       = ($check03===true && \count($path_parts_sk) <= 2);
        if ($check03b === true && $check03a === false)
            {
            $path_save_key = self::public_certificateLocal_path();
            }
        if ($check00 === true)
            {
            $key_contents = self::get_filekey_contents($key_data);
            }
        else if ($res_privkey !== false)
            {
            $res_cert = self::generate_csr($dn, $res_privkey, $ndays);
            \openssl_x509_export($res_cert, $key_contents);
            }
        else
            {
            throw new Exception("Failed to get private key for certificate generation.");
            }
        $check04 = (\is_string($key_contents) === true && \mb_strlen($key_contents)
                > 0);
        if ($check04 === false)
            {
            throw new Exception("Failed to get/generate Certificate.");
            }
        if ($check01 === true && $check02 === false && $check03 === true)
            {
            self::save_filekey_contents($path_save_key, $key_contents); //TODO Check where is saved !
            }
//TODO Need to check if it's key here
        return $key_contents;
        }

//=========================================================================
    public static function get_public_key($key_data = false, $dn = [],
                                          $ndays = 365, $res_privkey = false,
                                          $path_save_key = '')
        {
        if ($res_privkey === false)
            {
            return false;
            }
        $check00 = (\is_string($key_data) === true && \mb_strlen($key_data) > 0);
        $check01 = (\is_string($path_save_key) === true && \mb_strlen($path_save_key)
                > 0);

        $path_parts = \pathinfo($key_data);
        $check02 = ((\is_array($path_parts) === true && \count($path_parts) >= 3));

        $path_parts_sk = \pathinfo($path_save_key);
        $check03 = ((\is_array($path_parts_sk) === true && \count($path_parts_sk) >= 3));

        $check04 = ((\is_array($path_parts) === false));
        $check05 = ($check00 === true && $check04 === true);
        $key_contents = false;
        if ($check00 === true && $check02 === true && ( \count($path_parts) > 3)
                && $check03 === true)
            {
            $key_contents = self::get_filekey_contents($key_data);
            }
        else if ($check05 === true)
            {
            $key_contents = $key_data;
            }
        if ($key_contents === false || (\is_string($key_contents) === true && \mb_strlen($key_contents) <= 10))
            {
            $pubKey_details = self::generate_public_key($dn, $ndays,
                                                        $res_privkey);
            $key_contents = $pubKey_details['key'];
            }

        if ($check01 === true && $check03 === true && $key_contents !== false)
            {
//Saving private key to file...
            if (\is_dir($path_save_key) === true)
                {
                $pubk_filename = $path_save_key . '/publicKey.pem';
                }
            else if (\is_file($path_save_key) === true && \is_readable($path_save_key) === true)
                {
                $pubk_filename = $path_save_key;
                }
            else
                {
                $pubk_filename = false;
                }

            if ($check03 === true && $pubk_filename !== false)
                {
                self::save_filekey_contents($pubk_filename, $key_contents);
                }
//            self::save_filekey_contents($path_save_key, $key_contents); //TODO Check where is saved !
            }
//TODO Need to check if it's key here
        return $key_contents;
        }

//=========================================================================
    public static function get_filekey_contents($filename = false)
        {
        $check00 = (\is_string($filename) === true && \mb_strlen($filename) > 0);
        if ($check00 === true)
            {
            $path_parts = \pathinfo($filename);
            }
        else
            {
            $path_parts = false;
            }
        $check01 = ($check00 === true && \is_array($path_parts) === true);
// If it's path then in array should be at least:
// dirname, basename, filename but extension can be missing.
        $check02 = ($check01 === true && \count($path_parts) >= 3);
        if ($check02 === true && \is_file($filename) === true && \is_readable($filename) === true)
            {
            $file_contents = \file_get_contents($filename);
            }
        else
            {
//            $file_contents = $filename;
            return false;
            }
        return $file_contents;
        }

//=========================================================================
    public static function save_filekey_contents($filename = false,
                                                 $data = false)
        {
        $check00 = (\is_string($filename) === true && \mb_strlen($filename) > 0);
        if ($check00 === true)
            {
            $path_parts = \pathinfo($filename);
            }
        else
            {
            $path_parts = false;
            }
        $check01 = ($check00 === true && \is_array($path_parts) === true && \count($path_parts) >= 3);
        $check02 = ($check01 === true && ( \is_writable($filename) === true || \is_writable($path_parts['dirname']) === true));
        $check03 = (\is_string($data) === true && \mb_strlen($data) > 0);
        if ($check02 === false || $check03 === false)
            {
            return false;
            }
        \file_put_contents($filename, $data);
        return true;
        }

//=========================================================================
    }
