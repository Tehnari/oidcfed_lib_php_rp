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

use Jose\Factory\JWKFactory;
use Jose\Object\JWK;
use Jose\JWTCreator;
use Jose\Signer;

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
    public static function generate_jwk_from_key_with_kid_and_parameter_array(
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
        if ($check01 === false && $check01a === true) {
            $key_passphrase = null;
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

    public static function create_jwt($payload, $headers,
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
        $jws         = $jwt_creator->sign(
                $payload, // The payload to sign
                $headers, // The protected headers (must contain at least the "alg" parameter)
                $jwk_signature_key  // The key used to sign (depends on the "alg" parameter). Must be typeof Object\JWKInterface
        );
        return $jws;
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

    public static function create_jws($header_object, $claims, $jwk) {

    }

}
