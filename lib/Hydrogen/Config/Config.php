<?php

namespace Hydrogen\Config;

use Hydrogen\Config\Exception\ReaderClassNotDefinedException;
use Hydrogen\Config\Exception\FileNotFoundException;
use Hydrogen\Config\Reader\ReaderInterface;

class Config
{
	private $_configArr = array();

	private $_scopes = array(); // config array scopes

	private static $_instance = null;
	private static $_merge_count = 0;

	private function __construct()
	{}

	public static function getInstance()
	{
		if (null == self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Merge new config array data with current config array data
	 *
	 * Config key name started with '_' is not allowed to be override.
	 *
	 * @param array $newConfigArr
	 * @param string $scope optional ''
	 * @return boolean
	 */
	public function mergeConfigArr($newConfigArr, $scope = '')
	{	
		$currentConfigArr = $op_source = array();
		$scope = trim($scope);

		$mergingScope = '';
		if (is_string($scope) && 0 < strlen($scope)) {

			if (!isset($this->_configArr[$scope])) {
				$this->_configArr[$scope] = array();
			}
			$currentConfigArr = $this->_configArr[$scope];
			$op_source = &$this->_configArr[$scope];

			$mergingScope = $scope;
		} else {
			$currentConfigArr = $this->_configArr;
			$op_source = &$this->_configArr;
		}

		if (!is_array($newConfigArr)) {
			$newConfigArr = array($newConfigArr);
		}

		try {
			if (self::$_merge_count >= 1) {
				// clear keys in newConfigArr that starts with '_'
				$this->_nestUnsertPrivateKey($newConfigArr);
			}
			
			// then we merge 'em!
			$configArr = array_replace_recursive($currentConfigArr, $newConfigArr);
			unset(
				$currentConfigArr,
				$newConfigArr
			);

			self::$_merge_count ++;
			$op_source = $configArr;

			if ('' !== $mergingScope 
				&& !in_array($mergingScope, $this->_scopes)) {

				$this->_scopes[] = $mergingScope;

			}

            return true;
		} catch (\Exception $e) {
            return false;
		}
	}

	/**
	 * parse file[$filePath] and merge the data into
	 *  current config array
	 *
	 * @param  string $filePath absolute path of the new config file
	 * @return bool
	 */
	public function mergeConfigFile($filePath)
	{
		if ($filePath && is_string($filePath)
		 && file_exists($filePath)) {

		 	$reader = $this->concreteReader($filePath);
		 
		 	$newConfigArr = $reader->read();
		 	if (is_array($newConfigArr)) {
		 		$basename = '';
		 		if (false !== $ext = strrchr($filePath, '.')) {
		 			$basename = basename($filePath, $ext);
		 		}
		 		return $this->mergeConfigArr($newConfigArr, $basename);
		 	}

		}

        return false;
	}

    /**
     * concrete the Reader class
     *
     * @param  string $file file abs path
     * @return \Hydrogen\Config\Reader\ReaderInterface
     * @throws ReaderClassNotDefinedException
     * @throws \Exception
     */
    protected function concreteReader($file)
    {
        $ext = '';
        if (!is_string($file) || empty($file)
            || false === $ext = strrchr($file, '.')) {

            throw new \UnexpectedValueException('file path must be string with ext.');

        }

        if (!file_exists($file)) {
            throw new FileNotFoundException('file: '.$file.' dose not exist!');
        }

        $ext = substr($ext, 1);
        $readerCls = ucfirst(strtolower($ext));
        $readerCls = 'Hydrogen\\Config\\Reader\\'.$readerCls;
        if (!class_exists($readerCls)) {

            throw new ReaderClassNotDefinedException('class: '.$readerCls.
                ' not found.');

        }

        $readerInstance = new $readerCls($file);
        if (! $readerInstance instanceof ReaderInterface) {

            throw new \Exception('class '. $readerCls. 'is not a impl
				of ReaderInterface');

        }
        return $readerInstance;
    }

	/**
	 * unset keys that starts with '_' recursively
	 * 
	 * @param  array &$array 
	 * @return void
	 */
	private function _nestUnsertPrivateKey(&$array)
	{
		foreach ($array as $k => &$v) {
			if (0 === strpos($k, '_')) {
				// not allow overwrite
				unset($array[$k]);
			} elseif (is_array($v)) {
				$this->_nestUnsertPrivateKey($v);
			}
		}
	}

	/**
	 * return current config array scopes(keys)
	 * 
	 * @return array 
	 */
	public function getScopes()
	{
		return $this->_scopes;
	}

	public function __set($name, $value) 
	{}

	public function __get($name)
	{}

	/**
	 * get config key value
	 * 
	 * @param  string $scope         config scope
	 * @param  string $section       config file section
	 * @param  string $key           config key
	 * @param  mixed $default_value optional null
	 * @return mixed specified config key value       
	 */
	public function get($scope, $section, $key, $default_value = null)
	{

		return isset($this->_configArr[$scope][$section][$key])
		 ? $this->_configArr[$scope][$section][$key]
		 : $default_value;

	}

    /**
     * get config scope data
     *
     * @param $scope
     * @return mixed the whole scope config data
     * (usually return as an array)
     */
	public function getScope($scope)
	{

		return isset($this->_configArr[$scope])
		 ? $this->_configArr[$scope]
		 : array();
		 
	}

	public static function setScopeArr($scopeArr)
	{
		if (!$scopeArr) {
			return ;
		}

		if (!is_array($scopeArr)) {
			if (is_string($scopeArr)) {
				$scopeArr = array($scopeArr);
			}
		} else {
			$scopeArr = array_values($scopeArr);
			$scopeArr = array_filter($scopeArr, function ($val) {
				return is_string($val) ? true : false;
			});
		}


	}
	
	public function dump()
	{
		return $this->_configArr;
	}

	public function __toString()
	{
		return ''; 
	}

	private function __clone()
	{}
}