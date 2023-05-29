<?php

namespace App\Lib;

/**
 * Class Dot
 *
 */
class DotArray
{
    /**
     * Returns whether or not the $key exists within $arr
     *
     * @param array  $arr
     * @param string $key
     *
     * @return bool
     */
    public static function has($arr, $key)
    {
        if (strpos($key, '.') !== false && count(($keys = explode('.', $key)))) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $arr)) {
                    return false;
                }
                $arr = $arr[$key];
            }
            return true;
        }
        return array_key_exists($key, $arr);
    }
    /**
     * Returns he value of $key if found in $arr or $default
     *
     * @param array       $arr
     * @param string      $key
     * @param null|mixed  $default
     *
     * @return mixed
     */
    public static function get($arr, $key, $default = null)
    {
        if (!is_array($arr)) {
            return $default;
        }
        if (strpos($key, '.') !== false && count(($keys = explode('.', $key)))) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $arr)) {
                    return $default;
                }
                $arr = $arr[$key];
            }
            return $arr;
        }
        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }
    /**
     * Sets the $value identified by $key inside $arr
     *
     * @param array  &$arr
     * @param string $key
     * @param mixed  $value
     */
    public static function set(&$arr, $key, $value)
    {
        if (!is_array($arr)) {
            $arr = [];
        }

        $loc = &$arr;
        foreach (explode('.', $key) as $step) {
            $loc = &$loc[$step];
        }
        $loc = $value;
        return $arr;

        /*$copy = $arr;
        if (!is_array($copy)) {
            $copy = [];
        }
        if (strpos($key, '.') !== false && ($keys = explode('.', $key)) && count($keys)) {
            while (count($keys) > 0) {
                $key = array_shift($keys);
                if (!isset($copy[$key]) || !is_array($copy[$key])) {
                    $copy[$key] = [];
                }
                $copy = $copy[$key];
            }
            $copy[array_shift($keys)] = $value;
        } else {
            $copy[$key] = $value;
        }
        $arr = $copy;
        return $copy;*/
    }
    /**
     * Deletes a $key and its value from the $arr
     *
     * @param  array &$arr
     * @param string $key
     */
    public static function delete(array &$arr, $key)
    {
        if (strpos($key, '.') !== false && ($keys = explode('.', $key)) && count($keys)) {
            while (count($keys) > 1) {
                $arr = &$arr[array_shift($keys)];
            }
            unset($arr[array_shift($keys)]);
        } else {
            unset($arr[$key]);
        }
    }
}
