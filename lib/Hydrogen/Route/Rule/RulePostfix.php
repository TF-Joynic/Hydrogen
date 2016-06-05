<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Route\Exception\InvalidArgumentException;

class RulePostfix extends AbstractRule
{

    public function __construct($ruleStr, array $ruleContext)
    {
        if (!is_string($ruleStr) || 0 == strlen($postfix = ltrim($ruleStr, '.'))) {
            throw new InvalidArgumentException('Route Rule[postfix]: invalid rule string!');
        }

        $this->_terminable = false;
        $this->_ruleStr = $postfix;
        $this->_ruleContext = $ruleContext;
    }

    /**
     * $path: user/profile.json
     *
     * @param $path
     * @return array
     */
    public function apply(&$path)
    {
        if (false !== $postfixPos = strrpos($path, '.')) {
            $realPath = substr($path, 0, $postfixPos);
            $postfix = substr($path, $postfixPos + 1);

            if ($postfix == $this->_ruleStr) {
                $path = $realPath;
                return $this->_ruleContext;
            }
        }

        return false;
    }
}