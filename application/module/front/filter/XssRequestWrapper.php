<?php

namespace application\module\front\filter;


use Hydrogen\Http\Request\ServerRequest;
use Hydrogen\Utils\Sanitizer;

class XssRequestWrapper extends ServerRequest
{
    public function getAttribute($name, $default = null)
    {
        return Sanitizer::sanitizeXss(parent::getAttribute($name, $default));
    }


}