<?php

namespace Hydrogen\Route\Rule;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RuleVersion extends AbstractRule
{
    public function route()
    {

    }

    public function __construct($ruleStr)
    {
        parent::__construct($ruleStr);
    }

    /**
     * @param $path
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return bool|\Closure
     */
    public function apply(&$path, ServerRequestInterface &$request, ResponseInterface &$response)
    {
        // TODO: Implement apply() method.
    }
}