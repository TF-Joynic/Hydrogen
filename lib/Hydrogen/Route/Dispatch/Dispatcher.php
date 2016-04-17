<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Http\Response\Response;
use Hydrogen\Application\Execute\Executor;
use Hydrogen\Http\Request\ServerRequest as Request;
use Hydrogen\Route\Exception\RuntimeException;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Load\Exception\LoadFailedException;

class Dispatcher extends AbstractDispatcher
{
    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    public function __construct(Request $request, Response $response)
    {
        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     * concrete request & response
     */
    public function dispatch()
    {
        $target_module = $this->_request->getContextAttr('module');
        $target_ctrl = $this->_request->getContextAttr('ctrl');
        $target_act = $this->_request->getContextAttr('act');

        if (!$target_module || !$target_act || !$target_ctrl) {
            return false;
        }

        // firstly we must confirm the ctrl is reachable
        $EXEC = Executor::getInstance();
        $moduleDir = $EXEC->getModuleDir();

        $moduleDir = rtrim($moduleDir, '/\\');
        $initFileNamePost = $EXEC->getModuleInitFileName();

        $moduleInitFile = $moduleDir . '/Module' . $initFileNamePost;
        $this->importFileByAbsPath($moduleInitFile);

        $mvcInitFile = $moduleDir . '/' . $target_module . '/' . ucfirst($target_module) . $initFileNamePost;
        $this->importFileByAbsPath($mvcInitFile);
        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');

        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        $ctrlClassBaseName = ($target_ctrl) . $EXEC->getCtrlClassPostfix();
        $mvcCtrlClassName = 'application\\'.$tmp.$target_module
            . '\\ctrl\\' . $ctrlClassBaseName;

        $actPostFix = $EXEC->getActMethodPostfix();
        $actMethodName = $target_act . $actPostFix;

        $actReturn = null;
        try {
            $actReturn = $this->executeAct($mvcCtrlClassName, $actMethodName);
        } catch (DispatchException $e) {

            // force to ErrorCtrl -> (indexAct) beneath the same dir
            $actReturn = $this->handleMvcError(str_replace($ctrlClassBaseName,
                $EXEC->getErrorCtrlName().$EXEC->getCtrlClassPostfix(),
                $mvcCtrlClassName), $EXEC->getErrorActName().$actPostFix, $e);

        }

        /** ViewModel timing! */
        // concrete body from $mvcCtrlInstance for RESPONSE
        $this->_response->withBody();

        // attach 'em to ctrl
//        $

        // plugin init
//		$

        // Dispatch


        // plugin terminate
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
            throw new RuntimeException('Error ctrl: ' . $mvcErrorCtrlClassName . ' has no act called: ' . $mvcErrorActName);
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

        $mvcCtrlInstance->withRequest($this->_request)->withResponse($this->_response);

        return $mvcCtrlInstance->$actMethodName();
    }

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