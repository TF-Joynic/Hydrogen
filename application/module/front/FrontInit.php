<?php

use Hydrogen\Application\Execute\Executor;

\Hydrogen\Load\Loader::getInstance()->import('application/module/ModuleInit.php');

$baseModuleNS = 'application\\module\\front';

foreach (array('ctrl') as $registry) {
    Executor::setNamespaceDir($baseModuleNS.'\\'.$registry, APPLICATION_PATH.'/module/front/'.$registry, true);
}
