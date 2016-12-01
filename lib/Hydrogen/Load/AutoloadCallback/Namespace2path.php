<?php

namespace Hydrogen\Load\AutoloadCallback;

use Hydrogen\Load\Autoloader;

//include __DIR__.DIRECTORY_SEPARATOR.'/AbstractAutoloadCallback.php';

class Namespace2Path extends AbstractAutoloadCallback
{
	public static function autoLoad($class_name)
	{
		$classPath = str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name.'.php');

        /*if (false !== strpos($classPath, 'View')) {
            echo($classPath)."<br />";exit;
        }*/
		Autoloader::getInstance()->loadClass($classPath);
	}
}