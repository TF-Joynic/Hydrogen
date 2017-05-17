<?php

namespace Hydrogen\Load;

class Loader
{
    private static $_loadedFile = array();

    /**
     * check whether one class is loaded already.
     *
     * @param  string  $className
     * @return boolean
     */
    public static function isLoaded($className)
    {
        return class_exists($className, false);
    }

    public static function getLoadedFile()
    {
        return self::$_loadedFile;
    }

    public static function _load($path, $force = false, $returnContent = false)
    {
        if (($force || self::checkLoadingPermission($path))
            && (false !== $realPath = stream_resolve_include_path($path))) {

            if (in_array($realPath, self::$_loadedFile)) {
                return true;
            }

            // if the class file do exist, we need to include it.
            /** @noinspection PhpIncludeInspection */
            $content = require($realPath);
            self::$_loadedFile[] = $realPath;
            return $returnContent ? $content : true;
        }

        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    public static function checkLoadingPermission($path)
    {
        // class or file beneath vendor  is always has permission to include
        if (0 === strpos($path, VENDOR_PATH)) {
            return true;
        }

        $pathInfo = pathinfo($path);

        $firstCharAscii = ord($pathInfo['filename'][0]);
        if ('php' == $pathInfo['extension']
            && 97 <= $firstCharAscii && $firstCharAscii <= 122) {

            $debug_backtrace = debug_backtrace();
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

    public static function getAbsPath($filePath)
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
     * @param bool $force ignore same dir
     * @param bool $returnContent
     * @return bool
     * @throws \Exception
     */
    public static function import($filePath, $force = false, $returnContent = false)
    {
        return self::_load(self::getAbsPath($filePath), $force, $returnContent);
    }

}