<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Http\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;

class RuleFixed extends AbstractRule
{
    public function __construct($ruleStr)
    {
        if (0 == strlen($ruleStr)) {
            throw new InvalidArgumentException('rule str must not be empty');
        }

        $this->_ruleStr = $this->fmtRuleStr($ruleStr);
    }

    /**
     * @param $path
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Closure|bool
     */
    public function apply(&$path, RequestInterface $request, ResponseInterface $response)
    {
        if (!is_string($path) || 0 == strlen($path)) {
            throw new InvalidArgumentException('path must be type string and can not be empty!');
        }

        if ($path == $this->_ruleStr) {
            $this->performCallback($request, $response);
            return true;
        }

        return false;
    }
}