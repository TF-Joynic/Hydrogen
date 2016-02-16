<?php

namespace Hydrogen\Http\Filter;

interface FilterInterface
{
	public function init();

	public function doFilter(Hydrogen\Http\Request $request, 
		Hydrogen\Http\Response $response, Filter\FilterChain $filterChain);

	public function destroy();
}