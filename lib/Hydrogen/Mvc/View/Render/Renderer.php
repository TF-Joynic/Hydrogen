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
     *
     * @param bool $output
     * @return string
     */
    public function render($output = false)
    {
        $compiler = new Compiler($this->_tplFilePath);
        $compileContent = $compiler->compile();

        ob_start();
        extract($this->_vars);
//        var_dump('=='.$compileContent);exit;
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
     * extract var array to PHP symbol table
     */
    public function extractVars()
    {
        extract($this->_vars);
    }

}