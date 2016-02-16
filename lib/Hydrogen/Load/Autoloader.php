<?php

namespace Hydrogen\Load;

include __DIR__.'/AbstractAutoLoader.php';

use Hydrogen\Load\AutoloadCallback;

class Autoloader extends AbstractAutoLoader
{
	CONST CALLBACK_NS2PATH = 'Namespace2path';

	public static $_instance = null;

	private function __construct()
	{}

	public static function getInstance()
	{
		if (null === self::$_instance)
			self::$_instance = new self();

		return self::$_instance;
	}

    public function attachNamespace($namespace, $dir, $prepend = false)
    {
        if ($namespace && is_string($namespace) && $dir && is_string($dir)) {
            $attachment = array($namespace => $dir);

            $prepend ? array_unshift($this->_namespaces, $attachment)
                : array_push($this->_namespaces, $attachment);

            return true;
        }

        return false;
    }

    public function detachNamespace($namespace)
    {
        if (!$this->_namespaces) {
            return true;
        }

        foreach ($this->_namespaces as $ns => $dir) {
            if ($namespace == $ns) {
                unset($this->_namespaces[$ns]);
                break;
            }
        }

        return true;
    }

	public function attachCallback($callbackClassNames = array(self::CALLBACK_NS2PATH))
	{
		if (!is_array($callbackClassNames)) {
			$callbackClassNames = array($callbackClassNames);
		}

		$callbackClassPath = $this->_getCallbackClassPath();

		foreach ($callbackClassNames as $key => $callbackClassName) {

			if (!isset($this->_autoloadCallbacks[$callbackClassName])) {

				include $callbackClassPath.DIRECTORY_SEPARATOR
				.$callbackClassName.'.php';

				// echo $callbackClassName;exit;

				/*echo $callbackClassPath.DIRECTORY_SEPARATOR
				.$callbackClassName.'.php';exit;*/

				$callbackNsClass = 'Hydrogen\\Load\\AutoloadCallback\\'.$callbackClassName;

				$callbackClass = new $callbackNsClass();
				$callbackClass->registerCallback();
				$this->_autoloadCallbacks[$callbackClassName] = $callbackClass;
			}
		}

	}

	public function detachCallback()
	{
		return false;
	}

	private function __clone()
	{}
}