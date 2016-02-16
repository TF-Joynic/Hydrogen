<?php

namespace Hydrogen\Http\Response;

use Hydrogen\Http\Cookie\Cookie as CookieEntity;
use Hydrogen\Http\Exception\InvalidArgumentException;

/**
 * Cookie manipulation class for Class Response
 *
 * @package Hydrogen\Http\Response
 */
class cookie
{
	/**
	 * tell the browser to set a cookie
	 * allow set multiple cookies at once
	 * 
	 * @param mixed $cookies cookie arr
     * @param boolean $raw
	 * @return void
	 */
	public function add($cookies, $raw = false)
	{
		if (!is_array($cookies)) {
			$cookies = array($cookies);
		}

		foreach ($cookies as $cookie) {

			if ($cookie instanceof CookieEntity) {

				$raw ? 
				setrawcookie($cookie->name, $cookie->value, 
					$cookie->expire, $cookie->path, $cookie->domain, 
					$cookie->secure, $cookie->httpOnly)
				:
				setcookie($cookie->name, $cookie->value, 
					$cookie->expire, $cookie->path, $cookie->domain, 
					$cookie->secure, $cookie->httpOnly);

			} else {

				throw new InvalidArgumentException(
					'Argument: $cookies must be Hydrogen\Http\Cookie\Cookie
					 instance or array of it');

			}
		}
	}

	/**
	 * remove one or multi cookies
	 * 
	 * @param  Cookie|string|array $cookies
	 * @return  void
	 */
	public function remove($cookies)
	{
		if (!is_array($cookies)) {
			$cookies = array($cookies);
		}

		foreach ($cookies as $cookie) {

			if (is_string($cookie) && 0 < strlen($cookie)) { 
				$cookie = new CookieEntity($cookie);
				$cookie->expires = $_SERVER['REQUEST_TIME'] - 86400;
			} elseif (!($cookie instanceof CookieEntity)) {

				throw new InvalidArgumentException(
					'Argument: $cookies must be 
					Hydrogen\Http\Cookie\Cookie instance
					 or array of cookieName');
				
			}

			setcookie($cookie->name, $cookie->value, 
					$cookie->expire, $cookie->path, $cookie->domain, 
					$cookie->secure, $cookie->httpOnly);

		}
	}
}