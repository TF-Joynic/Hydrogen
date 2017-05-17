<?php

namespace Hydrogen\Load\AutoloadCallback;


class ThriftClient extends AbstractAutoloadCallback
{
    protected function resolveClassPath($className)
    {
        if ($classLoadMapPath = parent::resolveClassPath($className)) {
            return $classLoadMapPath;
        }

        $classPath = $this->namespaceSep2DirSep($className);
        if (false !== stripos($classPath, 'thrift')) {
            $classPath = str_replace('Client', '', $classPath);
        }

        return $classPath.'.php';
    }
}