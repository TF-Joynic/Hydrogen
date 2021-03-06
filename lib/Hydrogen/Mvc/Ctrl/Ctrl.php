<?php

namespace Hydrogen\Mvc\Ctrl;

use application\module\front\filter\WebSecurityFilterChain;
use Hydrogen\Application\ApplicationContext;
use Hydrogen\Http\Filter\FilterChainInterface;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Http\Response\Response as Response;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;
use Hydrogen\Mvc\View\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    protected $_view = null;

    /**
     * @var array of PluginInterface
     */
    protected $_plugins = array();

    private $_active_plugins = array();

    protected $_layoutEnabled = true;

    /**
     * @var \Hydrogen\Http\Filter\FilterChainInterface|array
     */
    private $_filterChain = array();

    public function __construct()
    {
    }

    public function init()
    {
    }

    /**
     * name that represent this Ctrl instance
     */
    public function getName()
    {
        return static::class;
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

    public function getAbsUrl()
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

    public function enableLayout($doEnable = true)
    {
        $this->_layoutEnabled = true;
    }

    public function render($tpl, $vars, $output = false)
    {
        if (null == $this->_view) {
            $absTplPath = $this->getAbsTplFilePath($tpl);
            $this->_view = new View($absTplPath, $vars, $this->_layoutEnabled ? $this->getAbsTplFilePath($this->_layout) : null);
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
            ApplicationContext::getModuleDirPath(),
            $module,
            ApplicationContext::getTemplateDirName()
        )));

        return $templatePath.DIRECTORY_SEPARATOR.$tpl.'.'.ApplicationContext::getTemplatePostfix();
    }

    public function postRender()
    {
    }

    public function withRequest(ServerRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function withResponse(ResponseInterface $response)
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

    /**
     * @return array
     */
    public function interceptors()
    {
        return array();
    }

    /**
     * @return array
     */
    public function filters()
    {
        return array(
            WebSecurityFilterChain::class => array(
            )
        );
    }
}