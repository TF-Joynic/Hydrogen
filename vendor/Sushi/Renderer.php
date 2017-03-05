<?php

namespace Sushi;

use Hydrogen\Mvc\View\Render\RendererInterface;

/**
 * render vars to template
 */
class Renderer implements RendererInterface
{
    private $_compiler = null;

    public function __construct()
    {
        $this->_compiler = new Compiler();
    }

    /**
     * @param $tplFilePath
     * @param $vars
     * @param bool $output
     * @return string|void
     */
    public function render($tplFilePath, $vars, $output = false)
    {
        $compileContent = $this->_compiler->compile($tplFilePath);

        ob_start();
        extract($vars);
        eval('?>'.$compileContent);

        if ($output) {
            $content = ob_get_clean();
            ob_end_clean();
            return $content;
        } else {
            ob_end_flush();
        }
    }

    /**
     * @return string
     */
    public function getLeftDelimiter()
    {
        return $this->_compiler->getLeftDelimiter();
    }

    /**
     * @param string $leftDelimiter
     * @return $this
     */
    public function setLeftDelimiter($leftDelimiter)
    {
        $this->_compiler->setLeftDelimiter($leftDelimiter);
        return $this;
    }

    /**
     * @return string
     */
    public function getRightDelimiter()
    {
        return $this->_compiler->getRightDelimiter();
    }

    /**
     * @param string $rightDelimiter
     * @return $this
     */
    public function setRightDelimiter($rightDelimiter)
    {
        $this->_compiler->setRightDelimiter($rightDelimiter);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompileDir()
    {
        return $this->_compiler->getCompileDir();
    }

    /**
     * @param mixed $compileDir
     * @return $this
     */
    public function setCompileDir($compileDir)
    {
        $this->_compiler->setCompileDir($compileDir);
        return $this;
    }

}