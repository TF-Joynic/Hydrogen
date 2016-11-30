<?php

namespace Hydrogen\Mvc\ViewModel;

use Hydrogen\Http\Response\Stream;
use Psr\Http\Message\StreamInterface;

class TplViewModel extends ViewModel
{
    public function __construct($vars, $tplName = '')
    {
        parent::__construct($vars, $tplName);
    }

    /**
     * Output http response body, and it must be an instance of Stream according to PSR-7
     *
     * @return StreamInterface
     */
    public function concreteBody()
    {
        $bodyStream = new Stream(Stream::DEFAULT_STREAM_WRAPPER, array(
            Stream::MODE => 'w'
        ));

        $body =<<<HTML
<html>
    <head>
        <title>Voodoo</title>
    </head>
    <body>
        <h1>Vooodoo</h1>
    </body>
</html>
HTML;

        $len = $bodyStream->write($body);
        return $bodyStream;
    }


}