<?php

namespace Hydrogen\Http\Filter;

/**
 * if FilterChain is a list, Filter is just Node entity of the list
 */
class FilterChain implements \Iterator
{
	public function __construct()
	{
		echo 'FilterChain initialized!';
	}

	public function current()
	{}

	public function next()
	{}

	public function key()
	{}

	public function rewind()
	{}

	public function valid()
	{}
}