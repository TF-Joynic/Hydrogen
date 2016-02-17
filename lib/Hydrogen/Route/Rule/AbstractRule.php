<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Route\Rule;

abstract class AbstractRule implements RuleInterface
{
	protected $_ruleStr = '';

    protected $_ruleContext = array();
}