<?php

namespace Hydrogen\Mvc\Ctrl\Plugin;

interface PluginInterface
{
	/**
	 * Plugin activate | the plugin start execution
	 */
	public function activate();

	/**
	 * Plugin terminate | the plugin end execution
	 */
	public function terminate();
}