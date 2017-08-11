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

use \Jose\Checker\AudienceChecker;
use \Jose\Checker\ExpirationChecker;
use \Jose\Checker\IssuedAtChecker;
use \Jose\Checker\NotBeforeChecker;
use \Jose\Factory\CheckerManagerFactory;
use \Jose\Factory\JWKFactory;
use \Jose\Factory\JWSFactory;
use \Jose\Factory\JWEFactory;
use \Jose\Factory\KeyFactory;
use \Jose\Factory\LoaderFactory;
use \Jose\Factory\VerifierFactory;
use \Jose\Object\JWSInterface;
use \Jose\Object\JWKSet;
use \Jose\Object\JWKSetInterface;
use \Jose\Object\JWKInterface;
use \Jose\Object\JWK;
use \Jose\JWTCreator;
use \Jose\Signer;
use \Jose\Loader;
use \Jose\JWTLoader;
use Exception;

/**
 * Description of security_jose (JSON Object Signing and Encryption)
 * More info about jose: https://datatracker.ietf.org/wg/jose/documents/
 *
 * @author constantin
 */
class security_jose {

    /**
     * This function will help with generation of the jose/jwk. In this case
     * passphrase can be set (if exist in the key).
     * Additional parameters ('kid', 'alg' and 'use')
     * are not mandatory but recommended.
     * @param type $key_content
     * @param type $key_passphrase
     * @param array $additional_parameters
     * @param type $kid
     * @param type $json_return
     * @return type
     * @throws Exception
     */
    public static function generate_jwk_from_key_with_parameter_array(
    $key_content, $key_passphrase = null, array $additional_parameters = [],
    $json_return = false) {
        $check00 = (\is_array($additional_parameters) === true);
        if ($check00 === false) {
            $additional_parameters = [];
        }
        //Generate JOSE/JWK for Public Key
        $check01  = (\is_string($key_passphrase) === true && \mb_strlen($key_passphrase)
                > 0);
        $check01a = ($check01 === false && empty($key_passphrase) === true);
        $check01b = ($key_content instanceof \Jose\Object\JWK);
        if ($check01 === false && $check01a === true && $check01b === false) {
            $key_passphrase = null;
        }
        else if ($check01b === true ){
            //If it's JWK we just return it ...
            return $key_content;
        }
        else {
            throw new Exception('Failed to build jose/jwk. Wrong key passphrase.');
//                return false;
        }
        $jwk = JWKFactory::createFromKey($key_content, $key_passphrase,
                                         $additional_parameters);
        if ($json_return !== false) {
            $jwk_out = \json_encode($jwk, \JSON_PARTIAL_OUTPUT_ON_ERROR);
            return $jwk_out;
        }
        else {
            return $jwk;
        }
    }

    public static function create_jwk_from_values(array $param,
                                                  $returnToPublic = false,
                                                  $returnPEM = false) {
        if (\is_object($param) === true) {
            $param = (array) $param;
        }
        if (\is_array($param) === false) {
            throw new Exception('Not a array provided for JWK(S) creation');
        }
        $jwk = JWKFactory::createFromValues($param);
//        $jwk = new JWKFactory();
//        $jwk->createKey($param);
        if ($returnToPublic !== false) {
            $jwk = $jwk->toPublic();
        }
        if ($returnPEM !== false) {
            $jwk = $jwk->toPEM();
        }
        return $jwk;
    }

    public static function create_jwk_from_values_in_json($values_str = false,
                                                          $kid_to_search = false) {
        $check00  = ($values_str instanceof \Jose\Object\JWKInterface);
        $check00a = ($values_str instanceof \Jose\Object\JWK);
        if ($check00 === true || $check00a === true) {
            return $values_str;
        }

        try {
            $values_arr = self::search_json_array_for_clientid($values_str,
                                                               $kid_to_search);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            throw new Exception($exc->getTraceAsString());
        }
        $kid_jwk = \oidcfed\security_jose::create_jwk_from_values($values_arr);
        $check01 = ($kid_jwk instanceof \Jose\Object\JWKInterface);
        $check02 = ($kid_jwk instanceof \Jose\Object\JWK);
        if ($check01 === false && $check02 === false) {
            throw new Exception("Couldn't generate JWK object.");
        }
        return $kid_jwk;
    }

    public static function search_json_array_for_clientid($values_str = false,
                                                          $kid_to_search = false) {

        $check00 = (\is_string($values_str) === true && \mb_strlen($values_str) > 0);
        if ($check00 === true) {
            $values_str = \json_decode($values_str, true);
        }
        $key_values_arr = false;
        $check01        = (\is_array($values_str) === true && \count($values_str)
                > 0 );
        $check02        = ($check01 === true && \array_key_exists('kid',
                                                                  $values_str[0]) === true);
        if ($check01 === false) {
            throw new Exception('Not a array/json values provided!');
        }
        else if ($check02 === true) {
            foreach ($values_str as $msPKvalue) {
                $check00 = (is_array($msPKvalue) === true && array_key_exists('kid',
                                                                              $msPKvalue));
                $check01 = ($check00 === true && $msPKvalue['kid'] === $kid_to_search);
                if ($check01 === false) {
                    continue;
                }
                $key_values_arr = $msPKvalue;
            }
        }
        $check03 = (\is_array($key_values_arr) === true && \count($key_values_arr)
                > 0 && \array_key_exists('kid', $key_values_arr) === true && $key_values_arr['kid'] === $kid_to_search);
        if ($check02 === false && $check03 === false) {
            throw new Exception('ClientID not found!');
        }
        return $key_values_arr;
    }

    public static function create_jwks_from_values(array $param) {
        $jwks = self::create_jwk_from_values($param);
        return $jwks;
    }

    public static function create_jwks_from_uri($param) {
        $check00 = (\is_string($param) === true && \mb_strlen($param) > 0);
        $check01 = (\parse_url($param) !== false);
        if ($check00 === false || $check01 === false) {
            throw new Exception('Not an URL provided for JWK creation');
        }
        $jwks = JWKFactory::createFromJKU($param, true);
        return $jwks;
    }

    /**
     * This function help to create JWT (signed JWS)
     * @param type $payload
     * @param array $protected_headers
     * @param Object\JWKInterface $jwk_signature_key
     * @param type $jws_signer
     * @param type $jws_signer_alg
     * @return type
     * @throws Exception
     */
    public static function create_jwt($payload, array $protected_headers,
                                      $jwk_signature_key = false,
                                      $jws_signer = false,
                                      $jws_signer_alg = ['RS256', 'HS512']) {
        if ($jwk_signature_key === false) {
            throw new Exception('Failed to create JWT. Wrong parameters.');
//                return false;
        }
        // We create a Signer object with the signature algorithms we want to use
        $signer      = self::create_signer($jws_signer_alg, $jws_signer);
        $jwt_creator = new JWTCreator($signer);
        $jwt         = $jwt_creator->sign(
                // The payload to sign
                $payload,
                // The protected headers (must contain at least the "alg" parameter)
                $protected_headers,
                // The key used to sign (depends on the "alg" parameter).
                // Must be type of Object\JWKInterface
                $jwk_signature_key
        );
        return $jwt;
    }

    /**
     * This function create an object of JWSFactoryInterface.
     * Returned object has methods that convert to JSON.
     * @param string|array $payload
     * @param bool $is_payload_detached
     * @return Object\JWSFactoryInterface
     * @throws Exception
     */
    public static function create_jws($payload, $is_payload_detached = false) {
        $is_payload_check = \boolval($is_payload_detached);
        if (\is_bool($is_payload_check) === false) {
            throw new Exception('Wrong parameters. Payload is not boolean.');
//                return false;
        }
        return JWSFactory::createJWS($payload, $is_payload_check);
    }

    /**
     * This function create an object of JWSFactoryInterface and signed.
     * Returned object has methods that convert to JSON.
     * @param type $payload
     * @param array $protected_headers
     * @param type $jwk_signature_key
     * @param type $jws_signer
     * @param type $jws_signer_alg
     * @return type
     */
    public static function create_jws_and_sign($payload,
                                               array $protected_headers,
                                               $jwk_signature_key = false,
                                               $jws_signer = false,
                                               $jws_signer_alg = ['RS256', 'HS512']) {
        return self::create_jwt($payload, $protected_headers,
                                $jwk_signature_key, $jws_signer, $jws_signer_alg);
    }

    public static function create_signer($jws_signer_alg = ['RS256', 'HS512'],
                                         $jws_signer = false) {
        if ($jws_signer === false) {
            throw new Exception('Failed to create Signer. Check JWS.');
//                return false;
        }
        // We create a Signer object with the signature algorithms we want to use
        $signer = Signer::createSigner($jws_signer_alg);
        if ($jws_signer !== false) {
            try {
                // Then we sign
                $signer->sign($jws_signer);
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                $exc_message = $exc->getTraceAsString();
                throw new Exception('Failed to create Signer. Error/exception message:' . $exc_message);
            }
        }
        return $signer;
    }

    public static function check_jose_jwt_string_base64enc($jose_string,
                                                           $returnString = false) {
        $check00 = (\is_string($jose_string) === true && \mb_strlen($jose_string)
                > 0);
        if ($check00 === false) {
            throw new Exception('Not a JOSE string received as input.');
        }
        $strArr  = \explode('.', $jose_string);
        $check01 = (\is_array($strArr) === true && \count($strArr) === 3);
        if ($check01 === false) {
            throw new Exception('Not enougth parts in a JOSE string received as input.');
        }
        if ($returnString !== false) {
            return $jose_string;
        }
        else {
            return $strArr;
        }
    }

    public static function get_jose_jwt_header_to_object($jose_string) {
        try {
            $jose_stringArr_checked = self::check_jose_jwt_string_base64enc($jose_string,
                                                                            false);
        }
        catch (Exception $exc) {
            $jose_stringArr_checked = false;
//            echo $exc->getTraceAsString();
            $exc_message            = $exc->getTraceAsString();
            throw new Exception('Not a JOSE/JWT string received. Error/exception message:' . $exc_message);
        }
        if ($jose_stringArr_checked === false) {
            return false;
        }
        //TODO Need to finish here (base64_decode, get alg, and check)...
        $jose_jwt_str_header      = $jose_stringArr_checked[0];
        $jose_jwt_json_header     = \base64_decode($jose_jwt_str_header);
        $jose_jwt_json_header_obj = \json_decode($jose_jwt_json_header);
        $check00                  = ($jose_jwt_json_header_obj === FALSE || $jose_jwt_json_header_obj === NULL);
        if ($check00 === true) {
            throw new Exception('Problems with decoding JOSE/JWT header from string received as input.');
        }
//        $check01 = (\is_object($jose_jwt_json_header_obj) === true
//        && \property_exists($jose_jwt_json_header_obj, 'alg')===true
//        && \property_exists($jose_jwt_json_header_obj, 'typ')===true);
        $check01 = (\is_object($jose_jwt_json_header_obj) === true && \property_exists($jose_jwt_json_header_obj,
                                                                                       'alg') === true);
        if ($check01 === FALSE) {
            throw new Exception('Header Parameters typ and alg not found on JOSE/JWT Header');
        }

        return $jose_jwt_json_header_obj;
    }

    public static function get_jose_jwt_payload_to_object($jose_string) {
        try {
            $jose_stringArr_checked = self::check_jose_jwt_string_base64enc($jose_string,
                                                                            false);
        }
        catch (Exception $exc) {
            $jose_stringArr_checked = false;
//            echo $exc->getTraceAsString();
            $exc_message            = $exc->getTraceAsString();
            throw new Exception('Not a JOSE/JWT string received. Error/exception message:' . $exc_message);
        }
        if ($jose_stringArr_checked === false) {
            return false;
        }
        //TODO Need to finish here (base64_decode, get alg, and check)...
        $jose_jwt_str_payload      = $jose_stringArr_checked[1];
        $jose_jwt_json_payload     = \base64_decode($jose_jwt_str_payload);
        $jose_jwt_json_payload_obj = \json_decode($jose_jwt_json_payload);
        $check00                   = ($jose_jwt_json_payload_obj === FALSE || $jose_jwt_json_payload_obj === NULL);
        if ($check00 === true) {
            throw new Exception('Problems with decoding JOSE/JWT header from string received as input.');
        }
//        $check01 = (\is_object($jose_jwt_json_header_obj) === true
//        && \property_exists($jose_jwt_json_header_obj, 'alg')===true
//        && \property_exists($jose_jwt_json_header_obj, 'typ')===true);
        $check01 = (\is_object($jose_jwt_json_payload_obj) === true && (\property_exists($jose_jwt_json_payload_obj,
                                                                                         'claims') === true
                || (\property_exists($jose_jwt_json_payload_obj, 'signing_keys') === true
                && \property_exists($jose_jwt_json_payload_obj, 'iss') === true)));
        if ($check01 === FALSE) {
            throw new Exception('Header Parameters typ and alg not found on JOSE/JWT Header');
        }
        return $jose_jwt_json_payload_obj;
    }

    public static function jwt_async_verify_sign_from_string_base64enc(
    $jose_string, $pubSignatureKey = false
    ) {
        // We create our loader.
        $loader          = new Loader();
        $jose_obj_loaded = $loader->load($jose_string);
        $jwt_header      = self::get_jose_jwt_header_to_object($jose_string);
        $jwt_signatures  = $jose_obj_loaded->getSignatures();
        $jwk_pubKey      = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($pubSignatureKey);
        $check00         = ($jwk_pubKey instanceof \Jose\Object\JWK);
        if ($check00 === false) {
            throw new Exception("Public key wasn't provided...");
        }
        //TODO Need to search clientid in claims from jwt signatures
        $result = false;
        foreach ($jwt_signatures as $jwt_skey => $jwt_sval) {
            $check01 = ($jwt_sval instanceof \Jose\Object\Signature);
            if ($check01 === false) {
                continue;
            }
            try {
//                echo "<br>****************************<br>";
                $result = $loader->loadAndVerifySignatureUsingKey(
                        $jose_string, $pubSignatureKey, [$jwt_header->alg],
                        $jwt_sval
                );
            }
            catch (Exception $exc) {
//                $result = false;
//                echo $exc->getTraceAsString();
//                echo "<br>";
            }
            $check02 = ($result instanceof \Jose\Object\JWS);
            if ($check02 === true) {
                return $result;
            }
        }
    }


    public static function jwt_sync_decrypt_from_string_base64enc(
    $jose_string, $privSignatureKey = false, $passPhrase = null, $arr_allowed_content_encr_alg=[]
    ) {
        //TODO Need to search clientid in claims from jwt signatures
        // We create our loader.
        $loader          = new Loader();
        $jose_obj_loaded = $loader->load($jose_string);
        $jwt_header      = self::get_jose_jwt_header_to_object($jose_string);
        $jwt_signatures  = $jose_obj_loaded->getSignatures();
        $privKey_woPass      = \oidcfed\security_keys::get_private_key_without_passphrase($privSignatureKey, $passPhrase);
        $jwk_privKey      = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($privKey_woPass, $passPhrase);
        $check00         = ($jwk_privKey instanceof \Jose\Object\JWK);
        if ($check00 === false) {
            throw new Exception("Private key wasn't provided...");
        }

        //TODO Need to FINISH HERE
        $result = false;
        foreach ($jwt_signatures as $jwt_skey => $jwt_sval) {
            $check01 = ($jwt_sval instanceof \Jose\Object\Signature);
            if ($check01 === false) {
                continue;
            }
            try {
//                echo "<br>****************************<br>";
                $result = $loader->loadAndDecryptUsingKey($jose_string, $jwk_privKey, $jwt_header->alg, $arr_allowed_content_encr_alg);
            }
            catch (Exception $exc) {
//                $result = false;
//                echo $exc->getTraceAsString();
//                echo "<br>";
            }
            $check02 = ($result instanceof \Jose\Object\JWS);
            if ($check02 === true) {
                return $result;
            }
        }
    }

    public static function get_jwt_claims($jose_string) {
        // We create our loader.
        $loader          = new Loader();
        $jose_obj_loaded = $loader->load($jose_string);
        $pl              = false;
        if ($jose_obj_loaded->hasClaims() === true) {
            $pl = $jose_obj_loaded->getClaims();
            return $pl;
        }
        else {
            throw new Exception("Claims not found.");
        }
    }

    public static function get_jwt_signatures_protected_header($jose_string) {
        // We create our loader.
        $loader          = new Loader();
        $jose_obj_loaded = $loader->load($jose_string);
        $jose_signatures = $jose_obj_loaded->getSignatures();
        if ($jose_obj_loaded->countSignatures() === 0) {
            throw new Exception("Signature(s) not found.");
        }
        $signArr = [];
        foreach ($jose_signatures as $josekey => $joseval) {
            $check00 = ($joseval instanceof \Jose\Object\Signature);
            if ($check00 === false) {
                continue;
            }
            try {
                $signArr[$josekey] = $joseval->getProtectedHeaders();
            }
            catch (Exception $exc) {
//                $protected_header = false;
//                echo $exc->getTraceAsString();
                continue;
            }
        }
        return $signArr;
    }

}
