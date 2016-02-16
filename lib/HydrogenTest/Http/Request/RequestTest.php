<?php

namespace HydrogenTest\Http\Request;

use Hydrogen\Http\Request;
use Hydrogen\Debug\Variable;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHeaders()
    {
        $curl = new Request\Curl('http://www.hydrogen.local');

        $options = array(
            'HTTPHEADER' => array('Accept: vnd.example-com.foo+json; version=1.1')
        );
        $curl->setOptArr($options);
        $result = $curl->exec();
        $curl->close();

        Variable::dump($result);
    }
}