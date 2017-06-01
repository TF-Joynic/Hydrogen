<?php

namespace Hydrogen\Http\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FilterChainInterface
{
    public function getId();

    public function doFilter(RequestInterface $request, ResponseInterface $response);
}