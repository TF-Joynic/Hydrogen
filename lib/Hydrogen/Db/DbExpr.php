<?php

namespace Hydrogen\Db;

class DbExpr
{
    private $_expr = '';

    public function __construct($expr)
    {
        $this->_expr = $expr;
    }

    public function __toString()
    {
        return $this->_expr;
    }
}