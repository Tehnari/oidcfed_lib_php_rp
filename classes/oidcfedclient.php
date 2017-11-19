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

require '../parameters.php';

/**
 * Description of oidcfed
 *
 * @author constantin
 */
class oidcfedClient extends \OpenIdConnectClient\OpenIdConnectClient {
//class oidcfedClient {

    /**
     * (static) This static function can help with getting and saving oidc config (or other json files)
     * @param string $url_oidc_config
     * @param bool $show_config
     * @param string $filename
     * @param bool $return_pretty
     * @return boolean
     */
    public static function get_oidc_config($url_oidc_config = false,
                                           $show_config = false,
                                           $filename = false,
                                           $return_pretty = true) {
        if (($url_oidc_config === false || \is_string($url_oidc_config) === false)
                && $filename === false && $return_pretty === false) {
            return false;
        }
//----------
        $curl        = \curl_init($url_oidc_config);
        \curl_setopt($curl, \CURLOPT_FAILONERROR, true);
        \curl_setopt($curl, \CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, \CURLOPT_SSL_VERIFYHOST, false);
        \curl_setopt($curl, \CURLOPT_SSL_VERIFYPEER, false);
        $result_curl = \curl_exec($curl);
        if ($return_pretty === true) {
            $result_output = \json_encode(\json_decode($result_curl),
                                                       \JSON_PRETTY_PRINT);
        }
        else {
            $result_output = \json_encode(\json_decode($result_curl),
                                                       \JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        if ($show_config !== false) {
            echo "<pre>";
//echo $result_curl;
            echo $result_output;
            echo "</pre>";
        }
        if ($filename !== false && \is_string($filename) === true) {
            \file_put_contents($filename, $result_output);
        }
        unset($curl);
        unset($result_curl);
        return $result_output;
//----------
    }

    /**
     * (static) This static function can get openid configuration from /.well_known link
     * @param string $base_url
     * @param string $param
     * @param type $default
     * @param bool $cert_verify
     * @return type
     * @throws Exception
     */
    public static function get_well_known_openid_config_data($base_url,
                                                             $param = null,
                                                             $default = null,
                                                             $cert_verify = true) {
        $check00 = (\is_string($base_url) === true && \is_array(\pathinfo($base_url)) === true
                && \count(\pathinfo($base_url)) > 0 );
        if ($check00 === false) {
            throw new Exception("Failed to get data. Bad url.");
        }
        $well_known_config_url = \rtrim($base_url, "/") . "/.well-known/openid-configuration";
        $wf_json_data          = \oidcfed\configure::getUrlContent($well_known_config_url,
                                                                   $cert_verify);
        //Get OIDC web finger data
        $wellKnown             = \json_decode($wf_json_data, true); //We will use (internal) associative arrays.
        $check01               = (\is_array($wellKnown) === true || \is_object($wellKnown) === true);
        $check02               = ($check01 === true && \count((array) $wellKnown)
                > 0);
        if ($check02 === false) {
            throw new Exception("Failed to get data. Bad data received.");
        }
        $check03 = (isset($param) === true && \is_string($param) === true && ((array) isset($wellKnown[$param]) === true));
        $check04 = (isset($default) === true );
        if ($check03 === true) {
            return $wellKnown[$param];
        }
        else if ($check04 === true) {
            return $default;
        }
        else {
            return $wellKnown;
        }
    }

    /**
     * (static) This static function can be used in case of webfinger needs
     * @param string $base_url
     * @param type $param
     * @param type $default
     * @param bool $cert_verify
     *
     */
    public static function get_well_known_webfinger_data($base_url,
                                                         $param = null,
                                                         $default = null,
                                                         $cert_verify = true) {

    }

    public static function VerifySignatureAndInterpretProviderInfo($full_url) {

        try {
            $openid_known = \oidcfed\oidcfedClient::get_well_known_openid_config_data($full_url,
                                                                                      null,
                                                                                      null,
                                                                                      false);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            $openid_known = false;
        }
        $keys_bundle_url = null;
        $sigkey_url      = null;
        $jwks            = null;
        $jwks_payload    = null;
        $jws_struc       = null;
        $pre_check00     = (\is_string($keys_bundle_url) && \mb_strlen($keys_bundle_url)
                > 0);
        $pre_check01     = (\is_string($sigkey_url) && \mb_strlen($sigkey_url) > 0);
        if ($pre_check00 && $pre_check01) {
            try {
                $keys_bundle   = \oidcfed\configure::getUrlContent($keys_bundle_url,
                                                                   false);
                $sigkey_bundle = \oidcfed\configure::getUrlContent($sigkey_url,
                                                                   false);
                $jwks_bundle   = \oidcfed\security_jose::create_jwks_from_uri($sigkey_url,
                                                                              true);
                $jwks          = \oidcfed\metadata_statements::verify_signature_keys_from_MS($keys_bundle,
                                                                                             false,
                                                                                             $jwks_bundle);
                if ($jwks instanceof \Jose\Object\JWS) {
                    $jwks_payload = $jwks->getPayload();
                }
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                $jwks_payload = false;
                $openid_known = false;
//                throw new Exception("Have some dificulties with JWKS Payload");
            }
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            $check00a = (\is_array($openid_known) === true);
            $check00b = ($jwks_payload);
            $check00  = ($check00a && $check00b);
            $check01  = ($check00 === true && \array_key_exists('metadata_statements',
                                                                $openid_known) === true
                    && \is_array($openid_known['metadata_statements']) === true && \count($openid_known['metadata_statements'])
                    > 0);
            $check02  = ($check00 === true && \array_key_exists('metadata_statement_uris',
                                                                $openid_known) === true
                    && \is_array($openid_known['metadata_statement_uris']) === true
                    && \count($openid_known['metadata_statement_uris']) > 0);
            $ms_tmp   = false;
            if ($check01 === false && $check02 === true) {
                $ms_tmp = $openid_known['metadata_statement_uris'];
                foreach ($ms_tmp as $ms_tmp_key => $ms_tmp_val) {
                    $ms_tmp[$ms_tmp_key] = \oidcfed\configure::getUrlContent($ms_tmp_val,
                                                                             false);
                }
                $openid_known['metadata_statements'] = $ms_tmp;
            }
            unset($ms_tmp);
            foreach ($openid_known['metadata_statements'] as $ms_key =>
                        $ms_value) {
//                $ms_header = \oidcfed\security_jose::get_jose_jwt_header_to_object($ms_value);
//                unset($ms_header);
                $jws_struc = \oidcfed\metadata_statements::unpack_MS($ms_value,
                                                                     null,
                                                                     $jwks->getPayload()["bundle"],
                                                                     false,
                                                                     false);
                if (!$jws_struc) {
//        echo "Have some dificulties";
//                    throw new Exception("Have some dificulties with JWS Structure");
                    $jws_struc = false;
                }
            }

            $result                   = new \stdClass();
            $certificateLocal_content = \oidcfed\security_keys::get_csr(false,
                                                                        $dn,
                                                                        $priv_key_woPass,
                                                                        $ndays,
                                                                        $path_dataDir_real);

            $certificateLocal_path   = \oidcfed\security_keys::public_certificateLocal_path();
            $openidFedClient         = new \oidcfed\oidcfedClient([
//    'provider_url'  => $openid_known['registration_endpoint'],
                'provider_url'        => $full_url,
                'client_id'           => $openid_known['issuer'],
                'client_secret'       => $passphrase,
                'clientName'          => 'oidcfed_lib_rp',
                'metadata_statements' => $openid_known['metadata_statements']
            ]);
            $result->openidFecClient = $openidFedClient;
            $openidFedClient->addScopes(['openid', 'email', 'profile']);
            if (!$client_secret) {
                $openidFedClient->register();
//Using this  client_id and client_secret
                $client_id     = $openidFedClient->getClientID();
                $client_secret = $openidFedClient->getClientSecret();
            }
            if ($client_id) {
                $result->client_id = $client_id;
            }
            else {
                $result->client_id = null;
            }
            if ($client_secret) {
                $result->client_secret = $client_secret;
            }
            else {
                $result->client_secret = null;
            }
            return $result;
        }
//        return false;
        throw new Exception("Verification failed, nothing failed...");
    }

}
