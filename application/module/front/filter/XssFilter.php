<?php

namespace application\module\front\filter;

use Hydrogen\Http\Filter\FilterChainInterface;
use Hydrogen\Http\Filter\FilterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class XssFilter implements FilterInterface
{

    public function init()
    {
        // TODO: Implement init() method.
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param FilterChainInterface $filterChain
     * @return mixed
     */
    public function doFilter(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
    {
        // TODO: Implement doFilter() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }
}