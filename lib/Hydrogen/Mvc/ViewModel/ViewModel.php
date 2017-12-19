<?php

namespace Hydrogen\Mvc\ViewModel;


use Psr\Http\Message\StreamInterface;


abstract class ViewModel
{
    protected $_headers = array(
        HTTP_HEADER_CONTENT_TYPE => 'text/html;charset=utf-8'
    );

    /**
     * @var array|object
     */
    protected $_vars = null;
    protected $_tpl = null;

    public function __construct($vars, $tplName = '')
    {
        $this->_vars = $vars;
        $this->_tpl = $tplName;
    }

    public function setHeader($name, $value)
    {
        $this->_headers[$name] = $value;
    }

    /**
     * concrete response header
     *
     * @return array
     */
    public function concreteHeader()
    {
        return$this->_headers;
    }

    /**
     * Output http response body, and it must be an instance of Stream according to PSR-7
     *
     * @return StreamInterface
     */
    public abstract function concreteBody();


}