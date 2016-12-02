<?php

namespace Hydrogen\Mvc\View\Render;


use Hydrogen\Mvc\View\Exception\InvalidTemplateFileException;

class Compiler
{
    private $_tplFilePath;
    private static $_compileFilePostfix = '.php';

    public function __construct($tplPath)
    {
        if (!$tplPath || !is_string($tplPath) || !file_exists($tplPath)) {

            throw new InvalidTemplateFileException('Tpl file is invalid: ' . $this->_tplFilePath.
                ', make sure it do exists and can be accessed');

        }
    }

    public function getTplCompileFileName()
    {
        // abs path md5
        return md5($this->_tplFilePath).self::$_compileFilePostfix;
    }

    public function compile()
    {
        if (!$this->isNeedCompile()) {
            return ;
        }

        $md5SumOfTpl = $this->getTplFileFingerprint($this->_tplFilePath);

    }

    private function buildCompileHeaderInfo($absTplPath, $md5Sum, $compileTimeFloat)
    {
        return array(
            'tpl' => $absTplPath,
            'md5' => $md5Sum,
            'compile_at' => $compileTimeFloat
        );
    }

    /**
     * return string
     *
     * @param $infoArr
     * @return string
     */
    private function sleepCompileHeaderInfo($infoArr)
    {
        return json_encode($infoArr);
    }

    /**
     * return array
     *
     * @param $infoStr
     * @return array|mixed
     */
    private function wakeupCompileHeaderInfo($infoStr)
    {
        return 0 < strlen($infoStr) ? json_decode($infoStr) : array();
    }

    /**
     * compare fingerprint
     */
    public function isNeedCompile()
    {
        return $this->getTplFileFingerprint($this->_tplFilePath) != 0;
    }

    private static function getTplFileFingerprint($absPth)
    {
        return md5_file($absPth);
    }

    /**
     * return array
     */
    private function getCompileFileHeaderInfo()
    {

    }

}