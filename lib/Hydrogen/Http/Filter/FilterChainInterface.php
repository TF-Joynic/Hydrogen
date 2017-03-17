<?php

namespace Hydrogen\Http\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FilterChainInterface extends \Iterator
{
    function doFilter(RequestInterface $request, ResponseInterface $response);
}