<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

use Lcobucci\JWT;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signature;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
//use Lcobucci\Jose\Parsing\Parser;

/* Example of using:
//        $keys_bundle_url = 'https://agaton-sax.com:8080/bundle';
//        $sigkey_url      = 'https://agaton-sax.com:8080/bundle/sigkey';
//        $verify_cert     = $oidcFedRp->verify_cert;
//        $keys_bundle     = \oidcfed\configure::getUrlContent($keys_bundle_url,
//                                                             false);
//        $sigkey_bundle   = \oidcfed\configure::getUrlContent($sigkey_url, false);
//        $jwks_bundle     = \oidcfed\security_jose::create_jwks_from_uri($sigkey_url,
//                                                                        true);
//        $jwks = $jwks_bundle;
//        $lcobucciToken = \oidcfed\oidcfedClient::lcobucci_parseJwtString($keys_bundle);
 */

/**
 * Description of lcobucci_parser
 *
 * @author constantin
 */
class lcobucci_parser {
    public static function lcobucci_parseJwtString($stringJwt) {
        if (\is_string($stringJwt)) {
            $token = (new Parser())->parse((string) $stringJwt);
            return $token;
        }
        return null;
    }

    public static function lcobucci_get_parts($token) {
        $check00 = ($token instanceof \Lcobucci\JWT\Token);
        if (!$check00) {
            throw new Exception("Bad parameter.");
        }
        $tokenObj          = new \stdClass();
        $tokenObj->payload = $token->getPayload();
        $tokenObj->claims  = $token->getClaims();
        $tokenObj->headers = $token->getHeaders();
        return $tokenObj;
    }

}
