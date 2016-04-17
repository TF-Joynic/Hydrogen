<?php

namespace Hydrogen\Mvc\ViewModel;
use Psr\Http\Message\StreamInterface;
use Hydrogen\Http\Response\Response;

abstract class ViewModel
{
    protected $_vars = null;

    public function __construct($vars)
    {
        $this->_vars = $vars;
    }

    /**
     * concrete response header
     */
    public function concreteHeader()
    {
        return array(
            HTTP_HEADER_CONTENT_TYPE => 'application-json;charset=utf-8'
        );
    }

    /**
     * Output http response body, and it must be an instance of Stream according to PSR-7
     *
     * @return StreamInterface
     */
    public abstract function concreteBody();
}