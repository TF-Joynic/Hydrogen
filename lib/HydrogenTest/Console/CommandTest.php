<?php
use Hydrogen\Console\Command\Command;

/**
 * test
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testHasSpecifiedArg()
    {
        $cmd = new Command('ddd --io');
        $this->assertTrue($cmd->hasSpecifiedArg());
    }

}