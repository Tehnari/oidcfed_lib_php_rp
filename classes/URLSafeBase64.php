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

/**
 * URLSafeBase64 Class will help with converting string to base64 url safe
 * See RFC4648 for more info.
 * http://www.faqs.org/rfcs/rfc4648.html
 *
 */
class URLSafeBase64 {

    //=========================================================================
    // Code in this block is Based on class: JOSE_URLSafeBase64
    // From https://packagist.org/packages/gree/jose
    static function encode($input) {
        return \str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }

    static function decode($input) {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    //==========================================================================
    //Code below is from php.net manual:
    //http://php.net/manual/en/function.base64-encode.php#63543
    function urlsafe_b64encode($string) {
        $data_pre = \base64_encode($string);
        $data = \str_replace(['+', '/', '='], ['-', '_', ''], $data_pre);
        return $data;
    }

    function urlsafe_b64decode($string) {
        $data = \str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = \strlen($data) % 4;
        if ($mod4) {
            $data .= \substr('====', $mod4);
        }
        return \base64_decode($data);
    }
    //==========================================================================
}
