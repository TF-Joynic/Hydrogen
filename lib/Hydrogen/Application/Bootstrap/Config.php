<?php

namespace Hydrogen\Application\Bootstrap;

class Config extends Decorator
{
	// private $_bootstrap = null;
	public function doBootstrap()
	{
		$this->_bootstrap->doBootstrap();
	}
}