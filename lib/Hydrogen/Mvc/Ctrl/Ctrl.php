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

    protected static $_plugins = array();

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
    public function fwd()
    {

    }

    public function redirect($href)
    {
        $this->rdt($href);
    }

    public function rdt($href)
    {
        header('Location:' . $href);
        exit;
    }

    public function getUrl()
    {

    }

    /**
     * hook, run before ctrl construct
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

    public static function registerPlugin(PluginInterface $plugin)
    {
        self::$_plugins[] = $plugin;
        return true;
    }

    public static function clearPlugin()
    {
        self::$_plugins = array();
        return true;
    }
}