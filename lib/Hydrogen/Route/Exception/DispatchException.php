<?php

namespace Hydrogen\Route\Exception;

class DispatchException extends \RuntimeException
{
    /**
     * @param string $dispatchErrorMsg
     * @param int $httpStatusCode
     * @param \Exception|null $previous
     */
    public function __construct($dispatchErrorMsg = "", $httpStatusCode = 404, \Exception $previous = null)
    {
        parent::__construct($dispatchErrorMsg, $httpStatusCode, $previous);
    }
}