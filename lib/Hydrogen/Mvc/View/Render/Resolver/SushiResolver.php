<?php

namespace Hydrogen\Mvc\View\Resolver;


use Hydrogen\Mvc\View\Render\RendererInterface;

class SushiResolver extends AbstractResolver
{
    private $_tplFilePath;
    private $_vars;
    private $_output;


    public function __construct(RendererInterface $renderer, $tplFilePath, $vars, $output)
    {
        parent::__construct($renderer);
        $this->_tplFilePath = $tplFilePath;
        $this->_vars = $vars;
        $this->_output = $output;
    }

    public function render()
    {
        $this->_renderer->render($this->_tplFilePath, $this->_vars, $this->_output);
    }
}