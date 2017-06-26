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

namespace oidcfed;

/**
 * Just class for basic configuration of this library (why not? :) )
 *
 * @author constantin
 */
class configure {

    public static $config_template = [
        "countryName" => 'XX',
        "stateOrProvinceName" => 'State',
        "localityName" => 'SomewhereCity',
        "organizationName" => 'MySelf',
        "organizationalUnitName" => 'Whatever',
        "commonName" => 'mySelf',
        "emailAddress" => 'user@domain.com',
        "privkeypass" => '1234',
        "numberofdays" => 365,
        "path_data" => "",
        "path_keys" => "/keys",
        "configure_filename" => 'oidcfed_lib_configure.json'
    ];

    /**
     * This function will get configure date for this lib
     * @param string $path_data
     * @param string $path_keys
     * @param string $path_tmp
     * @return boolean || object
     */
    static function get_oidcfed_lib_configure($path_data = false,
                                              $path_keys = false,
                                              $path_tmp = false) {
        if ($path_data === false) {
            return false;
        }
        if ($path_keys === false) {
            $path_keys = $path_data . "/keys";
        }
        if ($path_tmp === false) {
            $path_tmp = \sys_get_temp_dir();
        }

        $filename_lib_configure = "oidcfed_lib_configure.json";
        $configure_raw = false;
        if (is_readable($path_data . "/" . $filename_lib_configure)) {
            $configure_raw = file_get_contents($path_data . "/" . $filename_lib_configure);
        }
        if (\mb_strlen($configure_raw) > 0) {
            try {
                $configure = \json_decode($configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $configure = false;
            }
        }
        else {
            return false;
        }
        return $configure;
    }

    /**
     * This function will set configure date for this lib
     * @param string $path_data
     * @param json $configure_raw
     * @param string $path_keys
     * @param string $path_tmp
     * @return boolean
     */
    static function set_oidcfed_lib_configure($path_data = false,
                                              $configure_raw = false,
                                              $path_keys = false,
                                              $path_tmp = false) {
        if ($path_data === false) {
            return false;
        }
        if ($path_keys === false) {
            $path_keys = $path_data . "/keys";
        }
        if ($path_tmp === false) {
            $path_tmp = \sys_get_temp_dir();
        }
        if ($configure_raw === false || \is_string($configure_raw) === false || (\is_string($configure_raw) === true
                && \mb_strlen($configure_raw) === 0)) {
            return false;
        }
        if (\mb_strlen($configure_raw) > 0) {
            try {
                $configure = \json_decode($configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                $configure = false;
            }
        }
        // Check if we can save configure
        $filename_lib_configure = "oidcfed_lib_configure.json";
        if (is_writable($path_data . "/" . $filename_lib_configure) === false) {
            return false;
        }
        // If we have configure_raw in json format, we proceed to save configure.
        if ($configure !== false) {
            try {
                \file_put_contents($filename_lib_configure, $configure_raw);
            }
            catch (Exception $exc) {
                echo $exc->getTraceAsString();
                return false;
            }
        }
        return true;
    }

}
