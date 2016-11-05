<?php

namespace Hydrogen\Config\Cache;

class Memcached implements CacheInterface
{
	public static function dependentExtensions()
	{
		return 'memcache';
	}

	public static function add(string $key, $var, $ttl = 0)
	{
		memcached_add($key, $var, $ttl);
	}

	public static function set(string $key, $var, $ttl = 0)
	{
		return memcached_set($key, $var, false, $ttl);
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