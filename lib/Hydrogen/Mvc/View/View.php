<?php

namespace Hydrogen\Mvc;

use Hydrogen\Http\Exception\InvalidArgumentException;

class View
{
    private $_tpl;
    private $_vars;
    private $_layout = null;

	public function __construct($tpl, $vars, $layout = null)
	{
        if (is_object($tpl)) {
            $tpl = $tpl.'';
        }

        if (!is_string($tpl)) {
            throw new InvalidArgumentException('invalid argument $tpl');
        }
        $this->_tpl = $tpl;

        if (is_object($vars)) {
            $vars = get_object_vars($vars);
        }
        if (!is_array($vars)) {
            $vars = array();
        }

        $this->_vars = $vars;
        if ($layout) $this->_layout = $layout;
	}


}