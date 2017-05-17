<?php

namespace Hydrogen\Load\AutoloadCallback;


class Namespace2Path extends AbstractAutoloadCallback
{
    protected function resolveClassPath($className)
    {
        if ($classLoadMapPath = parent::resolveClassPath($className)) {
            return $classLoadMapPath;
        }

        return $this->namespaceSep2DirSep($className).'.php';
    }
}