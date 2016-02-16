<?php

namespace Hydrogen\Http\Filter;

// use Hydrogen\Http\Filter;

class Filter implements FilterInterface
{
	public function __construct()
	{}

	public function init()
	{
		return new FilterChain();
	}

	public function doFilter(Hydrogen\Http\Request $request, 
		Hydrogen\Http\Response $response, Filter\FilterChain $filterChain)
	{}

	/**
	 * When 
	 * @return [type] [description]
	 */
	public function destroy()
	{

	}

	public function __destruct()
	{

	}
}