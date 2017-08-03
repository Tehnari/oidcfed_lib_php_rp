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

use Exception;

//require '../vendor/autoload.php';
require_once 'autoloader.php';
\oidcfed\autoloader::init();
////require_once '../parameters.php';
//use Jose\Loader;

//require (__DIR__.'/../vendor/autoload.php');

/**
 * Description of metadata_statements
 *
 * @author constantin
 */
class metadata_statements {

    public static function check_info_in_MS($param) {
        $claims_must      = ["scope"];
        $scopes_supported = [
            "profile", "openid",
            "offline_access", "phone",
            "address", "email"
        ];
    }

    public static function check_MS_signature($ms) {

    }

    public static function create_MS($param) {

    }

    public static function flattening_MS($param) {

    }

    public static function get_RP_keys_for_FO($param) {

    }

    public static function get_FO_list_from_MS($param) {

    }

    public static function merge_two_MS($param) {

    }

    public static function unpack_MS($jwt_string, $sign_keys) {
        $keys       = [];
        // We create our loader.
//        $loader     = new Loader();
//        $loader->

    }

    public static function verify_OP_keys_from_MS($param) {

    }

    public static function validation_MS($param) {

    }

    public static function verify_OP_keys_from_jwks_uri($param) {

    }

}
