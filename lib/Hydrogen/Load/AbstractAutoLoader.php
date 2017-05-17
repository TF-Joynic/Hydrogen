<?php

namespace Hydrogen\Load;

// use Hydrogen\Load;
include __DIR__.'/Loader.php';

abstract class AbstractAutoLoader
{
    protected $_namespaces = array();

	protected $_autoloadCallbacks = array();

    protected abstract function attachNamespace($namespace, $dir, $prepend = false);

    protected abstract function detachNamespace($namespace);

	public function getRegisteredCallbacks()
    {
        return spl_autoload_functions();
//		return $this->_autoloadCallbacks;
	}

    protected abstract function attachCallback($callbackClassNames);

	protected abstract function detachCallback($callbackClassName);

	protected function getCallbackClassPath()
	{
		return __DIR__.DIRECTORY_SEPARATOR.'AutoloadCallback';
	}


	/**
	 * include a class by specifying its path, and append to loaded
	 * 
	 * @param  string $classPath classPath: 'Hydrogen/Log/Logger.php' for instance.
     * @return boolean
	 */
	public function loadClass($classPath)
	{
        if (!$classPath) {
            return ;
        }

		$classPath = ltrim($classPath, '/\\');

		// if the class path start with 'Hydrogen', goto lib
		if ($fullPath = Loader::getAbsPath($classPath)) {
			if ($this->_doLoad($fullPath)) {
				return ;
			}
		}

        if ($this->_namespaces) {
            // attempt to load registered namespaces
            foreach ($this->_namespaces as $k => $ns_dir) {
                foreach ($ns_dir as $ns => $dir) {
                    $dir = rtrim($dir, '/\\');

                    if (0 === strpos($classPath, str_replace('\\', DIRECTORY_SEPARATOR, $ns))) {
                        if ($this->_doLoad(rtrim($dir, $ns).$classPath))
                            return ;
                        else
                            continue;

                    }
                }
            }
        }

        return ;
	}

    public function getNamespaces()
    {
        return $this->_namespaces;
    }

	private function _doLoad($path)
	{
		return Loader::_load($path);
	}
}