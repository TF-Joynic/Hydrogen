<?php

namespace Hydrogen\Config\Reader;

use Hydrogen\Config\Exception\FileNotFoundException;
use Hydrogen\Config\Exception\ReaderClassNotDefinedException;

class ReaderFactory
{
	/**
	 * concrete the Reader class
	 *
	 * @param  string $file file abs path
	 * @return \Hydrogen\Config\Reader\ReaderInterface
	 * @throws ReaderClassNotDefinedException
	 * @throws \Exception
	 */
	public static function factory($file)
	{
		$ext = '';
		if (!is_string($file) || empty($file) 
			|| false === $ext = strrchr($file, '.')) {

			throw new \UnexpectedValueException('file path must be string with ext.');

		}

		if (!file_exists($file)) {
			throw new FileNotFoundException('file: '.$file.' dose not exist!');
		}

		$ext = substr($ext, 1);
		$readerCls = ucfirst(strtolower($ext));
		$readerCls = 'Hydrogen\\Config\\Reader\\'.$readerCls;
		if (!class_exists($readerCls)) {

			throw new ReaderClassNotDefinedException('class: '.$readerCls.
				' not found.');

		}

		$readerInstance = new $readerCls($file);
		if (! $readerInstance instanceof ReaderInterface) {

			throw new \Exception('class '. $readerCls. 'is not a impl
				of ReaderInterface');

		}
		return $readerInstance;
	}
}