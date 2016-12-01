<?php

namespace Hydrogen\Mvc\View\Render;

use Hydrogen\Application\Execute\Executor;
use Hydrogen\Exception;
use Hydrogen\Mvc\View\Exception\InvalidCompilePathException;
use Hydrogen\Mvc\View\Exception\InvalidTemplateFileException;

/**
 * render vars to template
 */
class Renderer
{
    private $_tplFilePath;

    public function __construct($tplFilePath, $vars)
    {
        $this->_tplFilePath = $tplFilePath;
        $this->_vars = $vars;
    }

    /**
     * parse tpl into php file
     */
    public function parseTpl()
    {
        if (!$this->_tplFilePath || !file_exists($this->_tplFilePath)) {
            throw new InvalidTemplateFileException('Tpl file is invalid: ' . $this->_tplFilePath);
        }

        list($hasCached, $cacheFilePath) = $this->hasCacheFile($this->_tplFilePath);
        if ($hasCached) {
            return $cacheFilePath;
        }

        // read in line
        $handle = fopen($this->_tplFilePath, 'rb');
        if (!$handle) {

            throw new InvalidTemplateFileException('Could not read tpl file: '
                . $this->_tplFilePath . ', probably caused by no access permission!');

        }

        $compileFileHandle = fopen($cacheFilePath, 'wb');
        if (!$compileFileHandle) {

            throw new InvalidCompilePathException('Could not access compile file: '
                . $this->_tplFilePath . ', probably caused by no access permission!');

        }

        while (!feof($handle)) {
            $line = fgets($handle);
            if (false != strpos($line, '{$')) {
                $line = $this->parseLine($line);
            }

            // write into output buffer
            fwrite($compileFileHandle, $line);
        }
        fclose($compileFileHandle);
        fclose($handle);
//        file_put_contents('/Users/xiaolei/Documents/hy.log',$cacheFilePath );
        return $cacheFilePath;
    }

    private function readFromCache($cachedFilePath)
    {
        return file_get_contents($cachedFilePath);
    }

    private function getCacheFileName($filePath)
    {
        if (!$filePath) {
            return false;
        }

        $md5 = md5_file($filePath);
        if (false === $md5) {
            return false;
        }

        return basename($filePath).'.'.$md5;
    }

    private function hasCacheFile($filePath)
    {
        $cacheFileName = $this->getCacheFileName($filePath);
        if (!is_dir(COMPILE_PATH)) {
            throw new InvalidCompilePathException('compile dir: '.COMPILE_PATH.' is invalid, make sure it do exist!');
        }

        $cacheFilePath = COMPILE_PATH.DIRECTORY_SEPARATOR.$cacheFileName;
        if (false !== $cacheFileName && file_exists($cacheFilePath)) {
            return array(true, $cacheFilePath);
        }

        return array(false, $cacheFilePath);
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

    /**
     * extract var array to PHP symbol table
     */
    public function extractVars()
    {
        extract($this->_vars);
    }

}