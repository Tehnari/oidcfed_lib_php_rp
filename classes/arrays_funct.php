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
 * This class will help to find if we have an associative array or not
 *
 * @author constantin
 */
class arrays_funct {
    /**
     * Source :: https://stackoverflow.com/a/14026836/7727461
     *
     * Since PHP stores all arrays as associative internally, there is no proper
     * definition of a scalar array.
     *
     * As such, developers are likely to have varying definitions of scalar array,
     * based on their application needs.
     *
     * In this file, I present 3 increasingly strict methods of determining if an
     * array is scalar.
     *
     * @author David Farrell <DavidPFarrell@gmail.com>
     */

    /**
     * isArrayWithOnlyIntKeys defines a scalar array as containing
     * only integer keys.
     *
     * If you are explicitly setting integer keys on an array, you
     * may need this function to determine scalar-ness.
     *
     * @param array $a
     * @return boolean
     */
    public static function isArrayWithOnlyIntKeys(array $a) {
        if (!\is_array($a)) {
            return false;
        }
        foreach ($a as $k => $v) {
            if (!\is_int($k)) {
                return false;
            }
        }
        return true;
    }

    /**
     * isArrayWithOnlyAscendingIntKeys defines a scalar array as
     * containing only integer keys in ascending (but not necessarily
     * sequential) order.
     *
     * If you are performing pushes, pops, and unsets on your array,
     * you may need this function to determine scalar-ness.
     *
     * @param array $a
     * @return boolean
     */
    public static function isArrayWithOnlyAscendingIntKeys(array $a) {
        if (!\is_array($a)) {
            return false;
        }
        $prev = null;
        foreach ($a as $k => $v) {
            if (!\is_int($k) || (null !== $prev && $k <= $prev)) {
                return false;
            }
            $prev = $k;
        }
        return true;
    }

    /**
     * isArrayWithOnlyZeroBasedSequentialIntKeys defines a scalar array
     * as containing only integer keys in sequential, ascending order,
     * starting from 0.
     *
     * If you are only performing operations on your array that are
     * guaranteed to either maintain consistent key values, or that
     * re-base the keys for consistency, then you can use this function.
     *
     * @param array $a
     * @return boolean
     */
    public static function isArrayWithOnlyZeroBasedSequentialIntKeys(array $a) {
        if (!\is_array($a)) {
            return false;
        }
        $i = 0;
        foreach ($a as $k => $v) {
            if ($i++ !== $k) {
                return false;
            }
        }
        return true;
    }

}
