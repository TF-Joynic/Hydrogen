<?php
// runtime environment definition:
$env = '';
if (isset($_SERVER['RUNTIME_ENV']) && $_SERVER['RUNTIME_ENV']) {
    $env = trim($_SERVER['RUNTIME_ENV']);
} elseif (false !== stripos(PHP_OS, 'WINNT') || false !== stripos(PHP_OS, 'darwin')) {
    $env = 'development';
} elseif ('cgi' == php_sapi_name()) {

} elseif ('cli' == php_sapi_name()) {

}

if ($env) {
    defined('ENV') || define('ENV', $env);
}

if (!defined('ENV') || empty(ENV) || !in_array(ENV, array('production', 'test', 'development'))) {
    throw new \RuntimeException('RUNTIME_ENV is not properly defined!');
}
// runtime environment definition end <-


if (!function_exists('hydrogenErrorHandler')) {
    function hydrogenErrorHandler($errno, $errmsg, $filename, $errline, $vars) {
        echo $filename,'--',$errline;
    }
//    set_error_handler('hydrogenErrorHandler');
}

if (!function_exists('hydrogenExceptionHandler')) {
    function hydrogenExceptionHandler(\Exception $e) {
        echo $e->getCode(),'--',$e->getFile(),'--'.$e->getFile();
    }

//    set_exception_handler('hydrogenExceptionHandler');
}

if (!function_exists('isPro')) {
    function isPro() {
        return defined('ENV') && ENV === 'production';
    }
}

if ('cgi' == php_sapi_name() && !function_exists('isTest')) {
    function isTest() {
        return defined('ENV') && ENV === 'test';
    }
}

if (!function_exists('isDev')) {
    function isDev() {
        return !defined('ENV') || ENV === 'development';
    }
}

if (!function_exists('pre') && !isPro()) {
}
function pre($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

defined('SCOPE_APPICATION') || define('SCOPE_APPICATION', 'application');
defined('SCOPE_DATABASE') || define('SCOPE_DATABASE', 'database');
defined('SCOPE_MEMCACHE') || define('SCOPE_MEMCACHE', 'memcache');