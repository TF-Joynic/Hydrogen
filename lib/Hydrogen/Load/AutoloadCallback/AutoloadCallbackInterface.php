<?php

namespace Hydrogen\Load\AutoloadCallback;

interface AutoloadCallbackInterface
{
	public function autoLoad($className);
}