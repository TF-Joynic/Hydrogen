<?php

namespace Hydrogen\Load\AutoloadCallback;

interface AutoloadCallbackInterface
{
	public static function autoLoad($class_name);

    public function registerCallback();
}