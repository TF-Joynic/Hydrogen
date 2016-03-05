<?php

namespace Hydrogen\Route;

use Hydrogen\Debug\Variable;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;
use Hydrogen\Load\Loader;
use Hydrogen\Route\Rule\RuleInterface;
use Hydrogen\Route\UrlMatch\UrlMatcher;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Route\Dispatch\Dispatcher;

class Router 
{
	private static $_instance = null;

    public $_rules = array();

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
        if (!$routeRule->isTerminable()) {
            array_unshift($this->_rules, $routeRule);
        } else {
            $this->_rules[] = $routeRule;
        }

        return self::$_instance;
    }

	public function route()
	{
        $urlMatcher = new UrlMatcher();

        if (!empty($this->_rules))
            $urlMatcher->setUserRouteRule($this->_rules);

        $request = new Request();
        $response = new Response();
        if (false === $matchResult = $urlMatcher->match($request, $response)) {
        	// match failed
        	throw new DispatchException('can not match url: '.$_SERVER['REQUEST_URI'].', review your typo!');
        }

        // dispatch now
        $dispatcher = new Dispatcher($request, $response);
        $dispatcher->dispatch();
	}

}