<?php

namespace Hydrogen\Utils;

class Str
{
	public static function truncate($str)
	{

	}

	public static function startWith($haystack, $needle)
	{
		return 0 === strpos($haystack, $needle);
	}

	 public static function endWith($haystack, $needle)
	 {
		return strrchr($haystack, $needle) === $needle;
	 }

}