<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Application\ApplicationContext;
use Hydrogen\Route\Exception\RuntimeException;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Load\Exception\LoadFailedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher extends AbstractDispatcher
{
    /**
     * @var ServerRequestInterface
     */
    private $_request;

    /**
     * @var ResponseInterface
     */
    private $_response;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     * concrete request & response
     */
    public function dispatch()
    {
        $target_module = $this->_request->getContextAttr(MODULE);
        $target_ctrl = $this->_request->getContextAttr(CTRL);
        $target_act = $this->_request->getContextAttr(ACT);

        if (!$target_module || !$target_act || !$target_ctrl) {
            return false;
        }

        // firstly we must confirm the ctrl is reachable
        $moduleDir = ApplicationContext::getModuleDir();

        $moduleDir = rtrim($moduleDir, '/\\');
        $initFileNamePost = ApplicationContext::getModuleInitFileName();

        $moduleInitFile = $moduleDir . '/Module' . $initFileNamePost;
        $this->importFileByAbsPath($moduleInitFile);

        $mvcInitFile = $moduleDir . '/' . $target_module . '/' . ucfirst($target_module) . $initFileNamePost;
        $this->importFileByAbsPath($mvcInitFile);
        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');

        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        $ctrlClassBaseName = ($target_ctrl) . ApplicationContext::getCtrlClassPostfix();
        $mvcCtrlClassName = 'application\\'.$tmp.$target_module
            . '\\ctrl\\' . $ctrlClassBaseName;

        $actPostFix = ApplicationContext::getActMethodPostfix();
        $actMethodName = $target_act . $actPostFix;

        $actViewModel = null;

        try {
            $this->executeAct($mvcCtrlClassName, $actMethodName);

        } catch (DispatchException $e) {

            // force to ErrorCtrl -> (indexAct) beneath the same dir
            $actViewModel = $this->handleMvcError(str_replace($ctrlClassBaseName,
                ApplicationContext::getErrorCtrl().ApplicationContext::getCtrlClassPostfix(),
                $mvcCtrlClassName), ApplicationContext::getErrorAct().$actPostFix, $e);

        }

    }

    /**
     * @param string $mvcErrorCtrlClassName
     * @param string $mvcErrorActName
     * @param DispatchException $e
     * @return \Hydrogen\Mvc\ViewModel\ViewModel
     */
    public function handleMvcError($mvcErrorCtrlClassName, $mvcErrorActName, DispatchException $e)
    {
        if (!class_exists($mvcErrorCtrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new RuntimeException('Error ctrl class: ' . $mvcErrorCtrlClassName . ' is not properly defined!');
        }

        $mvcCtrlInstance = new $mvcErrorCtrlClassName();
        if (! $mvcCtrlInstance instanceof Ctrl) {
            throw new DispatchException('Ctrl class: '.$mvcErrorCtrlClassName.' is not subclass of Ctrl', 404);
        }

        $methodVar = array($mvcCtrlInstance, $mvcErrorActName);
        if (!method_exists($mvcCtrlInstance, $mvcErrorActName) || !is_callable($methodVar, true)) {
            throw new DispatchException('Error ctrl: ' . $mvcErrorCtrlClassName . ' has no act called: ' . $mvcErrorActName, 404);
        }

        $mvcCtrlInstance->withRequest($this->_request);
        $this->_response->withStatus($e->getCode());
        $mvcCtrlInstance->withResponse($this->_response);

        return $mvcCtrlInstance->$mvcErrorActName();
    }

    /**
     * @param string $mvcCtrlClassName
     * @param string $actMethodName
     */
    private function executeAct($mvcCtrlClassName, $actMethodName)
    {
        if (!class_exists($mvcCtrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new DispatchException('ctrl class: ' . $mvcCtrlClassName . ' is not found', 404);
        }

        $mvcCtrlInstance = new $mvcCtrlClassName();

        if (! $mvcCtrlInstance instanceof Ctrl) {
            throw new DispatchException('Ctrl class: '.$mvcCtrlClassName.' is not subclass of Ctrl', 404);
        }

        // preDispatch
        $mvcCtrlInstance->preDispatch();

        $methodVar = array($mvcCtrlInstance, $actMethodName);
        if (!method_exists($mvcCtrlInstance, $actMethodName) || !is_callable($methodVar, true, $callable_name)) {
            throw new DispatchException('ctrl: ' . $mvcCtrlClassName . ' has no act called: ' . $actMethodName, 404);
        }

        // plugin
        $mvcCtrlInstance->activatePlugins();

        // filter
        

        // interceptor


        $mvcCtrlInstance->withRequest($this->_request);
        $mvcCtrlInstance->withResponse($this->_response);

        // init
        $mvcCtrlInstance->init();

        $viewModel = $this->invokeCtrlAct($mvcCtrlInstance, $actMethodName);

        $mvcCtrlInstanceResp = $mvcCtrlInstance->getResponse();

        // http header(s)
        foreach ($viewModel->concreteHeader() as $headerName => $headerValue) {
            $mvcCtrlInstanceResp->withHeader($headerName, $headerValue);
        }

        // http body
        $mvcCtrlInstanceResp->withBody($viewModel->concreteBody());

        // plugin
        $mvcCtrlInstance->terminatePlugins();

        // postDispatch
        $mvcCtrlInstance->postDispatch();

        // response http body!
        $this->performResponse($mvcCtrlInstanceResp);
    }

    /**
     * @param $mvcCtrlInstance
     * @param $actMethodName
     * @return \Hydrogen\Mvc\ViewModel\ViewModel
     */
    private function invokeCtrlAct(&$mvcCtrlInstance, $actMethodName)
    {
        return $mvcCtrlInstance->$actMethodName();
    }

    /**
     * @param ResponseInterface $response
     */
    private function performResponse(&$response)
    {
        if (true) {
            ob_start();
            /*pre($response->getHeaders());exit;*/
            foreach ($response->getHeaders() as $headerName => $headerValue) {
                header(sprintf('%s: %s', $headerName, $headerValue), false);
            }

            $responseBody = $response->getBody();
            echo $responseBody->__toString();
            $responseBody->close();
            ob_end_flush();
        }

    }

    /*private function beforeActHooks()
    {

    }

    private function afterActHooks()
    {

    }*/

    private function importFileByAbsPath($absPath)
    {
        if (!file_exists($absPath)) {
            return false;
        }

        if (false === Loader::getInstance()->import($absPath)) {
            throw new LoadFailedException('Failed to load file: '.$absPath);
        }

        return true;
    }
}