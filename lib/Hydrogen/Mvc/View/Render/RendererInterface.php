<?php

namespace Hydrogen\Mvc\View\Render;

interface RendererInterface
{
    /**
     * @param $tplFilePath
     * @param $vars
     * @param bool $output
     * @return string|void
     */
    public function render($tplFilePath, $vars, $output = false);
}