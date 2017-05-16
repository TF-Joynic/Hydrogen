<?php

namespace Hydrogen\Load\AutoloadCallback;

include __DIR__.DIRECTORY_SEPARATOR.'/AutoloadCallbackInterface.php';

abstract class AbstractAutoloadCallback implements AutoloadCallbackInterface
{
	const NAMESPACE_SEPARATOR = '\\';

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
}