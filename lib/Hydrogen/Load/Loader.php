<?php

namespace Hydrogen\Load;

use Hydrogen\Load\Exception\FileUnaccessibleException;

class Loader
{
    private $_loadedFile = array();
    public static $_instance = null;

    private function __construct()
    {}

    public static function getInstance()
    {
        if (null === self::$_instance)
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * check whether one class is loaded already.
     *
     * @param  string  $className
     * @return boolean
     */
    public function isLoaded($className)
    {
        return class_exists($className, false);
    }

    public function getLoadedFile()
    {
        return $this->_loadedFile;
    }

    public function _load($path)
    {
        if ($this->checkLoadingPermission($path) && (false !== $realPath = stream_resolve_include_path($path))) {
            if (in_array($realPath, $this->_loadedFile)) {
                return true;
            }

            // if the class file do exist, we need to include it.
            /** @noinspection PhpIncludeInspection */
            if (include($realPath)) {
                $this->_loadedFile[] = $realPath;
                return true;
            }
        }

        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    public function checkLoadingPermission($path)
    {
        // class or file beneath vendor  is always has permission to include
        if (0 === strpos($path, VENDOR_PATH)) {
            return true;
        }

        $pathInfo = pathinfo($path);

        $debug_backtrace = debug_backtrace();
        $firstCharAscii = ord($pathInfo['filename'][0]);
        if ('php' == $pathInfo['extension']
            && 97 <= $firstCharAscii && $firstCharAscii <= 122) {
            // this is a private class (file)
            // shall be visible to the parent dir only

            $invokeSourceFile = '';

            foreach ($debug_backtrace as $key => $trace) {
                if ('spl_autoload_call' == $trace['function']
                    || (__CLASS__ == $trace['class'] && 'import' == $trace['function'])) {

                    $invokeSourceFile = $trace['file'];
                    break;

                }
            }
            $invokeSourceFileInfo = pathinfo($invokeSourceFile);

            if ($pathInfo['dirname'] &&
                $pathInfo['dirname'] !== $invokeSourceFileInfo['dirname']) {

                return false;

            }
        }

        return true;
    }

    public function getAbsPath($filePath)
    {
        if (!$filePath || !is_string($filePath)) {
            return false;
        }

        if (file_exists($filePath)) {
            return $filePath;
        }

        $filePath = trim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $filePath));

        if (!defined('APPLICATION_PATH') || !defined('VENDOR_PATH')
            || !defined('LIB_PATH')) {

            throw new \Exception('*_PATH constant is not defined completely');

        }

        $search = array(
            'application',
            'vendor',
            'lib'
        );

        foreach ($search as $key => $value) {

            if (0 === strpos($filePath, $value)
                && $filePath = substr_replace($filePath,
                    constant(strtoupper($value).'_PATH'), 0, strlen($value))) {

                break;

            }

        }

        return $filePath;
    }


    /**
     * include file manually
     *
     * @param  string $filePath
     * @return bool
     * @throws Exception\FileUnaccessibleException
     * @throws \Exception
     */
    public function import($filePath)
    {
        return self::_load($this->getAbsPath($filePath));
    }

    private function __clone()
    {}
}