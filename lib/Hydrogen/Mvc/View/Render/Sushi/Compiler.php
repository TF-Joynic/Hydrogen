<?php

namespace Hydrogen\Mvc\View\Render\Sushi;

use Hydrogen\Mvc\View\Exception\InvalidCompilePathException;
use Hydrogen\Mvc\View\Exception\InvalidTemplateFileException;

class Compiler
{
    private static $_compileFilePostfix = '.php';
    private static $_headerLineQuotes = array('<?php /**', '*/ ?>');
    const HEADER_INFO_KEY_TPL_PATH = 'tpl';
    const HEADER_INFO_KEY_MD5 = 'md5';
    const HEADER_INFO_KEY_COMPILE_AT = 'compile_at';

    private $_leftDelimiter = '{$';
    private $_rightDelimiter = '}';

    private $_compileDir = null;

    public function __construct()
    {

    }

    public static function getTplCompileFileName($tplFilePath)
    {
        // abs path md5
        return md5($tplFilePath).'.tpl'.self::$_compileFilePostfix;
    }

    /**
     * @param $tplPath
     * @return string compiled content
     */
    public function compile($tplPath)
    {
        if (!$tplPath || !is_string($tplPath) || !file_exists($tplPath)) {

            throw new InvalidTemplateFileException('Tpl file is invalid: ' . $tplPath.
                ', make sure it do exists and can be accessed');

        }

        $compileFilePath = $this->getCompileDir().DIRECTORY_SEPARATOR.self::getTplCompileFileName($tplPath);
        $md5SumOfTpl = self::getTplFileFingerprint($tplPath);

        if (file_exists($compileFilePath)) {
            $headerLine = $this->readCompileFileHeaderLine($compileFilePath);
            $headerInfo = $this->getCompileFileHeaderInfo($headerLine);
            if ($headerInfo[self::HEADER_INFO_KEY_MD5] == $md5SumOfTpl) {
                return file_get_contents($compileFilePath);
            }
        }

        $compileFileHeaderInfoArr = $this->buildCompileHeaderInfo($tplPath,
            $md5SumOfTpl, microtime(true));

        $compileFileHeaderStr = self::$_headerLineQuotes[0].
            $this->sleepCompileHeaderInfo($compileFileHeaderInfoArr).
            self::$_headerLineQuotes[1].PHP_EOL;

        // read in line
        $handle = fopen($tplPath, 'rb');
        if (!$handle) {

            throw new InvalidTemplateFileException('Could not read tpl file: '
                . $tplPath . ', probably caused by insufficient permission!');

        }

        $oldErrorReporting = error_reporting(0);
        $compiledContent = $compileFileHeaderStr;
        while (!feof($handle)) {
            $line = fgets($handle);

            if (false != strpos($line, $this->_leftDelimiter)
                && false !== strpos($line, $this->_rightDelimiter)) {

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
        $leftDelimiter = $this->getLeftDelimiter();
        $rightDelimiter = $this->getRightDelimiter();

        $patternStr = sprintf('/%s([^%s]+)%s/', $leftDelimiter, $rightDelimiter, $rightDelimiter);
        return preg_replace_callback($patternStr, function ($matches) {
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

    /**
     * @return string
     */
    public function getLeftDelimiter()
    {
        return $this->_leftDelimiter;
    }

    /**
     * @param string $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter)
    {
        $this->_leftDelimiter = $leftDelimiter;
    }

    /**
     * @return string
     */
    public function getRightDelimiter()
    {
        return $this->_rightDelimiter;
    }

    /**
     * @param string $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter)
    {
        $this->_rightDelimiter = $rightDelimiter;
    }

    /**
     * @return null
     */
    public function getCompileDir()
    {
        return $this->_compileDir;
    }

    /**
     * @param null $compileDir
     */
    public function setCompileDir($compileDir)
    {
        $this->_compileDir = $compileDir;
    }

}