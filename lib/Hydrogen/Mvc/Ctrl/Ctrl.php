<?php

namespace Hydrogen\Mvc\Ctrl;

use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response as Response;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;

class Ctrl
{
    /**
     * @var Request|null
     */
    private $_request = null;

    /**
     * @var Response|null
     */
    private $_response = null;

    /**
     * render view template
     * @var bool
     */
    private $_doRender = true;

    /**
     * @var array of PluginInterface
     */
    private $_plugins = array();

    private $_active_plugins = array();

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
}