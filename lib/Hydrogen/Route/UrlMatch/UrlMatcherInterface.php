<?php

namespace Hydrogen\Route\UrlMatch;

use Hydrogen\Http\Request\ServerRequest;
use Hydrogen\Http\Response\Response;

interface UrlMatcherInterface
{
    /**
     * @param ServerRequest $request
     * @param Response $response
     * @return mixed
     */
    public function match(ServerRequest &$request, Response &$response);
}