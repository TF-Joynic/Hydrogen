<?php

namespace Hydrogen\Route\Rule;


use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool|\Closure
     */
    public function apply(&$path, RequestInterface $request, ResponseInterface $response);

    public function fmtRuleStr($ruleStr);

    public function isTerminable();
}