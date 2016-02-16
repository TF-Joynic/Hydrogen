<?php

/**
 *
 * @author Terrance Fung <wkf.joynic@gmail.com>
 * @category   Hydrogen
 * @copyright  Copyright (c) Terrance Fung - Joynic [wkf.joynic@gmail.com]
 * @license    New BSD License in file {$application_root}/lisense.txt   
 * @version    $Id$ {$version}
 */

# CLI
define('FILE_DESC_TPL_PATH', './file_desc.tpl');
define('VERSION', '1.0.0');
define('REPLACE_EXT', '.php');

$renders = array(
	'version' => VERSION, 
	'application_root' => '/application/',
	'license_url'=> ''
);

global $prependContent, $except_dirs, $except_files;
$prependContent = file_get_contents(FILE_DESC_TPL_PATH);
$except_dirs = array(
	'Zend',
);
$except_files = array(
	'index.php'
);

if (isset($argv[1]) && $argv[1]) {
	$path = $argv[1];

	processFileOrDir($path);

	echo "~Fin~".PHP_EOL;
}

/**
 * 变量替换
 */
function processTpl($content) {
	preg_replace_callback('/\{\$(\w+)\}/', function ($matches) {
		if (isset($renders[$matches[1]]) && $renders[$matches[1]]) {
			return $renders[$matches[1]];
		}

		return '';
	}, $content);
}

function processFileOrDir($path)
{
	global $prependContent, $except_files, $except_dirs;
	$target = '<?php';

	if (file_exists($path)
	 && REPLACE_EXT === strrchr($path, '.')) {

	 	if (!in_array(basename($path), $except_files)) {
	 		$file_content = file_get_contents($path);
			
			if (false !== strpos($file_content, $target)
			 && false === strpos($file_content, $prependContent)) {

				$sub_file_content = substr($file_content, 5);
				$new_file_content = $target.PHP_EOL.$prependContent.$sub_file_content;

				if (strlen($new_file_content) > strlen($file_content)) {
					// 写入
					file_put_contents($path, $new_file_content);
					fwrite(STDOUT, "'{$path}' process OK!".PHP_EOL);
				}

			} else {
				fwrite(STDOUT, "'{$path}' file contains no PHP TAG OR Processed Already!".PHP_EOL);
			}

	 	} else {
	 		fwrite(STDOUT, "'{$path}' is in except_files list".PHP_EOL);
	 	}

	} elseif (is_dir($path)) {

		if (!in_array(basename($path), $except_dirs)) {
			$ret = scandir($path);
			unset($ret[0], $ret[1]);

			foreach ($ret as $r) {
				$fileName = $path.DIRECTORY_SEPARATOR.$r;
				processFileOrDir($fileName);
			}
		} else {
			fwrite(STDOUT, "'{$path}' is in except_dir list".PHP_EOL);
		}

	} else {
		fwrite(STDOUT, "'{$path}' doesn't seem to be ".REPLACE_EXT." File or Directory!".PHP_EOL);
	}
}