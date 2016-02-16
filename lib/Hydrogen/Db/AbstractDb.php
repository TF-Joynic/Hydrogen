<?php

namespace Hydrogen\Db;
use Hydrogen\Config\Config;

abstract class AbstractDb
{
	// config 
	const CONFIG_SCOPE = 'database';

	protected $_config = null;
	protected $_connection = null;

	public function __construct()
	{
		// load config
		$this->_config = Config::getInstance()
		->getScope(self::CONFIG_SCOPE);

	}

    /**
     * @return mixed|null
     */
    public function getConfig()
	{
		return $this->_config;
	}

	/**
	 * @param $db_alias
	 * @param bool|false $force_master
	 * @return object|resource|null
	 */
	public abstract function getConnection($db_alias, $force_master = false);

	/**
	 * Test connection is ok or not.
	 * 
	 * @return boolean
	 */
	public function isConnected()
	{
		return null != $this->_connection;
	}

	/**
	 * close an opened connection
	 * 
	 * @return mixed
	 */
	public function closeConnection()
	{
		return $this->_disconnect();
	}

	public abstract function beginTransaction();

	public abstract function commit();

	public abstract function rollback();

	public abstract function descTable($table);

	public abstract function listTables();
}