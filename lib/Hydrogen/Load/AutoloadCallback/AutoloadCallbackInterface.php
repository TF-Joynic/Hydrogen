<?php

namespace Hydrogen\Load\AutoloadCallback;

interface AutoloadCallbackInterface
{
	public static function autoLoad($class_name);

    public function registerCallback();

    /**
     * @param bool|false $fallback fallback to __autoload() function
     * @return mixed
     */
    public function unregisterCallback($fallback = false);
}