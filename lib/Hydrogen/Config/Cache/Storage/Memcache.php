<?php

namespace Hydrogen\Config\Cache;

class Memcache implements CacheInterface
{
	public static function dependentExtensions()
	{
		return 'memcache';
	}

	public static function add(string $key, $var, $ttl = 0)
	{
		memcache_add($key, $var, false, $ttl);
	}

	public static function set(string $key, $var, $ttl = 0)
	{
		return memcache_set($key, $var, false, $ttl);
	}

	public static function get(string $key)
	{
		return memcache_get($key);
	}

	public static function remove(string $key)
	{
		return memcache_delete($key);
	}
}