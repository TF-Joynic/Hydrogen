<?php

\Hydrogen\Load\Loader::getInstance()->import('application/module/ModuleInit.php');

$EXE = \Hydrogen\Application\Execute\Executor::getInstance();
$moduleBaseNS = 'application\\module\\admin';

foreach (array('ctrl', 'model', 'view', 'src', 'plugin') as $registry) {
    $EXE->setNamespaceDir($moduleBaseNS.'\\'.$registry, APPLICATION_PATH.'/module/admin/'.$registry, true);
}