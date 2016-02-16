<?php

namespace Hydrogen\Config\Writer;

use Hydrogen\Config\Exception\FileNotFoundException;

class Writer
{
	public function __construct($filePath)
	{
		if (!file_exists($filePath)) {
			throw new FileNotFoundException('Could not find the file: '.$filePath);
		}

		/*if () {
			
		}*/
	}

	/**
	 * modify the config file
	 * 
	 * @param string $scope   config scope(file basename)
	 * @param string $section config file section
	 * @param string $key     config key
	 * @param mixed  $value   must be scalar value
	 */
	public function set($scope, $section, $key, $value)
	{
		if (!is_scalar($value)) {
			return false;
		}

		/*if () {
			
		}*/
	}

	public function write()
	{
		
	}
}