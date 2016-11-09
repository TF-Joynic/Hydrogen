<?php

namespace Hydrogen\Console\Command;

use Hydrogen\Console\Directive;

/**
 * The command class to be excuted
 */
class Command
{
    const MODULE = 'module';
    const CSL = 'csl';
    const ACT = 'act';

    private $_cmdStr = '';
    private $_params = array();

    public function __construct($argv)
    {
        $cmdStr =  isset($argv[0]) ? $argv[0] : 0;
        array_shift($argv);

        $params = $argv;

        if (is_string($cmdStr) && $cmdStr) {
            $this->_cmdStr = $cmdStr;
        }

        if ($params) {
            if (!is_array($params)) {
                $params = array($params);
            }
            $this->_params = $params;
        }
    }

    public function execute()
    {}

    public function hasSpecifiedArg()
    {
        $argOptPrefix = Directive::getArgNameOptPrefix();
        $argOptPrefixLen = strlen($argOptPrefix);

        if ((false !== $pos = strpos($this->_cmdStr, $argOptPrefix))
            && $ch = $this->isAlphabeticChar(substr($this->_cmdStr, $pos + $argOptPrefixLen, 1))) {

            return true;

        }

        return false;
    }

    private function isAlphabeticChar($char)
    {
        if (!$char || !is_string($char)) {
            return false;
        }

        if (0 == $charLen = strlen($char)) {
            return false;
        }

        if ($charLen > 1) {
            $char = $char[0];
        }

        $charOrd = ord($char);
        if ((65 <= $charOrd && $charOrd <= 90) || (97 <= $charOrd && $charOrd <= 122)) {
            return true;
        }

        return false;
    }

    /**
     * extract Module name, Csl name and Act name [:String] out from command Str
     *
     * @return array
     */
    public function extractInstructionNames()
    {

    }

    public function extractCallableParams()
    {

    }

    /**
     * extract out the callable params from the command string
     *
     * @return array
     */
    public function extractInstructionParams()
    {

    }

    /**
     * @return string
     */
    public function getCmdStr()
    {
        return $this->_cmdStr;
    }

    /**
     * @param string $cmdStr
     */
    public function setCmdStr($cmdStr)
    {
        $this->_cmdStr = $cmdStr;
    }

}