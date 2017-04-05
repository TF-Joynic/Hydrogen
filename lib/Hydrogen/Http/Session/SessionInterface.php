<?php

namespace Hydrogen\Http\Session;

interface SessionInterface
{
    // session_start();
    public static function start($options = array());
    public static function set($key, $value);
    public static function has($key);
    public static function get($key);
    public static function remove($key);

    public static function clear();

    public static function abort();

    public static function setCacheExpiration();
    public static function getCacheExpiration();

    public static function setCacheLimiter($limiter);
    public static function getCacheLimiter();

    /**
     * @param $prefix null|string
     * @return mixed
     */
    public static function createId($prefix = null);

    /**
     * @return string
     */
    public static function encode();

    /**
     * @param $data string
     * @return mixed
     */
    public static function decode($data);

    public static function destroy();

    /**
     * @return int
     */
    public static function gc();



}