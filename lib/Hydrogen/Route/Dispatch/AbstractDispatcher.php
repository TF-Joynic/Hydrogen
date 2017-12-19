<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Application\ApplicationContext;
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

        } catch (DispatchException | \Exception $x) {

            $dispatchException = new DispatchException('An error occurred! ' .$x->getMessage(),
                $x instanceof DispatchException ? $x->getCode() : 500, $x);

            $targetModule = $request->getContextAttr(MODULE);
            $dispatchException->setModule($targetModule);
            $dispatchException->setCtrl($request->getContextAttr(CTRL));
            $dispatchException->setAct($request->getContextAttr(ACT));
            $dispatchException->setCtrlNamespace($this->getCtrlNamespace($targetModule));

            $actViewModel = $this->handleMvcError($request, $response, $dispatchException);
        }
    }

    protected function getCtrlNamespace($targetModule)
    {
        $moduleDir = ApplicationContext::getModuleDirPath();

        $moduleBaseNamespace = ltrim(str_replace(APPLICATION_PATH, '', $moduleDir), '/\\');
        $tmp = $moduleBaseNamespace ? $moduleBaseNamespace .'\\' : '';

        return 'application\\'.$tmp.$targetModule
            . '\\'.strtolower(ApplicationContext::getCtrlClassPostfix());
    }
}