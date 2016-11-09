<?php

namespace Hydrogen\Console\Command;

/**
 * parse input command, get callable method and args etc.
 */
class parser
{
    public static function validateCommand($cmdStr)
    {
        if (!$cmdStr) {
            return false;
        }

        $regx = '/^(\w+)::(\w+)::(\w+)$/i';
        if (preg_match($regx, $cmdStr)) {
            return true;
        }

        return false;
    }

    public static function extractInstructionNames($cmdStr)
    {
        /*$instructions = [
            Command::MODULE => '',
            Command::CSL => '',
            Command::ACT => ''
        ];

        if (!$cmdStr) {
            return $instructions;
        }*/


    }

}
