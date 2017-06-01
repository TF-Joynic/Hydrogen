<?php

namespace Hydrogen\Utils;

class Str
{
    const UNIX_DIR_SEP = '/';

	public static function truncate($str)
	{

	}

	public static function startWith($haystack, $needle)
	{
		return 0 === strpos($haystack, $needle);
	}

	 public static function endWith($haystack, $needle)
	 {
		return strstr($haystack, $needle) === $needle;
	 }

    /**
     * test given string is probably a directory string. without call is_dir(), without IO.
     *
     * WinNT: C:\user\profile
     * *unix: /data/profile
     *
     * @param $str
     * @return boolean
     */
	 public static function isDirStr($str)
     {
        if (!$str) return false;

        if (preg_match('#\*|\$|\[|\]|\+|\-|\&|\%|\#|\!|`|\s#', $str)) {
            return false;
        }

        if ('WINNT' == PHP_OS) {
            $str = str_replace(DIRECTORY_SEPARATOR, self::UNIX_DIR_SEP, $str);
            if (false === strpos($str, ':') || false === strpos($str, self::UNIX_DIR_SEP)) {
                return false;
            }
        } else {
            if (false === strpos($str, self::UNIX_DIR_SEP)) {
                return false;
            }
        }

        return true;
     }

}