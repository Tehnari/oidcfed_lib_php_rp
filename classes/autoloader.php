<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of autoload
 *
 * @author constantin
 */
class autoloader {

    public static function init() {
        spl_autoload_extensions(".php"); // comma-separated list
        spl_autoload_register();
    }

}
