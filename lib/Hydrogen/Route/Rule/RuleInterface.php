<?php

namespace Hydrogen\Route\Rule;

interface RuleInterface
{
    /**
     * @param $path
     * @return array
     */
    public function apply($path);
}