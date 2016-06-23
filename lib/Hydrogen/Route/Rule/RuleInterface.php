<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;

interface RuleInterface
{
    public function setCallback(\Closure $callback);

    /**
     * @return \Closure
     */
    public function getCallback();

    public function performCallback();

    /**
     * @param $path
     * @param Request $request
     * @param Response $response
     * @return bool|\Closure
     */
    public function apply(&$path, Request &$request, Response &$response);

    public function fmtRuleStr($ruleStr);

    public function isTerminable();
}