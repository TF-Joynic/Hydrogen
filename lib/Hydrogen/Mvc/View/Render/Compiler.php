<?php

namespace Hydrogen\Mvc\View\Render;


use Hydrogen\Mvc\View\Exception\InvalidCompilePathException;
use Hydrogen\Mvc\View\Exception\InvalidTemplateFileException;

class Compiler
{
    private $_tplFilePath;
    private static $_compileFilePostfix = '.php';
    private static $_headerLineQuotes = array('<?php /**', '*/ ?>');
    const HEADER_INFO_KEY_TPL_PATH = 'tpl';
    const HEADER_INFO_KEY_MD5 = 'md5';
    const HEADER_INFO_KEY_COMPILE_AT = 'compile_at';

    public function __construct($tplPath)
    {
        if (!$tplPath || !is_string($tplPath) || !file_exists($tplPath)) {

            throw new InvalidTemplateFileException('Tpl file is invalid: ' . $this->_tplFilePath.
                ', make sure it do exists and can be accessed');

        }

        $this->_tplFilePath = $tplPath;
    }

    public function getTplCompileFileName()
    {
        // abs path md5
        return md5($this->_tplFilePath).'.tpl'.self::$_compileFilePostfix;
    }

    /**
     * @return string compiled content
     */
    public function compile()
    {
        $compileFilePath = COMPILE_PATH.DIRECTORY_SEPARATOR.$this->getTplCompileFileName();
        $md5SumOfTpl = $this->getTplFileFingerprint($this->_tplFilePath);

        if (file_exists($compileFilePath)) {
            $headerLine = $this->readCompileFileHeaderLine($compileFilePath);
            $headerInfo = $this->getCompileFileHeaderInfo($headerLine);
            if ($headerInfo[self::HEADER_INFO_KEY_MD5] == $md5SumOfTpl) {
                return file_get_contents($compileFilePath);
            }
        }

        $compileFileHeaderInfoArr = array(
            self::HEADER_INFO_KEY_TPL_PATH => $this->_tplFilePath,
            self::HEADER_INFO_KEY_MD5 => $md5SumOfTpl,
            self::HEADER_INFO_KEY_COMPILE_AT => microtime(true)
        );

        $compileFileHeaderStr = self::$_headerLineQuotes[0].
            $this->sleepCompileHeaderInfo($compileFileHeaderInfoArr).self::$_headerLineQuotes[1].PHP_EOL;

        // read in line
        $handle = fopen($this->_tplFilePath, 'rb');
        if (!$handle) {

            throw new InvalidTemplateFileException('Could not read tpl file: '
                . $this->_tplFilePath . ', probably caused by insufficient permission!');

        }

        $oldErrorReporting = error_reporting(0);
        $compiledContent = $compileFileHeaderStr;
        while (!feof($handle)) {
            $line = fgets($handle);
            if (false != strpos($line, '{$')) {
                $line = $this->parseLine($line);
            }

            $compiledContent .= $line;
        }
        error_reporting($oldErrorReporting);

        // write
        file_put_contents($compileFilePath, $compiledContent);
        return $compiledContent;
    }

    public static function readCompileFileHeaderLine($compileFilePath)
    {
        $headerLine = '';
        $handle = fopen($compileFilePath, 'rb');
        if ($handle) {
            $headerLine = fgets($handle);
        }
        fclose($handle);

        return str_replace(self::$_headerLineQuotes, '', $headerLine);
    }

    /**
     * {$var_name}  =>  <?php echo $var_name;?>
     *
     * must be '{$' - '}' pairs!
     *
     * @param $line
     * @return string
     */
    private function parseLine($line)
    {
        return preg_replace_callback('/{\$([^}]+)}/i', function ($matches) {
            if ($matches && 2 == count($matches)) {
                return '<?php echo $'.$matches[1].';?>';
            }

            return '';
        }, $line);
    }

    private function buildCompileHeaderInfo($absTplPath, $md5Sum, $compileTimeFloat)
    {
        return array(
            self::HEADER_INFO_KEY_TPL_PATH => $absTplPath,
            self::HEADER_INFO_KEY_MD5 => $md5Sum,
            self::HEADER_INFO_KEY_COMPILE_AT => $compileTimeFloat
        );
    }

    /**
     * return string
     *
     * @param $infoArr
     * @return string
     */
    private static function sleepCompileHeaderInfo($infoArr)
    {
        return json_encode($infoArr);
    }

    /**
     * return array
     *
     * @param $infoStr
     * @return array|mixed
     */
    private static function wakeupCompileHeaderInfo($infoStr)
    {
        return 0 < strlen($infoStr) ? json_decode($infoStr, true) : array();
    }

    /**
     * compare fingerprint
     */
    public function isNeedCompile()
    {
        if (!file_exists($this->getTplCompileFileName())) {
            return true;
        }

        return $this->getTplFileFingerprint($this->_tplFilePath) != 0;
    }

    private static function getTplFileFingerprint($absPth)
    {
        return md5_file($absPth);
    }

    /**
     *
     * @param $headerLine
     * @return array
     */
    private static function getCompileFileHeaderInfo($headerLine)
    {
        if (!$headerLine) {
            return array();
        }

        return self::wakeupCompileHeaderInfo($headerLine);
    }

}