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

    public static function create_MS(array $param_payload,
                                     array $protected_headers = ["alg" => "", "kid" => ""],
                                     \Jose\Object\JWK $jwk_signature_key = null,
                                     \Jose\Object\JWS $jws_signer = null,
                                     array $jws_signer_alg = ['RS256', 'HS512']) {
        foreach ($param_payload as $pkey => $pvalue) {
            $check00 = (\is_string($pvalue) && \mb_strlen($pvalue) > 0);
            $check01 = (\is_array($pvalue));
            $check02 = (\is_object($pvalue) || \is_array($pvalue));
            switch ($pkey) {
                case "client_id":
                    if (!$check00) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "client_secret":
                    if (!$check00) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "scope":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                case "claims":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "client_id_issued_at":
                    break;
                case "client_secret_expires_at":
                    break;
                case "redirect_uris":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "metadata_statement_uris":
                    if (!$check02) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "metadata_statement":
                    if (!$check02) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "signing_keys":
                    if (!$check02) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "signing_keys_uri":
                    break;
                case "signed_jwks_uri":
                    break;
                case "federation_usage":
                    break;
                case "issuer":
                    if (!$check00) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "response_types":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "response_types_supported":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "subject_types_supported":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                case "grant_types_supported":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                case "id_token_signing_alg_values_supported":
                    if (!$check01) {
                        throw new Exception("Bad parameters for MS, please check: " . $pkey . " :: " . $pvalue);
                    }
                    break;
                default:
                    break;
            }
        }
        $ms_ = \oidcfed\security_jose::create_jws_and_sign($param_payload,
                                                           $protected_headers,
                                                           $jwk_signature_key,
                                                           $jws_signer,
                                                           $jws_signer_alg);
        echo "";
        return $ms_;
    }

    public static function flattening_MS($param) {

    }

    public static function get_RP_keys_for_FO($param) {

    }

    public static function get_FO_list_from_MS($param) {

    }

    public static function check_MS_scopes_supported($ms1 = [],
                                                     $ms2_example = [
    ]) {
        $check00 = (\is_array($ms1) && \array_key_exists("scopes_supported",
                                                         $ms1));
        $check01 = (\is_array($ms2_example) && \array_key_exists("scopes_supported",
                                                                 $ms2_example));
        if ($check00 === false || $check01 === false) {
            throw new Exception("Bad parameters, or scopes_supported parameter not found.");
        }
        $ms1_scopes_supported = $ms1["scopes_supported"];
        $ms2_scopes_supported = $ms2_example["scopes_supported"];
        if (\is_object($ms1_scopes_supported)) {
            $ms1_scopes_supported = (array) $ms1_scopes_supported;
        }
        if (\is_object($ms2_scopes_supported)) {
            $ms2_scopes_supported = (array) $ms2_scopes_supported;
        }
        if (\is_array($ms1_scopes_supported) === false || \is_array($ms2_scopes_supported) === false) {
            throw new Exception("Bad parameter as scopes_supported provided.");
        }
        try {
            $intersect = \array_intersect($ms1_scopes_supported,
                                          $ms2_scopes_supported);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            throw new Exception($exc->getMessage());
        }

        if (\count($intersect) === \count($ms1["scopes_supported"])) {
            return true;
        }
        else {
            return false;
//            throw new Exception("Scopes not found in the provided list.");
//            throw new Exception("Problems with scopes when checking provided scopes supported list.");
        }
    }

    public static function merge_two_ms($ms1 = [], $ms_compound = [],
                                        $ms_claim_skip = true) {
        $check00  = (\is_array($ms1) === true && \count($ms1) > 0);
        $check01  = (\is_array($ms_compound) === true );
//        $check01a = ($check01 && \count($ms_compound) > 0);
        $check02  = ($check00 === false && $check01 === true);
        $check02a = ($check00 === false || $check01 === false);
        if ($check02) {
            return $ms_compound;
        }
        else if ($check02a === true) {
            throw new Exception("Bad parameters recieved: Not an Array type!");
        }
        //TODO Please CHECK the code below!!!
        echo "";
        foreach ($ms1 as $ms1_key => $ms1_value) {
            //Skipping metadata statements
            if ($ms_claim_skip && $ms1_key === "metadata_statements") {
                continue;
            }
            $check03 = (\array_key_exists($ms1_key, $ms_compound));
            if ($check03 === false) {
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }
            //Check if is a claim/parameter: scopes_supported
            if (isset($ms1_key) && $ms1_key === "scopes_supported" && isset($ms_compound["scopes_supported"])) {
                try {
                    $check_scopes = \oidcfed\metadata_statements::check_MS_scopes_supported($ms1_value,
                                                                                            $ms_compound);
                    if (!$check_scopes) {
//                        throw new Exception("Problem with scopes checking.");
                        echo "Problem with scopes checking.";
                    }
                }
                catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                    throw new Exception($exc->getMessage());
                }
            }
            // Check if is a subset
            //String
            $check04  = (\is_string($ms1_value));
            $check04a = (\is_string($ms_compound[$ms1_key]));
            $check04b = ($check04 && $check04a && $ms1_value === $ms_compound[$ms1_key]);
            $check04c = ($check04 && $check04a && $check04b === false);
            if ($check04b) {
                throw new Exception("Error: A bad subset value found for " . ((string) $ms1_key));
            }
            else if ($check04c) {
                //If is not a subset we can add a value
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }

            //Simple list
            $check05  = (\is_array($ms1_value) && \oidcfed\arrays_funct::isArrayWithOnlyIntKeys($ms1_value));
            $check05a = (\is_array($ms_compound[$ms1_key]) && \oidcfed\arrays_funct::isArrayWithOnlyIntKeys($ms_compound[$ms1_key]));
            $check05b = (($check05 && \count($ms1_value) > 0) && ($check05a && \count($ms_compound[$ms1_key])
                    > 0) && \count($ms1_value) < \count($ms_compound[$ms1_key]));
            $check05c = (($check05 && \count($ms1_value) > 0) && ($check05a && \count($ms_compound[$ms1_key])
                    > 0) && \count($ms1_value) >= \count($ms_compound[$ms1_key]) );
            if ($check05b) {
                //If is not a subset we can add a value
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }
            else if ($check05c && \count(\array_intersect($ms1_value,
                                                          $ms_compound[$ms1_key])) === \count($ms1_value)) {
                throw new Exception("Error: A bad subset value found for " . ((string) $ms1_key));
            }

            //Booleans
            $check06  = (\is_bool($ms1_value));
            $check06a = (\is_bool($ms_compound[$ms1_key]));
            $check06b = ($check06 && $check06a && \boolval($ms1_value) === \boolval($ms_compound[$ms1_key]));
            $check06c = ($check06 && $check06a && $check06b === false);
            if ($check06b) {
                throw new Exception("Error: A bad subset value found for " . ((string) $ms1_key));
            }
            else if ($check06c) {
                //If is not a subset we can add a value
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }

            //Integer/Floats
            $check07  = (\is_numeric($ms1_value));
            $check07a = (\is_numeric($ms_compound[$ms1_key]));
            $check07b = ($check07 && $check07a && $ms1_value >= $ms_compound[$ms1_key]);
            $check07c = ($check07 && $check07a && $check07b === false);
            if ($check07b) {
                throw new Exception("Error: A bad subset value found for " . ((string) $ms1_key));
            }
            else if ($check07c) {
                //If is not a subset we can add a value
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }

            //Assoc. arrays/dictionary
            $check08  = (\is_array($ms1_value));
            $check08a = (\is_array($ms_compound[$ms1_key]));
            $check08b = (($check08 && \count($ms1_value) > 0) && ($check08a && \count($ms_compound[$ms1_key])
                    > 0) && \count($ms1_value) < \count($ms_compound[$ms1_key]));
            $check08c = (($check08 && \count($ms1_value) > 0) && ($check08a && \count($ms_compound[$ms1_key])
                    > 0) && \count($ms1_value) >= \count($ms_compound[$ms1_key]) );
            if ($check08b) {
                //If is not a subset we can add a value
                $ms_compound[$ms1_key] = $ms1_value;
                continue;
            }
            else if ($check08c) {
                $arr_intersect  = \array_intersect($ms1_value,
                                                   $ms_compound[$ms1_key]);
                $compare_result = \oidcfed\arrays_funct::get_compare_results_for_two_objects($arr_intersect,
                                                                                             $ms1_value);
                if (!$compare_result) {
                    throw new Exception("Error: A bad subset value found for " . ((string) $ms1_key));
                }
                else {
                    //If is not a subset we can add a value
                    $ms_compound[$ms1_key] = $ms1_value;
                    continue;
                }
            }
            //TODO Need to finish here and debug !!!
        }



        return $ms_compound;
    }

    public static function get_compound_ms_static($ms1 = false,
                                                  $ms_compound = []) {
        echo "";
        if ($ms1 === false || \is_array($ms_compound) === false) {
            throw new Exception("Bad parameters recieved!");
        }
        if (\is_array($ms1) === true && count($ms1) > 0) {
            $ms1_claims = $ms1;
        }
        else if ($ms1 instanceof \Jose\Object\JWS) {
            try {
                $ms1_claims = \oidcfed\security_jose::get_jws_claims_from_structure($ms1);
            }
            catch (Exception $exc) {
//            echo $exc->getTraceAsString();
                throw new Exception($exc->getMessage() . " Trace:" . $exc->getTraceAsString());
            }
        }
        $check00 = (\is_array($ms1_claims) === true && \count($ms1_claims) > 0);
        if ($check00 === false) {
            throw new Exception("Have a problem with getting claims from MS.");
        }
        $check01  = (is_array($ms1_claims[0]) && array_key_exists("iat",
                                                                  $ms1_claims[0])
                && isset($ms1_claims[0]["iat"]) === true);
        $check01a = (is_array($ms1_claims) && array_key_exists("iat",
                                                               $ms1_claims) && isset($ms1_claims["iat"]) === true);
        $check01b = (is_array($ms1_claims) && array_key_exists("metadata_statements",
                                                               $ms1_claims) && isset($ms1_claims["metadata_statements"]) === true);
        if ($check01 === true) {
            foreach ($ms1_claims as $ms1_cl_keys => $ms1_cl_val) {
                $check02 = (\is_array($ms1_cl_val) === true && \count($ms1_cl_val)
                        > 0);
                if ($check02 === false) {
                    continue;
                }
                //TODO Please CHECK the code below!!!
                $check03  = (isset($ms1_cl_val[0]["iat"]) === true);
                $check03a = (isset($ms1_cl_val[0]["metadata_statements"]) === true);
                $check04  = (isset($ms1_cl_val["iat"]) === true);
                $check04a = (isset($ms1_cl_val["metadata_statements"]) === true);
                if ($check03 === true && $check03a === true) {
                    foreach ($ms1_cl_val[0]["metadata_statements"] as
                                $ms1_cl_vkey => $ms1_cl_vval2) {
                        $check05 = (\is_array($ms1_cl_vval2) === true && \count($ms1_cl_vval2)
                                > 0);
                        if ($check05 === false) {
                            continue;
                        }
                        $ms_compound = self::get_compound_ms_static($ms1_cl_vval2,
                                                                    $ms_compound);
                    }
                }
                else if ($check04 === true && $check04a === true) {
                    foreach ($ms1_cl_val["metadata_statements"] as $ms1_cl_vkey3 =>
                                $ms1_cl_vval3) {
                        $check05 = ($ms1_cl_vval3 instanceof \Jose\Object\JWS);
                        $check06 = (\is_array($ms1_cl_vval3) === true && \count($ms1_cl_vval3)
                                > 0);
                        if ($check05 === true) {
//                            try {
//                                $ms_payload = $ms1_cl_vval3->getPayload();
//                            }
//                            catch (Exception $exc) {
//                                echo $exc->getTraceAsString();
//                            }
                            try {
                                $ms_claims = $ms1_cl_vval3->getClaims();
                            }
                            catch (Exception $exc) {
                                echo $exc->getTraceAsString();
                            }
                            $ms_compound = self::get_compound_ms_static($ms_claims,
                                                                        $ms_compound);
                        }
                        else if ($check06 === true) {
                            $ms_compound = self::get_compound_ms_static($ms1_cl_vval3,
                                                                        $ms_compound);
                        }
                        else if ($check06 === false) {
                            continue;
                        }
                    }
                }
            }
        }
        else if ($check01a === true && $check01b === true) {
            foreach ($ms1_claims["metadata_statements"] as $ms1_cl_key4 =>
                        $ms1_cl_vval4) {
                $check05 = (\is_array($ms1_cl_vval4) === true && \count($ms1_cl_vval4)
                        > 0);
                if ($check05 === false) {
                    continue;
                }
                $ms_compound = self::get_compound_ms_static($ms1_cl_vval4,
                                                            $ms_compound);
            }
        }
        else if ($check01a === true && $check01b === false) {
            $ms_compound = self::merge_two_ms($ms1_claims, $ms_compound);
        }
//        echo "";
        return $ms_compound;
    }

    public function get_compound_ms($jwt1 = false, $ms_compound = []) {
        try {
            return self::get_compound_ms_static($jwt1, $ms_compound);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            echo 'Caught exception: ', $exc->getMessage(), "\n";
        }
    }

    /**
     * Function can help with unpacking MS.
     * @param type $jwt_string
     * @param type $signing_keys
     * @param type $signing_keys_bundle
     * @param type $claim_iss
     * @param type $cert_verify
     * @return type
     * @throws Exception
     * @deprecated since version v0.0.2
     */
    public static function unpack_MS($jwt_string, $signing_keys,
                                     $signing_keys_bundle = [],
                                     $claim_iss = false, $cert_verify = true) {
        $claims                     = \oidcfed\security_jose::get_jwt_claims_from_string($jwt_string);
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
////        $check01_signed_jwks_uri  = ($check00 === true && \array_key_exists('signed_jwks_uri',
////                                                                            $claims) === true);
//        $check01_jwks_uri = ($check00 === true && \array_key_exists('jwks_uri',
//                                                                    $claims) === true);
//        $check01_jwks     = ($check00 === true && \array_key_exists('jwks',
//                                                                    $claims) === true);
//        if ($check01_jwks === false && $check01_jwks_uri === true) {
//            $claims["jwks"] = \oidcfed\configure::getUrlContent($claims["jwks_uri"],
//                                                                $cert_verify);
//        }
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
        $verify_sign_result = false;
        if (\is_array($signing_keys_bundle) === true && \array_key_exists($claim_iss,
                                                                          $signing_keys_bundle) === true) {
            try {

                $verify_sign_result = self::verify_signature_keys_from_MS($jwt_string,
                                                                          $claim_iss,
                                                                          $signing_keys_bundle[$claim_iss]);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                return null;
            }
        }
        else {
            try {
                $verify_sign_result = self::verify_signature_keys_from_MS($jwt_string,
                                                                          $claim_iss,
                                                                          $signing_keys_bundle);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                return null;
            }
        }
        if ($verify_sign_result !== false) {
            return $claims;
        }
    }

    public static function unpack($jwt_string, $signing_keys,
                                  $cert_verify = false) {
        //By default allowing connecting to unsecure connection.
        //Because of self signed certificates/keys...
        $check00 = (\is_string($jwt_string) && \mb_strlen($jwt_string) > 0);
        if (!$check00) {
            throw new Exception("Bad parameters recieved. Check JWT string.");
        }
        $ms_str   = false;
        $ms_arr   = [];
        $check00a = ($signing_keys instanceof \Jose\Object\JWK);
        $check00b = ($signing_keys instanceof \Jose\Object\JWKSet);
        $check00c = ((!$check00a && !$check00b) && \is_array($signing_keys) && \count($ms_arr)
                > 0);
        if ($check00a || $check00b || $check00c) {
            $keys = $signing_keys;
        }
        else {
            $keys = [];
        }
        $claim_kid = false;
        $claims    = \oidcfed\security_jose::get_jwt_claims_from_string($jwt_string);
        $check01   = (\is_array($claims) === true && \count($claims) > 0);
        $check02   = ($check01 && \array_key_exists('metadata_statements',
                                                    $claims) === true && \is_array($claims["metadata_statements"])
                && \count($claims["metadata_statements"]) > 0);
        $check03   = ($check01 && \array_key_exists('metadata_statements_uris',
                                                    $claims) === true && \is_array($claims["metadata_statements_uris"])
                && \count($claims["metadata_statements_uris"]) > 0);
        $check04   = ($check01 && \array_key_exists('iss', $claims) === true && \is_string($claims["iss"])
                && \mb_strlen($claims["iss"]));
        if ($check02) {
            // ==> Here should be some processing <==
            $ms1 = [];
            foreach ($claims["metadata_statements"] as $cmkey => $cmvalue) {
                $check05 = (\is_string($cmvalue) && \mb_strlen($cmvalue) > 0);
                if (!$check05) {
                    continue;
                }
                $_ms = self::unpack($cmvalue, $keys);
                //Something should be added to keys and MS
            }
        }
        else if ($check03) {
            // ==> Here should be some processing <==
            $tmp_ms = $claims['metadata_statement_uris'];
            $ms_str = \oidcfed\configure::getUrlContent($tmp_ms, $cert_verify);
            $ms1    = [];
            foreach ($claims["metadata_statements"] as $cmkey => $cmvalue) {
                $check05 = (\is_string($cmvalue) && \mb_strlen($cmvalue) > 0);
                if (!$check05) {
                    continue;
                }
                try {
                    $_keys = self::get_signing_keys_as_jwks_from_ms($cmvalue,
                                                                    $cert_verify);
                }
                catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                    echo "";
                }

                $_ms = self::unpack($cmvalue, $keys);
                //Something should be added to keys and MS
            }
        }
        else if ($check04) {
            // ==> Here  MS should be verified <==
            try {
                $_keys = self::get_signing_keys_as_jwks_from_ms($jwt_string,
                                                                $cert_verify);
            }
            catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                echo "";
            }
            
            $jws_checked = self::verify_signature_keys_from_MS($jwt_string,
                                                               $claims["iss"],
                                                               $keys);
        }

        // ==> Here  MS should be verified <==
        try {
            $jws_checked = self::verify_signature_keys_from_MS($jwt_string,
                                                               $claims["iss"],
                                                               $keys);
        }
        catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        if ($jws_checked) {
            return $claims;
        }
        else {
//            return false;
            throw new Exception("No claims found or signature verification failed.");
        }
    }

    public static function get_signing_keys_as_jwks_from_ms($jwt_string,
                                                            $cert_verify = false) {
        $check00 = (\is_string($jwt_string) && \mb_strlen($jwt_string) > 0);
        if (!$check00) {
            throw new Exception("Bad parameters recieved. Check JWT string.");
        }
        $claims  = \oidcfed\security_jose::get_jwt_claims_from_string($jwt_string);
        $check01 = (\is_array($claims) === true && \count($claims) > 0);
        $check02 = ($check01 && \array_key_exists('signing_keys', $claims) === true
                && \is_array($claims["signing_keys"]) && \count($claims["signing_keys"])
                > 0);
        $check03 = ($check01 && \array_key_exists('signing_keys_uris', $claims) === true
                && \is_array($claims["signing_keys_uris"]) && \count($claims["signing_keys_uris"])
                > 0);
        if ($check02) {
            // ==> Here  MS should be verified <==
            echo "";
            $signing_keys = $claims["signing_keys"];
            return $signing_keys;
        }
        else if ($check03) {
            // ==> Here  MS should be verified <==
            echo "";
            $signing_keys = \oidcfed\configure::getUrlContent($claims["signing_keys_uris"],
                                                              $cert_verify);
            return $signing_keys;
        }
        throw new Exception("Signing keys not found!");
    }

    public static function verify_signature_keys_from_MS($ms = false,
                                                         $iss_kid = false,
                                                         $sign_keys = []) {
        $check00  = (\is_string($ms) === true && \mb_strlen($ms) > 0);
        unset($iss_kid);
//        $check00a = (\is_string($iss_kid)===true && \is_array($sign_keys)===true && isset($sign_keys[$iss_kid]));
//        if(\is_string($iss_kid)===true && $check00a === false){
//            throw new Exception("Didn't found signature key.");
//        }
        $check02a = (\is_array($sign_keys) === true && \count($sign_keys) > 0);
        $check02b = ($sign_keys instanceof \Jose\Object\JWKSet);
        $check02c = ($sign_keys instanceof \Jose\Object\JWK);
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
        else if ($check02c === true) {
            //We working below with arrays, and in this case we add JWK to array
            $sign_keys_tmp = [$sign_keys];
            $sign_keys     = $sign_keys_tmp;
        }
        $ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($ms);
        foreach ($sign_keys as $skkey => $skval) {
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
            $check05b = ($jwk instanceof \Jose\Object\JWK); //Check if it is a key values
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

    /**
     * Decodes, verifies and flattens a compounded MS for a specific federation operator
     * P.S.: Based on function from  https://github.com/alejandro-perez/OIDCFederatedMetadataStatement/blob/master/src/main/java/org/geant/oidcfed/FederatedMetadataStatement.java
     * @param string $ms_jwt
     * @param string $fo_op
     * @param array $root_keys
     */
    public static function verifyMetadataStatement($ms_jwt, $fo_op, $root_keys) {
        $check00      = (\is_string($ms_jwt) && \mb_strlen($ms_jwt) > 0);
        $check01      = (\is_string($fo_op) && \mb_strlen($fo_op) > 0);
        $check02a     = (\is_array($root_keys) && \count($root_keys) > 0);
        $check02b     = ($root_keys instanceof \Jose\Object\JWK);
        $check02c     = ($root_keys instanceof \Jose\Object\JWKSet);
        $check02Check = ($check02a || $check02b || $check02c);
        if (!$check00 || !$check01 || !$check02Check) {
            throw new Exception("Bad parameters received!");
        }
        //Get pub sigkey for $ms_jwt
        $pubkeysArr = null;
        if ($check02a && \array_key_exists($fo_op, $root_keys) && \array_key_exists("keys",
                                                                                    $root_keys[$fo_op])) {
            $pubkeysArr = $root_keys[$fo_op];
        }
        if ($check02a && ($pubkeysArr === null || (\is_array($pubkeysArr) && count($pubkeysArr) === 0))) {
            throw new Exception("Public Keys not found for " . $fo_op);
        }
        else if ($check02b || $check02c) {
            $pubKeyJwks = $root_keys;
        }
        else {
            try {
                $pubKeyJwks = \oidcfed\security_jose::create_jwks_from_values($pubkeysArr);
            }
            catch (Exception $exc) {
//            echo $exc->getTraceAsString();
                throw new Exception("Problems with Public Keys search for " . $fo_op);
            }
        }
        $check00a = ($pubKeyJwks instanceof \Jose\Object\JWK);
        $check00b = ($pubKeyJwks instanceof \Jose\Object\JWKSet);
        if (!$check00a && !$check00b) {
            throw new Exception("Bad public key provided...");
        }
//            $keysTmp = $pubKeyJwks->getKeys();
        try {
            $jwks = \oidcfed\security_jose::jwt_async_verify_sign_from_string_base64enc($ms_jwt,
                                                                                        $pubKeyJwks);
            echo "";
        }
        catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
            throw new Exception("Verification of the MS for $fo_op failed.");
        }

        $jwks_payload = null;
        if ($jwks instanceof \Jose\Object\JWS) {
//            echo "<br>=============Verify (Keys Bundle) signature result=============<br>";
//            print_r($jwks->getPayload());
            $jwks_payload = $jwks->getPayload();
        }
        else {
            throw new Exception("Verification of the MS for $fo_op failed.");
        }
        echo "";
    }

}
