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
     * @var int
     */
    private static $_dispatchCount;

    /**
     * initialize public module logic and target module before get Ctrl instance
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function initModule(RequestInterface $request, ResponseInterface $response)
    {
        $targetModule = $request->getContextAttr(MODULE);

        // init common module INIT logic
        $moduleDir = ApplicationContext::getModuleDirPath();

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
    }

    /**
     * get ctrl and act name
     *
     * @param $targetModule
     * @param $targetCtrl
     * @param $targetAct
     * @return array
     */
    public function getCtrlClassAndActMethodName($targetModule, $targetCtrl, $targetAct)
    {
        $ctrlNamespace = $this->getCtrlNamespace($targetModule);

        $ctrlClassName = $targetCtrl . ApplicationContext::getCtrlClassPostfix();
        $mvcCtrlClassName = $ctrlNamespace.'\\' . $ctrlClassName;

        $actPostFix = ApplicationContext::getActMethodPostfix();
        $actMethodName = $targetAct . $actPostFix;

        return array($mvcCtrlClassName, $actMethodName);
    }

    private function getCtrlNamespace($targetModule)
    {
        $moduleDir = ApplicationContext::getModuleDirPath();

        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');
        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        return 'application\\'.$tmp.$targetModule
            . '\\'.strtolower(ApplicationContext::getCtrlClassPostfix());
    }

    /**
     * get Ctrl instance of target ctrl
     *
     * @param $ctrlClassName
     * @return Ctrl
     */
    public function getCtrlInstance($ctrlClassName)
    {
        if (!class_exists($ctrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new DispatchException('ctrl class: ' . $ctrlClassName . ' is not found', 404);
        }

        $mvcCtrlInstance = new $ctrlClassName();
        if (! $mvcCtrlInstance instanceof Ctrl) {
            throw new DispatchException('Ctrl class: '.$ctrlClassName.' is not subclass of Ctrl', 404);
        }

        return $mvcCtrlInstance;
    }

    public function executeCtrlAct(Ctrl $ctrlInstance, $actMethodName, RequestInterface $request, ResponseInterface $response)
    {
        self::$_dispatchCount ++;

        try {
            // plugin
            $ctrlInstance->activatePlugins();

            $ctrlInstance->withRequest($request);
            $ctrlInstance->withResponse($response);

            // preDispatch
            $ctrlInstance->preDispatch();

            // interceptor


            // filter

            // init
            $ctrlInstance->init();

            $actInstance = new Act($ctrlInstance, $actMethodName);
            $viewModel = $actInstance->execute();

            $ctrlInstanceResp = $ctrlInstance->getResponse();

            // http header(s)
            foreach ($viewModel->concreteHeader() as $headerName => $headerValue) {
                $ctrlInstanceResp->withHeader($headerName, $headerValue);
            }

            // http body
            $ctrlInstanceResp->withBody($viewModel->concreteBody());

            // postDispatch
            $ctrlInstance->postDispatch();

            // response http body!
            $this->performResponse($ctrlInstanceResp);

            // plugin
            $ctrlInstance->terminatePlugins();
        } catch (\Exception $x) {
            $dispatchException = new DispatchException('An error occurred! ' .$x->getMessage(), 500, $x);
            $targetModule = $request->getContextAttr(MODULE);
            $dispatchException->setModule($targetModule);
            $dispatchException->setCtrl($request->getContextAttr(CTRL));
            $dispatchException->setAct($request->getContextAttr(ACT));
            $dispatchException->setCtrlNamespace($this->getCtrlNamespace($targetModule));

            throw new DispatchException('An error occurred! ' .$x->getMessage(), 500, $x);
        }
    }

    /**
     * handle mvc error to return human readable page
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param DispatchException $e
     * @return \Hydrogen\Mvc\ViewModel\ViewModel
     */
    public function handleMvcError(RequestInterface $request, ResponseInterface $response, DispatchException $e)
    {
        $mvcErrorCtrlClassName = ApplicationContext::getErrorCtrlName();
        $mvcErrorCtrlClassName = $e->getCtrlNamespace().'\\'.$mvcErrorCtrlClassName;

        if (self::$_dispatchCount > 1) {

            throw new RuntimeException('Error ctrl class: ' . $mvcErrorCtrlClassName
                . ' caused loop dispatch! current dispatch count: '.self::$_dispatchCount);

        }

        if (!class_exists($mvcErrorCtrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new RuntimeException('Error ctrl class: ' . $mvcErrorCtrlClassName . ' is not properly defined!');
        }

        $mvcCtrlInstance = new $mvcErrorCtrlClassName();
        if (! $mvcCtrlInstance instanceof Ctrl) {
            throw new DispatchException('Ctrl class: '.$mvcErrorCtrlClassName.' is not subclass of Ctrl', 404);
        }

        return $this->executeCtrlAct($mvcCtrlInstance, ApplicationContext::getErrorActName()
            .ApplicationContext::getActMethodPostfix(), $request, $response);
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