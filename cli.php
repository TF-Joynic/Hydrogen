<?php

/**
 * Hydrogen Command Line Interface
 */

ini_set('memory_limit', '170M');

if (!function_exists('DOPRINT')) {
    function DOPRINT($str, $withDate = false) {
        $date = $withDate ? '['.date('Y-m-d H:i:s O').']' : '';
        $tpl = '# '.$date.' %s '.PHP_EOL;

        if (is_array($str)) {
            if (!$str) {
                echo '-'.PHP_EOL;
                return;
            }

            foreach ($str as $line) {
                if (is_array($line)) {
                    DOPRINT($line, $withDate);
                } else {
                    echo sprintf($tpl, $line);
                }
            }
        } else {
            echo sprintf($tpl, $str);
        }
    }
}

$argc --;
var_dump($argc);
array_shift($argv);
var_dump($argv);

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

include LIB_PATH.'/Hydrogen/Load/Autoloader.php';

$autoloader = Hydrogen\Load\Autoloader::getInstance();
$autoloader->attachNamespace('application', $base);
$autoloader->attachCallback(
    array(
        Hydrogen\Load\Autoloader::CALLBACK_NS2PATH
    )
);


static $_command_separatar = '::', $_baseNamespace = 'application\\console', $_cslPostFix = 'Csl', $_actionPostFix = 'Act';

global $consoleHelper;

/**
 * @return Hydrogen\Console\ConsoleHelper|null
 */
function instantiateConsoleHelper() {
    global $consoleHelper;
    if (null != $consoleHelper && $consoleHelper instanceof Hydrogen\Console\ConsoleHelper) {
        return $consoleHelper;
    }

    global $_baseNamespace;
    $consoleHelper = new Hydrogen\Console\ConsoleHelper($_baseNamespace, APPLICATION_PATH.'/console');
    return $consoleHelper;
}

if (!isset($argv[0]) || !$argv[0]) {
    $console = $consoleHelper;
    $callableModules = $console->getCallableModules();
    DOPRINT('Invalid command ! Callable module list: ##');
    DOPRINT($callableModules);exit(1);
}

$pathSymbol = trim($argv[0], $_command_separatar." \n\r\0\x0B\t");

if (false === strpos($pathSymbol, $_command_separatar)) {
    DOPRINT(' Specified Path is invalid! ');
    DOPRINT('   example: Test::Abc::foo -p1=123 -p2=Jerry ');
    exit(1);
}

$pathArr = explode($_command_separatar, $pathSymbol);

$module = $class = $action = '';
if ($pathArr) {
    $pathArr = array_slice($pathArr, 0, 3);

    $i = 0;
    while ($tmp = array_pop($pathArr)) {
        if (0 == $i) {
            $action = $tmp;
        } elseif (1 == $i) {
            $class = $tmp;
        } else {
            $module = $tmp;
        }

        $i ++;
    }
}

echo $module.'::'.$class.'::'.$action.PHP_EOL;

// route! start:
$className = $_baseNamespace.NAMESPACE_SEPARATOR
    .trim($module.NAMESPACE_SEPARATOR.$class.$_cslPostFix.NAMESPACE_SEPARATOR, NAMESPACE_SEPARATOR);

echo 'app_path: '.APPLICATION_PATH.PHP_EOL;
echo 'className: '.$className.PHP_EOL;

if (!class_exists($className, true)) {
    DOPRINT('Could not find the command script file');
    DOPRINT('  Existing command class: ');
    exit(1);
}

$EXE = new $className;
## method
$expectedMethodName = $action.$_actionPostFix;
if (!method_exists($EXE, $expectedMethodName)) {
    DOPRINT('Act: '.$action.' doesn\'t exist! in Class: '.$className);
    DOPRINT('# Methods list: ');

    $consoleHelper = instantiateConsoleHelper();
    $methods = $consoleHelper->getCallableMethods($EXE, \ReflectionMethod::IS_PUBLIC);
    $callableMethods = array();
    foreach ($methods as $method) {
        $tmpMethodName = $method->name;
        if (strrchr($tmpMethodName, $_actionPostFix) === $_actionPostFix) {
            $callableMethods[] = $tmpMethodName;
        }
    }

    DOPRINT($callableMethods);
    exit(1);
}

## arguments
## -args Terry,1223,false
$requireMethodArgs = array();
/*
foreach () {

}
if () {

}*/
$consoleHelper = instantiateConsoleHelper();
$callableParams = $consoleHelper->extractCallableParams($argv);
call_user_func_array(array($EXE, $expectedMethodName), $callableParams);
exit;


/*$reflectionClass = new ReflectionClass($className);

$methodList = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

DOPRINT('callable method list:');
$callableMethods = array();

foreach ($methodList as $method) {

    $callableMethods[] =
        $method->getName();

}*/


exit;

$reflectionMethod = $reflectionClass->getMethod($action);
var_dump($reflectionMethod);