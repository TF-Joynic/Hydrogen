<?php

namespace Hydrogen\Route\UrlMatch;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface UrlMatcherInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function match(ServerRequestInterface &$request, ResponseInterface &$response);
}