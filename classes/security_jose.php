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

/**
 * Description of security_jose (JSON Object Signing and Encryption)
 * More info about jose: https://datatracker.ietf.org/wg/jose/documents/
 *
 * @author constantin
 */
class security_jose {

    public static function generate_jwk_with_public_key_and_kid($public_key,
                                                                $kid,
                                                                $json_return = false) {
        //Generate JOSE/JWK for Public Key
        $jwk_pub = JWKFactory::createFromKey($public_key);
        $jwk_elements = $jwk_pub->getAll();
        if ($jwk_pub->has('kid') === false && \is_array($jwk_elements) === true) {
            $jwk_elements['kid'] = $kid;
        }
        if (\is_array($jwk_elements)) {
            $jwk_out = new JWK($jwk_elements);
        }
        else {
            $jwk_out = false;
        }
        if ($json_return !== false) {
            $jwk_out = \json_encode($jwk_out, \JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        return $jwk_out;
    }

}
