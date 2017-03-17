<?php

namespace Hydrogen\Http\Filter;

// use Hydrogen\Http\Filter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Filter implements FilterInterface
{
	public function __construct()
	{}

	public function init()
	{
		return new FilterChain();
	}

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param FilterChainInterface $filterChain
     * @return mixed
     */
    public function doFilter(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
    {

    }

	public function destroy()
	{

	}

	public function __destruct()
	{
        $this->destroy();
	}

}