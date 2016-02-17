<?php

namespace Hydrogen\Route;

use Hydrogen\Http\Request\ServerRequest;
use Hydrogen\Http\Response\Response;
use Hydrogen\Route\Rule\RuleInterface;
use Hydrogen\Route\UrlMatch\UrlMatcher;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Route\Dispatch\Dispatcher;

class Router 
{
	private static $_instance = null;

    private $_rules = array();

	private function __construct()
	{}

	private function __clone()
	{}

	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    public function addRule(RuleInterface $routeRule)
    {
        $this->_rules[] = $routeRule;
    }

	public function route()
	{
        $urlMatcher = new UrlMatcher();
        global $module, $ctrl, $act;

        if (!empty($this->_rules))
            $urlMatcher->setUserRouteRule($this->_rules);

        $request = new ServerRequest();
        $response = new Response();
        if (false === $matchResult = $urlMatcher->match($request, $response)) {
        	// match failed
        	throw new DispatchException('can not match url: '.$_SERVER['REQUEST_URI'].', review your typo!');
        }

        list($module, $ctrl, $act) = $matchResult;

        $request->setExtraParam('module', $module);
        $request->setExtraParam('ctrl', $ctrl);
        $request->setExtraParam('act', $act);

        // dispatch now
        $dispatcher = new Dispatcher($request, $response);
        $dispatcher->dispatch();
//        pomvc();
	}

}