<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Application\Execute\Executor;
use Hydrogen\Http\Request\ServerRequest;
use Hydrogen\Http\Response\Response;
use Hydrogen\Load\Loader;
use Hydrogen\Load\Exception\LoadFailedException;
use Hydrogen\Route\Exception\DispatchException;
use Hydrogen\Route\Exception\RuntimeException;

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

    public function __construct(ServerRequest $request, Response $response)
    {
        $this->_request = $request;
        $this->_response = $response;
        if (1) {

        }

        /*$this->_module = $_module;
        $this->_ctrl = $_ctrl;
        $this->_act = $_act;*/
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
//        pomvc();exit;

        $mvcInitFile = $moduleDir . '/' . $target_module . '/' . ucfirst($target_module) . $initFileNamePost;
        $this->importFileByAbsPath($mvcInitFile);
        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');

        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        $ctrlClassBaseName = ($target_ctrl) . $EXEC->getCtrlClassPostfix();
        $mvcCtrlClassName = 'application\\'.$tmp.$target_module
            . '\\ctrl\\' . $ctrlClassBaseName;

        $actMethodName = $target_act . $EXEC->getActMethodPostfix();
//        pre(Autoloader::getInstance()->getNamespaces());exit;

        try {
            $this->executeAct($mvcCtrlClassName, $actMethodName);
        } catch (DispatchException $e) {

            // force to ErrorCtrl -> (indexAct)
            $this->handleMvcError(str_replace($ctrlClassBaseName,
                $EXEC->getErrorCtrlName().$EXEC->getCtrlClassPostfix(), $mvcCtrlClassName), $EXEC->getDefaultAct(), $e);

        }
//        $response = new Response();

        // attach 'em to ctrl
//        $

        // plugin init
//		$

        // Dispatch


        // plugin terminate
    }

    public function handleMvcError($mvcErrorCtrlClassName, $mvcErrorActName, DispatchException $e)
    {
        if (!class_exists($mvcErrorCtrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new RuntimeException('Error ctrl class: ' . $mvcErrorCtrlClassName . ' is not properly defined!');
        }

        $mvcCtrlInstance = new $mvcErrorCtrlClassName($this->_request, $this->_response);
        if (!method_exists($mvcCtrlInstance, $mvcErrorActName)) {
            throw new RuntimeException('Error ctrl: ' . $mvcErrorCtrlClassName . ' has no act called: ' . $mvcErrorActName);
        }

        $mvcCtrlInstance->$mvcErrorActName();
    }

    private function executeAct($mvcCtrlClassName, $actMethodName)
    {
        if (!class_exists($mvcCtrlClassName, true)) {
            // second argument means we use autoload impl to find the class
            throw new DispatchException('ctrl class: ' . $mvcCtrlClassName . ' is not found', 404);
        }

        $mvcCtrlInstance = new $mvcCtrlClassName($this->_request, $this->_response);
        if (!method_exists($mvcCtrlInstance, $actMethodName)) {
            throw new DispatchException('ctrl: ' . $mvcCtrlClassName . ' has no act called: ' . $actMethodName, 404);
        }

        $mvcCtrlInstance->$actMethodName();
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