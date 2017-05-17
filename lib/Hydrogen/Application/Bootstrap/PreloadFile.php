<?php

namespace Hydrogen\Application\Bootstrap;


use Hydrogen\Application\ApplicationContext;
use Hydrogen\Load\Loader;

class PreloadFile extends Decorator
{
    public function doBootstrap()
    {
        $this->_bootstrap->doBootstrap();

        global $classLoadConfigArr;
        if (isset($classLoadConfigArr[PRELOADFILES]) && $classLoadConfigArr[PRELOADFILES]) {
            foreach ($classLoadConfigArr[PRELOADFILES] as $preloadFile) {
                Loader::import($preloadFile, true);
            }
        }
    }

}