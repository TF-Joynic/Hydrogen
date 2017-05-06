<?php

use Hydrogen\Application\ApplicationContext;

\Hydrogen\Load\Loader::getInstance()->import('application/module/ModuleInit.php');

$moduleBaseNS = 'application\\module\\admin';

foreach (array('ctrl', 'model', 'view', 'src', 'plugin') as $registry) {
    ApplicationContext::setNamespaceDir($moduleBaseNS.'\\'.$registry, APPLICATION_PATH.'/module/admin/'.$registry, true);
}