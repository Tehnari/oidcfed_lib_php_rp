<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

/**
 * URLSafeBase64 is Based on class: JOSE_URLSafeBase64
 * From https://packagist.org/packages/gree/jose
 * 
 */
class URLSafeBase64 {
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
}