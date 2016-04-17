<?php

namespace Hydrogen\Mvc\ViewModel;

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
        return new Stream();
    }
}