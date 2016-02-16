<?php

namespace Hydrogen\Config\Cache;

use Hydrogen\Config\Exception;

class cacheFactory
{
	public static function factory($cacheType)
	{
		$cacheCls = '';
		if (!is_string($cacheType) || empty($cacheType)) {

			throw new \UnexpectedValueException(
				'cacheType must be string');

		}

		$cls = strtolower($cacheType);

		$cacheCls = ucfirst($cls);
		if (!class_exists($cacheCls)) {

			throw new CacheStorageClassNotDefinedException(
				'class: '.$cacheType.
				' not found.');
			
		}

		$extensions = call_user_func(array($cacheCls, 'dependentExtensions'));

		if ($extensions) {
			if (!is_array($extensions)) {
				$extensions = array($extensions);
			}
			foreach ($extensions as $extension) {
				if ($extension && is_string($extension)
 				 && !extension_loaded($extension)) {

 				 	throw new CacheStorageNotSupported('Cache: '.
 				 		$cacheType.' is not supported on this machine');

				}
			}
			
		}
		
		$cacheInstance = new $cacheCls;

		if (! $cacheInstance instanceof CacheInterface) {

			throw new \Exception('class '. $cacheCls. 'is not a impl 
				of ParserInterface');

		}
		return $cacheInstance;
	}
}