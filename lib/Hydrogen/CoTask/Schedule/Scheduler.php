<?php

namespace Hydrogen\Task\Schedule;

if (version_compare(PHP_VERSION, '5.5', '<')) {
	throw new \Exception('Generator is not
	 supported!PHP VERSION: '.PHP_VERSION);
}

use Hydrogen\CoTask\Task;
use Hydrogen\CoTask\Exception\TaskHaltException;
use application\console\Task\CoTask;

class Scheduler
{
/*	const TASK_STATUS_RUNNING = 1;
	const TASK_STATUS_SUSPENDED = 2;
	const TASK_STATUS_DEAD = 3;*/

	private static $_existing_tasks = [

	];

    public function __construct($funcName, $args)
    {
    	return $this->create($funcName, $args);
    }

    /**
     * @param $funcName
     * @param $args
     */
    public function create($funcName, $args)
    {	
    	if (CoTask::$_tasks
    	 && isset(CoTask::$_tasks[$funcName])) {

    		$coTaskRule = CoTask::$_tasks[$funcName]['rule'];

    		$coTaskRuleArgScope = CoTask::$_tasks[$funcName]['rule_arg_scope'];

    		switch ($coTaskRule) {
    			case CoTask::COTASK_MUTEX:
    				$targetTask = $this
    				->raiseTaskIntrospection($funcName, $args, $coTaskRuleArgScope);
    				if ($targetTask) {
    					echo "exist same task, exit!".PHP_EOL;
    					exit;
    				}

    				break;
    			
    			default:
    				# code...
    				break;
    		}
    	}

    	$newTask = new Task($funcName, $args);
    	if (isset(self::$_existing_tasks[$funcName])) {
            self::$_existing_tasks[$funcName][] = $newTask;
    	} else {
            self::$_existing_tasks[$funcName] = [$newTask];
    	}

    	return ;
    }

	/**
	 * return running tasks
	 * @param $taskName
	 * @return array
	 */
	public function runningTasks($taskName)
	{
		return $taskName ? self::$_existing_tasks[$taskName] : self::$_existing_tasks;
	}

    /**
     * make the task introspect
     *
     * @param string $taskFuncName
     * @param array $args
     * @param int $rule_arg_scope
     * @return Task [description]
     */
    public function raiseTaskIntrospection($taskFuncName, $args, $rule_arg_scope)
    {
    	if (self::$_existing_tasks 
    		&& is_array(self::$_existing_tasks)) {

    		if (isset(self::$_existing_tasks[$taskFuncName])) {
    			$thisKindOfTasks = self::$_existing_tasks[$taskFuncName];

    			if (!is_array($thisKindOfTasks)) {
					$thisKindOfTasks = array($thisKindOfTasks);	    			
    			}

    			foreach ($thisKindOfTasks as $task) {

    				if (($task instanceof Task)
    				 && $task->introspect($args, $rule_arg_scope)) {

						return $task;

    				}
    			}
    		}

    	}

    	return null;
    }

    /**
     * resume a task base on PHP Generator::send()
     *
     * @param Task $task
     * @return mixed result or false when failed
     */
    public function resume(Task $task)
    {
    	if (!$task->valid()) {
    		$task->setStatus(Task::STATUS_DEAD);
    		return false;
    	}

    	$args = func_get_args();
    	if ($args) {
			$ret = $task->send($args);
    	} else {
    		$task->next();
    		$ret = null;
    	}
    	$task->setStatus(Task::STATUS_RUNNING);

    	return $ret;
    }

    /**
     * halt the task
     *
     * @param Task $task
     * @return bool true if succ, false if failed to halt
     */
    public function halt(Task $task)
    {
    	$haltResult = $task->_throw(new TaskHaltException(
    		'Scheduler\'s sending halt command. HALT NOW!'));

    	if ($haltResult) {
    		$task->setStatus(Task::STATUS_DEAD);
//    		unset(self::$_existing_tasks[$task->name()]);
    		return true;
    	}

    	return false;
    }

    public function status(Task $task)
    {
    	return $task->status();
    }


}