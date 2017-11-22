<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Description of lcobucci_rsa
 *
 * @author constantin
 */
class lcobucci_rsa_256 {

    public function verifySignature($param) {
        $signer = new Sha256();

        $token = (new Builder())->issuedBy('http://example.com') // Configures the issuer (iss claim)
                ->canOnlyBeUsedBy('http://example.org') // Configures the audience (aud claim)
                ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->issuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->canOnlyBeUsedAfter(time() + 60) // Configures the time that the token can be used (nbf claim)
                ->expiresAt(time() + 3600) // Configures the expiration time of the token (nbf claim)
                ->with('uid', 1) // Configures a new claim, called "uid"
                ->sign($signer, 'testing') // creates a signature using "testing" as key
                ->getToken(); // Retrieves the generated token


        var_dump($token->verify($signer, 'testing 1')); // false, because the key is different
        var_dump($token->verify($signer, 'testing')); // true, because the key is the same
    }

}
