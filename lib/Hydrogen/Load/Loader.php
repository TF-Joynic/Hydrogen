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
        /*if (false !== strpos($path, 'Publish')) {
            echo $path;exit;
        }*/
        if ('WINNT' == PHP_OS) {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        }

        $pathInfo = pathinfo($path);
        $debug_backtrace = debug_backtrace();
        $firstCharAscii = ord($pathInfo['filename'][0]);
        if ('php' == $pathInfo['extension']
            && 97 <= $firstCharAscii && $firstCharAscii <= 122) {
            // this is a private class (file)
            // shall be visible to the parent dir only

            $invokeSourceFile = '';

//            print_r($debug_backtrace);exit;
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
                /*throw new FileUnaccessibleException(
                    'Specified file |'.$path.
                    '| is invisible to dir :||'.$invokeSourceFileInfo['dirname'].'|'
                );*/

            }
        }

        if (false !== $realPath = stream_resolve_include_path($path)) {

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
     * include file manually
     *
     * @param  string $filePath
     * @return bool
     * @throws Exception\FileUnaccessibleException
     * @throws \Exception
     */
    public function import($filePath)
    {
        if (!$filePath || !is_string($filePath)) {
            return false;
        }

        $filePath = trim($filePath);

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

        return self::_load($filePath);
    }

    private function __clone()
    {}
}