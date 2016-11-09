<?php

namespace HydrogenTest\Console\Command;

use Hydrogen\Console\Command\parser;

class parserTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateCommand()
    {
        $cmdStr = 'A::c::c';
        $this->assertTrue(parser::validateCommand($cmdStr));
    }
}