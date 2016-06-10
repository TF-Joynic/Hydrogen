<?php

/**
 * ### Hydrogen_Application Core Class ###
 *
 * @category Hydrogen
 * @package Hydrogen\Application
 * @author Terrance Fung <wkf.joynic@gmail.com>
 */
namespace Hydrogen\Application;

use Hydrogen\Route\Router;
use Hydrogen\Application\Bootstrap\Base;
use Hydrogen\Application\Bootstrap\RuntimeCheckup;
use Hydrogen\Application\Bootstrap\Config;

final class Application
{
	public function __construct()
	{
        // init config etc.
	}

	/**
	 * [bootstrap description]
	 * @return void
	 */
	private function bootstrap()
	{
		$bootstrap = new Base();

		$bootstrap = new RuntimeCheckup($bootstrap);
		$bootstrap = new Config($bootstrap);
		$bootstrap->doBootstrap();
	}

	public function run()
	{
        $this->bootstrap();
        Router::getInstance()->route();
	}
}
