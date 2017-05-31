<?php

namespace Hydrogen\Application\Bootstrap;


use Hydrogen\Application\ApplicationContext;
use Hydrogen\Load\Loader;

class PreloadFile extends Decorator
{
    public function doBootstrap()
    {
        $this->_bootstrap->doBootstrap();

        if ($preloadFiles = Loader::getPreloadFiles()) {
            foreach ($preloadFiles as $preloadFile) {
                Loader::import($preloadFile, true);
            }
        }
    }

}