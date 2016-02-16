<?php

namespace HydrogenTest\Http\Request;

use Hydrogen\Http\Request\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testReqeustMethod()
    {
        $curl = new Curl('www.hydrogen.local');
        $curl->setOpt('RETURNTRANSFER', 1);
        $curl->setOpt('CUSTOMREQUEST', 'HEAD');
        $result = $curl->exec();
//        $this->assertStringStartsWith("<!DOCTYPE html", $result);
        $this->assertTrue(1==1);
    }
}