<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Http\Exception\InvalidArgumentException;

class RuleFixed extends AbstractRule
{
    public function __construct($ruleStr, array $context)
    {
        if (0 == strlen($ruleStr)) {
            throw new InvalidArgumentException('rule str must not be empty');
        }

        $this->_ruleStr = $ruleStr;
        $this->_ruleContext = $context;
    }

    public function apply($path)
    {
        if (!is_string($path) || 0 == strlen($path)) {
            throw new InvalidArgumentException('path must be type string and can not be empty!');
        }
    }
}