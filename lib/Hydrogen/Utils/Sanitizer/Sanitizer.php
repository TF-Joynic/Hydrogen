<?php

namespace Hydrogen\Utils;


class Sanitizer
{
    public static function sanitizeXss($str)
    {
        if (!$str) return $str;

        if (is_string($str)) {
            if (0 == strlen($str)) {
                return $str;
            }

            $str = self::escape($str);
        } elseif (is_array($str)) {
            foreach ($str as &$v) {
                $v = self::sanitizeXss($v);
            }
        }

        return $str;
    }

    public static function escape($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }
}