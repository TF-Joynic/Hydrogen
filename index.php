<?php

use application\module\front\filter\WebSecurityFilterChain;
use Hydrogen\Load\Loader;
use Hydrogen\Debug\Variable;
use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;
use Hydrogen\Route\Rule\RulePostfix;
use Hydrogen\Route\Rule\RuleClosure;
use Hydrogen\Application\ApplicationContext;

if ('WINNT' != PHP_OS && false === stripos(PHP_OS, 'darwin')) {
	echo '<strong>Hello, SAE!</strong>';
} else {
	$base = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'].'/');

	$vendor_path = $base.'vendor';
	$lib_path = $base.'lib';
	$application_path = $base.'application';
	$global_config_path = $base.'config';

    defined('APPLICATION_PATH') || define('APPLICATION_PATH', $application_path);
    defined('VENDOR_PATH') || define('VENDOR_PATH', $vendor_path);
    defined('LIB_PATH') || define('LIB_PATH', $lib_path);

    defined('COMPILE_PATH') || define('COMPILE_PATH',
        APPLICATION_PATH.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'compile');

    defined('GLOBAL_CONFIG_PATH')
	 || define('GLOBAL_CONFIG_PATH', $global_config_path);

    defined('MODULE') || define('MODULE', 'module');
    defined('CTRL') || define('CTRL', 'ctrl');
    defined('ACT') || define('ACT', 'act');

    require LIB_PATH.'/Hydrogen/Load/Autoloader.php';
	$autoloader = Hydrogen\Load\Autoloader::getInstance();
	$autoloader->attachCallback(
        array(
            Hydrogen\Load\Autoloader::CALLBACK_NS2PATH,
            Hydrogen\Load\Autoloader::CALLBACK_COMPOSER,
            Hydrogen\Load\Autoloader::CALLBACK_THRIFTCLIENT
        )
    );

    $autoloader->attachNamespace('Psr', LIB_PATH.DIRECTORY_SEPARATOR.'Psr');
    $autoloader->attachNamespace('application', APPLICATION_PATH, true);
    $autoloader->attachNamespace('Hydrogen', LIB_PATH.DIRECTORY_SEPARATOR.'Hydrogen', true);

    // include Framework constant
    Loader::import('lib/Hydrogen/Constant/Http.php');
    Loader::import('lib/Hydrogen/Include/Functions.php');

    $classLoadConfigArr = Loader::import('application/config/'.ApplicationContext::getClassLoadConfigFile(), true, true);
    $autoloader->setClassLoadMap($classLoadConfigArr[CLASSLOADMAP]);

	$CONFIG = Hydrogen\Config\Config::getInstance();
	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.ApplicationContext::getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.'application.ini');

	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.ApplicationContext::getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.ENV.DIRECTORY_SEPARATOR
		.'application.ini');

    $CONFIG->mergeConfigFile(APPLICATION_PATH.
        DIRECTORY_SEPARATOR.ApplicationContext::getApplicationConfigDir()
        .DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        .'database.ini');

    ApplicationContext::setModuleDir(APPLICATION_PATH.DIRECTORY_SEPARATOR.$CONFIG->get(SCOPE_APPICATION, 'application', '_module_dir'));
    ApplicationContext::setEnabledModules($CONFIG->get(SCOPE_APPICATION, 'application', '_enabled_modules'));
    ApplicationContext::setTemplatePostfix("tpl");

    // Executor filters
    $webSecurityFilterChain = new WebSecurityFilterChain();
    $commonFilters = array(
        WebSecurityFilterChain::class => $webSecurityFilterChain
    );
    ApplicationContext::setFilters($commonFilters);

	$application = new Hydrogen\Application\Application();
	$application->run();
}