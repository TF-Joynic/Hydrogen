<?php

use Hydrogen\Application\ApplicationContext;

\Hydrogen\Load\Loader::import('application/module/ModuleInit.php');

$baseModuleNS = 'application\\module\\front';
ApplicationContext::setNamespaceDir($baseModuleNS, APPLICATION_PATH.'/module/front');

$thriftNamespace = 'Thrift';
ApplicationContext::setNamespaceDir($thriftNamespace, VENDOR_PATH.DIRECTORY_SEPARATOR.'Thrift');