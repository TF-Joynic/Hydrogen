<?php

namespace HydrogenTest\Console;

use Hydrogen\Console\Directive;

class DirectiveTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCommandSeparator()
    {
        $sp = '$$';
        Directive::setCommandSeparator($sp);
        var_dump(Directive::getCommandSeparator());
    }

    public function testSetCommandSeparator()
    {

    }
}