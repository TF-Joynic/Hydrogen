<?php
// runtime environment definition:
if (isset($_SERVER['RUNTIME_ENV'])) {
    $tmp = trim($_SERVER['RUNTIME_ENV']);
    if ($tmp) {
        defined('ENV') || define('ENV', $tmp);
    }
}

if ('cli' != php_sapi_name()) {
    if (!defined('ENV') || empty(ENV) || !in_array(ENV, array('production', 'test', 'development'))) {
        throw new \RuntimeException('RUNTIME_ENV is not properly defined!');
    }
// runtime environment definition end!
}

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

if (!function_exists('isTest')) {
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
    function pre($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
}

if (!function_exists('HydrogenLog')) {
    function HydrogenLog($str, $level = null, $storage = null) {

        if (null === $level) {

            $level = class_exists('Hydrogen\\Log\\Logger')
                ? Hydrogen\Log\Logger::LEVEL_INFO
                : 'info';

        }

        if (null === $storage) {

            $storage = class_exists('Hydrogen\\Log\\Logger')
                ? Hydrogen\Log\Logger::STORAGE_FILE
                : 'file';

        }

        $err_dest = APPLICATION_PATH. '/log';
//        error_log($str, )
    }
}

if (!function_exists('get_variable_name')) {
    function get_variable_name(&$var, $scope = NULL) {
        if (NULL == $scope) {
            $scope = $GLOBALS;
        }

        $tmp  = $var;
        $var   = "tmp_exists_" . mt_rand();
        $name = array_search($var, $scope, TRUE);
        $var   = $tmp;
        return $name;
    }
}

if (!function_exists('pomvc')) {
    function pomvc() {
        global $module, $ctrl, $act;

        echo "<br />m: ".$module."<br />";
        echo "c: ".$ctrl."<br />";
        echo 'a: '.$act."<br />";
        exit;
    }
}
