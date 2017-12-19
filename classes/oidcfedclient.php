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

require_once 'autoloader.php';
\oidcfed\autoloader::init();

//use Lcobucci\Jose\Parsing\Parser;
//use Lcobucci\JWT\Parser;
//use Lcobucci\JWT\Signature;
//use Lcobucci\JWT\Claim;
//use Lcobucci\JWT\Token;
//use Lcobucci\JWT\ValidationData;

define('__ROOT__', dirname(dirname(__FILE__)));
//require_once(__ROOT__ . '/parameters.php');
//require '../parameters.php';
require (dirname(dirname(__FILE__)) . '/parameters.php');

/**
 * Description of oidcfed
 *
 * @author constantin
 */
class oidcfedClient extends \Jumbojett\OpenIDConnectClient {
//class oidcfedClient {

    /**
     * @var mixed holds well-known openid server properties
     */
    public $wellKnown   = false;
    public $verify_host = false;
    public $verify_peer = false;
    public $verify_cert = true;

//    /**
//     * @var array holds authentication parameters
//     */
//    protected $authParams = array();

    /**
     * Used for arbitrary value generation for nonces and state
     *
     * @return string
     */
    public static function generateRandString_static() {
        return md5(uniqid(rand(), TRUE));
    }

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
        $check03 = (isset($param) === true && $param !== null && \is_string($param) === true
                && ((array) isset($wellKnown[$param]) === true));
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

    public function setVerifyCert($param) {
        if (!\is_bool($param)) {
            return false;
        }
        $this->verify_cert = $param;
        $this->setVerifyHost($param);
        $this->setVerifyPeer($param);
        $this->verify_host = $this->getVerifyHost();
        $this->verify_peer = $this->getVerifyPeer();
    }

    public static function fetchURL_static($url, $post_body = null,
                                           $headers = []) {
        return $this->fetchURL($url, $post_body, $headers);
    }

    public function get_jwks_from_uri($url, $verifyCert = true) {
        if (!\is_string($url)) {
            throw new Exception("Bad url provided.");
        }
        try {
            $jwks = \oidcfed\configure::getUrlContent($url, $verifyCert);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            throw new Exception("Couldn't fetch jwks_uri");
        } // var_dump($jwks);
        try {
            $jwks_assocArr = \json_decode($jwks, true);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            $jwks_assocArr = false;
        }
        if (!$jwks_assocArr && $jwks && \is_string($jwks) && \mb_strlen($jwks)) {
            $jwksStruct = \oidcfed\security_jose::check_jose_jwt_string_base64enc($jwks,
                                                                                  true);
            return $jwksStruct;
        }
        if (\is_array($jwks_assocArr)) {
            $jwksStruct = \oidcfed\security_jose::create_jwks_from_values_in_json($jwks);
            return $jwksStruct;
        }
        throw new Exception("JWKS not found!");
    }

    public function get_jwks_from_wellKnown() {

        $check00 = (\is_array($this->wellKnown) && \count($this->wellKnown) > 0);
        if (!$check00) {
            throw new Exception("OIDC wellKnown not found!");
        }
//Fetching signed_jwks_uri
        try {
            if ($this->wellKnown["signed_jwks_uri"]) {
                $signed_jwks_uri = $this->wellKnown["signed_jwks_uri"];
                $jwks            = $this->fetchURL($signed_jwks_uri);
            }
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            throw new Exception("Couldn't fetch signed_jwks_uri");
        }
        try {
            $jws_assocArr = \json_decode($jwks, true);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            $jws_assocArr = false;
        }
        if (\is_array($jws_assocArr)) {
            try {
                $jws = \oidcfed\security_jose::create_jws($jwks);
                return $jws;
            }
            catch (Exception $exc) {
//                echo $exc->getTraceAsString();
                $jws = false;
            }
        }
//Fetching jwks
        if ((!\is_array($jws_assocArr) && !$jws) && $this->wellKnown["jwks_uri"]) {
            try {
                $jwks_uri = $this->wellKnown["jwks_uri"];
                $jwks     = $this->fetchURL($jwks_uri);
            }
            catch (Exception $exc) {
//            echo $exc->getTraceAsString();
                throw new Exception("Couldn't fetch jwks_uri");
            }
        }
        try {
            $jwks_assocArr = \json_decode($jwks, true);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            $jwks_assocArr = false;
        }
        if (\is_array($jwks_assocArr)) {
            $jwksStruct = \oidcfed\security_jose::create_jwks_from_values_in_json($jwks);
            return $jwksStruct;
        }
        throw new Exception("JWKS not found!");
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
                $keys_bundle = \oidcfed\configure::getUrlContent($keys_bundle_url,
                                                                 false);
//                $sigkey_bundle = \oidcfed\configure::getUrlContent($sigkey_url, false);
                $jwks_bundle = \oidcfed\security_jose::create_jwks_from_uri($sigkey_url,
                                                                            true);
                $jwks        = \oidcfed\metadata_statements::verify_signature_keys_from_MS($keys_bundle,
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
        throw new Exception("Verification failed, nothing found...");
    }

    public function get_webfinger_data($resource_var = null, $host_url = null,
                                       $rel = "http://openid.net/specs/connect/1.0/issuer",
                                       $httpcon_type = "https://",
                                       $return_var = "href") {
        $out_var_arr  = [];
        $host_url_arr = [];

        $url_obj = new \stdClass();
        if ($host_url === null) {
            $host_url_v0 = $this->getProviderURL();
            $host_url    = \rtrim(\rtrim($host_url_v0), "/");
//            $httpcon_type = "";
        }
        if ($resource_var === null) {
            $resource_var = \oidcfed\configure::client_id();
        }
        if (\preg_match("/(http|https|ftp)[:\/\/]+([\w:]+)/", $resource_var,
                        $host_url_arr) && (empty($httpcon_type) || \is_null($httpcon_type))) {
            $httpcon_type = "https://";
            unset($host_url_arr);
        }
        if (\is_string($resource_var) && \mb_strlen($resource_var) > 0 && \preg_match("/(http|https|ftp)[:\/\/]+([\w:]+)/",
                                                                                      $resource_var,
                                                                                      $out_var_arr)) {
            $url_obj->resource = $httpcon_type . $out_var_arr[2];
            $url_obj->rel      = "http://openid.net/specs/connect/1.0/provider";
            $url_string        = $host_url . "/.well-known/webfinger?resource=" . urlencode($url_obj->resource) . "&rel=" . \urlencode($url_obj->rel);
//            $url_string        = $host_url . "/.well-known/webfinger?resource=" . \urlencode($url_obj->resource) . "&rel=" . \urlencode($url_obj->rel);
//            $url_string        = $httpcon_type . $host_url . "/.well-known/webfinger?resource=" . $url_obj->resource . "&rel=" . $url_obj->rel;
//
            //just returning HREF
            return $url_obj->resource;
        }
        else if (\is_string($resource_var) && \mb_strlen($resource_var) > 0 && \preg_match("/([\w\.-]+)[@]+([\w:]+)/",
                                                                                           $resource_var,
                                                                                           $out_var_arr)
                && \is_array($out_var_arr) && \count($out_var_arr) > 1) {
            $url_obj->resource = "acct:" . $out_var_arr[0];
            $url_obj->rel      = $rel;
            $url_string        = $host_url . "/.well-known/webfinger?resource=" . \urlencode($url_obj->resource) . "&rel=" . \urlencode($url_obj->rel);
//            $url_string        = $httpcon_type . $host_url . "/.well-known/webfinger?resource=" . urlencode($url_obj->resource) . "&rel=" . $url_obj->rel;
        }
        else {
            throw new Exception("Webfinger: nothing found...");
        }
        $cert_verify = $this->verify_cert;
        $this->setVerifyCert($cert_verify);
        $result      = \oidcfed\configure::getUrlContent($url_string,
                                                         $cert_verify);
        $json_arr    = \json_decode($result, true);
        $check00     = (\is_string($return_var) && \mb_strlen($return_var) > 0 && (\mb_strrpos($return_var,
                                                                                               "href") !== false));
        $check01     = (\is_string($result) && \mb_strlen($result) > 0);
        $check02     = ( \is_array($json_arr) && \count($json_arr) > 0 && \array_key_exists("href",
                                                                                            $json_arr["links"][0]));
        if ($check00 && $check01 && $check02) {
            return $json_arr["links"][0]["href"];
        }
        else {
            return $result;
        }
    }

    public function implicit_flow() {
// Do a preemptive check to see if the provider has thrown an error from a previous redirect
        if (isset($_REQUEST['error'])) {
            $desc = isset($_REQUEST['error_description']) ? " Description: " . $_REQUEST['error_description']
                        : "";
            throw new Exception("Error: " . $_REQUEST['error'] . $desc);
        }
// Throw an error if the server returns one
        if (isset($token_json->error)) {
            if (isset($token_json->error_description)) {
                throw new Exception($token_json->error_description);
            }
            throw new Exception('Got response: ' . $token_json->error);
        }

// Do an OpenID Connect session check
        if (isset($_REQUEST['state']) && $_REQUEST['state'] != $this->getState()) {
            throw new Exception("Unable to determine state");
        }
// Do an OpenID Connect session check
        if (isset($_REQUEST['nonce']) && $_REQUEST['nonce'] != $this->getNonce()) {
            throw new Exception("Unable to determine nonce");
        }

// Cleanup state
        $this->unsetState();
        $this->unsetNonce();

// Generate and store a nonce in the session
// The nonce is an arbitrary value
        $nonce = $this->setNonce($this->generateRandString());

// State essentially acts as a session key for OIDC
        $state = $this->setState($this->generateRandString());


        $this->setRedirectURL($this->getRedirectURL());

        if ($this->getProviderConfigValue("authorization_endpoint")) {
            $authorization_endpoint = $this->getProviderConfigValue("authorization_endpoint");
        }
        else {
            throw new Exception("Authorization Endpoint Not Found");
        }


//Create redirect URI for implicit flow
//        $redirect_uri = \rtrim($this->getRedirectURL(), '/')."&scope=openid%20profile&state=".$state;
        $redirect_uri = \rtrim($this->getRedirectURL(), '/');

        $auth_params = array(
            'client_id'     => $this->getClientID(),
            'response_type' => 'id_token token',
            'scope'         => 'openid profile',
            'redirect_uri'  => $redirect_uri,
            'state'         => $state,
            'nonce'         => $nonce
        );


        $authorization_endpoint .= (\strpos($authorization_endpoint, '?') === false
                    ? '?' : '&') . \http_build_query($auth_params, null, '&');

        session_commit();
        $this->redirect($authorization_endpoint);
    }

    public function dynamic_registration_and_auth_code($verifyCert = false,
                                                       $private_key = null,
                                                       $passphrase = null) {
        $provider_url      = rtrim(rtrim($this->getProviderURL()), '/') . "/";
        $path_dataDir_real = \oidcfed\configure::path_dataDir();
        try {
            $clientName = \oidcfed\configure::getClientName();
        }
        catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        try {
            $clientData       = \oidcfed\oidcfedClient::get_clientName_id_secret($path_dataDir_real,
                                                                                 $clientName,
                                                                                 $provider_url);
            \reset($clientData);
            $clientDataArrVal = \current($clientData);
//            $clientDataArrVal = $clientData;
        }
        catch (Exception $exc) {
//    echo $exc->getTraceAsString();
            $clientDataArrVal = null;
            echo "";
        }
        if (\is_array($clientDataArrVal) && \array_key_exists("client_id",
                                                              $clientDataArrVal)
                && \array_key_exists("client_secret", $clientDataArrVal)) {
            $client_id     = $clientDataArrVal["client_id"];
            $client_secret = $clientDataArrVal["client_secret"];
        }
        else {
            $client_id     = null;
            $client_secret = null;
        }
        $check00 = (\is_array($clientDataArrVal));
        $check01 = ($check00 && \array_key_exists("client_secret_expires_at",
                                                  $clientDataArrVal));
        $check02 = ($check01 && isset($clientDataArrVal["client_secret_expires_at"])
                && \is_numeric($clientDataArrVal["client_secret_expires_at"]) && ($clientDataArrVal["client_secret_expires_at"] <= (time()
                + 120)));
        if ($check02) {
            $client_secret = null;
        }
        //---===---
        //Certificate generation (!) In this case is only one (just for signing (!)
        $dn              = \oidcfed\configure::dn();
        $ndays           = \oidcfed\configure::ndays();
        $priv_key_woPass = \oidcfed\security_keys::get_private_key_without_passphrase($private_key,
                                                                                      $passphrase);
        try {
            $certificateLocal_content = \oidcfed\security_keys::get_csr(false,
                                                                        $dn,
                                                                        $priv_key_woPass,
                                                                        $ndays,
                                                                        $path_dataDir_real);
        }
        catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $certificateLocal_content = null;
        }

        try {
            $certificateLocal_path = \oidcfed\security_keys::public_certificateLocal_path();
        }
        catch (Exception $exc) {
            echo "<pre>";
            echo $exc->getTraceAsString();
            echo "</pre>";
        }
        //Key is allready without passphrase (!)
        $csr               = \oidcfed\security_keys::get_filekey_contents($certificateLocal_path);
        $pathLocal_content = \pathinfo($certificateLocal_path);
        $pathPrivateKey    = \rtrim($pathLocal_content['dirname'], '/') . "/" . "privateKey.pem";
//        $privkey_pem           = \openssl_pkey_get_private($pathPrivateKey, $passphrase);
        $privkey_pem       = \oidcfed\configure::private_key($pathPrivateKey,
                                                             $passphrase);
        $pubkey_pem        = \oidcfed\configure::public_key($privkey_pem);
        //---===---
        if (!(\is_string($client_secret) && \mb_strlen($client_secret) > 0) || !(\is_string($client_id)
                && \mb_strlen($client_id) > 0)) {
            //Dynamic registration for this client
//            $oidc_dyn = new \oidcfed\oidcfedClient($provider_url);
            try {
                $this->setVerifyCert($verifyCert);
                $this->setVerifyHost($verifyCert);
                $this->setVerifyPeer($verifyCert);
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }
            $this->setClientName($clientName);
            $well_known = $this->wellKnown;
            // ---===---
            //TODO Here MS CHECK/Verify should be added !!!
            if (\is_array($well_known) && \array_key_exists("metadata_statements",
                                                            $well_known) && \count($well_known["metadata_statements"])
                    > 0) {

                $ms_arr      = [];
                $ms_compound = [];
                foreach ($well_known['metadata_statements'] as $ms_key =>
                            $ms_value) {
                    try {
//                        $jws_struc = \oidcfed\metadata_statements::unpack_MS($ms_value,                                                                             null);
                    }
                    catch (Exception $exc) {
                        echo "<pre>";
                        echo $exc->getTraceAsString();
                        echo "</pre>";
                    }
                    echo "";
                    try {
                        $jws_struc = \oidcfed\metadata_statements::unpack($ms_value,                                                                             null);
                    }
                    catch (Exception $exc) {
                        echo "<pre>";
                        echo $exc->getTraceAsString();
                        echo "</pre>";
                    }
                }
            }
            // ---===---
            $additional_parameters = [
//            'kid' => $this->getClientID(),
//                'kid' => $client_id,
                "use" => "sig"
            ];
            $privkey_jwt           = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($privkey_pem,
                                                                                                        null,
                                                                                                        $additional_parameters,
                                                                                                        false);
            $pubkey_jwt            = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($pubkey_pem,
                                                                                                        null,
                                                                                                        $additional_parameters,
                                                                                                        false);
            $pubKeyArr             = $pubkey_jwt->jsonSerialize();
            $param_payload         = [
                "signing_keys"     => ["keys" => [(object) $pubKeyArr]],
                "federation_usage" => "registration"
            ];
            $ms_brut0              = \oidcfed\metadata_statements::create_MS($param_payload,
                                                                             ["alg" => "RS256",
                        "kid" => ""], $privkey_jwt);
            if (\is_string($ms_brut0) && \mb_strlen($ms_brut0) > 0) {
                $this->addAuthParam(["metadata_statements" => $ms_brut0]);
            }
            if (\is_array($well_known) && \array_key_exists("metadata_statements",
                                                            $well_known) && \count($well_known["metadata_statements"])
                    > 0) {
                $param_payload["metadata_statements"] = $well_known["metadata_statements"];
            }
            if (\is_array($well_known) && \array_key_exists("metadata_statements",
                                                            $well_known)) {
                try {
                    $this->registerOIDCfed_OP();
                }
                catch (Exception $exc) {
                    echo "<pre>";
                    echo $exc->getTraceAsString();
                    echo "</pre>";
                }
                $client_id     = $this->getClientID();
                $client_secret = $this->getClientSecret();
            }
            else {
                try {
                    $this->register();
                }
                catch (Exception $exc) {
                    echo "<pre>";
                    echo $exc->getTraceAsString();
                    echo "</pre>";
                }
                $client_id     = $this->getClientID();
                $client_secret = $this->getClientSecret();
                $dataToSave    = ["provider_url"  => $provider_url, "client_id"     => $client_id,
                    "client_secret" => $client_secret, "client_name"   => $clientName,
                    "iat"           => \time(), "exp"           => (\time() + 3600)];

                try {
                    \oidcfed\oidcfedClient::save_clientName_id_secret($path_dataDir_real,
                                                                      $dataToSave);
                }
                catch (Exception $exc) {
                    echo "<pre>";
                    echo $exc->getTraceAsString();
                    echo "</pre>";
                }
            }
        }

        //TODO Here MS should be added !!!
        $additional_parameters = [
//            'kid' => $this->getClientID(),
            'kid' => $client_id,
            "use" => "sig"
        ];
        $privkey_jwt           = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($privkey_pem,
                                                                                                    null,
                                                                                                    $additional_parameters,
                                                                                                    false);
        $pubkey_jwt            = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($pubkey_pem,
                                                                                                    null,
                                                                                                    $additional_parameters,
                                                                                                    false);

        echo "";
        try {
            $oidc = new \oidcfed\oidcfedClient($provider_url, $client_id,
                                               $client_secret);

            $oidc->setVerifyCert(false);
            $oidc->setCertPath($certificateLocal_path);
        }
        catch (Exception $exc) {
            echo "<pre>";
            echo $exc->getTraceAsString();
            echo "</pre>";
        }
        $oidc->setClientName($clientName);
        if (isset($provider_url)) {
            $oidc->setProviderURL($provider_url);
        }
        if (isset($client_id)) {
            $oidc->setClientID($client_id);
        }
        if (\is_array($clientDataArrVal) && \count($clientDataArrVal) > 0) {
            $oidc->addAuthParam(["nbf" => $clientDataArrVal["exp"]]);
        }
        /*
          $pubKeyArr     = $pubkey_jwt->jsonSerialize();
          $redirect_url  = $this->getRedirectURL();
          $param_payload = [
          "signing_keys"                          => ["keys" => [(object) $pubKeyArr]],
          "id_token_signing_alg_values_supported" => [
          "RS256",
          "RS512"
          ],
          "scope"                                 => ["openid", "profile"],
          "claims"                                => [
          "sub",
          "name",
          "email",
          "picture"
          ],
          "federation_usage"                      => "registration",
          "redirect_uris"                         => [$redirect_url]
          ];
          $well_known    = $this->wellKnown;
          if (\is_array($well_known) && \array_key_exists("metadata_statements",
          $well_known) && \count($well_known["metadata_statements"])
          > 0) {
          $param_payload["metadata_statements"] = $well_known["metadata_statements"];
          }
          $ms_brut = \oidcfed\metadata_statements::create_MS($param_payload,
          ["alg" => "RS256",
          "kid" => ""], $privkey_jwt);
          if (\is_string($ms_brut) && \mb_strlen($ms_brut) > 0) {
          $oidc->addAuthParam(["metadata_statements" => $ms_brut]);
          }
         */
        if (isset($_REQUEST["code"])) {
            $req_code = $_REQUEST["code"];
//            $oidc->addAuthParam(["issuer" => $provider_url, "at_hash" => $req_code]);
            $oidc->addAuthParam(["issuer" => $provider_url]);
        }
        try {
            $oidc->authenticate();
        }
        catch (Exception $exc) {
            echo "<pre>";
            echo $exc->getTraceAsString();
            echo "</pre>";
        }
        try {
//        $name = $oidc->requestUserInfo('diana');
            $name = $oidc->requestUserInfo();
            echo "<pre>";
            var_dump($name);
            echo "</pre>";
        }
        catch (Exception $exc) {
            echo "<pre>";
            echo $exc->getTraceAsString();
            echo "</pre>";
        }
        echo " === == ";
    }

    /**
     * Dynamic registration
     *
     * @throws OpenIDConnectClientException
     */
    public function registerOIDCfed_OP() {

        $registration_endpoint = $this->getProviderConfigValue('registration_endpoint');

        $send_object = (object) array(
                    'redirect_uris' => array($this->getRedirectURL()),
                    'client_name'   => $this->getClientName()
        );

        $response = $this->fetchURL($registration_endpoint,
                                    \json_encode($send_object));

        $json_response = \json_decode($response);

        // Throw some errors if we encounter them
        if ($json_response === false) {
            throw new Exception("Error registering: JSON response received from the server was invalid.");
        }
        elseif (isset($json_response->{'error_description'})) {
            throw new Exception($json_response->{'error_description'});
        }

        $this->setClientID($json_response->{'client_id'});

        // The OpenID Connect Dynamic registration protocol makes the client secret optional
        // and provides a registration access token and URI endpoint if it is not present
        if (isset($json_response->{'client_secret'})) {
            $this->setClientSecret($json_response->{'client_secret'});
        }
        else {
            throw new Exception("Error registering:
                                                    Please contact the OpenID Connect provider and obtain a Client ID and Secret directly from them");
        }

        if (isset($json_response->{'client_name'})) {
            //Save registration data
            $client_id         = $this->getClientID();
            $client_secret     = $this->getClientSecret();
            $clientName        = $json_response->{'client_name'};
            $provider_url      = $this->getProviderURL();
            $path_dataDir_real = \oidcfed\configure::path_dataDir_real();
            if (\is_string($json_response)) {
                $json_response_arr = \json_decode($json_response, true);
            }
            else {
                $json_response_arr = (array) $json_response;
            }
            $dataToSave     = ["provider_url"  => $provider_url, "client_id"     => $client_id,
                "client_secret" => $client_secret, "client_name"   => $clientName,
                "iat"           => \time(), "exp"           => (\time() + 3600)];
//            $dataToSave_fin = \array_merge_recursive($dataToSave,$json_response_arr);
            $dataToSave_fin = \array_merge($dataToSave, $json_response_arr);

            try {
                \oidcfed\oidcfedClient::save_clientName_id_secret($path_dataDir_real,
                                                                  $dataToSave_fin);
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }
        }
    }

    /**
     *
     * @param array $payload
     * @param type $pubkey
     * @param \Jose\Object\JWS $privkey
     * @param array $protected_headers
     * @param array $jws_signer_alg
     * @return \Jose\Object\JWS
     * @throws Exception
     * @deprecated since version 0.0.1
     */
    public function constructs_signing_request_registration(array $payload = null,
                                                            $pubkey = null,
                                                            $privkey = null,
                                                            array $protected_headers =
    [
    ], array $jws_signer_alg = []) {
        $pubKey_details = false;
        $str_cert       = false; // Here we will save public key
        $res_pubkey     = false;
//        $pubKey_details       = false;
        $check00        = (\is_array($protected_headers) && \count($protected_headers)
                > 0);
        $check01        = (\is_array($jws_signer_alg) && \count($jws_signer_alg)
                > 0);
        if (!$pubKey_details || !$privkey || !$check00 || !$check01) {
            throw new Exception("Bad parameters received!");
        }
        try {
            $additional_parameters = [
                'kid' => $this->getClientID(),
                "use" => "sig"
            ];
            $jwk_pub_json          = \oidcfed\security_jose::generate_jwk_from_key_with_parameter_array($pubkey,
                                                                                                        null,
                                                                                                        $additional_parameters,
                                                                                                        true);
            $pubkey_obj            = \json_decode($jwk_pub_json);
        }
        catch (Exception $exc) {
//            echo $exc->getTraceAsString();
            throw new Exception("Problems with public key: " . $exc);
        }
        $signing_keys = (object) ["keys" => [$pubkey_obj]];
        if (!$payload) {
            $payload = ["federation_usage" => "registration", "signing_keys" => $signing_keys];
        }
        if ($this->wellKnown["token_endpoint_auth_signing_alg_values_supported"]) {
            $jwk_signature_key = $this->wellKnown["token_endpoint_auth_signing_alg_values_supported"];
        }
        else {
            throw new Exception("ALG not found in wellknown");
        }
        if ($privkey instanceof \Jose\Object\JWS) {
            $jws_signer = $privkey;
        }
        else if (\is_string($privkey) && \mb_strlen($privkey) > 0) {
            $jws_signer = \oidcfed\security_jose::create_jws($privkey);
        }
        $ms_jws = \oidcfed\security_jose::create_jws_and_sign($payload,
                                                              $protected_headers,
                                                              $jwk_signature_key,
                                                              $jws_signer);
        if ($ms_jws instanceof \Jose\Object\JWS) {
            return $ms_jws;
        }
        throw new Exception("Something wrong happened!!!");
    }

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//  From OpenDConnectClient
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    public function getRedirectURL() {

// If the redirect URL has been set then return it.
        if (property_exists($this, 'redirectURL') && $this->redirectURL) {
            return $this->redirectURL;
        }

// Other-wise return the URL of the current page

        /**
         * Thank you
         * http://stackoverflow.com/questions/189113/how-do-i-get-current-page-full-url-in-php-on-a-windows-iis-server
         */
        /*
         * Compatibility with multiple host headers.
         * The problem with SSL over port 80 is resolved and non-SSL over port 443.
         * Support of 'ProxyReverse' configurations.
         */

        if (isset($_SERVER["HTTP_UPGRADE_INSECURE_REQUESTS"]) && ($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] == 1)) {
            $protocol = 'https';
        }
        else {
            $protocol = @$_SERVER['HTTP_X_FORWARDED_PROTO'] ?: @$_SERVER['REQUEST_SCHEME']
                        ?: ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
                        ? "https" : "http");
        }

        $port = @intval($_SERVER['HTTP_X_FORWARDED_PORT']) ?: @intval($_SERVER["SERVER_PORT"])
                    ?: (($protocol === 'https') ? 443 : 80);

        $host = @explode(":", $_SERVER['HTTP_HOST'])[0] ?: @$_SERVER['SERVER_NAME']
                    ?: @$_SERVER['SERVER_ADDR'];

        $port = (443 == $port) || (80 == $port) ? '' : ':' . $port;

        return sprintf('%s://%s%s/%s', $protocol, $host, $port,
                       @trim(reset(explode("?", $_SERVER['REQUEST_URI'])), '/'));
    }

    /**
     * @param $jwt string encoded JWT
     * @param int $section the section we would like to decode
     * @return object
     */
    protected function decodeJWT($jwt, $section = 0) {

        $parts = explode(".", $jwt);
        return json_decode(base64url_decode($parts[$section]));
    }

    /**
     * Get's anything that we need configuration wise including endpoints, and other values
     *
     * @param $param
     * @param string $default optional
     * @throws OpenIDConnectClientException
     * @return string
     *
     */
    protected function getProviderConfigValue($param, $default = null) {

// If the configuration value is not available, attempt to fetch it from a well known config endpoint
// This is also known as auto "discovery"
        if (!isset($this->providerConfig[$param])) {
            if (!$this->wellKnown) {
                $well_known_config_url = rtrim($this->getProviderURL(), "/") . "/.well-known/openid-configuration";
                $this->wellKnown       = json_decode($this->fetchURL($well_known_config_url));
            }
            $wellKnown = $this->wellKnown;
            if ($wellKnown && !is_object($wellKnown)) {
                $wellKnown = (object) $wellKnown;
            }
            $value = false;
            if (isset($wellKnown->{$param})) {
                $value = $wellKnown->{$param};
            }

            if ($value) {
                $this->providerConfig[$param] = $value;
            }
            elseif (isset($default)) {
// Uses default value if provided
                $this->providerConfig[$param] = $default;
            }
            else {
                throw new Exception("The provider {$param} has not been set. Make sure your provider has a well known configuration available.");
            }
        }

        return $this->providerConfig[$param];
    }

    public static function save_clientName_id_secret($dirPath = null,
                                                     array $data = []) {
        if (\is_readable($dirPath) && \rtrim($dirPath, "/") && \is_dir($dirPath)
                && isset($data) && \is_array($data) && \array_key_exists("client_name",
                                                                         $data)) {
//            reset($data);
//            $keyD = key($data);
//            $filename = $dirPath . "/" . str_replace(" ", "_", $keyD) . ".json";
            $clientName  = $data["client_name"];
            $dirPathReal = \realpath($dirPath);
            $filename    = $dirPathReal . "/" . str_replace(" ", "_",
                                                            $clientName) . ".json";
        }
        else {
//            throw new Exception("Directory is not readable");
            die("Directory is not readable");
        }
        if (isset($data) && \is_array($data) && isset($filename) && \is_readable($filename)) {
            $json_content = \file_get_contents($filename);
            $json_arr     = \json_decode($json_content, true);
            if (\is_array($json_arr) && \array_key_exists("provider_url", $data)
                    && isset($data["provider_url"])) {
                $client_data_arr = self::search_providerUrl_data_for_clientName(
                                $data["provider_url"], $json_content);
            }
            else {
                $client_data_arr = null;
            }
            if (\is_array($client_data_arr)) {
                \reset($client_data_arr);
                $jd_key            = \key($client_data_arr);
                $json_arr[$jd_key] = $client_data_arr[$jd_key];
                $data              = $json_arr;
//                \unlink($filename);
//                \file_put_contents($filename,
//                                   \json_encode($json_arr[$jd_key],
//                                                \JSON_PARTIAL_OUTPUT_ON_ERROR));
//                return true;
            }
//            throw new Exception("No data found in the file or file is empty!");
        }
        if (isset($data) && \is_array($data) && isset($filename) && \array_key_exists("provider_url",
                                                                                      $data)) {
            \unlink($filename);
            \file_put_contents($filename,
                               \json_encode([$data],
                                            \JSON_PARTIAL_OUTPUT_ON_ERROR));
            return true;
        }
        else if (isset($data) && \is_array($data) && isset($filename) && \array_key_exists("provider_url",
                                                                                           $data) === false) {
            \unlink($filename);
            \file_put_contents($filename,
                               \json_encode($data, \JSON_PARTIAL_OUTPUT_ON_ERROR));
            return true;
        }
        throw new Exception("Can't write content to file");
    }

    public static function get_clientName_id_secret($dirPath, $clientName,
                                                    $provider_url) {
        if (\is_readable($dirPath) && \rtrim($dirPath, "/") && \is_dir($dirPath)) {
            $filename = $dirPath . "/" . str_replace(" ", "_", $clientName) . ".json";
        }
        else {
            throw new Exception("dirPath doesn't exists or not readable");
        }
        if (\is_readable($filename)) {
            $file_path_real = \realpath($filename);
            $file_contents  = \file_get_contents($file_path_real);
        }
        else {
            throw new Exception("File is empty!");
        }
        $json_obj = \json_decode($file_contents, true);
        if (\is_array($json_obj) && \is_string($provider_url)) {
            return self::search_providerUrl_data_for_clientName(
                            $provider_url, $file_contents);
        }
        else if ($json_obj) {
            return $json_obj;
        }
        throw new Exception("Bad json content in the file.");
    }

    public static function search_providerUrl_data_for_clientName(
    $provider_url, $jsonStr) {
        if (!\is_string($jsonStr)) {
            throw new Exception("Not a string provided!");
        }
        $json_arr = \json_decode($jsonStr, TRUE);
        if (\is_array($json_arr) && \array_key_exists("provider_url", $json_arr)
                && isset($json_arr["provider_url"])) {
            return $json_arr;
        }
        if (!\is_array($json_arr)) {
            throw new Exception("Json structure not found!");
        }
        foreach ($json_arr as $jakey => $javal) {
            if (!\is_array($javal)) {
                continue;
            }
            if (\array_key_exists("provider_url", $javal) && isset($javal["provider_url"])
                    && rtrim(rtrim($javal["provider_url"]), '/') === rtrim(rtrim($provider_url),
                                                                                 '/')
            ) {
                return [$jakey => $javal];
            }
        }
    }

}
