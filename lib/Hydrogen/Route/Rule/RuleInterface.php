<?php

namespace Hydrogen\Route\Rule;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return bool|\Closure
     */
    public function apply(&$path, ServerRequestInterface &$request, ResponseInterface &$response);

    public function fmtRuleStr($ruleStr);

    public function isTerminable();
}