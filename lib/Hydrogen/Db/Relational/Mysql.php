<?php

namespace Hydrogen\Db\Relational\Mysql;

use Hydrogen\Db\Relational\AbstractRelationalDb;

/**
 * Class Mysql
 * PHP mysql_* style connection class
 *
 * @package Hydrogen\Db\Relational\Mysql
 */
class Mysql extends AbstractRelationalDb
{
	/**
	 * do connect
	 *
	 * @param  string $dbname
	 * @param  bool $force_master [description]
	 * @return void               [description]
	 */
	protected function _connect($dbname, $force_master)
	{
		
	}

	public function query($sql, $bind = array())
	{

	}

	public function beginTransaction()
	{
		// TODO: Implement beginTransaction() method.
	}

	public function commit()
	{
		// TODO: Implement commit() method.
	}

	public function rollback()
	{
		// TODO: Implement rollback() method.
	}

	public function descTable($table)
	{
		// TODO: Implement descTable() method.
	}

	public function listTables()
	{
		// TODO: Implement listTables() method.
	}

	/**
	 * get relation db where clause
	 *
	 * @param  array $where conditional array to concrete where clause
	 * @return string        where clause
	 */
	public function whereExpr($where)
	{
		// TODO: Implement whereExpr() method.
	}

	public function insert($table, $data)
	{
		// TODO: Implement insert() method.
	}

	public function update($table, array $data, $condition)
	{
		// TODO: Implement update() method.
	}

	public function delete($table, $condition)
	{
		// TODO: Implement delete() method.
	}

	protected function _disconnect($dbname, $force_master)
	{
		// TODO: Implement _disconnect() method.
	}
}
