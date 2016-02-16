<?php

namespace Hydrogen\Utils;

class Arr
{
	public static function Column($arr, $column_key, $index_key = null)
	{
		if (null !== $index_key 
			&& (!is_string($index_key) && !is_int($index_key))) {

			throw new Exception("The index key should be
			 either a string or an integer");

		}

		if (!is_array($arr)) {
			$arr = array($arr);
		}

		if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			return array_column($arr, $column_key, $index_key);
		}

		$indexArr = $columnArr = array();
		foreach ($arr as $k => $v) {
			if (null !== $index_key) {
				$indexArr[] = $v[$index_key];
			}

			$columnArr[] = $v[$column_key];
		}

		return $indexArr ? array_combine($indexArr, $columnArr) : $columnArr;
	}
}