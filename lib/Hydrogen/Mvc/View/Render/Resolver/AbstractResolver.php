<?php

namespace Hydrogen\Mvc\View\Resolver;


use Hydrogen\Mvc\View\Render\RendererInterface;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var RendererInterface
     */
    protected $_renderer;

    protected function __construct(RendererInterface $renderer)
    {
        $this->_renderer = $renderer;
    }

    public abstract function render();

}