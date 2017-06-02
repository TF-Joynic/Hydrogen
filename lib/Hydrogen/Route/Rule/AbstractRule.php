<?php

namespace Hydrogen\Route\Rule;


abstract class AbstractRule implements RuleInterface
{
    protected $_ruleStr = '';
    protected $_terminable = true;

    protected $_callback = null;
    protected $_ruleContext = array();

    public abstract function __construct($ruleStr);

    public function setCallback(\Closure $callback)
    {
        $this->_callback = $callback;
    }

    public function getCallback()
    {
        return $this->_callback;
    }

    public function performCallback()
    {
        $ruleCallback = $this->getCallback();

        if ($ruleCallback instanceof \Closure) {
            $args = func_get_args();
            call_user_func_array($ruleCallback, $args);
        }
    }

    public function fmtRuleStr($ruleStr)
    {
        return trim($ruleStr);
    }

    public function isTerminable()
    {
        return $this->_terminable;
    }
}