<?php

namespace Hydrogen\Http\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FilterInterface
{
	public function init();

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param FilterChainInterface $filterChain
     * @return mixed
     */
    public function doFilter(RequestInterface &$request, ResponseInterface &$response, FilterChainInterface $filterChain);

	public function destroy();
}