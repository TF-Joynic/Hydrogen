<?php

namespace Hydrogen\Route\Rule;

interface RuleInterface
{
    /**
     * @param $path
     * @return boolean|array
     */
    public function apply(&$path);

    public function fmtRuleStr($ruleStr);

    public function isTerminable();
}