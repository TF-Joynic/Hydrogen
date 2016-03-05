<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Http\Exception\InvalidArgumentException;

class RuleFixed extends AbstractRule
{
    public function __construct($ruleStr, array $ruleContext)
    {
        if (0 == strlen($ruleStr)) {
            throw new InvalidArgumentException('rule str must not be empty');
        }

        $this->_ruleStr = $this->fmtRuleStr($ruleStr);
        $this->_ruleContext = $ruleContext;
    }

    /**
     * @param $path
     * @return array|bool
     */
    public function apply(&$path)
    {
        if (!is_string($path) || 0 == strlen($path)) {
            throw new InvalidArgumentException('path must be type string and can not be empty!');
        }

        if ($path == $this->_ruleStr) {
            return $this->_ruleContext;
        }

        return false;
    }
}