<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\KeyParser; // you can use Lcobucci\JWT\Signer\Ecdsa\Sha256 if you're using ECDSA keys
use Lcobucci\JWT\Signer\Ecdsa\Sha256; // you can use Lcobucci\JWT\Signer\Ecdsa\Sha256 if you're using ECDSA keys

/**
 * Description of lcobucci_rsa
 *
 * @author constantin
 */

class lcobucci_ecdsa_512 {

    public function verifySignature($param) {
        $signer     = new Sha256();
        $privateKey = new Key('file://{path to your private key}');

        $token = (new Builder())->issuedBy('http://example.com') // Configures the issuer (iss claim)
                ->canOnlyBeUsedBy('http://example.org') // Configures the audience (aud claim)
                ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->issuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->canOnlyBeUsedAfter(time() + 60) // Configures the time that the token can be used (nbf claim)
                ->expiresAt(time() + 3600) // Configures the expiration time of the token (exp claim)
                ->with('uid', 1) // Configures a new claim, called "uid"
                ->sign($signer, $privateKey) // creates a signature using your private key
                ->getToken(); // Retrieves the generated token

        $publicKey = new Key('file://{path to your public key}');

        var_dump($token->verify($signer, $publicKey));
    }

}
