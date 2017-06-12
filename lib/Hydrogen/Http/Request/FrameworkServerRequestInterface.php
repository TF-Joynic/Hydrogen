<?php

namespace Hydrogen\Http\Request;


use Psr\Http\Message\ServerRequestInterface;

interface FrameworkServerRequestInterface extends ServerRequestInterface
{
    public function isGet();

    public function isPost();

    public function isPut();

    public function isPatch();

    public function isDelete();

    public function isHead();

    public function isOptions();

    public function isAjax();

    public function getQuery($name, $default = null);

    public function getQueryInt($name, $default = 0);

    /**
     * get raw query param without filter
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getQueryRaw($name, $default = null);

    public function getPost($name, $default = null);

    public function getPostInt($name, $default = 0);

    public function getPostRaw($name, $default = null);

    public function getAttributeInt($name, $default = null);

    public function getAttributeRaw($name, $default = null);

    public function withAttributes($attrs);

    /**
     * @param $name
     * @return array
     */
    public function getContextAttr($name);

    /**
     * @param $name
     * @param $value
     * @return self
     */
    public function withContextAttr($name, $value);
}