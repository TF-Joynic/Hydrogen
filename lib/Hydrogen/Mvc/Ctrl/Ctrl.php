<?php

namespace Hydrogen\Mvc\Ctrl;

use Hydrogen\Application\Execute\Executor;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response as Response;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;
use Hydrogen\Mvc\View\View;

class Ctrl
{
    /**
     * @var string
     */
    protected $_layout = null;

    /**
     * @var Request|null
     */
    protected $_request = null;

    /**
     * @var Response|null
     */
    protected $_response = null;

    /**
     * render view template
     * @var bool
     */
    protected $_doRender = true;

    private $_view = null;

    /**
     * @var array of PluginInterface
     */
    private $_plugins = array();

    private $_active_plugins = array();

    /**
     * @var \Hydrogen\Http\Filter\FilterChain|array
     */
    private $_filterChain = null;

    public function __construct()
    {
    }

    public function init()
    {
    }

    /**
     * forward to another action
     *
     * @return void
     */
    public function forward()
    {

    }

    public function redirect($href)
    {
        header('Location:' . $href);
        exit;
    }

    public function getUrl()
    {

    }

    /**
     * hook, run before ctrl act runs
     */
    public function preDispatch()
    {

    }

    /**
     * hook, run after ctrl construct and before ctrl act render
     */
    public function postDispatch()
    {
    }

    public function preRender()
    {

    }

    public function render($tpl, $vars, $output = false, $enableLayout = false)
    {
        if (null == $this->_view) {
            $absTplPath = $this->getAbsTplFilePath($tpl);
            $this->_view = new View($absTplPath, $vars, $enableLayout ? $this->getAbsTplFilePath($this->_layout) : null);
        }

        return $this->_view->render($output);
    }

    /**
     * @param $tpl
     * @return bool|string
     */
    private function getAbsTplFilePath($tpl)
    {
        if (0 == strlen($tpl)) {
            return false;
        }

        $module = $this->_request->getContextAttr(MODULE);

        $templatePath = implode(DIRECTORY_SEPARATOR, array_filter(array(
            Executor::getModuleDir(),
            $module,
            Executor::getTemplateDir()
        )));

        return $templatePath.DIRECTORY_SEPARATOR.$tpl.'.'.Executor::getTemplatePostfix();
    }

    public function postRender()
    {
    }

    public function withRequest(Request $request)
    {
        $this->_request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function withResponse(Response $response)
    {
        $this->_response = $response;
        return $this;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    public function registerPlugin(PluginInterface $plugin)
    {
        $this->_plugins[] = $plugin;
        return $this;
    }

    /**
     * In case of re-activating the plugin(s), remove the plugin after it's been activated.
     *
     * @param bool|false $reverse
     * @return PluginInterface
     */
    private function getNextPlugin($reverse = false)
    {
        $next = null;

        if (!$reverse) {
            $next = array_shift($this->_plugins);
            null !== $next && array_unshift($this->_active_plugins, $next);
        } else {
            $next = array_shift($this->_active_plugins);
        }

        return $next;
    }

    public function activatePlugins()
    {
        while ($currentPlugin = $this->getNextPlugin()) {
            $currentPlugin->activate();
        }

        return $this;
    }

    public function terminatePlugins()
    {
        while ($currentPlugin = $this->getNextPlugin(true)) {
            $currentPlugin->activate();
        }

        return $this;
    }

    public function clearPlugin()
    {
        $this->_plugins = array();
        return $this;
    }

//    public function
}