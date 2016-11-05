<?php

namespace Hydrogen\Mvc\View\Render;

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

        if ($cached = $this->hasCacheFile($this->_tplFilePath)) {
            return $this->readFromCache($cached);
        }

        // read in line
        $handle = fopen($this->_tplFilePath, 'rb');
        if (!$handle) {

            throw new InvalidTemplateFileException('Could not read tpl file: '
                . $this->_tplFilePath . ', probably caused by no access permission!');

        }

        while (!feof($handle)) {
            $line = fgetc($handle);
            if (false != strpos($line, '{$')) {
                $line = $this->parseLine($line);
            }

            // write into cacheFile

        }
        fclose($handle);

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
        $cacheFilePath = COMPILE_PATH.DIRECTORY_SEPARATOR.$cacheFileName;
        if (false !== $cacheFileName && file_exists($cacheFilePath)) {
            return $cacheFilePath;
        }

        return false;
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

}