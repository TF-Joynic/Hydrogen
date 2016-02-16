<?php

namespace Hydrogen\CoTask;

use application\console\Task\CoTask;

/**
 * class that represent a Generator
 */
class Task
{
	private $_generator = null;

	const STATUS_RUNNING = 1;
	const STATUS_SUSPENDED = 2;
	const STATUS_DEAD = 3;

	private $_id = '';
	private $_name = '';
	private $_args = [];

	private $_status = null;

	/**
	 * create a new task(generator)
	 *
	 * @param $taskName
	 * @param $args
	 * @throws \Exception
	 * @internal param $ [type] $taskName [description]
	 * @internal param $ [type] $args     [description]
	 */
	public function __construct($taskName, $args)
	{
		if (!$taskName || !is_string($taskName)) {
			throw new \InvalidArgumentException(
				'taskName must be valid string');
		}

		$generator = call_user_func_array($taskName, $args);

		if (!$generator instanceof \Generator) {
			throw new \Exception('specified func: ' . $taskName . '()
			 is not a generator!');
		}

		$this->_id = md5($taskName . '|' . implode('_', $args));
		$this->_name = $taskName;
		$this->_generator = $generator;
		$this->_status = self::STATUS_SUSPENDED;
	}

	public function name()
	{
		return $this->_name;
	}

	public function setStatus($status)
	{
		$this->_status = $status;
	}

	public function status()
	{
		return $this->_status;
	}

	public function send($args)
	{
		return $this->_generator->send($args);
	}

	public function _throw(\Exception $exception)
	{
		return $this->_generator->throw($exception);
	}

	public function current()
	{
		return $this->_generator->current();
	}

	public function key()
	{
		return $this->_generator->key();
	}

	public function next()
	{
		$this->_generator->next();
	}

	public function rewind()
	{
		$this->_generator->rewind();
	}

	public function valid()
	{
		return $this->_generator->valid();
	}

	/**
	 * task introspect runtime arguments of itself,
	 *
	 * @param $args
	 * @param $rule_arg_scope
	 * @return bool true if runtime arguments matches,
	 *  false if doesn't match
	 */
	public function introspect($args, $rule_arg_scope)
	{
		for ($i = 0; $i < $rule_arg_scope; $i ++) {
			if ($args[$i] != $this->_args[$i]) {
				return false;
			}
		}

		return true;
	}
}