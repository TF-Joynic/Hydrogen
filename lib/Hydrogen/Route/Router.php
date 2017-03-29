<?php

namespace Hydrogen\Route;

use Hydrogen\Debug\Variable;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response;
use Hydrogen\Load\Loader;
use Hydrogen\Route\Exception\InvalidRuleCallbackException;
use Hydrogen\Route\Exception\UrlMatchFailedException;
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

    public function addRule(RuleInterface $routeRule, \Closure $callback)
    {
        if (false !== $callbackClosure = $callback->bindTo($routeRule, $routeRule)) {
            $routeRule->setCallback($callbackClosure);
        } else {
            throw new InvalidRuleCallbackException('invalid callback closure specified!');
        }

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

        try {
            $matchResult = $urlMatcher->match($request, $response);
        } catch (\Exception $e) {
            throw new UrlMatchFailedException($e->getMessage() . '-- can not match url: '.$_SERVER['REQUEST_URI']);
        }

        if (true !== $matchResult) {
            throw new UrlMatchFailedException('url match failed!');
        }

        // dispatch now
        $dispatcher = new Dispatcher($request, $response);
        $dispatcher->dispatch();
	}

}