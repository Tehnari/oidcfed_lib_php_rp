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

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Keychain; // just to make our life simpler

/**
 * Description of security_jose_lcobucci
 *
 * @author constantin
 */

class security_jose_lcobucci {

    //TODO need to write function for generation JWK with HMAC
    // Below are function that help to generate JWK using ECDSA algorithm

    public static function generate_jwt_using_ecdsa_sha512($application_id,
                                                           $keyfile) {
        $signer = new \Lcobucci\JWT\Signer\Ecdsa\Sha512();
        return self::generate_jwt_using_rsa_sha256($application_id, $keyfile,
                                                   $signer);
    }

    public static function generate_jwt_using_ecdsa_sha384($application_id,
                                                           $keyfile) {
        $signer = new \Lcobucci\JWT\Signer\Ecdsa\Sha384();
        return self::generate_jwt_using_rsa_sha256($application_id, $keyfile,
                                                   $signer);
    }

    public static function generate_jwt_using_ecdsa_sha256($application_id,
                                                           $keyfile) {
        $signer = new \Lcobucci\JWT\Signer\Ecdsa\Sha384();
        return self::generate_jwt_using_rsa_sha256($application_id, $keyfile,
                                                   $signer);
    }

    // Below are function that help to generate JWK using RSA algorithm
    public static function generate_jwt_using_rsa_sha512($application_id,
                                                         $keyfile) {
        $signer = new \Lcobucci\JWT\Signer\Rsa\Sha512();
        return self::generate_jwt_using_rsa_sha256($application_id, $keyfile,
                                                   $signer);
    }

    public static function generate_jwt_using_rsa_sha384($application_id,
                                                         $keyfile) {
        $signer = new \Lcobucci\JWT\Signer\Rsa\Sha384();
        return self::generate_jwt_using_rsa_sha256($application_id, $keyfile,
                                                   $signer);
    }

    public static function generate_jwt_using_rsa_sha256($jwt = false,
                                                         $application_id = '',
                                                         $keyfile = '',
                                                         $signer = false) {
        // Based on source: https://docs.nexmo.com/tools/application-api/application-security
        //$jwt souold be typeof

        date_default_timezone_set('UTC');    //Set the time for UTC + 0
        $key = file_get_contents($keyfile);  //Retrieve your private key

        if ($signer === false) {
            $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256();
        }
        $privateKey = new Key($key);
        if ($jwt === false) {
            $jwt = (new Builder());
        }
        $this->signature = $signer->sign(
            $this->getToken()->getPayload(),
            $key
        );
        $jwt->IssuedAt(time() - date('Z')) // Time token was generated in UTC+0
                ->with('application_id', $application_id) // ID for the application you are working with
                ->identifiedBy(base64_encode(mt_rand()), true)
                ->sign($signer, $privateKey) // Create a signature using your private key
                ->getToken(); // Retrieves the JWT

        return $jwt;
    }

// End of generating part of the script

    public static function parse_token_from_string($input_string) {
        return self::get_token_from_string($input_string);
    }

    public static function get_token_from_string($input_string) {
        $check00 = (\is_string($input_string) === true && \mb_strlen($input_string)
                > 0);
        if ($check00 === false) {
            throw new Exception('Not string recieved as input.');
        }
        // Parses from a string
        $token = (new Parser())->parse((string) $input_string);
// Retrieves the token header
//        $token->getHeaders();
// Retrieves the token claims
//        $token->getClaims();
//        echo $token->getHeader('jti');
//        echo $token->getClaim('iss');
//        echo $token->getClaim('uid');
        return $token;
    }

    // End of parsing token to object Lcobucci\JWT\Token

    public static function validate_token($token) {
        //TODO to be finished
        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer('http://example.com');
        $data->setAudience('http://example.org');
        $data->setId('4f1g23a12aa');

        var_dump($token->validate($data)); // false, because we created a token that cannot be used before of `time() + 60`

        $data->setCurrentTime(time() + 60); // changing the validation time to future

        var_dump($token->validate($data)); // true, because validation information is equals to data contained on the token

        $data->setCurrentTime(time() + 4000); // changing the validation time to future

        var_dump($token->validate($data)); // false, because token is expired since current time is greater than exp
    }

    //Working with signatures in/on token ( :-) )

    public static function check_signatures_hmac_on_token($token) {
//TODO Change here to check what type of signature is
        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
//        $signer = new Sha256();
//TODO Below strings should be changed !!!
        $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                ->setAudience('http://example.org') // Configures the audience (aud claim)
                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                ->set('uid', 1) // Configures a new claim, called "uid"
                ->sign($signer, 'testing') // creates a signature using "testing" as key
                ->getToken(); // Retrieves the generated token


        var_dump($token->verify($signer, 'testing 1')); // false, because the key is different
        var_dump($token->verify($signer, 'testing')); // true, because the key is the same
        //TODO to be finished
    }

    public static function check_signatures_ecdsa_sha256_on_token($token) {
        $signer = new \Lcobucci\JWT\Signer\Ecdsa\Sha256();
        return self::check_signatures_rsa_sha256_on_token($token, $signer);
        //TODO to be finished
    }

    public static function check_signatures_rsa_sha256_on_token($token,
                                                                $signer = false) {
//use Lcobucci\JWT\Signer\Rsa\Sha256; // you can use Lcobucci\JWT\Signer\Ecdsa\Sha256 if you're using ECDSA keys

        if ($signer === false) {
            $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256();
        }

        $keychain = new Keychain();

        $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                ->setAudience('http://example.org') // Configures the audience (aud claim)
                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                ->set('uid', 1) // Configures a new claim, called "uid"
                ->sign($signer,
                       $keychain->getPrivateKey('file://{path to your private key}')) // creates a signature using your private key
                ->getToken(); // Retrieves the generated token
//var_dump($token->verify($signer, $keychain->getPublicKey('file://{path to your public key}')); // true when the public key was generated by the private one =)
        //TODO to be finished
    }

}
