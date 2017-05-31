<?php

namespace application\console\Test;

use Hydrogen\Console\Console;
//use Psr\Log\

class TestCsl extends Console
{
    const NS_SEPARATOR = '\\';

    public function log()
    {
    }

    public function printAct($a)
    {
        echo '$a: '.$a;
    }
}