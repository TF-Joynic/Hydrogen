<?php

namespace HydrogenTest\Http\Request;

use Hydrogen\Http\Request\Client\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testReqeustMethod()
    {
        $curl = new Curl('www.hydrogen.local');
        $curl->setOpt('RETURNTRANSFER', 1);
        $result = $curl->exec();

        $this->assertStringStartsWith('index', $result);
    }
}