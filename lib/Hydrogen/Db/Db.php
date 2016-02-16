<?php

namespace Hydrogen\Db;

class Db extends AbstractDb
{
	const NODE_MASTER = 'master';
	const NODE_SLAVE = 'slave';


	/**
	 * @param $db_alias
	 * @param bool|false $force_master
	 * @return object|resource|null
	 */
	public function getConnection($db_alias, $force_master = false)
	{
		// TODO: Implement getConnection() method.
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
}