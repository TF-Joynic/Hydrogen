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


    public static function abort();
    public static function clear();
    public static function setCacheLimiter($limiter);
    public static function getCacheLimiter();

}