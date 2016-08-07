<?php

namespace Hydrogen\Console;

use Hydrogen\Console\Command\Command;

class Directive
{
    private static $_commandSeparator = '::';
    private static $_argNameOptPrefix = '--';

    /**
     * @return string
     */
    public static function getCommandSeparator()
    {
        return self::$_commandSeparator;
    }

    /**
     * @param string $commandSeparator
     * @return bool
     */
    public static function setCommandSeparator($commandSeparator)
    {
        if (0 == strlen($commandSeparator)) {
            return false;
        }

        self::$_commandSeparator = $commandSeparator;
        return true;
    }

    /**
     * @return string
     */
    public static function getArgNameOptPrefix()
    {
        return self::$_argNameOptPrefix;
    }

    /**
     * @param string $argNameOptPrefix
     */
    public static function setArgNameOptPrefix($argNameOptPrefix)
    {
        self::$_argNameOptPrefix = $argNameOptPrefix;
    }
}