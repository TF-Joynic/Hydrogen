<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Exception;
use Hydrogen\Http\Exception\InvalidArgumentException;

class RuleParam extends AbstractRule
{
    CONST RULE_PARAM_PREFIX = ':';

    public function __construct($ruleStr, array $ruleContext)
    {
        if (!is_string($ruleStr) || false === strpos($ruleStr, '/'.self::RULE_PARAM_PREFIX)) {
            throw new InvalidArgumentException('Route Rule[param] must have #:{segment}# specified! Example: /user/:id');
        }

        $this->_ruleStr = $this->fmtRuleStr($ruleStr);
        $this->_ruleContext = $ruleContext;
    }

    /**
     * @param $path
     * @return array
     */
    public function apply($path)
    {
        list ($param_names, $rule_segments) = $this->extractFromRuleStr();
        if (empty($param_names)) {
            return false;
        }

        $path_segments = explode('/', $path);

        array_shift($rule_segments);
        array_shift($path_segments);
        /*var_dump($param_names);
        var_dump($path_segments);exit;*/

        $tmp_param = array();
        if (count($rule_segments) == count($path_segments)) {
            foreach ($rule_segments as $k => $v) {
                if (false !== strpos($v, self::RULE_PARAM_PREFIX)) {
                    $param_name = array_shift($param_names);
                    $tmp_param[$param_name] = $path_segments[$k];
                } elseif ($v === $path_segments[$k]) {
                    continue;
                } else {
                    return false;
                }
            }

            $this->_ruleContext['param'] = array_merge($this->_ruleContext['param'], $tmp_param);
            return $this->_ruleContext;
        }

        return false;
    }

    /**
     * /user/:id/info
     */
    public function extractFromRuleStr()
    {
        $seg_delimiter = '/';

        $param_names = array();
        $segments = explode($seg_delimiter, $this->_ruleStr);

//        var_dump($segments);exit;
        foreach ($segments as $k => $segment) {
            if (0 < strlen($segment) && false !== strpos($segment, self::RULE_PARAM_PREFIX)) {
                $param_names[] = ltrim($segment, self::RULE_PARAM_PREFIX);
            }
        }

        return array($param_names, $segments);
    }
}