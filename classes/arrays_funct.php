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

    /**
     * This function will help with intersect keys and values recursively
     * without array key reordering
     * Source from php docs , element in docs: array_intersect,
     * author: caffinated, date: 10-Nov-2012 09:13
     *
     * @param type $array1
     * @param type $array2
     * @return type
     */
    public static function array_intersect_recursive($array1, $array2) {
        foreach ($array1 as $key => $value) {
            if (!isset($array2[$key])) {
                unset($array1[$key]);
            }
            else {
                if (\is_array($array1[$key])) {
                    $array1[$key] = self::array_intersect_recursive($array1[$key],
                                                                    $array2[$key]);
                }
                elseif ($array2[$key] !== $value) {
                    unset($array1[$key]);
                }
            }
        }
        return $array1;
    }

    /**
     * This function will help to compare two objects or arrays and give
     * response for each element.
     * Source from php docs , element in docs: array_diff_assoc,
     * author: shadow_games at abv dot bg , date: 12-Jun-2014 12:45
     *
     * @param type $object_1
     * @param type $object_2
     * @param type $object_1_Identifier
     * @param type $object_2_Identifier
     * @return type
     */
    public static function compare_two_object_recursive($object_1, $object_2,
                                                        $object_1_Identifier = false,
                                                        $object_2_Identifier = false) {
        $object1 = (array) $object_1;
        $object2 = (array) $object_2;
        $object3 = array();

        $o1i = $object_1_Identifier ? $object_1_Identifier : 1;
        $o2i = $object_2_Identifier ? $object_2_Identifier : 2;

        foreach ($object1 as $key => $value) {
            if (is_object($object1[$key])) {
                $object1[$key] = (array) $object1[$key];
                $object2[$key] = (array) $object2[$key];
                $object3[$key] = (object) self::compare_two_object_recursive($object1[$key],
                                                                             $object2[$key],
                                                                             $o1i,
                                                                             $o2i);
            }
            elseif (is_array($object1[$key])) {
                $object3[$key] = self::compare_two_object_recursive($object1[$key],
                                                                    $object2[$key],
                                                                    $o1i, $o2i);
            }
            else {
                if ($object1[$key] == $object2[$key]) {
                    $object3[$key]['comparison_status'] = "SAME";
                }
                else {
                    $object3[$key]['comparison_status'] = "NOT THE SAME";
                    $object3[$key][$o1i]                = $object1[$key];
                    $object3[$key][$o2i]                = $object2[$key];
                }
            }
        }
        return $object3;
    }

    /**
     * This function will compare two objects or arrays and will return a boolean
     * or will throw a exception in a case of error
     * @param type $object_1
     * @param type $object_2
     * @param type $compare_arr
     * @return boolean
     * @throws Exception
     */
    public static function get_compare_results_for_two_objects($object_1 = [],
                                                               $object_2 = [],
                                                               $compare_arr = false) {
        $result  = true;
        $check00 = (\is_array($compare_arr) === true && \count($compare_arr) > 0);
        if (\count($object_1) > 0 && \count($object_2) > 0 && $compare_arr === false) {
            $compare_arr = self::compare_two_object_recursive($object_1,
                                                              $object_2);
        }
        else if ($check00 === false) {
            throw new Exception("Bad parameters recieved.");
        }

        foreach ($compare_arr as $c_key => $c_value) {
            if (empty($c_value) || \is_array($c_value) === false) {
                continue;
            }
            if (\is_object($compare_arr[$c_key])) {
                $compare_arr[$c_key] = (array) $compare_arr[$c_key];
                try {
                    $compare_arr[$c_key] = self::compare_two_object_recursive([],
                                                                              [],
                                                                              $compare_arr[$c_key]);
                }
                catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                }
            }
            else if (\is_array($compare_arr[$c_key])) {
//                $compare_arr[$c_key] = (array) $compare_arr[$c_key];
                try {
                    $compare_arr[$c_key] = self::compare_two_object_recursive([],
                                                                              [],
                                                                              $compare_arr[$c_key]);
                }
                catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                }
            }

            if (\array_key_exists("comparison_status", $c_value) === true && \is_string($c_value["comparison_status"]) === true
                    && \mb_strtolower($c_value["comparison_status"]) !== "same") {
                $result = false;
            }
        }
        return $result;
    }

}
