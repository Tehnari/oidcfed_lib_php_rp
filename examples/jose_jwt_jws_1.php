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
////Loading classes
//require '../vendor/autoload.php';
//require '../classes/autoloader.php';
//\oidcfed\autoloader::init();
require '../parameters.php';

//global $path_dataDir, $privateKeyName, $publicKeyName,
// $path_dataDir_real, $private_key_path, $public_key_path,
// $passphrase, $configargs, $client_id, $private_key, $public_key;


echo "========================================================================<br>";

use Jose\Checker\AudienceChecker;
use Jose\Checker\ExpirationChecker;
use Jose\Checker\IssuedAtChecker;
use Jose\Checker\NotBeforeChecker;
use Jose\Factory\CheckerManagerFactory;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWEFactory;
use Jose\Factory\JWSFactory;
use Jose\Factory\KeyFactory;
use Jose\Factory\LoaderFactory;
use Jose\Factory\VerifierFactory;
use Jose\Object\Signature;
use Jose\Object\SignatureInterface;
use Jose\Object\JWSInterface;
use Jose\Object\JWKSet;
use Jose\Object\JWK;
use Jose\JWTCreator;
use Jose\Signer;
use Jose\Loader;

echo "<pre>";
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
$jws                         = JWSFactory::createJWS('A JWS with a detached payload',
                                                     true);
print_r($jws);
echo "========================================================================<br>";
echo "Working with Metadata Statements. <br>";
echo "Using examples from: https://openid.andreas.labs.uninett.no ";
echo "<br>>>><br>";
echo "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Imh0dHBzOi8vZmVpZGUubm8vIn0.eyJjbGllbnRfbmFtZSI6IkZvb2RsZSBwb2xscyBhbmQgc3VydmV5cyIsImNvbnRhY3RzIjpbImFuZHJlYXMuc29sYmVyZ0B1bmluZXR0Lm5vIiwia29udGFrdEB1bmluZXR0Lm5vIl0sInJlZGlyZWN0X3VyaXMiOlsiaHR0cHM6Ly9mb29kbC5vcmcvY2FsbGJhY2siLCJodHRwczovL3d3dy5mb29kbC5vcmcvY2FsbGJhY2siXSwicmVzcG9uc2VfdHlwZXMiOlsiY29kZSJdLCJjbGFpbXMiOlsic3ViIiwibmFtZSIsInBpY3R1cmUiXSwiaXNzIjoiaHR0cHM6Ly9mZWlkZS5uby8iLCJzaWduaW5nX2tleXMiOlt7Imt0eSI6IlJTQSIsImtpZCI6Imh0dHBzOi8vZm9vZGwub3JnLyIsIm4iOiJ5bFdpZlhpbEczSlBEeDYwbWoxMng4c0pzeVlvWWZLcnlMdE5JVDAwR2oyd1hpUWlPNHVhUGlYTXZzRnltck5nZHBLUGtNVGlNRG5kYWVWWTFjSlc0NVRKRDAzbGQxTVZIc2tVdnJBREh3QTZKcXpLYV9qVlZlWjdFaGtsdzlXWjlFZDB5NnloUm9rS2xNSGpmY3RPQlVORXhoM3FmY0VUQ0N0Q0JIWWhWS0VMUFZVRWRlZ3lTWXFsTlB5QjlzR2xhdTY3UFFlMmdpMHRhWUstUXZnN1h3c29FTDJsUnNnS185R25FYjZQSEp2M2FrNlhkUTJSX0VnbDUtTTRxMV9xcEFKWXNNRTR4TnZVdXpqcVo5Z05ZSVJmUk5tWDhoMElwemZ6cnp2QjNhRFhLMktWb0V0N0JoSVBiYmNXYTk5dG5xODJNUW82c09lNVNIRk1uRjhGUVEiLCJlIjoiQVFBQiJ9XSwibWV0YWRhdGFfc3RhdGVtZW50cyI6WyJleUpoYkdjaU9pSlNVekkxTmlJc0luUjVjQ0k2SWtwWFZDSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlpXUjFaMkZwYmk1dmNtY3ZJbjAuZXlKamJHRnBiWE1pT2xzaWMzVmlJaXdpYm1GdFpTSXNJbVZ0WVdsc0lpd2ljR2xqZEhWeVpTSmRMQ0pwWkY5MGIydGxibDl6YVdkdWFXNW5YMkZzWjE5MllXeDFaWE5mYzNWd2NHOXlkR1ZrSWpwYklsSlRNalUySWl3aVVsTTFNVElpWFN3aWFYTnpJam9pYUhSMGNITTZMeTlsWkhWbllXbHVMbTl5Wnk4aUxDSnphV2R1YVc1blgydGxlWE1pT2x0N0ltdDBlU0k2SWxKVFFTSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlptVnBaR1V1Ym04dklpd2liaUk2SWpVeFJtTTVZVmhCTlRjMmNUazRlRUYzY2xReWNteEdiVUpyYzFRdE9UUktaRjh3YUd4Q2VXWlFORjlMT0VOMVRFSlVUVGhJUTJ4cFptUjNaVlJHWkZWa1UybENORFJsZEVNMldVeFJja3BGU1hsQ1dsOTRMVWRwUlhJMWIwMVNUbXh6TmtoWGJWZHViRUo0WVVSQlZURTJlbTVJVVRBMlJtRnJRWEoyUVZab2JrVmtUblY1TmpWblp6bFNNa2xCWnpGU2MxZENRV0Z3ZHpSbVZrWlRlWFpOVWt0bFMxTlVNWHBDV1ZZM04zTmxhVTlXYVVSSFozSjVkM2hDUTNkbmVqZFlSbGN6ZEdsNFVGcElRbTgyVG5FelIxVkZkMnBvWTFSTExXSkpOaTB6YlZVeldUSkJlREJTTjFNemFuSk9abEJMUlhWcE1FOXRZMkpKWlhGa2RTMUtUMlp2VEZGR1ZFOWZORTR0WWtKb1ZYcFhhR2RxUVZrMFdsbFdSR1ZQVWxSZlNIUlVkMVppZDBrMGVEZzRUbnBIVDBOeWVWcHFaVlpVU0RONE9FSmxWV0ZKYTNaVFJIbGtZMGxzWkZkS2IyaDRiRVZwZHlJc0ltVWlPaUpCVVVGQ0luMWRMQ0pwWVhRaU9qRTBPRGc0TnpNeU16Y3NJbVY0Y0NJNk1UUTRPRGczTmpnek9Dd2lZWFZrSWpvaWFIUjBjSE02THk5bVpXbGtaUzV1Ynk4aWZRLlU4c3VJSWtrNEIxLWxib1RuM1BFYVljUENMR3B2eW5sd2ptWmV6UnBiNWwybTR1U0N1aDd0c0NPUWd5RUdWVHpPc1NrV1JLeDlJbzVRREQwOWhINFo2Tzk0SVFZSG14X184aVhreld4ek9QNmdzSnROVnFxbE50NnBlanNzN1JoOU5hMXlyQWlibGl0YmJfMnlqaTlVaWtSUFdWd3U1czVqRmFJM1JtSXhCWnlPQm9LY29uTWVFMFB0aE9QOHB2ckZjallQYmk1Qjg1N3F2NzlnQWZVbVk4OTViYWhsUWhBMUdUamVrenBCdFZDQml3Yl90QjBtMFJwb0xBZzFvbENrYXdRVVhOZWVCRDdFckFTWGZBYzBpbm9WWm8tYzRWMGFMM3FobXFpR2IxVHdzZ2RhdXdHbEc1RzgtYlFCT25Edkg2Vmo2SGFDTG5pb1BoM3JheE1rdyIsImV5SmhiR2NpT2lKU1V6STFOaUlzSW5SNWNDSTZJa3BYVkNJc0ltdHBaQ0k2SW1oMGRIQnpPaTh2YTJGc2JXRnlMbTl5Wnk4aWZRLmV5SmpiR0ZwYlhNaU9sc2ljM1ZpSWl3aWJtRnRaU0pkTENKcFpGOTBiMnRsYmw5emFXZHVhVzVuWDJGc1oxOTJZV3gxWlhOZmMzVndjRzl5ZEdWa0lqcGJJbEpUTWpVMklsMHNJbWx6Y3lJNkltaDBkSEJ6T2k4dmEyRnNiV0Z5TG05eVp5OGlMQ0p6YVdkdWFXNW5YMnRsZVhNaU9sdDdJbXQwZVNJNklsSlRRU0lzSW10cFpDSTZJbWgwZEhCek9pOHZabVZwWkdVdWJtOHZJaXdpYmlJNklqVXhSbU01WVZoQk5UYzJjVGs0ZUVGM2NsUXljbXhHYlVKcmMxUXRPVFJLWkY4d2FHeENlV1pRTkY5TE9FTjFURUpVVFRoSVEyeHBabVIzWlZSR1pGVmtVMmxDTkRSbGRFTTJXVXhSY2twRlNYbENXbDk0TFVkcFJYSTFiMDFTVG14ek5raFhiVmR1YkVKNFlVUkJWVEUyZW01SVVUQTJSbUZyUVhKMlFWWm9ia1ZrVG5WNU5qVm5aemxTTWtsQlp6RlNjMWRDUVdGd2R6Um1Wa1pUZVhaTlVrdGxTMU5VTVhwQ1dWWTNOM05sYVU5V2FVUkhaM0o1ZDNoQ1EzZG5lamRZUmxjemRHbDRVRnBJUW04MlRuRXpSMVZGZDJwb1kxUkxMV0pKTmkwemJWVXpXVEpCZURCU04xTXphbkpPWmxCTFJYVnBNRTl0WTJKSlpYRmtkUzFLVDJadlRGRkdWRTlmTkU0dFlrSm9WWHBYYUdkcVFWazBXbGxXUkdWUFVsUmZTSFJVZDFaaWQwazBlRGc0VG5wSFQwTnllVnBxWlZaVVNETjRPRUpsVldGSmEzWlRSSGxrWTBsc1pGZEtiMmg0YkVWcGR5SXNJbVVpT2lKQlVVRkNJbjFkTENKcFlYUWlPakUwT0RnNE56TXlNemNzSW1WNGNDSTZNVFE0T0RnM05qZ3pPQ3dpWVhWa0lqb2lhSFIwY0hNNkx5OW1aV2xrWlM1dWJ5OGlmUS5vUGl4azZUWkZqeEprUmt6SGF0YjhPNzhEY2xVMmFKUjg4N0FqX1NTWWE5Y2xrRXZnRDVpRy00ZGRwWG5hVGNpNjRDWnBoUzJkV3JOQ2JHcERCdHl6MGdsQldyZmI3dnpxSnRYX25FWVVuOGZ5T1hqdWlXbUc5TlZuUEZIOXBia1BLZkk3NzdxWFRFM2EycklBb3hoaTZpMFdaTmxBTkFqQ0xmZTJxRHVJVEp3TFpzUmZGMi13a3lJZWN2MHFEY2NaRENRVkppbnRZVFdoQVhBTnhjVnJsNFZYaWEwQ3hCWVFOa3VwTFNFMmtvTDNiZU1pZExyVjRxOTJvbFRTa0dMR1BuVzdqcTc2Q1pxdG1QR2c0aEpIZG9seXA3YzdWbkstT1pWUXdFR3NURjVUOGxrYm9uV2p6TjVPR19kU1FjcGhOX1g0OXhIS1lXNDJKRmRaZ3pvU3ciXSwiaWF0IjoxNDg4ODczMjM4LCJleHAiOjE0ODg4NzY4Mzh9.wjM0fZxf0kM88E7Rk1cU74pxZYyqvzpimZijVIC_710G2TUYkD9TV8Zcz1Bl7v9xuANxwSlW29fHk2lK5O8eDBizwfX4dUJwulnkCJHUcY8hWLpxIJa_o2lXBDXeOCHpey7kySLe859bQLsXUI_LEqHjyKysOfk5TJhzno930HfuTa6ixNefSRt3_owYCiDCkHPluuSx2l9ot058qBK6Lwqno4fMF5DoVRTALeMnLhsy-iIcVYILMNJuEl9tmlIftnYQ_V1HRk1vZlibjJZa4PdeNe1250yrX3lDbluxoSPydy30tfdRRP9DwAbIq9_L8uKGt4qptsWS0WNd_Tdp1A";
echo "<br><<<<br>";
$ms_example                  = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Imh0dHBzOi8vZmVpZGUubm8vIn0.eyJjbGllbnRfbmFtZSI6IkZvb2RsZSBwb2xscyBhbmQgc3VydmV5cyIsImNvbnRhY3RzIjpbImFuZHJlYXMuc29sYmVyZ0B1bmluZXR0Lm5vIiwia29udGFrdEB1bmluZXR0Lm5vIl0sInJlZGlyZWN0X3VyaXMiOlsiaHR0cHM6Ly9mb29kbC5vcmcvY2FsbGJhY2siLCJodHRwczovL3d3dy5mb29kbC5vcmcvY2FsbGJhY2siXSwicmVzcG9uc2VfdHlwZXMiOlsiY29kZSJdLCJjbGFpbXMiOlsic3ViIiwibmFtZSIsInBpY3R1cmUiXSwiaXNzIjoiaHR0cHM6Ly9mZWlkZS5uby8iLCJzaWduaW5nX2tleXMiOlt7Imt0eSI6IlJTQSIsImtpZCI6Imh0dHBzOi8vZm9vZGwub3JnLyIsIm4iOiJ5bFdpZlhpbEczSlBEeDYwbWoxMng4c0pzeVlvWWZLcnlMdE5JVDAwR2oyd1hpUWlPNHVhUGlYTXZzRnltck5nZHBLUGtNVGlNRG5kYWVWWTFjSlc0NVRKRDAzbGQxTVZIc2tVdnJBREh3QTZKcXpLYV9qVlZlWjdFaGtsdzlXWjlFZDB5NnloUm9rS2xNSGpmY3RPQlVORXhoM3FmY0VUQ0N0Q0JIWWhWS0VMUFZVRWRlZ3lTWXFsTlB5QjlzR2xhdTY3UFFlMmdpMHRhWUstUXZnN1h3c29FTDJsUnNnS185R25FYjZQSEp2M2FrNlhkUTJSX0VnbDUtTTRxMV9xcEFKWXNNRTR4TnZVdXpqcVo5Z05ZSVJmUk5tWDhoMElwemZ6cnp2QjNhRFhLMktWb0V0N0JoSVBiYmNXYTk5dG5xODJNUW82c09lNVNIRk1uRjhGUVEiLCJlIjoiQVFBQiJ9XSwibWV0YWRhdGFfc3RhdGVtZW50cyI6WyJleUpoYkdjaU9pSlNVekkxTmlJc0luUjVjQ0k2SWtwWFZDSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlpXUjFaMkZwYmk1dmNtY3ZJbjAuZXlKamJHRnBiWE1pT2xzaWMzVmlJaXdpYm1GdFpTSXNJbVZ0WVdsc0lpd2ljR2xqZEhWeVpTSmRMQ0pwWkY5MGIydGxibDl6YVdkdWFXNW5YMkZzWjE5MllXeDFaWE5mYzNWd2NHOXlkR1ZrSWpwYklsSlRNalUySWl3aVVsTTFNVElpWFN3aWFYTnpJam9pYUhSMGNITTZMeTlsWkhWbllXbHVMbTl5Wnk4aUxDSnphV2R1YVc1blgydGxlWE1pT2x0N0ltdDBlU0k2SWxKVFFTSXNJbXRwWkNJNkltaDBkSEJ6T2k4dlptVnBaR1V1Ym04dklpd2liaUk2SWpVeFJtTTVZVmhCTlRjMmNUazRlRUYzY2xReWNteEdiVUpyYzFRdE9UUktaRjh3YUd4Q2VXWlFORjlMT0VOMVRFSlVUVGhJUTJ4cFptUjNaVlJHWkZWa1UybENORFJsZEVNMldVeFJja3BGU1hsQ1dsOTRMVWRwUlhJMWIwMVNUbXh6TmtoWGJWZHViRUo0WVVSQlZURTJlbTVJVVRBMlJtRnJRWEoyUVZab2JrVmtUblY1TmpWblp6bFNNa2xCWnpGU2MxZENRV0Z3ZHpSbVZrWlRlWFpOVWt0bFMxTlVNWHBDV1ZZM04zTmxhVTlXYVVSSFozSjVkM2hDUTNkbmVqZFlSbGN6ZEdsNFVGcElRbTgyVG5FelIxVkZkMnBvWTFSTExXSkpOaTB6YlZVeldUSkJlREJTTjFNemFuSk9abEJMUlhWcE1FOXRZMkpKWlhGa2RTMUtUMlp2VEZGR1ZFOWZORTR0WWtKb1ZYcFhhR2RxUVZrMFdsbFdSR1ZQVWxSZlNIUlVkMVppZDBrMGVEZzRUbnBIVDBOeWVWcHFaVlpVU0RONE9FSmxWV0ZKYTNaVFJIbGtZMGxzWkZkS2IyaDRiRVZwZHlJc0ltVWlPaUpCVVVGQ0luMWRMQ0pwWVhRaU9qRTBPRGc0TnpNeU16Y3NJbVY0Y0NJNk1UUTRPRGczTmpnek9Dd2lZWFZrSWpvaWFIUjBjSE02THk5bVpXbGtaUzV1Ynk4aWZRLlU4c3VJSWtrNEIxLWxib1RuM1BFYVljUENMR3B2eW5sd2ptWmV6UnBiNWwybTR1U0N1aDd0c0NPUWd5RUdWVHpPc1NrV1JLeDlJbzVRREQwOWhINFo2Tzk0SVFZSG14X184aVhreld4ek9QNmdzSnROVnFxbE50NnBlanNzN1JoOU5hMXlyQWlibGl0YmJfMnlqaTlVaWtSUFdWd3U1czVqRmFJM1JtSXhCWnlPQm9LY29uTWVFMFB0aE9QOHB2ckZjallQYmk1Qjg1N3F2NzlnQWZVbVk4OTViYWhsUWhBMUdUamVrenBCdFZDQml3Yl90QjBtMFJwb0xBZzFvbENrYXdRVVhOZWVCRDdFckFTWGZBYzBpbm9WWm8tYzRWMGFMM3FobXFpR2IxVHdzZ2RhdXdHbEc1RzgtYlFCT25Edkg2Vmo2SGFDTG5pb1BoM3JheE1rdyIsImV5SmhiR2NpT2lKU1V6STFOaUlzSW5SNWNDSTZJa3BYVkNJc0ltdHBaQ0k2SW1oMGRIQnpPaTh2YTJGc2JXRnlMbTl5Wnk4aWZRLmV5SmpiR0ZwYlhNaU9sc2ljM1ZpSWl3aWJtRnRaU0pkTENKcFpGOTBiMnRsYmw5emFXZHVhVzVuWDJGc1oxOTJZV3gxWlhOZmMzVndjRzl5ZEdWa0lqcGJJbEpUTWpVMklsMHNJbWx6Y3lJNkltaDBkSEJ6T2k4dmEyRnNiV0Z5TG05eVp5OGlMQ0p6YVdkdWFXNW5YMnRsZVhNaU9sdDdJbXQwZVNJNklsSlRRU0lzSW10cFpDSTZJbWgwZEhCek9pOHZabVZwWkdVdWJtOHZJaXdpYmlJNklqVXhSbU01WVZoQk5UYzJjVGs0ZUVGM2NsUXljbXhHYlVKcmMxUXRPVFJLWkY4d2FHeENlV1pRTkY5TE9FTjFURUpVVFRoSVEyeHBabVIzWlZSR1pGVmtVMmxDTkRSbGRFTTJXVXhSY2twRlNYbENXbDk0TFVkcFJYSTFiMDFTVG14ek5raFhiVmR1YkVKNFlVUkJWVEUyZW01SVVUQTJSbUZyUVhKMlFWWm9ia1ZrVG5WNU5qVm5aemxTTWtsQlp6RlNjMWRDUVdGd2R6Um1Wa1pUZVhaTlVrdGxTMU5VTVhwQ1dWWTNOM05sYVU5V2FVUkhaM0o1ZDNoQ1EzZG5lamRZUmxjemRHbDRVRnBJUW04MlRuRXpSMVZGZDJwb1kxUkxMV0pKTmkwemJWVXpXVEpCZURCU04xTXphbkpPWmxCTFJYVnBNRTl0WTJKSlpYRmtkUzFLVDJadlRGRkdWRTlmTkU0dFlrSm9WWHBYYUdkcVFWazBXbGxXUkdWUFVsUmZTSFJVZDFaaWQwazBlRGc0VG5wSFQwTnllVnBxWlZaVVNETjRPRUpsVldGSmEzWlRSSGxrWTBsc1pGZEtiMmg0YkVWcGR5SXNJbVVpT2lKQlVVRkNJbjFkTENKcFlYUWlPakUwT0RnNE56TXlNemNzSW1WNGNDSTZNVFE0T0RnM05qZ3pPQ3dpWVhWa0lqb2lhSFIwY0hNNkx5OW1aV2xrWlM1dWJ5OGlmUS5vUGl4azZUWkZqeEprUmt6SGF0YjhPNzhEY2xVMmFKUjg4N0FqX1NTWWE5Y2xrRXZnRDVpRy00ZGRwWG5hVGNpNjRDWnBoUzJkV3JOQ2JHcERCdHl6MGdsQldyZmI3dnpxSnRYX25FWVVuOGZ5T1hqdWlXbUc5TlZuUEZIOXBia1BLZkk3NzdxWFRFM2EycklBb3hoaTZpMFdaTmxBTkFqQ0xmZTJxRHVJVEp3TFpzUmZGMi13a3lJZWN2MHFEY2NaRENRVkppbnRZVFdoQVhBTnhjVnJsNFZYaWEwQ3hCWVFOa3VwTFNFMmtvTDNiZU1pZExyVjRxOTJvbFRTa0dMR1BuVzdqcTc2Q1pxdG1QR2c0aEpIZG9seXA3YzdWbkstT1pWUXdFR3NURjVUOGxrYm9uV2p6TjVPR19kU1FjcGhOX1g0OXhIS1lXNDJKRmRaZ3pvU3ciXSwiaWF0IjoxNDg4ODczMjM4LCJleHAiOjE0ODg4NzY4Mzh9.wjM0fZxf0kM88E7Rk1cU74pxZYyqvzpimZijVIC_710G2TUYkD9TV8Zcz1Bl7v9xuANxwSlW29fHk2lK5O8eDBizwfX4dUJwulnkCJHUcY8hWLpxIJa_o2lXBDXeOCHpey7kySLe859bQLsXUI_LEqHjyKysOfk5TJhzno930HfuTa6ixNefSRt3_owYCiDCkHPluuSx2l9ot058qBK6Lwqno4fMF5DoVRTALeMnLhsy-iIcVYILMNJuEl9tmlIftnYQ_V1HRk1vZlibjJZa4PdeNe1250yrX3lDbluxoSPydy30tfdRRP9DwAbIq9_L8uKGt4qptsWS0WNd_Tdp1A";
//echo "<br>";
//Public keys used in this example (or some from this json array/object)
$ms_example_public_keys_json = '[{"kty":"RSA","kid":"https://edugain.org/","n":"l7rt1yRvbiOKg8XeP_ICo0yDif-kOLWkUL5FAWKVWhWWAdnN2o1t_otuBX1xLeItE24he4qGHBzh2PQ4SRqau6ZVzx4-aJFzGZSbw6SswVXPlFR5dRkJMn4wxFOOVsSUnltO4K27X2Pf-gwlLFdH4q4QTNU5U8ijr76BnuUThdBYrxf2UQT7DDz6cPHaRdOUbuj_Ids9CmV6HyzdIFOfBx7DKS8o2fqH9Fa6-PKdMtDJiZ1KfjgstiNB04JAbQ1RI9Bl-No6NTUcZbD7Q0JF8iqY3Hogo9J_mL-SgQFGgwAoxQKoNeLk7uLHc69yIlyBJegrVkmHUKehIp3OZ5CW9w","e":"AQAB"},{"kty":"RSA","kid":"https://feide.no/","n":"51Fc9aXA576q98xAwrT2rlFmBksT-94Jd_0hlByfP4_K8CuLBTM8HClifdweTFdUdSiB44etC6YLQrJEIyBZ_x-GiEr5oMRNls6HWmWnlBxaDAU16znHQ06FakArvAVhnEdNuy65gg9R2IAg1RsWBAapw4fVFSyvMRKeKST1zBYV77seiOViDGgrywxBCwgz7XFW3tixPZHBo6Nq3GUEwjhcTK-bI6-3mU3Y2Ax0R7S3jrNfPKEui0OmcbIeqdu-JOfoLQFTO_4N-bBhUzWhgjAY4ZYVDeORT_HtTwVbwI4x88NzGOCryZjeVTH3x8BeUaIkvSDydcIldWJohxlEiw","e":"AQAB"},{"kty":"RSA","kid":"https://foodl.org/","n":"ylWifXilG3JPDx60mj12x8sJsyYoYfKryLtNIT00Gj2wXiQiO4uaPiXMvsFymrNgdpKPkMTiMDndaeVY1cJW45TJD03ld1MVHskUvrADHwA6JqzKa_jVVeZ7Ehklw9WZ9Ed0y6yhRokKlMHjfctOBUNExh3qfcETCCtCBHYhVKELPVUEdegySYqlNPyB9sGlau67PQe2gi0taYK-Qvg7XwsoEL2lRsgK_9GnEb6PHJv3ak6XdQ2R_Egl5-M4q1_qpAJYsME4xNvUuzjqZ9gNYIRfRNmX8h0IpzfzrzvB3aDXK2KVoEt7BhIPbbcWa99tnq82MQo6sOe5SHFMnF8FQQ","e":"AQAB"},{"kty":"RSA","kid":"https://kalmar.org/","n":"pO4fh3R8yBPjjpA1keMg2nDCD7PaDraqK5Wn4O8J_rtFgg_Beh1UQf6Y1yyVOkPDgk9zIvxtBLHA7_AHiEvC0_pHSzJNlppVhujUh8yVJrxaYDqwIM1h4J8xoqhkPjELkGX4_jOrQ3DUTXSk0BkGeeyxX8n-i7uRA8UGCyZ-74WxCQK4pK-0nFi_60pXX87bypsTxxtXP2AwE_WAlH_7wiShz5uyyYMGRxe5TxEo3NZuyMplz1KOnpU4YEUAofvjfafjrFSgvUpbVa4WXeoWkbV2S_fIL5sa3EEKO7qKmTHhwxNcCvxnlQBxKN8ZzUlRD0nzmCAuEZVBg2QIWjfPHQ","e":"AQAB"},{"kty":"RSA","kid":"https://open_federation.org/","n":"75wrESSoqSiG5hCCuOtG5SQQNcXZE8UgS-dyqFEUKKH-wLFPgoLItUq7djEsye2UalXZ2qaGtRS2cpn4k3zgwBA_UJvxgaV0eD7jXs71ZpxbBprX1PEnUudYVhJiGOR7PRrKrnEaoDGvgtfTiD1C7UBYZZc3qeORpsDAAp3O8EFETChvUyG9sOqHO0X47hXKqZXZvCmWddJjR5-r8R66O1cMGavsYEhUkYLN4Hw-e9HdkRiuiH5BtbXdKmMWuqZdou5p5xTPtKp6NMJ2raJDq9Pk0l9zuiJor22xV0ov1pT6GPxH70e42Ac_hmm9BysJGVdKpRzf_puD7-Y-Yh5iMw","e":"AQAB"}]';
//echo "<br>";
$ms_strArr                   = explode('.', $ms_example);
var_dump($ms_strArr);

echo "========================================================================<br>";
$ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($ms_example);
echo "<br>MS Header:<br>";
print_r($ms_header);
print_r($ms_header->alg);
//echo "========================================================================<br>";

echo "========================================================================<br>";
$ms_payload      = \oidcfed\security_jose::get_jose_jwt_payload_to_object($ms_example);
echo "<br>MS Payload:<br>";
print_r($ms_payload);
//echo "<br>MS Payload: JWK from signing_keys:<br>";
//print_r($ms_payload->signing_keys[0]);
//foreach ($ms_payload->signing_keys as $mspkey => $mspvalue) {
//    if (empty($mspvalue) === true) {
//        continue;
//    }
//    echo "<br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>";
//    echo ">>> " . (string) $mspkey . " <<<<br>";
//    try {
//        $jwk_from_signing_keys     = \oidcfed\security_jose::create_jwk_from_values((array) $mspvalue,
//                                                                                    true);
//        $jwk_from_signing_keys_PEM = \oidcfed\security_jose::create_jwk_from_values((array) $mspvalue,
//                                                                                    true);
//        print_r($jwk_from_signing_keys);
//        echo "<br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>";
//        print_r($jwk_from_signing_keys_PEM);
//        echo "<br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>";
//        print_r(json_encode($jwk_from_signing_keys));
//        echo "<br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br>";
//        unset($jwk_from_signing_keys);
//    }
//    catch (Exception $exc) {
//        echo $exc->getTraceAsString();
//    }
//}
// We create our loader.
$loader          = new Loader();
$jose_obj_loaded = $loader->load($ms_example);
echo "========================================================================<br>";
$pl              = $jose_obj_loaded->getPayload();
$ms_claims       = $jose_obj_loaded->getClaims();
echo "<br>Payload/Claims...<br>";
var_dump($pl);
//var_dump($ms_claims);
echo "========================================================================<br>";
$ms_signatures   = $jose_obj_loaded->getSignatures();
echo "<br>Signatures count: " . $jose_obj_loaded->countSignatures() . "<br>";
echo "<br>Signatures:<br>";
var_dump($ms_signatures);
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<br>";
print_r($ms_signatures[0]);
echo "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<br>";
echo "Protected header(s) (from signature):<br>";
$protected_headers = \oidcfed\security_jose::get_jwt_signatures_protected_header($ms_example);
print_r($protected_headers);
echo "========================================================================<br>";
echo "Creating JWK for public key for kid: 'kid' => string 'https://feide.no/' (in this example)<br>";
//Convert json to array (associative = true)...
//This will help us to create JWK using values from array.
$ms_example_public_keys_obj = json_decode($ms_example_public_keys_json, true);
$kid_to_search              = "https://feide.no/";
$kid_public_key_values      = false;
$kid_jwk                    = \oidcfed\security_jose::create_jwk_from_values_in_json($ms_example_public_keys_obj,
                                                                                     $kid_to_search);
if ($kid_jwk instanceof Jose\Object\JWKInterface) {
    echo "<br>";
    echo "##################################<br>";
    echo "@@@@ Instance of JWKInterface @@@@<br>";
    echo "<#################################<br>";
}
echo "";
var_dump($kid_jwk);
echo "========================================================================<br>";
// We load it and verify the signature
// Verifying our signature like is described here:
// https://github.com/Spomky-Labs/jose/blob/master/doc/operation/Verify.md
//$pubSignatureKey       = $ms_signatures[0];
$pubSignatureKey = $kid_jwk;
$result = \oidcfed\security_jose::jwt_async_verify_sign_from_string_base64enc($ms_example, $pubSignatureKey);
if (is_object($result) === true) {
    echo "Signature verified, below you can see our MS object (JWT/JWS)...<br>";
    print_r($result);
}
echo "<br>****************************<br>";

echo "</pre>";
