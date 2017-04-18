<?php

use Hydrogen\Application\ApplicationContext;

\Hydrogen\Load\Loader::getInstance()->import('application/module/ModuleInit.php');

$baseModuleNS = 'application\\module\\front';
ApplicationContext::setNamespaceDir($baseModuleNS, APPLICATION_PATH.'/module/front');
