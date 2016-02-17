<?php

use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;

$router = Router::getInstance();

$router->addRule(new RuleFixed('/simple/master', array(
    'module' => '',
    'ctrl' => 'branch',
    'act' => 'master'
)));