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

    public static function merge_two_MS($ms1=false, $ms2=false) {
        return false;
    }

    public static function unpack_MS($jwt_string, $signing_keys,
                                     $signing_keys_bundle = [],
                                     $claim_iss = false, $cert_verify = true) {
        $claims                     = \oidcfed\security_jose::get_jwt_claims($jwt_string);
        $ms_str                     = false;
        $ms_arr                     = [];
        $claim_kid                  = false;
        //Allowing unsecure connection is opposite for cert_verify
        $allow_unsecure_connections = !$cert_verify;
        $check00_issuer             = (\is_string($claim_iss) === true && \mb_strlen($claim_iss)
                > 0);
        $check00                    = (\is_array($claims) === true && \count($claims)
                > 0);
        $check01_iss                = ($check00_issuer === false && $check00 === true
                && \array_key_exists('iss', $claims) === true);
        $check01_kid                = ($check00 === true && \array_key_exists('kid',
                                                                              $claims) === true);
        if ($check01_iss === true) {
            $claim_iss = $claims['iss'];
        }
        if ($check01_kid === true) {
            $claim_kid = $claims['kid'];
        }
        if (\is_array($signing_keys_bundle) === false) {
            $signing_keys_bundle = [];
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // metadata_statement = MS
        // metadata_statement_uris = MS_uris
        //
        $check01_MS      = ($check00 === true && \array_key_exists('metadata_statements',
                                                                   $claims) === true);
        $check01_MS_uris = ($check00 === true && \array_key_exists('metadata_statement_uris',
                                                                   $claims) === true);
        if ($check00 === false) {
            throw new Exception('Claim(s) not found.');
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        if ($check01_MS === true) {
            $ms_str = $claims['metadata_statements'];
        }
        else if ($check01_MS_uris === true) {
            $tmp_ms = $claims['metadata_statement_uris'];
            $ms_str = \oidcfed\configure::getUrlContent($tmp_ms, $cert_verify);
        }
        else {
            $ms_str = false;
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//        $check01_signed_jwks_uri  = ($check00 === true && \array_key_exists('signed_jwks_uri',
//                                                                            $claims) === true);
//        $check01_jwks_uri         = ($check00 === true && \array_key_exists('jwks_uri',
//                                                                            $claims) === true);
//        $check01_jwks             = ($check00 === true && \array_key_exists('jwks',
//                                                                            $claims) === true);
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //If we have $signing_keys specified we will skip checks for claims
        $check_arr_sk = (\is_array($signing_keys) === true && \count($signing_keys)
                > 0);

        $check01_signing_keys     = ($check00 === true && $check_arr_sk === false
                && \array_key_exists('signing_keys', $claims) === true);
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        //Will work with claims["signing_keys_uri"] only if we don't have other solutions
        $check01_signing_keys_uri = ($check00 === true && $check_arr_sk === false
                && \array_key_exists('signing_keys_uri', $claims) === true);
        if ($check01_signing_keys === false && $check01_signing_keys_uri === true) {
            //We will store JWKS structure as needed for security_jose
            $signing_keys = \oidcfed\security_jose::create_jwks_from_uri($claims["signing_keys_uri"],
                                                                         $allow_unsecure_connections);
        }
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
        $check_arr_csk = (\is_array($claims['signing_keys']) === true && \count($claims['signing_keys'])
                > 0);
        if ($check01_signing_keys === true && $check_arr_sk === true && $check_arr_csk === true) {
            $signing_keys_tmp = \array_merge_recursive($claims['signing_keys'],
                                                       $signing_keys);
        }
        else if ($check01_signing_keys === true && $check_arr_csk === true && $check_arr_sk === false) {
            $signing_keys_tmp = $claims['signing_keys'];
        }
        else {
            $signing_keys_tmp = $signing_keys;
        }
        if (\is_array($signing_keys_tmp) === true && \is_array($signing_keys_bundle) === true) {
            $signing_keys_bundle = \array_merge_recursive($signing_keys_tmp,
                                                          $signing_keys_bundle);
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $check02 = (\is_string($ms_str) === true && \mb_strlen($ms_str) > 0);
        if ($check02 === true) {
            try {
                $ms_arr = \json_decode($ms_str, true);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
            }
        }
        $check03 = (\is_array($ms_str) === true && \count($ms_str) > 0);
        if ($check03 === true) {
            $ms_arr = $ms_str;
        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //Unpack all MS
        $check04 = (\is_array($ms_arr) === true && \count($ms_arr) > 0);
        if ($check04 === true) {
            $ms_tmp = [];
            foreach ($ms_arr as $msaval) {
                try {
                    $ms_tmp[] = self::unpack_MS($msaval, $signing_keys,
                                                $signing_keys_bundle);
                }
                catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                    continue;
                }
            }
            $claims['metadata_statements'] = $ms_tmp;
        }
        else {
            //If we don't have MS we should check if we have signature to check,
            //in toher case just return claims
//            $ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($jwt_string);
            if (\is_array($signing_keys_bundle) === true && \array_key_exists($claim_iss,
                                                                              $signing_keys_bundle) === true) {
                return self::verify_signature_keys_from_MS($jwt_string,
                                                           $claim_iss,
                                                           $signing_keys_bundle[$claim_iss]);
            }
            else {
                return self::verify_signature_keys_from_MS($jwt_string,
                                                           $claim_iss,
                                                           $signing_keys_bundle);
            }
            //At this moment returning claims if nothing else found
//            return $claims;
        }
        return $claims;
    }

    public static function verify_signature_keys_from_MS($ms = false,
                                                         $iss_kid = false,
                                                         $sign_keys = []) {
        $check00  = (\is_string($ms) === true && \mb_strlen($ms) > 0);
//        $check01 = (\is_string($iss_kid) === true && \mb_strlen($iss_kid) > 0);
        unset($iss_kid);
        $check02a = (\is_array($sign_keys) === true && \count($sign_keys) > 0);
        $check02b = ($sign_keys instanceof \Jose\Object\JWKSet);
//        $check02 = ($check02a === true || $check02b === true);
//        if ($check00 === false || $check01 === false || $check02 === false) {
        if ($check00 === false || ($check02a === false && $check02b === false)) {
            throw new Exception('Recieved incorect parameters.');
        }
        $jwk = false;
        //If we have JWKS (based assoc. array, where exist property keys)
        if ($check02b === true) {
            $keys = $sign_keys->getKeys();
        }
        else {
            $keys = false;
        }
        if ($check02a === true && \array_key_exists('keys', $sign_keys) === true
                && (\is_array($sign_keys['keys']) === true) && \count($sign_keys['keys'])
                > 0) {
            $sign_keys_tmp = [];
            foreach ($sign_keys['keys'] as $sks_value) {
                $sign_keys_tmp[] = $sks_value;
            }
            $sign_keys = $sign_keys_tmp;
        }
        else if ($check02b === true && (\is_array($keys) === true) && \count($keys)
                > 0) {
            $sign_keys = [];
            foreach ($keys as $sks_value) {
                $sign_keys[] = $sks_value;
            }
        }
        $ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($ms);
        foreach ($sign_keys as $skkey => $skval) {
            //In these case we will check all keys (for these realization, but MUST be changed later)
//            $check03 = (\is_array($skval) === true && \count($skval) > 0);
//            $check04a = ($check03 === true && \array_key_exists('iss', $skval));
//            $check04b = ($check03 === true && \array_key_exists('kid', $skval));
//            if ($check04a === true && $check04b === false) {
//                $skval['iss'] = $iss_kid;
//            }
//            else if ($check04a === false && $check04b === true) {
//                $skval['kid'] = $iss_kid;
//            }
//            else {
//                continue;
//            }
//            if ($check03 === false && $check05a ===false) {
//                continue;
//            }
            $check05a = ($skval instanceof \Jose\Object\JWK);
            if ($check05a === true) {
                $jwk = $skval;
            }
            else if (\is_array($skval) === true && \count($skval) > 0) {
                $jwk = \oidcfed\security_jose::create_jwk_from_values($skval,
                                                                      true);
            }
            else {
                continue;
            }
            $check05b = ($jwk instanceof \Jose\Object\JWK);
            if ($check05b === false) {
                continue;
            }
            $ms_header_kid = $ms_header->kid;
            $jwk_all_val   = $jwk->getAll();
            if ($ms_header_kid !== $jwk_all_val["kid"]) {
                continue;
            }
            try {
                $result = \oidcfed\security_jose::jwt_async_verify_sign_from_string_base64enc($ms,
                                                                                              $jwk);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                continue;
            }
            if (\is_object($result) === true) {
                return $result;
            }
        }
        throw new Exception("Bad Metadata Statement...");
    }

    /**
     * This function can verify Metadata Statements (MS) on FO site
     * @param type $base_url
     * @param type $fo_iss
     * @param type $ms
     * @param type $jwks
     * @return type
     * @throws Exception
     */
    public static function verify_MS_from_url($base_url, $fo_iss, $ms, $jwks) {
        $check00 = (\is_string($base_url) === true && \mb_strlen($base_url) > 0);
        $check01 = ($check00 === true && (\is_array(\pathinfo($base_url)) === true));
        if ($check01 === false) {
            throw new Exception("Verification failed. Bad parameter base_url for FO.");
        }
        $check02 = (\is_string($fo_iss) === true && \mb_strlen($fo_iss) > 0);
        if ($check02 === false) {
            throw new Exception("Verification failed. Bad parameter iss for FO.");
        }
        $check03 = (\is_string($ms) === true && \mb_strlen($ms) > 0);
        $check04 = ($check03 === true && \explode(".", $ms) === false);
        if ($check04 === false) {
            throw new Exception("Verification failed. Bad MS.");
        }
        $check05 = ($jwks instanceof \Jose\Object\JWKSets);
        if ($check05 === true) {
            $jwks = \json_encode($jwks, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        $check06 = ($check05 === false && (\is_string($jwks) === true && \mb_strlen($jwks)
                > 0));
        $check07 = ($check06 === true && (\is_array((\json_decode($jwks, true)) === true)));
        if ($check07 === false) {
            throw new Exception("Verification failed. Bad JWKS.");
            ;
        }
        try {
            $params_arr = ['iss' => $fo_iss, 'ms' => $ms, 'jwks' => $jwks];
            $result     = \oidcfed\configure::curl_get($base_url, $params_arr);
            return $result;
        }
        catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
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
