<?php

namespace Hydrogen\Mvc\View;

use Hydrogen\Http\Exception\InvalidArgumentException;
use Hydrogen\Mvc\View\Exception\InvalidTemplateFileException;

class View
{
    private $_tpl;
    private $_vars;
    private $_layout = null;

    private $_layoutEnabled = true;

	public function __construct($tpl, $vars, $layout = null)
	{
        if (!is_string($tpl)) {
            throw new InvalidArgumentException('invalid argument $tpl');
        }

        $this->_tpl = $tpl;

        if (is_object($vars)) {
            $vars = get_object_vars($vars);
        }
        if (!is_array($vars)) {
            $vars = array();
        }

        $this->_vars = $vars;
        if ($layout) $this->_layout = $layout;
	}

    public function disableLayout()
    {
        $this->_layoutEnabled = false;
        return $this;
    }

    public function render($output = false)
    {
        $outputContent = null;

        if (!file_exists($this->_tpl)) throw new InvalidTemplateFileException('file '.$this->_tpl.' do not exist!');

        $renderer = new Renderer();
        $renderer->setCompileDir(COMPILE_PATH);
        return $renderer->render($this->_tpl, $this->_vars, $output);
    }

}