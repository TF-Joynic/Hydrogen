<?php


use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;

$router = Router::getInstance();

$router->addRule(new RuleFixed('/simple/master', array(
    'module' => '',
    'ctrl' => 'branch',
    'act' => 'master'
)));

$router->addRule(new RuleParam('/simple/:id', array(
    'ctrl' => 'branch',
    'act' => 'master',
    'param' => array(
        'cd' => 1
    )
)));