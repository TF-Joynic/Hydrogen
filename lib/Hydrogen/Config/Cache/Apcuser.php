<?php

namespace Hydrogen\Config\Cache;

/**
 * Cache 'Apcu'(Apc user cache) impl. 
 */
class Apcuser implements CacheInterface
{
	public static function dependentExtensions()
	{
		return 'apcu';
	}

	public static function add(string $key, $var, $ttl = 0)
	{
		return apc_add($key, $var, $ttl);
	}

	public static function set(string $key, $var, $ttl = 0)
	{
		return apc_store($key, $var, $ttl);
	}

	public static function get(string $key)
	{
		$value = apc_fetch($key, $succ);
		return $succ ? $value : false;
	}

	public static function remove(string $key)
	{
		return apc_delete($key);
	}
}