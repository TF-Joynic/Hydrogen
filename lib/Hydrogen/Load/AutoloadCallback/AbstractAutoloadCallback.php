<?php

namespace Hydrogen\Load\AutoloadCallback;

use Hydrogen\Load\Autoloader;

include __DIR__.DIRECTORY_SEPARATOR.'AutoloadCallbackInterface.php';

abstract class AbstractAutoloadCallback implements AutoloadCallbackInterface
{
    const NAMESPACE_SEPARATOR = '\\';

    public function autoLoad($className)
    {
        Autoloader::getInstance()->loadClass($this->resolveClassPath($className));
    }

    /**
     * @param $className
     * @return string|bool string when success, false on failed to resolve
     */
    protected function resolveClassPath($className)
    {
        $classLoadMap = Autoloader::getInstance()->getClassLoadMap();
        if ($className && isset($classLoadMap[$className]) && $classLoadMap) {

            return $classLoadMap[$className];

        }

        return false;
    }

	public function registerCallback()
	{
        // bool spl_autoload_register ([ callable $autoload_function [, bool $throw = true [, bool $prepend = false ]]] )
		return spl_autoload_register(array($this, 'autoLoad'), true, true);
	}

    /**
     * @param bool|false $fallback fallback to __autoload() function
     * @return mixed
     */
    public function unregisterCallback($fallback = false)
    {
        $unregisterOp = spl_autoload_unregister(array($this, 'autoLoad'));

        $fallbackAutoloadCallbackFunctionName = '__autoload';
        if ($fallback && function_exists($fallbackAutoloadCallbackFunctionName)) {
            spl_autoload_register($fallbackAutoloadCallbackFunctionName, true);
        }

        return $unregisterOp;
    }

    public static function namespaceSep2DirSep($str)
    {
        if (!$str || self::NAMESPACE_SEPARATOR == DIRECTORY_SEPARATOR) {
            return $str;
        }

        return str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $str);
    }
}