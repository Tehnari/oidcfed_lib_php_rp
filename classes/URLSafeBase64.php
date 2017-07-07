<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
