<?php

namespace Hydrogen\Route\UrlMatch;

use Hydrogen\Debug\Variable;
use Hydrogen\Exception;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;
use Hydrogen\Application\Execute\Executor;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Route\Exception\UrlMatchFailedException;
use Hydrogen\Route\Rule\RuleInterface;

class UrlMatcher extends AbstractUrlMatcher
{
    private $_module = '';
    private $_ctrl = '';
    private $_act = '';

    private $_rules = array();

    public function __construct()
    {}

    public function setUserRouteRule($rules)
    {
        $this->_rules = $rules;
    }

    /**
     * match and extact module, ctrl and act
     *
     * @param Request $request
     * @param Response $response
     * @return boolean
     */
    public function match(Request &$request, Response &$response)
    {
        $sanitizedPath = trim(preg_replace('/\/{2,}/', '/', $request->getUri()->getPath()));

        $AVAILABLE_MODULES = Executor::getEnabledModules();
        $DEFAULT_MODULE = Executor::getDefaultModule();
        $DEFAULT_CTRL = Executor::getDefaultCtrl();
        $DEFAULT_ACT = Executor::getDefaultAct();

        if ($this->_rules) {
            foreach ($this->_rules as $routeRule) {

                if ($routeRule instanceof RuleInterface
                    && (false !== $ruleCallback = $routeRule->apply($sanitizedPath, $request, $response))) {

                    if ($routeRule->isTerminable()) {
                        $this->tailing($DEFAULT_MODULE, $DEFAULT_CTRL, $DEFAULT_ACT);

                        $request->setContextAttr('module', $this->_module);
                        $request->setContextAttr('ctrl', $this->_ctrl);
                        $request->setContextAttr('act', $this->_act);
                        return true;
                    }

                }

            }
        }

        $pathArr = explode('/', $sanitizedPath);
        array_shift($pathArr);
        foreach ($pathArr as $k => $segment) {
            if (!$segment) {
                break;
            }

            switch ($k) {
                case 0:
                    if (in_array($segment, $AVAILABLE_MODULES)) {
                        $this->_module = $segment;
                    } else {
                        $this->_module = $DEFAULT_MODULE;

                        $this->_ctrl = $segment;
                    }
                    break;

                case 1:
                    if ($this->_ctrl) {
                        $this->_act = $segment;
                    } else { // ctrl has not been assigned yet!
                        $this->_ctrl = $segment;
                    }
                    break;

                case 2:
                    if ($this->_ctrl) {
                        '' === $this->_act && $this->_act = $segment;
                    } else {
                        $this->_ctrl = $segment;
                    }
                    break;

                default:
                    return false;
                    break;
            }
        }

        /*var_dump($this->_module);
        var_dump($this->_ctrl);
        var_dump($this->_act);exit;*/

        $this->tailing($DEFAULT_MODULE, $DEFAULT_CTRL, $DEFAULT_ACT);

        $request->setContextAttr('module', $this->_module);
        $request->setContextAttr('ctrl', $this->_ctrl);
        $request->setContextAttr('act', $this->_act);
        return true;
    }

    public static function extractMvcName($path)
    {

    }

    /**
     * test whether a uri segment is version style or not
     *         
     * @param  string  $segment one uri segement
     * @return boolean 
     */
    public function isVersionSegement($segment)
    {
        $rev = strrev($segment);
        return is_numeric($rev[0]);
    }

    public function extractVersionSegment($segment) {
        $return = false;
        if (!$segment || false !== strpos($segment, ' ')) {
            return $return;
        }

        $boundary_right = 0;

        foreach (str_split($segment) as $k => $char) {
            if (is_numeric($char)) {
                $boundary_right = $k;
                break;
            }
        }

        if ($boundary_right == 0) {
            return $return;
        } else {
            $alpha = substr($segment, 0, $boundary_right);
            $numeric = $this->extractNumeric(substr($segment, $boundary_right));
            $return = array($alpha, $numeric);
        }

        return $return;
    }

    private function extractNumeric($str) {
        if (!is_string($str) || 0 == strlen($str)) {
            return false;
        }
        return preg_replace('/\./', '', $str);
    }

    private function extractAlphabet($str) {
        if (!is_string($str) || 0 == strlen($str)) {
            return false;
        }
        return preg_replace('/[^a-zA-Z_]/', '', $str);
    }

    /**
     * get ctrl name 
     * @param  string $versionSymbol uri version symbol
     * @param  string $versionNumber numeric string of version number without any dot
     * @return string                ctrl name
     */
    public function fmtVersionCtrl($versionSymbol, $versionNumber) 
    {
        return $versionSymbol.$versionNumber;
    }

    /**
     * process default module/ctrl/act name
     *
     * @param $default_module
     * @param $default_ctrl
     * @param $default_act
     */
    public function tailing($default_module, $default_ctrl, $default_act)
    {
        if (!$this->_module) {
            $this->_module = $default_module;
        }

        if (!$this->_ctrl) {
            $this->_ctrl = $default_ctrl;
        }

        $this->_ctrl = ucfirst($this->_ctrl);

        if (!$this->_act) {
            $this->_act = $default_act;
        }
    }
}