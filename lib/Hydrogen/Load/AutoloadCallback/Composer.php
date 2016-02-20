<?php

namespace Hydrogen\Load\AutoloadCallback;

use Hydrogen\Load\Autoloader;

//include __DIR__.DIRECTORY_SEPARATOR.'/AbstractAutoloadCallback.php';

class Composer extends AbstractAutoloadCallback
{
    public function autoLoad($class_name)
    {
        $class_name_info = pathinfo($class_name);
        $base_class_name = $class_name_info['basename'];
        $namespace = $class_name_info['dirname'];

        if ('Psr\Http\Message\ServerRequestInterface' == $class_name) {
//            echo $namespace.'/src/'.$base_class_name.'.php';exit;
        }

        $classPath = str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $namespace.'/src/'.$base_class_name.'.php');
        Autoloader::getInstance()->loadClass($classPath);
    }
}