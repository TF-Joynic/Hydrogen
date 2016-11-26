<?php

use Hydrogen\Debug\Variable;
use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;
use Hydrogen\Route\Rule\RulePostfix;
use Hydrogen\Route\Rule\RuleClosure;
use Hydrogen\Application\Execute\Executor;

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
        )
    );

    $autoloader->attachNamespace('Psr', LIB_PATH.DIRECTORY_SEPARATOR.'Psr', true);
    $autoloader->attachNamespace('application', APPLICATION_PATH);

	// include Framework constant
	Hydrogen\Load\Loader::getInstance()
		->import('lib/Hydrogen/Constant/Http.php');

    Hydrogen\Load\Loader::getInstance()
        ->import('lib/Hydrogen/Include/Functions.php');

//    pre($autoloader->getRegisteredCallbacks());exit;
	// config

	$CONFIG = Hydrogen\Config\Config::getInstance();
	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.Executor::getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.'application.ini');

	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.Executor::getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.ENV.DIRECTORY_SEPARATOR
		.'application.ini');

    $CONFIG->mergeConfigFile(APPLICATION_PATH.
        DIRECTORY_SEPARATOR.Executor::getApplicationConfigDir()
        .DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        .'database.ini');

    Executor::setModuleDir(APPLICATION_PATH.DIRECTORY_SEPARATOR.$CONFIG->get(SCOPE_APPICATION, 'application', '_module_dir'));
    Executor::setEnabledModules($CONFIG->get(SCOPE_APPICATION, 'application', '_enabled_modules'));
    require(Hydrogen\Load\Loader::getInstance()->getAbsPath(APPLICATION_PATH.'/config/route.php'));

    /*$router->addRule(new RulePostfix('.json', array(
        'header' => array(
            HTTP_HEADER_CONTENT_TYPE => 'application/json',
        ),
        'param' => array(
            'type' => 'JSON',
        ),
    )));

    $router->addRule(new RuleFixed('/simple/master', array(
        'module' => '',
        'ctrl' => 'branch',
        'act' => 'master'
    )));*/

    /*$router->addRule(new RuleParam('/simple/:id', array(
        'ctrl' => 'branch',
        'act' => 'master',
        'param' => array(
            'cd' => 1
        )
    )));*/


//    var_dump($router->_rules);exit;

	$application = new Hydrogen\Application\Application();
	$application->run();
}