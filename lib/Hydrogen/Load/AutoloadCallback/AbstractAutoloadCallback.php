<?php

namespace Hydrogen\Load\AutoloadCallback;

include __DIR__.DIRECTORY_SEPARATOR.'/AutoloadCallbackInterface.php';

abstract class AbstractAutoloadCallback implements AutoloadCallbackInterface
{
	const NAMESPACE_SEPARATOR = '\\';

	public function registerCallback()
	{
		spl_autoload_register(array($this, 'autoLoad'), true, true);
	}
}