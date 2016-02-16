<?php

namespace application\console\Task;

//use application\console\Task;
/**
 * Class CoTask
 * The CoTask Task Config Class
 *
 * @package application\console
 */

class CoTask
{
	const COTASK_MUTEX = 1;
	const COTASK_HALT_EXIST = 2;
	const COTASK_HALT_BOTH = 3;
	const COTASK_OVERRIDE = 4;
	const COTASK_COEXIST = 5;

	public static $_tasks = [
		'Publish::pushToFans' => [
			'rule' => self::COTASK_MUTEX,
			'rule_arg_scope' => 1
		],
	];


	public function __construct()
	{
		// code
	}

	public function test($a = 1)
	{
		echo $a.PHP_EOL;
	}

	public function run()
	{
		foreach ($this->_tasks as $task) {
//			$generator = call_user_func_array($task, );
		}
	}
}