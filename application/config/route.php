<?php

use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;
use Hydrogen\Route\Rule\RulePostfix;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;

$router = Router::getInstance();

$rulePostfixUserJson = new RulePostfix('.js');
$router->addRule($rulePostfixUserJson, function (Request $request, Response $response) {
//    $request->setContextAttr(CTRL, 'branch')->setContextAttr(ACT, 'master');
});

$router->addRule(new RuleFixed('/simple/master'), function(Request $request,Response $response) {
    $request->setContextAttr(CTRL, 'branch')->setContextAttr(ACT, 'master');
});

$ruleParamSimpleId = new RuleParam('/simple/'.RuleParam::RULE_PARAM_PREFIX.'id');
$router->addRule($ruleParamSimpleId, function(Request $request,Response $response) {
    $request->setContextAttr(CTRL, 'branch')->setContextAttr(ACT, 'master');
    $request->withAttribute("cd", 1);
});

