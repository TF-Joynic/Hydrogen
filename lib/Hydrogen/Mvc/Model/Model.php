<?php

namespace Hydrogen\Mvc\Model;

class Model
{
	const __DBNAME__ = '';
	const __TABLE__ = '';
	const __DRIVER__ = 'Hydrogen\\Db\\Relational\\PDO\\Mysql';

	/**
	 * @var null|\Hydrogen\Db\Relational\PDO\Mysql
	 */
	protected $_db = null;

	public function __construct()
	{

	}

	/*public function getDb()
	{
		return 
	}*/

	public function selectAll()
	{

	}
	
	public function count()
	{
		$sql = sprintf("select count(*) from %s", static::__TABLE__);
		return $this->_db->fetchOne($sql);
	}

	public function selectPage()
	{

	}

	public function __get($attr)
	{
		if ('_db' === $attr) {
			$name = static::__DRIVER__;
			$this->_db = new $name;
			return $this->_db;
		}
	}
} 