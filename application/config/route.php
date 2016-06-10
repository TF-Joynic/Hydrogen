<?php

use Hydrogen\Route\Router;
use Hydrogen\Route\Rule\RuleFixed;
use Hydrogen\Route\Rule\RuleParam;
use Hydrogen\Route\Rule\RulePostfix;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;

$router = Router::getInstance();
/*$router->addRule(new RuleFixed('/simple/master'), function(Request $request,Response $response) {
});*/

$router->addRule(new RuleParam('/simple/:id'), function(Request $request,Response $response) {
    $request->setContextAttr(CTRL, 'branch')->setContextAttr(ACT, 'master');
    $request->withAttribute("cd", 1);

    echo 'llll';
    $response->withStatus("101");
});