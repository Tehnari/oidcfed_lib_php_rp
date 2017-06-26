<?php

/**
 *
 * @author Constantin Sclifos
 * @copyright (c) 2017, Constantin Sclifos
 * @license    https://opensource.org/licenses/MIT
 *
 * Copyright MIT <2017> Constantin Sclifos <sclifcon@gmail.com>

  Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

  - The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 */

namespace oidcfed;

/**
 * Description of security_keys
 *
 * @author constantin
 */
class security_keys {

    public static $supported_algs = [
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];

    /*
      if (empty(static::$supported_algs[$alg])) {
      throw new DomainException('Algorithm not supported');
      }
     */

    public static function generate_private_key($key = '', $passphrase = "") {
        $check00 = (\is_string($key) === true && \mb_strlen($key) > 0);
        if ($check00 === true) {
            $key_contents = self::get_file_contents($key);
        }
        else {
            $key_contents = $key;
        }
        $private_key = \openssl_pkey_get_private($key_contents, $passphrase);
        return $private_key; // We generating private key, but not saving (in this function) !!!
    }

    public static function generate_public_key($dn = [], $ndays = 365) {
//        $dn = array();  // use defaults
        $str_cert = ""; // Here we will save public key
        $res_privkey = \openssl_pkey_new();
        $check01 = (\is_string($res_privkey) === true && \mb_strlen($res_privkey)
                > 0);
        if ($check01 === false) {
            return false;
        }
        $res_csr = self::generate_csr($dn, $res_privkey);
        $res_cert = \openssl_csr_sign($res_csr, null, $res_privkey, $ndays);
        \openssl_x509_export($res_cert, $str_cert);
        $res_pubkey = openssl_pkey_get_public($str_cert);
        return $res_pubkey; // We generating public key, but not saving (in this function) !!!
    }

    /**
     * This will help to generate certificate
     */
    public static function generate_csr($dn, $res_privkey) {
        $check00 = (\is_array($dn) === true && \count($dn) > 0);
        $check01 = (\is_string($res_privkey) === true && \mb_strlen($res_privkey)
                > 0);
        if ($check00 === true && $check01 === true) {
            $res_csr = \openssl_csr_new($dn, $res_privkey);
        }
        else {
            $res_csr = false;
        }
        return $res_csr;
    }

    public static function get_private_key($key = '') {
        $check00 = (\is_string($key) === true && \mb_strlen($key) > 0);
        if ($check00 === true) {
            $key_contents = self::get_file_contents($key);
        }
        else {
            $key_contents = $key;
        }
        return $key_contents;
    }

    public static function get_public_key($path = false) {
        //TODO Need to think...
    }

    public static function get_csr($path = false) {
        //TODO Need to think...
    }

    public static function get_file_contents($filename = false) {
        $check00 = (\is_string($filename) === true && \mb_strlen($filename) > 0);
        if ($check00 === true) {
            $path_parts = \pathinfo($filename);
        }
        else {
            $path_parts = false;
        }
        $check01 = ($check00 === true && \is_array($path_parts) === true);
        // If it's path then in array should be at least:
        // dirname, basename, filename but extension can be missing.
        $check02 = ($check01 === true && \count($path_parts) >= 3);
        if ($check02 === true && \is_file($filename) === true && \is_readable($filename) === true) {
            $file_contents = \file_get_contents($filename);
        }
        else {
            $file_contents = $filename;
        }
        return $file_contents;
    }

}
