<?php
/**
 * Created by IntelliJ IDEA.
 * User: xiaolei
 * Date: 17/5/4
 * Time: 16:40
 */

namespace application\module\front\ctrl;

use application\thrift\HelloServiceClient;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TSocket;

class ThriftCtrl extends FrontCtrl
{
    public function indexAct()
    {
        echo "this is thrift test...<br/>";

        $socket = new TSocket('localhost', 7911);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);

        $client = new HelloServiceClient($protocol);
        $transport->open();

        echo $client->helloString("protocol CCC");
        $transport->close();

        // 异步方式：
        /*$client->send_helloString("async");
        $transport->close();*/
    }
}