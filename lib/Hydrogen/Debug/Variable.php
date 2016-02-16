<?php

namespace Hydrogen\Debug;

class Variable
{
	public static function dump($var, $exit = false)
	{
		echo strtoupper(gettype($var))."<pre>";
		print_r($var);
		echo "</pre>";
		if ($exit) {
			exit;
		}
	}
}