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
//require 'vendor/autoload.php';
//require 'classes/autoloader.php';
////Loading classes
//\oidcfed\autoloader::init();
require (dirname(__FILE__).'/parameters.php');
echo "";
$post_in = NULL;
//if(is_array($_POST) && count($_POST)>0){
////    $post = $_POST;
//    $post_in = filter_input_array(INPUT_POST);
//}

echo "<form action=\"login.php\" method=\"post\">";
//echo "Provider url: <input type=\"text\" name=\"provider_url\"><br>";
echo "<p>Provider url (List): <input list=\"provider_url_list\" type=\"text\" name=\"provider_url\"></p>";
echo "<datalist id=\"provider_url_list\">";
$check00 = (is_array($provider_url_list) && count($provider_url_list) > 0) ;
if ($check00) {
    foreach ($provider_url_list as $plval) {
        if (is_object($plval) && property_exists($plval, "key") && property_exists($plval,
                                                                                   "value")) {
            echo "  <option value=\"" . $plval->value . "\" >" . $plval->key . "</option>";
        }
    }
}
//echo "  <option value=\"http://url1\">Provider1</option>";
echo "</datalist>";
//echo "Client ID: <input type=\"text\" name=\"client_id\"><br>";
//echo "Client Secret: <input type=\"password\" name=\"client_secret\"><br>";
echo "<input type=\"submit\">";
echo "</form>";


