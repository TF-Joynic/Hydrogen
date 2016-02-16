<?php

namespace Hydrogen\Application\Bootstrap;

class RuntimeCheckup extends Decorator
{
	public function doBootstrap()
	{
		$this->_bootstrap->doBootstrap();
//		echo "runt";
		/*if (defined('VENDOR_PATH') && 0 == strlen(VENDOR_PATH)) {
			throw new \Exception('Vendor path is not valid!');
		}*/
	}
}