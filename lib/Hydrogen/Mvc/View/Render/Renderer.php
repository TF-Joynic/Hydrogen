<?php

namespace Hydrogen\Mvc\View\Render;

/**
 * render vars to template
 */
class Renderer implements RendererInterface
{

    /**
     * @param $tplFilePath
     * @param $vars
     * @param bool $output
     * @return string
     */
    public function render($tplFilePath, $vars, $output = false)
    {
        $compileContent = $this->_compiler->compile($tplFilePath);

        ob_start();
        extract($vars);
        eval('?>'.$compileContent);

        if ($output) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        } else {
            ob_end_flush();
        }
    }


}