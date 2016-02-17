<?php

namespace Hydrogen\Route\Rule;

interface RuleInterface
{
    public function apply($path);
}