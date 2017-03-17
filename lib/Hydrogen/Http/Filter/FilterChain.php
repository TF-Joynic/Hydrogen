<?php

namespace Hydrogen\Http\Filter;

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