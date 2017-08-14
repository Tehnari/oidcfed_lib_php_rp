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

use Exception;

//require '../vendor/autoload.php';
require_once 'autoloader.php';
\oidcfed\autoloader::init();
////require_once '../parameters.php';
//use Jose\Loader;
//require (__DIR__.'/../vendor/autoload.php');

/**
 * Description of metadata_statements
 *
 * @author constantin
 */
class metadata_statements {

    public static function check_info_in_MS($param) {
        $claims_must      = ["scope"];
        $scopes_supported = [
            "profile", "openid",
            "offline_access", "phone",
            "address", "email"
        ];
    }

    public static function check_MS_signature($ms) {

    }

    public static function create_MS($param) {

    }

    public static function flattening_MS($param) {

    }

    public static function get_RP_keys_for_FO($param) {

    }

    public static function get_FO_list_from_MS($param) {

    }

    public static function merge_two_MS($param) {

    }

    public static function unpack_MS($jwt_string, $sign_keys, $keys = []) {
        $claims          = \oidcfed\security_jose::get_jwt_claims($jwt_string);
        $ms_str          = false;
        $ms_arr          = [];
        $check00         = (\is_array($claims) === true && \count($claims) > 0);
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // metadata_statement = MS
        // metadata_statement_uris = MS_uris
        //
        $check01_MS      = ($check00 === true && \array_key_exists('metadata_statements',
                                                                   $claims) === true);
        $check01_MS_uris = ($check00 === true && \array_key_exists('metadata_statement_uris',
                                                                   $claims) === true);
        if ($check00 === true) {
            throw new Exception('Claim(s) not found.');
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        if ($check01_MS === true) {
            $ms_str = $claims['metadata_statements'];
        }
        else if ($check01_MS_uris === true) {
            $tmp_ms = $claims['metadata_statement_uris'];
            $ms_str = \oidcfed\configure::getUrlContent($tmp_ms);
        }
        else {
            //verify signature
            return self::verify_signature_keys_from_MS($jwt_string,
                                                       $claims['iss'], $keys);
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        /*
          $claim_iss            = false;
          $claim_kid            = false;
          $check01_iss          = ($check00 === true && \array_key_exists('iss', $claims) === true);
          $check01_kid          = ($check00 === true && \array_key_exists('kid', $claims) === true);
          ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
          if ($check01_iss === true) {
          $claim_iss = $claims['iss'];
          }
          if ($check01_kid === true) {
          $claim_kid = $claims['kid'];
          }
         */
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $check01_signing_keys = ($check00 === true && \array_key_exists('signing_keys',
                                                                        $claims) === true);
        $check_arr_sk         = (\is_array($sign_keys) === true && \count($sign_keys)
                > 0);
        $check_arr_csk        = (\is_array($claims['signing_keys']) === true && \count($claims['signing_keys'])
                > 0);
        if ($check01_signing_keys === true && $check_arr_sk === true && $check_arr_csk === true) {
            $keys_tmp = \array_merge_recursive($claims['signing_keys'],
                                               $sign_keys);
        }
        else if ($check01_signing_keys === true && $check_arr_csk === true && $check_arr_sk === false) {
            $keys_tmp = $claims['signing_keys'];
        }
        else {
            $keys_tmp = $sign_keys;
        }
        if (\is_array($keys_tmp) === true) {
            $keys = \array_merge_recursive($keys_tmp, $keys);
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $check02 = (\is_string($ms_str) === true && \mb_strlen($ms_str) > 0);
        if ($check02 === true) {
            try {
                $ms_arr = json_decode($ms_str, true);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
            }
        }
        $check03 = (\is_array($ms_str) === true && \count($ms_str) > 0);
        if ($check03 === true) {
            $ms_arr = $ms_str;
        }
        //Unpack all MS
        $check04 = (\is_array($ms_arr) === true && \count($ms_arr) > 0);
        if ($check04 === true) {
            $ms_tmp = [];
            foreach ($ms_arr as $msaval) {
                $ms_tmp[] = self::unpack_MS($msaval, $sign_keys);
            }
        }
        $claims['metadata_statements'] = $ms_tmp;

        //TODO Need to check MS and verify signature(s)...
        $check05 = (self::verify_signature_keys_from_MS($jwt_string,
                                                        $claims['iss'], $keys));
        if ($check05 === true) {
            return claims;
        }
        else {
            throw new Exception("Couldn't verify signature in JWT/JWS.");
        }
    }

    public static function verify_signature_keys_from_MS($ms = false,
                                                         $pl = false,
                                                         $sign_keys = false) {
        $check00 = (\is_string($ms) === true && \mb_strlen($ms) > 0);
        $check01 = (\is_string($pl) === true && \mb_strlen($pl) > 0);
        $check02 = (\is_array($sign_keys) === true && \count($sign_keys) > 0);
        if ($check00 === false || $check01 === false || $check02 === false) {
            throw new Exception('Recieved incorect parameters.');
        }

        return true;
    }

    public static function validation_MS($param = false) {
        if ($param) {
            return true;
        }
        return false;
    }

//    public static function verify_signature_keys_from_jwks_uri($param) {
//
//    }
}
