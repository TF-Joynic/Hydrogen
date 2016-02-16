<?php

namespace Hydrogen\Config\Cache;

interface CacheInterface
{
	/**
	 * get the dependent extension(s)
	 * 
	 * @return array
	 */
	public static function dependentExtensions();

	/**
	 * add one key to the cache storage, 
	 * will return false if the key already exists
	 * 
	 * @param string  $key 
	 * @param mixed  $var 
	 * @param integer $ttl
	 * @return boolean
	 */
	public static function add(string $key, $var, $ttl = 0);

	/**
	 * will overwrite existed key
	 * 
	 * @param string  $key   
	 * @param mixed  $value 
	 * @param integer $ttl
	 */
	public static function set(string $key, $var, $ttl = 0);

	/**
	 * fetch value by key from the cache storage
	 * 
	 * @param  string $key 
	 * @return mixed false if failed
	 */
	public static function get(string $key);

	/**
	 * remove key from cache storage
	 * 
	 * @param  string $key
	 * @return boolean
	 */
	public static function remove(string $key);
}