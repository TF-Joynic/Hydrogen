<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Route\Rule;

abstract class AbstractRule implements RuleInterface
{
	protected $_ruleStr = '';

    protected $_ruleContext = array();

    public abstract function __construct($ruleStr, array $ruleContext);

    public function fmtRuleStr($ruleStr)
    {
        return trim($ruleStr);
    }
}