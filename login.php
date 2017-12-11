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

require (dirname(__FILE__) . '/parameters.php');
echo "";
$post_in       = NULL;
$oidcFedRp     = NULL;
$oidc_site_url = null;
if (is_array($_POST) && count($_POST) > 0) {
    $post_in = filter_input_array(INPUT_POST);
}
if (is_array($_REQUEST) && count($_REQUEST) > 0) {
    $request_in = $_REQUEST;
}

if ($post_in !== null && is_array($post_in) && array_key_exists("useAuthType",
                                                                $post_in)) {
    $useAuthType = $post_in["useAuthType"];
}
else {
//    $useAuthType = "authorization_code_static";
//    $useAuthType = "implicit_flow";
//    $useAuthType = "hybrid_flow";
    $useAuthType = "dynamic";
}

if ($post_in !== null && is_array($post_in) && array_key_exists("provider_url",
                                                                $post_in)) {
    $oidc_site_url = $post_in["provider_url"];
} else if($request_in !== null && is_array($request_in) && array_key_exists("iss",
                                                                $request_in)){
   $oidc_site_url = $request_in["iss"];
}
if (is_string($oidc_site_url) && mb_strlen($oidc_site_url) > 0) {
    //TODO All Flows are rewriting know !!!
    switch ($useAuthType) {
        /*
        case "authorization_code_static":
            $check03                  = (isset($post_in["client_id"]) && is_string($post_in["client_id"])
                    && mb_strlen($post_in["client_id"]) > 0);
            $check04                  = (isset($post_in["client_secret"]) && is_string($post_in["client_secret"])
                    && mb_strlen($post_in["client_secret"]) > 0);
//            echo "Getting or prepare certificate to use with OIDCFED Client...<br>";
            $certificateLocal_content = \oidcfed\security_keys::get_csr(false,
                                                                        $dn,
                                                                        $priv_key_woPass,
                                                                        $ndays,
                                                                        $path_dataDir_real);
            $certificateLocal_path    = \oidcfed\security_keys::public_certificateLocal_path();
            $check05                  = is_string($certificateLocal_path) && is_readable($certificateLocal_path);
            if (!$client_id) {
                $client_id = \oidcfed\configure::client_id();
            }
            if (!$client_secret) {
                $client_secret = \oidcfed\oidcfedClient::generateRandString_static();
//                $client_secret = md5(uniqid(rand(), TRUE));
            }
            $oidcFedRp = new \oidcfed\oidcfedClient($oidc_site_url, $client_id,
                                                    $client_secret);
            $oidcFedRp->setClientID($client_id);
            $oidcFedRp->setClientSecret($client_secret);
            try {
                $oidcFedRp->setCertPath($certificateLocal_path);
//            $responseTypes = $oidcFedRp->getResponseTypes();
                $oidcFedRp->wellKnown = \oidcfed\oidcfedClient::get_well_known_openid_config_data($oidc_site_url,
                                                                                                  null,
                                                                                                  null,
                                                                                                  false);
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("authorization_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['authorization_endpoint' => $oidcFedRp->wellKnown["authorization_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("token_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['token_endpoint' => $oidcFedRp->wellKnown["token_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("userinfo_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['userinfo_endpoint' => $oidcFedRp->wellKnown["userinfo_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("registration_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['registration_endpoint' => $oidcFedRp->wellKnown["registration_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("end_session_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['end_session_endpoint' => $oidcFedRp->wellKnown["end_session_endpoint"]]);
            }
            try {
                $oidcFedRp->authenticate();
//                $oidcFedRp->implicit_flow();
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }
            break;
        case "implicit_flow":
            $check03                  = (isset($post_in["client_id"]) && is_string($post_in["client_id"])
                    && mb_strlen($post_in["client_id"]) > 0);
            $check04                  = (isset($post_in["client_secret"]) && is_string($post_in["client_secret"])
                    && mb_strlen($post_in["client_secret"]) > 0);
//            echo "Getting or prepare certificate to use with OIDCFED Client...<br>";
            $certificateLocal_content = \oidcfed\security_keys::get_csr(false,
                                                                        $dn,
                                                                        $priv_key_woPass,
                                                                        $ndays,
                                                                        $path_dataDir_real);
            $certificateLocal_path    = \oidcfed\security_keys::public_certificateLocal_path();
            $check05                  = is_string($certificateLocal_path) && is_readable($certificateLocal_path);
            if (!$client_id) {
                $client_id = \oidcfed\configure::client_id();
            }
            if (!$client_secret) {
                $client_secret = \oidcfed\oidcfedClient::generateRandString_static();
//                $client_secret = md5(uniqid(rand(), TRUE));
            }
            //Static registration TEST
//    if ($check03 && $check04 && $check05) {
            $oidcFedRp = new \oidcfed\oidcfedClient($oidc_site_url, $client_id,
                                                    $client_secret);
            $oidcFedRp->setClientID($client_id);
            $oidcFedRp->setClientSecret($client_secret);
            try {
                $oidcFedRp->setCertPath($certificateLocal_path);
//            $responseTypes = $oidcFedRp->getResponseTypes();
                $oidcFedRp->wellKnown = \oidcfed\oidcfedClient::get_well_known_openid_config_data($oidc_site_url,
                                                                                                  null,
                                                                                                  null,
                                                                                                  false);
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("authorization_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['authorization_endpoint' => $oidcFedRp->wellKnown["authorization_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("token_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['token_endpoint' => $oidcFedRp->wellKnown["token_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("userinfo_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['userinfo_endpoint' => $oidcFedRp->wellKnown["userinfo_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("registration_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['registration_endpoint' => $oidcFedRp->wellKnown["registration_endpoint"]]);
            }
            if (\is_array($oidcFedRp->wellKnown) && \array_key_exists("end_session_endpoint",
                                                                      $oidcFedRp->wellKnown)) {
                $oidcFedRp->providerConfigParam(['end_session_endpoint' => $oidcFedRp->wellKnown["end_session_endpoint"]]);
            }
//            $oidcFedRp->addScope('profile');
//            $oidcFedRp->addScope('openid');
            try {
//                $oidcFedRp->authenticate();
                $oidcFedRp->implicit_flow();
            }
            catch (Exception $exc) {
                echo "<pre>";
                echo $exc->getTraceAsString();
                echo "</pre>";
            }

//            $oidcFedRp->authenticate();
            // this assumes success (to validate check if the access_token property is there and a valid JWT) :
            $clientCredentialsToken = $oidc->requestClientCredentialsToken()->access_token;
            echo "";
//            $oidcFedRp->authenticate();
//            $oidcFedRp->getAuthParams();
//            $client_id     = $oidcFedRp->getClientID();
//            $client_secret = $oidcFedRp->getClientSecret();
//        if(is_array($openid_known)){
//            foreach ($openid_known['metadata_statements'] as $ms_key => $ms_value) {
//                $result_MS_Verify = \oidcfed\metadata_statements::verifyMetadataStatement($ms_value, $ms_key, $jwks->getPayload()["bundle"]);
//                echo "";
//            }
//        }
//    }
            break;
            */
        case "dynamic":
            //Dynamic registration TEST
            $oidcFedRp              = new \oidcfed\oidcfedClient($oidc_site_url);
            $verifyCert             = false;
            $oidcFedRp->setVerifyCert($verifyCert);
//            $oidcFedRp->setVerifyHost($verifyCert);
//            $oidcFedRp->setVerifyPeer($verifyCert);
//            $webfinger_data         = $oidcFedRp->get_webfinger_data($oidc_site_url);
            $webfinger_data = rtrim(rtrim($oidcFedRp->get_webfinger_data($oidc_site_url)),'/').'/';
            $oidcFedRp->setProviderURL($webfinger_data);
            $oidcFedRp->wellKnown   = \oidcfed\oidcfedClient::get_well_known_openid_config_data($webfinger_data,
                                                                                                null,
                                                                                                null,
                                                                                                false);
            $oidcFedRp->dynamic_registration_and_auth_code($verifyCert,
                                                           $private_key,
                                                           $passphrase);
//            if ($check05)
//                {
//                try
//                    {
//                    $oidcFedRp->wellKnown = \oidcfed\oidcfedClient::get_well_known_openid_config_data($oidc_site_url,
//                                                                                                      null,
//                                                                                                      null,
//                                                                                                      false);
////            $responseTypes = $oidcFedRp->VerifySignatureAndInterpretProviderInfo($oidc_site_url);
//                    }
//                catch (Exception $exc)
//                    {
//                    echo "<pre>";
//                    echo $exc->getTraceAsString();
//                    echo "</pre>";
//                    }
////        echo "<pre>";
////        var_dump($oidcFedRp);
////        echo "</pre>";
//                if (is_array($oidcFedRp->wellKnown))
//                    {
//                    $jwks = $oidcFedRp->get_jwks_from_wellKnown();
////            echo "";
//                    }
//                else
//                    {
//                    $jwks = false;
//                    }
//                if (is_array($oidcFedRp->wellKnown))
//                    {
//                    foreach ($oidcFedRp->wellKnown['metadata_statements'] as
//                                $ms_key => $ms_value)
//                        {
////                        $result_MS_Verify = \oidcfed\metadata_statements::verifyMetadataStatement($ms_value,$ms_key,$jwks);
//                        $result_MS_Verify = \oidcfed\metadata_statements::unpack_MS($ms_value,
//                                                                                    null,
//                                                                                    $jwks,
//                                                                                    false,
//                                                                                    true);
//                        echo "";
//                        }
//                    }
//                }
            break;
        default:
            break;
    }
    echo "";
    $webfinger_data = $oidcFedRp->get_webfinger_data();
    echo "";
}



//$oidc = new OpenIDConnectClient('https://rp.certification.openid.net:8080',
//                                $client_id,
//                                'ClientSecretHere');
//$oidc->setCertPath('/path/to/my.cert');
//$oidc->authenticate();
//$name = $oidc->requestUserInfo('given_name');