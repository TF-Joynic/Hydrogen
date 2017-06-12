<?php

namespace Hydrogen\Mvc\Ctrl\Act;

use Hydrogen\Mvc\Ctrl\Ctrl;


class Act extends AbstractAct
{
    /**
     * @var Ctrl
     */
    private $_ctrlInstance = null;

    /**
     * @var string
     */
    private $_actName = null;

    public function __construct(Ctrl $ctrlInstance)
    {
        $this->_ctrlInstance = $ctrlInstance;
    }

    /**
     * execute the act
     *
     * @param $actName
     */
    public function execute($actName)
    {
        
    }

    public function render()
    {

    }
}