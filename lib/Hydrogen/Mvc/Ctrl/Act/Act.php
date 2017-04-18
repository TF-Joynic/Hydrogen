<?php

namespace Hydrogen\Mvc\Ctrl\Act;

class Act
{
    /**
     * @var \Hydrogen\Mvc\Ctrl\Ctrl
     */
    private $_ctrlInstance = null;

    /**
     * @var string
     */
    private $_actName = null;

    public function __construct($ctrlInstance, $actName)
    {
        $this->_ctrlInstance = $ctrlInstance;
        $this->_actName = $actName;
    }

    /**
     * execute the act
     */
    public function execute()
    {

    }

    public function render()
    {
        
    }
}