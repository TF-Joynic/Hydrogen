<?php

/**
 * publish misc.
 */
namespace application\console\Task;

use Hydrogen\Console\Console;

class PublishCsl extends Console
{
	/**
	 * @authenticated
	 * @hasData
	 *
	 * @aop mapping
	 * @param $uid
	 * @param $pid
	 */
	public function pushToFans($uid, $pid)
	{
		echo $uid.'--'.$pid.PHP_EOL;
		$fans = [
			'1222', '3455'
		];

		foreach ($fans as $fan) {
			echo 'pushing '.$pid.' to fan: '.$fan.PHP_EOL;
		}
	}

	public function cdAct()
	{
		return false;
	}
}