<?php

namespace Hydrogen\Route\Rule;

use Hydrogen\Http\Exception\InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RuleParam extends AbstractRule
{
    CONST RULE_PARAM_PREFIX = ':';
    CONST SEGMENT_DELIMITER = '/';

    public function __construct($ruleStr)
    {
        if (!is_string($ruleStr) || false === strpos($ruleStr, '/'.self::RULE_PARAM_PREFIX)) {
            throw new InvalidArgumentException('Route Rule[param] must have #:{segment}# specified! Example: /user/:id');
        }

        $this->_ruleStr = $this->fmtRuleStr($ruleStr);
    }

    /**
     * @param $path
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return \Closure|bool
     */
    public function apply(&$path, ServerRequestInterface &$request, ResponseInterface &$response)
    {
        list ($param_names, $rule_segments) = $this->extractFromRuleStr();
        if (empty($param_names)) {
            return false;
        }

        $path_segments = explode(self::SEGMENT_DELIMITER, $path);

        array_shift($rule_segments);
        array_shift($path_segments);

        $contextParams = array();
        if (count($rule_segments) == count($path_segments)) {
            foreach ($rule_segments as $k => $v) {
                if (false !== strpos($v, self::RULE_PARAM_PREFIX)) {
                    $param_name = array_shift($param_names);
                    $contextParams[$param_name] = $path_segments[$k];
                } elseif ($v === $path_segments[$k]) {
                    continue;
                } else {
                    return false;
                }
            }

            $request->withAttributes($contextParams);
            $this->performCallback($request, $response);
            return true;
        }

        return false;
    }

    /**
     * /user/:id/info
     */
    public function extractFromRuleStr()
    {
        $param_names = array();
        $segments = explode(self::SEGMENT_DELIMITER, $this->_ruleStr);

        foreach ($segments as $k => $segment) {
            if (0 < strlen($segment) && false !== strpos($segment, self::RULE_PARAM_PREFIX)) {
                $param_names[] = ltrim($segment, self::RULE_PARAM_PREFIX);
            }
        }

        return array($param_names, $segments);
    }
}