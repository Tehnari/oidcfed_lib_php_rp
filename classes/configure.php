<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace oidcfed;

/**
 * Just class for basic configuration of this library (why not? :) )
 *
 * @author constantin
 */
class configure {

    static function get_oidcfed_lib_configure($path_data=false, $path_keys=false,$path_tmp=false){
        if($path_data === false){
            return false;
        }
        if($path_keys === false){
            $path_keys = $path_data . "/keys";
        }
        if($path_tmp === false){
            $path_tmp = \sys_get_temp_dir();
        }

        $filename_lib_configure = "oidcfed_lib_configure.json";
        $configure_raw = false;
        if(is_readable($path_data . "/".$filename_lib_configure)){
            $configure_raw  = file_get_contents( $path_data . "/".$filename_lib_configure);
        }
    }
}
