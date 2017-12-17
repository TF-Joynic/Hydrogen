<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Route\Exception\DispatchException;
use Psr\Http\Message\ResponseInterface;

interface DispatcherInterface
{
	public function dispatch(RequestInterface $request, ResponseInterface $response);

    /**
     * initialize target module before get Ctrl instance
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
	public function initModule(RequestInterface $request, ResponseInterface $response);

    /**
     * get ctrl and act name
     *
     * @param $targetModule
     * @param $targetCtrl
     * @param $targetAct
     * @return array
     */
    public function getCtrlClassAndActMethodName($targetModule, $targetCtrl, $targetAct);

    /**
     * get Ctrl instance of target ctrl
     *
     * @param $ctrlClassName
     * @return Ctrl
     */
    public function getCtrlInstance($ctrlClassName);

    /**
     * @param Ctrl $ctrlInstance
     * @param $actMethodName
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function executeCtrlAct(Ctrl $ctrlInstance, $actMethodName, RequestInterface $request, ResponseInterface $response);

    /**
     * handle mvc error to return human readable page
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param DispatchException $e
     * @return \Hydrogen\Mvc\ViewModel\ViewModel
     */
    public function handleMvcError(RequestInterface $request, ResponseInterface $response, DispatchException $e);
}