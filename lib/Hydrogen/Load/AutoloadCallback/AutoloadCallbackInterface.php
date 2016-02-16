<?php

namespace Hydrogen\Load\AutoloadCallback;

interface AutoloadCallbackInterface
{
	public function autoLoad($class_name);

    public function registerCallback();
}