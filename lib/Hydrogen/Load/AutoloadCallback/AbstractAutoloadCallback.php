<?php

namespace Hydrogen\Load\AutoloadCallback;

include __DIR__.DIRECTORY_SEPARATOR.'/AutoloadCallbackInterface.php';

abstract class AbstractAutoloadCallback implements AutoloadCallbackInterface
{
	const NAMESPACE_SEPARATOR = '\\';

	public function registerCallback()
	{
        // bool spl_autoload_register ([ callable $autoload_function [, bool $throw = true [, bool $prepend = false ]]] )
		spl_autoload_register(array($this, 'autoLoad'), false, true);
	}
}