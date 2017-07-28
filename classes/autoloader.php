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

/**
 * Autoloader for classes
 * Based on sources from:
 * https://stackoverflow.com/questions/38663356/php-autoload-classes-from-different-directories
 *
 * @author Alko
 * @author Constantin Sclifos
 */

//define('PATH', realpath(dirname(__file__)) . '/classes') . '/';
define('PATH', realpath(dirname(__file__))) . '/';
define('DS', DIRECTORY_SEPARATOR);

class autoloader {

    private static $__loader;

    private function __construct() {
        \spl_autoload_register([$this, 'autoLoad']);
    }

    public static function init() {
        if (self::$__loader == null) {
            self::$__loader = new self();
        }

        return self::$__loader;
    }

    public function autoLoad($class) {
//        $exts = ['.class.php'];
        $exts = ['.php'];

        \spl_autoload_extensions("'" . \implode(',', $exts) . "'");
        \set_include_path(\get_include_path() . \PATH_SEPARATOR . \PATH);

        foreach ($exts as $ext) {
//            if (\is_readable($path = \BASE . \strtolower($class . $ext))) {
            if (\is_readable($path = PATH . DS. \strtolower($class . $ext))) {
                require_once $path;
                return true;
            }
        }
        self::recursiveAutoLoad($class, \PATH);
    }

    private static function recursiveAutoLoad($class, $path) {
        if (\is_dir($path) === false) {
            return false;
        }
        if (($handle = \opendir($path)) === false) {
            return false;
        }
        //Searching for files in specified directory ...
        while (($resource = \readdir($handle)) !== false) {
            if (($resource == '..') or ( $resource == '.')) {
                continue;
            }
            if (\is_dir($dir = $path . \DS . $resource)) {
                continue;
            }
            else
            if (\is_readable($file = $path . \DS . $resource)) {
                require_once $file;
            }
        }
        closedir($handle);
    }
}
