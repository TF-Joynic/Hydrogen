<?php

namespace Hydrogen\Mvc\ViewModel;

use Hydrogen\Http\Request\ServerRequest;
use Hydrogen\Http\Response\Stream;

class JsonViewModel extends ViewModel
{

    public function concreteHeader()
    {
        return array(
            HTTP_HEADER_CONTENT_TYPE => 'application-json;charset=utf-8'
        );
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

        $body = json_encode($this->_vars);
        $bodyStream->write($body);

        return $bodyStream;
    }
}