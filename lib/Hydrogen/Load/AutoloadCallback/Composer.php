<?php

namespace Hydrogen\Load\AutoloadCallback;

use Hydrogen\Load\Autoloader;

class Composer extends AbstractAutoloadCallback
{
    public function autoLoad($class_name)
    {
        $class_name = str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name);

        $class_name_info = pathinfo($class_name);
        $base_class_name = $class_name_info['basename'];
        $namespace = $class_name_info['dirname'];

        $classPath = $namespace.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$base_class_name.'.php';

        Autoloader::getInstance()->loadClass($classPath);
    }
}