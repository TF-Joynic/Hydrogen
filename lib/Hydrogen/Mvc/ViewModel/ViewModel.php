<?php

namespace Hydrogen\Mvc\ViewModel;

use Hydrogen\Http\Response\Response;

abstract class ViewModel
{
    protected $_vars = null;

    public function __construct($vars)
    {
        $this->_vars = $vars;
    }

    public function outputHeader()
    {

    }

    public abstract function output();
}