<?php

namespace HydrogenTest\CoTask;

use Hydrogen\CoTask\Task;
use Hydrogen\Debug\Variable;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $genFunc = function ($a) {
            yield ($a + 1);
            yield 3;
        };
        $task = new Task($generator, 1);

        Variable::dump($task);
    }
}