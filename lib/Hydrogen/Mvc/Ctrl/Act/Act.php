<?php

namespace Hydrogen\Mvc\Ctrl\Act;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\ViewModel;
use Hydrogen\Route\Exception\DispatchException;


class Act extends AbstractAct
{
    /**
     * @var Ctrl
     */
    private $_ctrlInstance = null;

    /**
     * @var string
     */
    private $_actMethodName = null;


    public function __construct(Ctrl $ctrlInstance, $actMethodName)
    {
        $this->_ctrlInstance = $ctrlInstance;
        $this->_actMethodName = $actMethodName;
    }

    /**
     * execute the act
     *
     * @return ViewModel
     */
    public function execute()
    {
        $methodVar = array($this->_ctrlInstance, $this->_actMethodName);
        if (!method_exists($this->_ctrlInstance, $this->_actMethodName) || !is_callable($methodVar)) {

            throw new DispatchException('ctrl: ' . $this->_ctrlInstance->getName()
                . ' has no act method called: ' . $this->_actMethodName, 404);

        }

        return $this->_ctrlInstance->{$this->_actMethodName}();
    }

    public function render()
    {

    }

}