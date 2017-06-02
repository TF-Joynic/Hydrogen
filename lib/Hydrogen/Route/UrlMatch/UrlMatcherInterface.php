<?php

namespace Hydrogen\Route\UrlMatch;


use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface UrlMatcherInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function match(RequestInterface $request, ResponseInterface $response);
}