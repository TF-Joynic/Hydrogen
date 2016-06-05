<?php

namespace Hydrogen\Route\Rule;

class RuleClosure
{
    private $cl;

    public function addRule($str, \Closure $callback)
    {
        $this->cl = $callback->bindTo($this, __CLASS__);
    }

    public function exe()
    {
        $cl = $this->cl;
        $cl();
    }
}