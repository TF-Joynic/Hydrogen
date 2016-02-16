<?php

\Hydrogen\Load\Loader::getInstance()->import('application/module/ModuleInit.php');

$EXE = \Hydrogen\Application\Execute\Executor::getInstance();

$baseModuleNS = 'application\\module\\front';

foreach (array('ctrl') as $registry) {
    $EXE->setNamespaceDir($baseModuleNS.'\\'.$registry, APPLICATION_PATH.'/module/front/'.$registry, true);
}
