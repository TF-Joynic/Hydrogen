<?php

namespace Hydrogen\Route\Rule;


use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool|\Closure
     */
    public function apply(&$path, RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement apply() method.
    }
}