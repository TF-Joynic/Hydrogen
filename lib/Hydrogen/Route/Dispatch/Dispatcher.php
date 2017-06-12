<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Http\Interceptor\InterceptorInterface;
use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Act\Act;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Application\ApplicationContext;
use Hydrogen\Route\Exception\RuntimeException;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Load\Exception\LoadFailedException;
use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher extends AbstractDispatcher
{
    /**
     * dispatch request to Ctrl::Act
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {
        $targetModule = $request->getContextAttr(MODULE);
        $targetCtrl = $request->getContextAttr(CTRL);
        $targetAct = $request->getContextAttr(ACT);

        if (!$targetModule || !$targetCtrl || !$targetAct) {
            return false;
        }

        // firstly we must confirm the ctrl is reachable
        $moduleDir = ApplicationContext::getModuleDir();

        $moduleDir = rtrim($moduleDir, '/\\');
        $initFileNamePost = ApplicationContext::getModuleInitFileName();

        $moduleInitFile = $moduleDir . '/Module' . $initFileNamePost;
        $this->importFileByAbsPath($moduleInitFile);

        $mvcInitFile = implode(DIRECTORY_SEPARATOR, array_filter(array(
            $moduleDir,
            $targetModule,
            ucfirst($targetModule) . $initFileNamePost
        )));

        $this->importFileByAbsPath($mvcInitFile);
        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');

        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        $ctrlClassBaseName = ($targetCtrl) . ApplicationContext::getCtrlClassPostfix();
        $mvcCtrlClassName = 'application\\'.$tmp.$targetModule
            . '\\ctrl\\' . $ctrlClassBaseName;

        $actPostFix = ApplicationContext::getActMethodPostfix();
        $actMethodName = $targetAct . $actPostFix;

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
     * get Ctrl instance of target ctrl
     *
     * @return Ctrl
     */
    public function getCtrlInstance()
    {
        // TODO: Implement getCtrlInstance() method.
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

        $methodVar = array($mvcCtrlInstance, $actMethodName);
        if (!method_exists($mvcCtrlInstance, $actMethodName) || !is_callable($methodVar, true, $callable_name)) {
            throw new DispatchException('ctrl: ' . $mvcCtrlClassName . ' has no act called: ' . $actMethodName, 404);
        }

        try {
            $mvcCtrlInstance->withRequest($this->_request);
            $mvcCtrlInstance->withResponse($this->_response);

            // preDispatch
            $mvcCtrlInstance->preDispatch();

            // plugin
            $mvcCtrlInstance->activatePlugins();

            // interceptor


            // filter


            // init
            $mvcCtrlInstance->init();

            $viewModel = $this->runCtrlAct($mvcCtrlInstance, $actMethodName);

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
        } catch (\Exception $x) {
            throw new DispatchException('An error occured! ' .$x->getMessage(), 500, $x);
        }

    }

    /**
     * @param $mvcCtrlInstance
     * @param $actMethodName
     * @return \Hydrogen\Mvc\ViewModel\ViewModel
     */
    private function runCtrlAct($mvcCtrlInstance, $actMethodName)
    {
        return $mvcCtrlInstance->$actMethodName();
    }

    /**
     * @param ResponseInterface $response
     */
    private function performResponse($response)
    {
        ob_start();

        foreach ($response->getHeaders() as $headerName => $headerValue) {
            header(sprintf('%s: %s', $headerName, $headerValue), false);
        }

        $responseBody = $response->getBody();
        echo $responseBody->__toString();
        $responseBody->close();
        ob_end_flush();
    }

    /**
     * @param $mvcCtrlInstance Ctrl
     */
    private function applyInterceptor($mvcCtrlInstance)
    {
        // fetch interceptors from Ctrl instance
        $interceptors = $mvcCtrlInstance->interceptors();
        if (!$interceptors) return ;

        $ctxInterceptors = ApplicationContext::getInterceptorInstances();

        foreach ($interceptors as $interceptor) {
            // fetch from ApplicationContext, if none, instantiate it

            $interceptorInstance = null;
            if (isset($ctxInterceptors[$interceptor])) {
                $interceptorInstance = $ctxInterceptors[$interceptor];
            } else {
                $interceptorInstance = new $interceptor;
            }

            $this->intercept($interceptorInstance);


        }


    }

    /**
     * @param InterceptorInterface $interceptor
     */
    private function intercept(InterceptorInterface $interceptor)
    {
        if (1) {

        }
    }

    private function importFileByAbsPath($absPath)
    {
        if (false === Loader::import($absPath)) {
            throw new LoadFailedException('Failed to load file: '.$absPath);
        }

        return true;
    }


}