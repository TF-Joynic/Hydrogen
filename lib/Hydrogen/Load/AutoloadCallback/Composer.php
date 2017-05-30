<?php

namespace Hydrogen\Load\AutoloadCallback;


class Composer extends AbstractAutoloadCallback
{
    protected function resolveClassPath($className)
    {
        if ($classLoadMapPath = parent::resolveClassPath($className)) {
            return $classLoadMapPath;
        }

        $className = $this->namespaceSep2DirSep($className);

        $class_name_info = pathinfo($className);
        $base_class_name = $class_name_info['basename'];
        $namespace = $class_name_info['dirname'];

        return $namespace.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$base_class_name.'.php';
    }
}