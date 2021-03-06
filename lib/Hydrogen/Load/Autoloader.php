<?php

namespace Hydrogen\Load;

require __DIR__.'/AbstractAutoLoader.php';


class Autoloader extends AbstractAutoLoader
{
	const CALLBACK_NS2PATH = 'Namespace2path';
    const CALLBACK_PSR = 'Psr';
    const CALLBACK_THRIFTCLIENT = 'ThriftClient';

	private static $_instance = null;

    private $_classLoadMap = array();

	private function __construct()
	{}

	public static function getInstance()
	{
		if (null === self::$_instance)
			self::$_instance = new self();

		return self::$_instance;
	}

    public function setClassLoadMap($loadMap)
    {
        $this->_classLoadMap = $loadMap;
    }

    public function getClassLoadMap()
    {
        return $this->_classLoadMap;
    }

    public function attachNamespace($namespace, $dir, $prepend = false)
    {
        if ($namespace && is_string($namespace) && $dir && is_string($dir)) {

            $dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $dir);

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

    /**
     * @param $callbackClassNames
     */
    public function attachCallback($callbackClassNames)
	{
        if (!$callbackClassNames) {
            return ;
        }

		if (!is_array($callbackClassNames)) {
			$callbackClassNames = array($callbackClassNames);
		}

		$callbackClassPath = $this->getCallbackClassPath();

        require $callbackClassPath.'/AbstractAutoloadCallback.php';

		foreach ($callbackClassNames as $key => $callbackClassName) {

			if (!isset($this->_autoloadCallbacks[$callbackClassName])) {

				require $callbackClassPath.DIRECTORY_SEPARATOR
				.$callbackClassName.'.php';

				$callbackNsClass = 'Hydrogen\\Load\\AutoloadCallback\\'.$callbackClassName;

				$callbackClassInstance = new $callbackNsClass();
                $callbackClassInstance->registerCallback();
				$this->_autoloadCallbacks[$callbackClassName] = $callbackClassInstance;
			}
		}

	}

	public function detachCallback($callbackClassName)
	{
        if (!$this->_autoloadCallbacks) {
            return false;
        }

        if (isset($this->_autoloadCallbacks[$callbackClassName])) {
            $fallback = 1 == count($this->_autoloadCallbacks);
            $callbackClassInstance = $this->_autoloadCallbacks[$callbackClassName];
            if ($callbackClassInstance->unregisterCallback($fallback)) {
                unset($this->_autoloadCallbacks[$callbackClassName]);
                return true;
            }
        }

		return false;
	}

	public function getComposerAutoloadClassMap()
    {
        $composerAutoloadFile = VENDOR_PATH.DIRECTORY_SEPARATOR.COMPOSER
            .DIRECTORY_SEPARATOR.COMPOSER_AUTOLOAD_CLASS_MAP_FILENAME;

        if (is_readable($composerAutoloadFile)) {
            /** @noinspection PhpIncludeInspection */
            return require($composerAutoloadFile);
        }

        return array();
    }

	private function __clone()
	{}
}