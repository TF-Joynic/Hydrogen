<?php

namespace Hydrogen\Route\Exception;

class DispatchException extends \RuntimeException
{
    /**
    @var string
     */
    private $_module;

    /**
     * @var string
     */
    private $_ctrl;

    /**
     * @var string
     */
    private $_act;

    /**
     * @var string
     */
    private $_ctrlNamespace;

    /**
     * @param string $dispatchErrorMsg
     * @param int $httpStatusCode
     * @param \Exception|null $previous
     */
    public function __construct($dispatchErrorMsg = "", $httpStatusCode = 404, \Exception $previous = null)
    {
        parent::__construct($dispatchErrorMsg, $httpStatusCode, $previous);
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * @param mixed $targetModule
     */
    public function setModule($targetModule)
    {
        $this->_module = $targetModule;
    }

    /**
     * @return string
     */
    public function getCtrl()
    {
        return $this->_ctrl;
    }

    /**
     * @param string $targetCtrl
     */
    public function setCtrl($targetCtrl)
    {
        $this->_ctrl = $targetCtrl;
    }

    /**
     * @return mixed
     */
    public function getAct()
    {
        return $this->_act;
    }

    /**
     * @param mixed $targetAct
     */
    public function setAct($targetAct)
    {
        $this->_act = $targetAct;
    }

    /**
     * @return string
     */
    public function getCtrlNamespace()
    {
        return $this->_ctrlNamespace;
    }

    /**
     * @param string $ctrlNamespace
     */
    public function setCtrlNamespace($ctrlNamespace)
    {
        $this->_ctrlNamespace = $ctrlNamespace;
    }



}