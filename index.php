<?php

use Hydrogen\Debug\Variable;
use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;
use Hydrogen\Route\Rule\RulePostfix;

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

    defined('GLOBAL_CONFIG_PATH')
	 || define('GLOBAL_CONFIG_PATH', $global_config_path);

	require LIB_PATH.'/Hydrogen/Load/Autoloader.php';

	$autoloader = Hydrogen\Load\Autoloader::getInstance();
	$autoloader->attachCallback(
        array(
            Hydrogen\Load\Autoloader::CALLBACK_NS2PATH,
            Hydrogen\Load\Autoloader::CALLBACK_COMPOSER
        )
    );

	$autoloader->attachNamespace('application', APPLICATION_PATH);
	$autoloader->attachNamespace('Psr', LIB_PATH.DIRECTORY_SEPARATOR.'Psr', true);

	// include Framework constant
	Hydrogen\Load\Loader::getInstance()
		->import('lib/Hydrogen/Constant/Http.php');

    Hydrogen\Load\Loader::getInstance()
        ->import('lib/Hydrogen/Include/Functions.php');

	// config
	$EXECUTOR = Hydrogen\Application\Execute\Executor::getInstance();

	$CONFIG = Hydrogen\Config\Config::getInstance();
	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.$EXECUTOR->getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.'application.ini');

	$CONFIG->mergeConfigFile(APPLICATION_PATH.
		DIRECTORY_SEPARATOR.$EXECUTOR->getApplicationConfigDir()
		.DIRECTORY_SEPARATOR.ENV.DIRECTORY_SEPARATOR
		.'application.ini');

    $CONFIG->mergeConfigFile(APPLICATION_PATH.
        DIRECTORY_SEPARATOR.$EXECUTOR->getApplicationConfigDir()
        .DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        .'database.ini');

	// Variable::dump($CONFIG->get('application', 'general', 'application_name', 'cdHyd'));
//	Variable::dump($CONFIG->dump(), true);

    /*$mysql = new Hydrogen\Db\Relational\PDO\Mysql('app_joynic');
	$result = $mysql->query('select * from sms')->fetchAll(\PDO::FETCH_ASSOC);*/

//	var_dump($result);


//    $mysql->get();

	/*$curl = new Hydrogen\Http\Request\Curl('http://www.baidu.com/');
	var_dump($curl);exit;*/

	/*$filter = new Hydrogen\Http\Filter\Filter();
	var_dump($filter->init());exit;*/

/*	$cookie2 = new Hydrogen\Http\Cookie\Cookie('kyo', 'Benimaru Nikaido');
	$cookie2->expire = $_SERVER['REQUEST_TIME'] + 344;

	$response = new Hydrogen\Http\Response\Response();
	$cookies = array(
		$cookie,
		$cookie2
	);
	$response->cookie->add($cookies);
	exit;*/
    $EXECUTOR->setModuleDir(APPLICATION_PATH.'/module');
    $EXECUTOR->setAvailableModules(array('admin'));

//    Hydrogen\Load\Loader::getInstance()->import(APPLICATION_PATH.'/config/route.php');


    $router = Router::getInstance();

    $router->addRule(new RulePostfix('.json', array(
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
    )));

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