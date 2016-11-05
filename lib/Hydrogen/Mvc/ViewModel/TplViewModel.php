<?php

namespace Hydrogen\Mvc\ViewModel;

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
        // TODO: Implement concreteBody() method.

    }


}