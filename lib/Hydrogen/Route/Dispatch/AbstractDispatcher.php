<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Hydrogen\Route\Exception\DispatchException;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractDispatcher implements DispatcherInterface
{
    /**
     * Dispatch Life Circle
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

        $this->initModule($request, $response);

        list($mvcCtrlClassName, $actMethodName)
            = $this->getCtrlClassAndActMethodName($targetModule, $targetCtrl, $targetAct);

        try {
            $this->executeCtrlAct($this->getCtrlInstance($mvcCtrlClassName), $actMethodName, $request, $response);
        } catch (DispatchException $e) {

            $actViewModel = $this->handleMvcError($request, $response, $e);

        }
    }
}