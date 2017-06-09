<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

/**
 * Description of oidc_dyn
 *
 * @author constantin
 */
class oidc_dyn {

    public static function init($issuer) {
        $oidc_dyn = new OpenIDConnectClient($issuer);
        $oidc_dyn->register();
        return $oidc_dyn;
    }

}
