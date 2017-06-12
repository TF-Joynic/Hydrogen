<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Psr\Http\Message\ResponseInterface;

interface DispatcherInterface
{
	public function dispatch(RequestInterface $request, ResponseInterface $response);

    /**
     * initialize target module before get Ctrl instance
     *
     * @param $targetModuleName
     * @return mixed
     */
	public function initModule($targetModuleName);

    /**
     * get Ctrl instance of target ctrl
     *
     * @param $targetCtrlName
     * @return Ctrl
     */
	public function getCtrlInstance($targetCtrlName);

//	public function 

}