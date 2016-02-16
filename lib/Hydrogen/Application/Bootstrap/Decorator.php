<?php

namespace Hydrogen\Application\Bootstrap;

class Decorator extends abstractBootstrap
{
	protected $_bootstrap = null;

	public function __construct(abstractBootstrap $bootstrap)
	{
		$this->_bootstrap = $bootstrap;
	}

	public function doBootstrap()
	{	
		$this->_bootstrap->doBootstrap();
	}
}