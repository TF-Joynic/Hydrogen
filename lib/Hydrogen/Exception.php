<?php

namespace Hydrogen;

class Exception extends \Exception
{
	public function __construct($message = "", $code = 0, Exception $previous = NULL)
	{
		# code...
		parent::__construct($message, $code, $previous);
	}

	/**
     * Returns previous Exception
     *
     * @return Exception|null
     */
    public function _getPrevious()
    {
        return $this->_previous;
    }

    public function __toString()
    {
    	return parent::__toString();
    }
}