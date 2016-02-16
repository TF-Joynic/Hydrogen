<?php

namespace Hydrogen\Route\Rule;

class RuleFixed extends AbstractRule
{
    public function __construct($ruleStr, $context)
    {
        if (0 < strlen($ruleStr)) {
            if (!is_array($context))
                $context = array($context);

            $this->_rule = array(
                'str' => $ruleStr,
                'context' => $context
            );
        }
    }

    public function apply($uri)
    {
        if (!is_array($this->_rule))
            $this->_rule = array();

        foreach ($this->_rule as $rule) {
            if (1) {

            }
        }
    }
}