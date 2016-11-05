<?php

namespace Hydrogen\Mvc\ViewModel;
use Psr\Http\Message\StreamInterface;
use Hydrogen\Http\Response\Response;

abstract class ViewModel
{
    protected $_vars = null;
    protected $_tpl = null;

    public function __construct($vars, $tplName = '')
    {
        $this->_vars = $vars;
        $this->_tpl = $tplName;
    }

    /**
     * concrete response header
     *
     * @return array
     */
    public function concreteHeader()
    {
        return array(
            HTTP_HEADER_CONTENT_TYPE => 'text/html;charset=utf-8'
        );
    }

    /**
     * Output http response body, and it must be an instance of Stream according to PSR-7
     *
     * @return StreamInterface
     */
    public abstract function concreteBody();


}