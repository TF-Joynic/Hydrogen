<?php

ini_set('memory_limit', '170M');
date_default_timezone_set('Asia/Shanghai');

$base = __DIR__;
$vendor_path = $base.'/vendor';
$lib_path = $base.'/lib';
$application_path = $base.'/application';
$global_config_path = $base.'/config';

defined('APPLICATION_PATH') || define('APPLICATION_PATH', $application_path);
defined('VENDOR_PATH') || define('VENDOR_PATH', $vendor_path);
defined('LIB_PATH') || define('LIB_PATH', $lib_path);

defined('GLOBAL_CONFIG_PATH')
|| define('GLOBAL_CONFIG_PATH', $global_config_path);

defined('NAMESPACE_SEPARATOR') || define('NAMESPACE_SEPARATOR', '\\');

require LIB_PATH.'/Hydrogen/Load/Autoloader.php';

$autoloader = Hydrogen\Load\Autoloader::getInstance();
$autoloader->attachNamespace('application', $base.DIRECTORY_SEPARATOR.'application');
$autoloader->attachCallback(
    array(
        Hydrogen\Load\Autoloader::CALLBACK_NS2PATH
    )
);

Hydrogen\Load\Loader::import(LIB_PATH.'/Hydrogen/Include/Functions.php');

//$argc --;
//array_shift($argv);

pre($argv);