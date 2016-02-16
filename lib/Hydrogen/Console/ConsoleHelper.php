<?php

namespace Hydrogen\Console;

//use

use Hydrogen\Load\Autoloader;
use Hydrogen\Load\Loader;
use Hydrogen\Console\Exception\ClassNotDefinedException;

class ConsoleHelper
{
    public $path = '';
    public $baseNamespace = '';
    public static $_callable_param_prefix = '--';

    public function __construct($namespace, $console_path)
    {
        $this->baseNamespace = $namespace;
        $this->path = $console_path;
    }

    /**
     * get callable module list
     *
     * @return array
     */
    public function getCallableModules()
    {
        $dirIterator = new \RecursiveDirectoryIterator($this->path,
            \FilesystemIterator::NEW_CURRENT_AND_KEY|\FilesystemIterator::SKIP_DOTS);

        $modules = array();

        foreach ($dirIterator as $k => $v) {

            $doVotedModule = false;

            if ($dirIterator->hasChildren()) {
                foreach ($dirIterator->getChildren() as $key => $value) {
                    $entension = $value->getExtension();
                    if (!$entension || 'php' !== $entension) {
                        continue;
                    }

                    $fileBasename = $value->getBasename('.php');

                    $module_to_be = $v->getBasename();

                    $expectedClassName = $this->baseNamespace.NAMESPACE_SEPARATOR
                        .$module_to_be.NAMESPACE_SEPARATOR.$fileBasename;

                    Loader::getInstance()->import($value->__toString());
                    if (!class_exists($expectedClassName, false)) { // not a standard class file!
                        continue;
                    }

                    if (!$doVotedModule) {
                        $modules[] = $module_to_be;
                        $doVotedModule = true;
                    }
                }
            }

        }

        return $modules;
    }

    /**
     * get callable methods within the classObj
     *
     * @param string|object $classObj
     * @param int $filter
     * @param bool $filterOwn
     * @return array array of \ReflectionMethod
     */
    public function getCallableMethods($classObj, $filter, $filterOwn = true)
    {
        if (is_string($classObj) && !class_exists($classObj, false))
            throw new ClassNotDefinedException('Class name: '.$classObj.' not defined yet!');

        $className = $classObj;
        if (is_object($classObj))
            $className = get_class($classObj);

        $reflectClass = new \ReflectionClass($classObj);
        $methods = array();
        if (!$reflectClass->isUserDefined() || !$reflectClass->isInstantiable())
            return $methods;

        $methods = $reflectClass->getMethods($filter);
        if ($filterOwn) {
            $needNeaten = false;
            foreach ($methods as $k => $method) {
                if ($method->class != $className) {
                    unset($methods[$k]);
                    !$needNeaten && $needNeaten = true;
                }
            }

            $needNeaten && $methods = array_values($methods);
        }

        return $methods;
    }

    /**
     * @param string|object $classObj
     * @param string $methodName
     * @return boolean|int
     */
    public function getRequiredMethodArgsCount($classObj, $methodName)
    {
        $reflectClass = new \ReflectionClass($classObj);

        try {
            $reflectMethod = $reflectClass->getMethod($methodName);
            return $reflectMethod->getNumberOfRequiredParameters();
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * @param string|object $classObj
     * @param string $methodName
     * @return boolean|array
     */
    public function getRequiredMethodArgs($classObj, $methodName)
    {
        $reflectClass = new \ReflectionClass($classObj);

        $requiredMethodsArgs = array();
        try {
            $reflectParams = $reflectClass->getMethod($methodName)->getParameters();
            foreach ($reflectParams as $reflectParam) {
                /*if ($reflectParam->is) {

                }*/
//                $requiredMethodsArgs =
            }
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * extra param for calling user function
     *
     * @param $argvs
     * @return array
     */
    public function extractCallableParams($argvs)
    {
        if (!is_array($argvs)) {
            $argvs = array($argvs);
        }

        $params = array();
        $cursor = 0;
        foreach ($argvs as $argPos => $argv) {
            if (0 === strpos($argv, self::$_callable_param_prefix)) {
                $pair = str_replace(self::$_callable_param_prefix, '', $argv);
                if (isset($argvs[$argPos + 1])) {
                    $params[$pair] = $argvs[$argPos + 1];
                } else {
                    $params[$pair] = null;
                }

                $cursor = $argPos + 1;

                if ($argPos <= $cursor) {
                    continue;
                }
            }
        }

        return $params;
    }
}