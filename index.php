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
require 'vendor/autoload.php';
require 'classes/autoloader.php';
//Loading classes
\oidcfed\autoloader::init();

// First testing dynaming registration ...
//$issuer = 'https://rp.certification.openid.net:8080/oidcfed_lib_php_rp/rp-response_type-code';
//$url_oidc_config = 'https://rp.certification.openid.net:8080/oidcfed_php_rp/rp-response_type-code/.well-known/openid-configuration';
//$oidc_config = \oidcfed\oidcfed::get_oidc_config($url_oidc_config, false, false, true);
////----------
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
echo "Here is just an example how to use libraries/classes!!!";
echo "<br>";
echo "Docs (and more cleaning) will be later...<br>";
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
// Some parameters are in static variables !!!
// You can just add a new value to static variables in \oidcfed\security_keys
//---------------------->>>>>
$path_dataDir      = \oidcfed\security_keys::$path_dataDir;
$privateKeyName    = \oidcfed\security_keys::$privateKeyName;
$publicKeyName     = \oidcfed\security_keys::$publicKeyName;
$path_dataDir_real = \oidcfed\security_keys::path_dataDir_real($path_dataDir);
$private_key_path  = \oidcfed\security_keys::private_key_path();
$public_key_path   = \oidcfed\security_keys::public_key_path();
$passphrase        = \oidcfed\security_keys::$passphrase;
$configargs        = \oidcfed\security_keys::$configargs;
// CLIENT ID is below:
$client_id         = \oidcfed\security_keys::parameter_kid_build();
$kid               = $client_id;
print_r($kid);
echo "<br>---------------------------------------<br>";
print_r(parse_url($kid));
$jwk_pub_json      = "";
//=============================================================================
//try {
//    mkdir($path_dataDir_real, 0777, true);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
//try {
//    mkdir($path_dataDir_real . '/keys', 0777, true);
//}
//catch (Exception $exc) {
//    echo $exc->getTraceAsString();
//}
$private_key       = \oidcfed\security_keys::get_private_key($private_key_path,
                                                             $passphrase,
                                                             $configargs,
                                                             $path_dataDir_real . '/keys');
echo "<br><b>Private key</b>:::===>>><br><pre>";
print_r($private_key);
echo "</pre><br><<<===:::End of <b>Private key</b><br>";
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
try {
    $priv_key_res = \oidcfed\security_keys::get_private_key_resource($private_key,
                                                                     $passphrase);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
$priv_key_details = openssl_pkey_get_details($priv_key_res);
//Getting private key without passphrase
openssl_pkey_export($priv_key_res, $priv_key_woPass);
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
try {
    $priv_key_res_woPass = \oidcfed\security_keys::get_private_key_resource($priv_key_woPass);
}
catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
//$priv_key_details = openssl_pkey_get_details($private_key);
//=============================================================================
// $private_key_toCheck can be resource or Private Key content (PEM format)
// Should be without passphrase !!!
$private_key_toCheck = $priv_key_woPass;
$public_key          = \oidcfed\security_keys::get_public_key($public_key_path,
                                                              $dn                  =
                [], $ndays               = 365, $private_key_toCheck,
                                                              $path_dataDir_real . '/keys');
echo "<br><b>Public key</b>:::===>>><br><pre>";
print_r($public_key);
echo "</pre><br><<<===:::End of <b>Public key</b><br>";

//=============================================================================
// TODO Work on/with JOSE should be rewrited !!!
//=============================================================================
//Generate JOSE/JWK for Private Key
echo "<pre>";

/*
  use Jose\Factory\JWKFactory;
  use Jose\Object\JWK;
  $jwk_priv = JWKFactory::createFromKey($priv_key_woPass, $passphrase);
  echo "JWK (Private KEY): <br>";
  print_r($jwk_priv);
  $jwk_priv_json = json_encode($jwk_priv, JSON_PARTIAL_OUTPUT_ON_ERROR);
  print_r($jwk_priv_json);
 */
echo "<br>";
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%";
echo "<br>";
//=============================================================================
//Generate JOSE/JWK for Public Key
// Using null as passprase, for an example,
//  and because before we have key without pass
$additional_parameters = [
    'kid' => $kid
];
$jwk_out               = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array(
                $public_key, null, $additional_parameters);
echo "JWK (Public KEY, resource array/object): <br>";
echo "vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv";
echo "<br>";
//print_r($jwk_pub);
print_r($jwk_out);
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%";
echo "<br>";
echo "JWK (Public KEY, JSON format): <br>";
//echo "<br>";
echo "vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv";
echo "<br>";
if (is_array($jwk_out) === true || is_object($jwk_out) === true) {
    $jwk_pub_json = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array(
                    $public_key, null, $additional_parameters, true);
}
print_r($jwk_pub_json);
echo "<br>";
echo "<br>";
echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
echo "<br>";
echo "========================================================================";

use Jose\Factory\JWSFactory;

/*
  $jws = JWSFactory::createJWS([
  'iss' => 'My server',
  'aud' => 'Your client',
  'sub' => 'Your resource owner',
  'exp' => time()+3600,
  'iat' => time(),
  'nbf' => time(),
  ]);
 */
$jws = JWSFactory::createJWS('A JWS with a detached payload', true);
print_r($jws);
echo "========================================================================";
echo "Working with Metadata Sttements. <br>";
echo "Using examples from: https://openid.andreas.labs.uninett.no ";
echo "<br>>>><br>";
echo "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Imh0dHBzOi8vZmVpZGUubm8vIn0.eyJjbGllbnRfbmFtZSI6IkZvb2RsZSBwb2xscyBhbmQgc3VydmV5cyIsImNvbnRhY3RzIjpbImFuZHJlYXMuc29sYmVyZ0B1bmluZXR0Lm5vIiwia29udGFrdEB1bmluZXR0Lm5vIl0sInJlZGlyZWN0X3VyaXMiOlsiaHR0cHM6Ly9mb29kbC5vcmcvY2FsbGJhY2siLCJodHRwczovL3d3dy5mb29kbC5vcmcvY2FsbGJhY2siXSwicmVzcG9uc2VfdHlwZXMiOlsiY29kZSJdLCJjbGFpbXMiOlsic3ViIiwibmFtZSIsInBpY3R1cmUiXSwiaXNzIjoiaHR0cHM6Ly9mZWlkZS5uby8iLCJzaWduaW5nX2tleXMiOlt7Imt0eSI6IlJTQSIsImtpZCI6Imh0dHBzOi8vZm9vZGwub3JnLyIsIm4iOiJ5bFdpZlhpbEczSlBEeDYwbWoxMng4c0pzeVlvWWZLcnlMdE5JVDAwR2oyd1hpUWlPNHVhUGlYTXZzRnltck5nZHBLUGtNVGlNRG5kYWVWWTFjSlc0NVRKRDAzbGQxTVZIc2tVdnJBREh3QTZKcXpLYV9qVlZlWjdFaGtsdzlXWjlFZDB5NnloUm9rS2xNSGpmY3RPQlVORXhoM3FmY0VUQ0N0Q0JIWWhWS0VMUFZVRWRlZ3lTWXFsTlB5QjlzR2xhdTY3UFFlMmdpMHRhWUstUXZnN1h3c29FTDJsUnNnS185R25FYjZQSEp2M2FrNlhkUTJSX0VnbDUtTTRxMV9xcEFKWXNNRTR4TnZVdXpqcVo5Z05ZSVJmUk5tWDhoMElwemZ6cnp2QjNhRFhLMktWb0V0N0JoSVBiYmNXYTk5dG5xODJNUW82c09lNVNIRk1uRjhGUVEiLCJlIjoiQVFBQiJ9XSwibWV0YWRhdGFfc3RhdGVtZW50cyI6WyJleUpoYkdjaU9pSlNVekkxTmlJc0luUjVjQ0k2SWtwWFZDSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlpXUjFaMkZwYmk1dmNtY3ZJbjAuZXlKamJHRnBiWE1pT2xzaWMzVmlJaXdpYm1GdFpTSXNJbVZ0WVdsc0lpd2ljR2xqZEhWeVpTSmRMQ0pwWkY5MGIydGxibDl6YVdkdWFXNW5YMkZzWjE5MllXeDFaWE5mYzNWd2NHOXlkR1ZrSWpwYklsSlRNalUySWl3aVVsTTFNVElpWFN3aWFYTnpJam9pYUhSMGNITTZMeTlsWkhWbllXbHVMbTl5Wnk4aUxDSnphV2R1YVc1blgydGxlWE1pT2x0N0ltdDBlU0k2SWxKVFFTSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlptVnBaR1V1Ym04dklpd2liaUk2SWpVeFJtTTVZVmhCTlRjMmNUazRlRUYzY2xReWNteEdiVUpyYzFRdE9UUktaRjh3YUd4Q2VXWlFORjlMT0VOMVRFSlVUVGhJUTJ4cFptUjNaVlJHWkZWa1UybENORFJsZEVNMldVeFJja3BGU1hsQ1dsOTRMVWRwUlhJMWIwMVNUbXh6TmtoWGJWZHViRUo0WVVSQlZURTJlbTVJVVRBMlJtRnJRWEoyUVZab2JrVmtUblY1TmpWblp6bFNNa2xCWnpGU2MxZENRV0Z3ZHpSbVZrWlRlWFpOVWt0bFMxTlVNWHBDV1ZZM04zTmxhVTlXYVVSSFozSjVkM2hDUTNkbmVqZFlSbGN6ZEdsNFVGcElRbTgyVG5FelIxVkZkMnBvWTFSTExXSkpOaTB6YlZVeldUSkJlREJTTjFNemFuSk9abEJMUlhWcE1FOXRZMkpKWlhGa2RTMUtUMlp2VEZGR1ZFOWZORTR0WWtKb1ZYcFhhR2RxUVZrMFdsbFdSR1ZQVWxSZlNIUlVkMVppZDBrMGVEZzRUbnBIVDBOeWVWcHFaVlpVU0RONE9FSmxWV0ZKYTNaVFJIbGtZMGxzWkZkS2IyaDRiRVZwZHlJc0ltVWlPaUpCVVVGQ0luMWRMQ0pwWVhRaU9qRTBPRGc0TnpNeU16Y3NJbVY0Y0NJNk1UUTRPRGczTmpnek9Dd2lZWFZrSWpvaWFIUjBjSE02THk5bVpXbGtaUzV1Ynk4aWZRLlU4c3VJSWtrNEIxLWxib1RuM1BFYVljUENMR3B2eW5sd2ptWmV6UnBiNWwybTR1U0N1aDd0c0NPUWd5RUdWVHpPc1NrV1JLeDlJbzVRREQwOWhINFo2Tzk0SVFZSG14X184aVhreld4ek9QNmdzSnROVnFxbE50NnBlanNzN1JoOU5hMXlyQWlibGl0YmJfMnlqaTlVaWtSUFdWd3U1czVqRmFJM1JtSXhCWnlPQm9LY29uTWVFMFB0aE9QOHB2ckZjallQYmk1Qjg1N3F2NzlnQWZVbVk4OTViYWhsUWhBMUdUamVrenBCdFZDQml3Yl90QjBtMFJwb0xBZzFvbENrYXdRVVhOZWVCRDdFckFTWGZBYzBpbm9WWm8tYzRWMGFMM3FobXFpR2IxVHdzZ2RhdXdHbEc1RzgtYlFCT25Edkg2Vmo2SGFDTG5pb1BoM3JheE1rdyIsImV5SmhiR2NpT2lKU1V6STFOaUlzSW5SNWNDSTZJa3BYVkNJc0ltdHBaQ0k2SW1oMGRIQnpPaTh2YTJGc2JXRnlMbTl5Wnk4aWZRLmV5SmpiR0ZwYlhNaU9sc2ljM1ZpSWl3aWJtRnRaU0pkTENKcFpGOTBiMnRsYmw5emFXZHVhVzVuWDJGc1oxOTJZV3gxWlhOZmMzVndjRzl5ZEdWa0lqcGJJbEpUTWpVMklsMHNJbWx6Y3lJNkltaDBkSEJ6T2k4dmEyRnNiV0Z5TG05eVp5OGlMQ0p6YVdkdWFXNW5YMnRsZVhNaU9sdDdJbXQwZVNJNklsSlRRU0lzSW10cFpDSTZJbWgwZEhCek9pOHZabVZwWkdVdWJtOHZJaXdpYmlJNklqVXhSbU01WVZoQk5UYzJjVGs0ZUVGM2NsUXljbXhHYlVKcmMxUXRPVFJLWkY4d2FHeENlV1pRTkY5TE9FTjFURUpVVFRoSVEyeHBabVIzWlZSR1pGVmtVMmxDTkRSbGRFTTJXVXhSY2twRlNYbENXbDk0TFVkcFJYSTFiMDFTVG14ek5raFhiVmR1YkVKNFlVUkJWVEUyZW01SVVUQTJSbUZyUVhKMlFWWm9ia1ZrVG5WNU5qVm5aemxTTWtsQlp6RlNjMWRDUVdGd2R6Um1Wa1pUZVhaTlVrdGxTMU5VTVhwQ1dWWTNOM05sYVU5V2FVUkhaM0o1ZDNoQ1EzZG5lamRZUmxjemRHbDRVRnBJUW04MlRuRXpSMVZGZDJwb1kxUkxMV0pKTmkwemJWVXpXVEpCZURCU04xTXphbkpPWmxCTFJYVnBNRTl0WTJKSlpYRmtkUzFLVDJadlRGRkdWRTlmTkU0dFlrSm9WWHBYYUdkcVFWazBXbGxXUkdWUFVsUmZTSFJVZDFaaWQwazBlRGc0VG5wSFQwTnllVnBxWlZaVVNETjRPRUpsVldGSmEzWlRSSGxrWTBsc1pGZEtiMmg0YkVWcGR5SXNJbVVpT2lKQlVVRkNJbjFkTENKcFlYUWlPakUwT0RnNE56TXlNemNzSW1WNGNDSTZNVFE0T0RnM05qZ3pPQ3dpWVhWa0lqb2lhSFIwY0hNNkx5OW1aV2xrWlM1dWJ5OGlmUS5vUGl4azZUWkZqeEprUmt6SGF0YjhPNzhEY2xVMmFKUjg4N0FqX1NTWWE5Y2xrRXZnRDVpRy00ZGRwWG5hVGNpNjRDWnBoUzJkV3JOQ2JHcERCdHl6MGdsQldyZmI3dnpxSnRYX25FWVVuOGZ5T1hqdWlXbUc5TlZuUEZIOXBia1BLZkk3NzdxWFRFM2EycklBb3hoaTZpMFdaTmxBTkFqQ0xmZTJxRHVJVEp3TFpzUmZGMi13a3lJZWN2MHFEY2NaRENRVkppbnRZVFdoQVhBTnhjVnJsNFZYaWEwQ3hCWVFOa3VwTFNFMmtvTDNiZU1pZExyVjRxOTJvbFRTa0dMR1BuVzdqcTc2Q1pxdG1QR2c0aEpIZG9seXA3YzdWbkstT1pWUXdFR3NURjVUOGxrYm9uV2p6TjVPR19kU1FjcGhOX1g0OXhIS1lXNDJKRmRaZ3pvU3ciXSwiaWF0IjoxNDg4ODczMjM4LCJleHAiOjE0ODg4NzY4Mzh9.wjM0fZxf0kM88E7Rk1cU74pxZYyqvzpimZijVIC_710G2TUYkD9TV8Zcz1Bl7v9xuANxwSlW29fHk2lK5O8eDBizwfX4dUJwulnkCJHUcY8hWLpxIJa_o2lXBDXeOCHpey7kySLe859bQLsXUI_LEqHjyKysOfk5TJhzno930HfuTa6ixNefSRt3_owYCiDCkHPluuSx2l9ot058qBK6Lwqno4fMF5DoVRTALeMnLhsy-iIcVYILMNJuEl9tmlIftnYQ_V1HRk1vZlibjJZa4PdeNe1250yrX3lDbluxoSPydy30tfdRRP9DwAbIq9_L8uKGt4qptsWS0WNd_Tdp1A";
echo "<br><<<<br>";
$ms_example = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Imh0dHBzOi8vZmVpZGUubm8vIn0.eyJjbGllbnRfbmFtZSI6IkZvb2RsZSBwb2xscyBhbmQgc3VydmV5cyIsImNvbnRhY3RzIjpbImFuZHJlYXMuc29sYmVyZ0B1bmluZXR0Lm5vIiwia29udGFrdEB1bmluZXR0Lm5vIl0sInJlZGlyZWN0X3VyaXMiOlsiaHR0cHM6Ly9mb29kbC5vcmcvY2FsbGJhY2siLCJodHRwczovL3d3dy5mb29kbC5vcmcvY2FsbGJhY2siXSwicmVzcG9uc2VfdHlwZXMiOlsiY29kZSJdLCJjbGFpbXMiOlsic3ViIiwibmFtZSIsInBpY3R1cmUiXSwiaXNzIjoiaHR0cHM6Ly9mZWlkZS5uby8iLCJzaWduaW5nX2tleXMiOlt7Imt0eSI6IlJTQSIsImtpZCI6Imh0dHBzOi8vZm9vZGwub3JnLyIsIm4iOiJ5bFdpZlhpbEczSlBEeDYwbWoxMng4c0pzeVlvWWZLcnlMdE5JVDAwR2oyd1hpUWlPNHVhUGlYTXZzRnltck5nZHBLUGtNVGlNRG5kYWVWWTFjSlc0NVRKRDAzbGQxTVZIc2tVdnJBREh3QTZKcXpLYV9qVlZlWjdFaGtsdzlXWjlFZDB5NnloUm9rS2xNSGpmY3RPQlVORXhoM3FmY0VUQ0N0Q0JIWWhWS0VMUFZVRWRlZ3lTWXFsTlB5QjlzR2xhdTY3UFFlMmdpMHRhWUstUXZnN1h3c29FTDJsUnNnS185R25FYjZQSEp2M2FrNlhkUTJSX0VnbDUtTTRxMV9xcEFKWXNNRTR4TnZVdXpqcVo5Z05ZSVJmUk5tWDhoMElwemZ6cnp2QjNhRFhLMktWb0V0N0JoSVBiYmNXYTk5dG5xODJNUW82c09lNVNIRk1uRjhGUVEiLCJlIjoiQVFBQiJ9XSwibWV0YWRhdGFfc3RhdGVtZW50cyI6WyJleUpoYkdjaU9pSlNVekkxTmlJc0luUjVjQ0k2SWtwWFZDSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlpXUjFaMkZwYmk1dmNtY3ZJbjAuZXlKamJHRnBiWE1pT2xzaWMzVmlJaXdpYm1GdFpTSXNJbVZ0WVdsc0lpd2ljR2xqZEhWeVpTSmRMQ0pwWkY5MGIydGxibDl6YVdkdWFXNW5YMkZzWjE5MllXeDFaWE5mYzNWd2NHOXlkR1ZrSWpwYklsSlRNalUySWl3aVVsTTFNVElpWFN3aWFYTnpJam9pYUhSMGNITTZMeTlsWkhWbllXbHVMbTl5Wnk4aUxDSnphV2R1YVc1blgydGxlWE1pT2x0N0ltdDBlU0k2SWxKVFFTSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlptVnBaR1V1Ym04dklpd2liaUk2SWpVeFJtTTVZVmhCTlRjMmNUazRlRUYzY2xReWNteEdiVUpyYzFRdE9UUktaRjh3YUd4Q2VXWlFORjlMT0VOMVRFSlVUVGhJUTJ4cFptUjNaVlJHWkZWa1UybENORFJsZEVNMldVeFJja3BGU1hsQ1dsOTRMVWRwUlhJMWIwMVNUbXh6TmtoWGJWZHViRUo0WVVSQlZURTJlbTVJVVRBMlJtRnJRWEoyUVZab2JrVmtUblY1TmpWblp6bFNNa2xCWnpGU2MxZENRV0Z3ZHpSbVZrWlRlWFpOVWt0bFMxTlVNWHBDV1ZZM04zTmxhVTlXYVVSSFozSjVkM2hDUTNkbmVqZFlSbGN6ZEdsNFVGcElRbTgyVG5FelIxVkZkMnBvWTFSTExXSkpOaTB6YlZVeldUSkJlREJTTjFNemFuSk9abEJMUlhWcE1FOXRZMkpKWlhGa2RTMUtUMlp2VEZGR1ZFOWZORTR0WWtKb1ZYcFhhR2RxUVZrMFdsbFdSR1ZQVWxSZlNIUlVkMVppZDBrMGVEZzRUbnBIVDBOeWVWcHFaVlpVU0RONE9FSmxWV0ZKYTNaVFJIbGtZMGxzWkZkS2IyaDRiRVZwZHlJc0ltVWlPaUpCVVVGQ0luMWRMQ0pwWVhRaU9qRTBPRGc0TnpNeU16Y3NJbVY0Y0NJNk1UUTRPRGczTmpnek9Dd2lZWFZrSWpvaWFIUjBjSE02THk5bVpXbGtaUzV1Ynk4aWZRLlU4c3VJSWtrNEIxLWxib1RuM1BFYVljUENMR3B2eW5sd2ptWmV6UnBiNWwybTR1U0N1aDd0c0NPUWd5RUdWVHpPc1NrV1JLeDlJbzVRREQwOWhINFo2Tzk0SVFZSG14X184aVhreld4ek9QNmdzSnROVnFxbE50NnBlanNzN1JoOU5hMXlyQWlibGl0YmJfMnlqaTlVaWtSUFdWd3U1czVqRmFJM1JtSXhCWnlPQm9LY29uTWVFMFB0aE9QOHB2ckZjallQYmk1Qjg1N3F2NzlnQWZVbVk4OTViYWhsUWhBMUdUamVrenBCdFZDQml3Yl90QjBtMFJwb0xBZzFvbENrYXdRVVhOZWVCRDdFckFTWGZBYzBpbm9WWm8tYzRWMGFMM3FobXFpR2IxVHdzZ2RhdXdHbEc1RzgtYlFCT25Edkg2Vmo2SGFDTG5pb1BoM3JheE1rdyIsImV5SmhiR2NpT2lKU1V6STFOaUlzSW5SNWNDSTZJa3BYVkNJc0ltdHBaQ0k2SW1oMGRIQnpPaTh2YTJGc2JXRnlMbTl5Wnk4aWZRLmV5SmpiR0ZwYlhNaU9sc2ljM1ZpSWl3aWJtRnRaU0pkTENKcFpGOTBiMnRsYmw5emFXZHVhVzVuWDJGc1oxOTJZV3gxWlhOZmMzVndjRzl5ZEdWa0lqcGJJbEpUTWpVMklsMHNJbWx6Y3lJNkltaDBkSEJ6T2k4dmEyRnNiV0Z5TG05eVp5OGlMQ0p6YVdkdWFXNW5YMnRsZVhNaU9sdDdJbXQwZVNJNklsSlRRU0lzSW10cFpDSTZJbWgwZEhCek9pOHZabVZwWkdVdWJtOHZJaXdpYmlJNklqVXhSbU01WVZoQk5UYzJjVGs0ZUVGM2NsUXljbXhHYlVKcmMxUXRPVFJLWkY4d2FHeENlV1pRTkY5TE9FTjFURUpVVFRoSVEyeHBabVIzWlZSR1pGVmtVMmxDTkRSbGRFTTJXVXhSY2twRlNYbENXbDk0TFVkcFJYSTFiMDFTVG14ek5raFhiVmR1YkVKNFlVUkJWVEUyZW01SVVUQTJSbUZyUVhKMlFWWm9ia1ZrVG5WNU5qVm5aemxTTWtsQlp6RlNjMWRDUVdGd2R6Um1Wa1pUZVhaTlVrdGxTMU5VTVhwQ1dWWTNOM05sYVU5V2FVUkhaM0o1ZDNoQ1EzZG5lamRZUmxjemRHbDRVRnBJUW04MlRuRXpSMVZGZDJwb1kxUkxMV0pKTmkwemJWVXpXVEpCZURCU04xTXphbkpPWmxCTFJYVnBNRTl0WTJKSlpYRmtkUzFLVDJadlRGRkdWRTlmTkU0dFlrSm9WWHBYYUdkcVFWazBXbGxXUkdWUFVsUmZTSFJVZDFaaWQwazBlRGc0VG5wSFQwTnllVnBxWlZaVVNETjRPRUpsVldGSmEzWlRSSGxrWTBsc1pGZEtiMmg0YkVWcGR5SXNJbVVpT2lKQlVVRkNJbjFkTENKcFlYUWlPakUwT0RnNE56TXlNemNzSW1WNGNDSTZNVFE0T0RnM05qZ3pPQ3dpWVhWa0lqb2lhSFIwY0hNNkx5OW1aV2xrWlM1dWJ5OGlmUS5vUGl4azZUWkZqeEprUmt6SGF0YjhPNzhEY2xVMmFKUjg4N0FqX1NTWWE5Y2xrRXZnRDVpRy00ZGRwWG5hVGNpNjRDWnBoUzJkV3JOQ2JHcERCdHl6MGdsQldyZmI3dnpxSnRYX25FWVVuOGZ5T1hqdWlXbUc5TlZuUEZIOXBia1BLZkk3NzdxWFRFM2EycklBb3hoaTZpMFdaTmxBTkFqQ0xmZTJxRHVJVEp3TFpzUmZGMi13a3lJZWN2MHFEY2NaRENRVkppbnRZVFdoQVhBTnhjVnJsNFZYaWEwQ3hCWVFOa3VwTFNFMmtvTDNiZU1pZExyVjRxOTJvbFRTa0dMR1BuVzdqcTc2Q1pxdG1QR2c0aEpIZG9seXA3YzdWbkstT1pWUXdFR3NURjVUOGxrYm9uV2p6TjVPR19kU1FjcGhOX1g0OXhIS1lXNDJKRmRaZ3pvU3ciXSwiaWF0IjoxNDg4ODczMjM4LCJleHAiOjE0ODg4NzY4Mzh9.wjM0fZxf0kM88E7Rk1cU74pxZYyqvzpimZijVIC_710G2TUYkD9TV8Zcz1Bl7v9xuANxwSlW29fHk2lK5O8eDBizwfX4dUJwulnkCJHUcY8hWLpxIJa_o2lXBDXeOCHpey7kySLe859bQLsXUI_LEqHjyKysOfk5TJhzno930HfuTa6ixNefSRt3_owYCiDCkHPluuSx2l9ot058qBK6Lwqno4fMF5DoVRTALeMnLhsy-iIcVYILMNJuEl9tmlIftnYQ_V1HRk1vZlibjJZa4PdeNe1250yrX3lDbluxoSPydy30tfdRRP9DwAbIq9_L8uKGt4qptsWS0WNd_Tdp1A";
echo "<br>";
$ms_strArr= explode('.', $ms_example);
var_dump($ms_strArr);

echo "========================================================================";
$ms_header = \oidcfed\security_jose::get_jose_header_to_object($ms_example);
var_dump($ms_header);
echo "========================================================================";
echo "</pre>";
