<?php

namespace Hydrogen\Db\Relational;

use Hydrogen\Db\AbstractDb;

abstract class AbstractRelationalDb extends AbstractDb
{
	const CONNECTION_VAR_NAME_PREFIX = '_conn';
	const NODE_MASTER = 'master';
	const NODE_SLAVE = 'slave';

	// sql CURD
	const COMMAND_INSERT = 'insert';
	const COMMAND_SELECT = 'select';
	const COMMAND_UPDATE = 'update';
	const COMMAND_DELETE = 'delete';
	const COMMAND_DROP = 'drop';  // drop table
	const COMMAND_ALTER = 'alter'; // alter table

	public $_dbname = '';
	public $_force_master = false;

	/**
	 * get connected to the database server
	 * 
	 * @param  string  $dbname database config alias 
	 * @param  boolean $force_master force fetch data from master node
	 * @return  \PDO|object|resource|null connection handle
	 */
	public function getConnection($dbname, $force_master = false)
	{
		$this->_connect($dbname, $force_master);
		$node = $force_master ? self::NODE_MASTER : self::NODE_SLAVE;
        return $this->_getNodeConnection($dbname, $node);
	}

	/**
	 * pick one slave node config from the slave config list
	 * 
	 * @param  array $slave_config config slave value			
	 * @return array single slave node config
	 */
	public function pickSlaveNode($slave_config)
	{
		if (!is_array($slave_config) || !$slave_config) {
			return false;
		}

		if (2 > count($slave_config)) {
			return $slave_config[0];	
		}

		$probArr = array();
		foreach ($slave_config as $seq => $slaveNode) {

			$probArr[$seq] = isset($slaveNode['weight'])
			 ? intval($slaveNode['weight']) : 10;

		}

		$pickSeq = $this->getProb($probArr);
		if ($pickSeq && is_int($pickSeq))
			return $slave_config[$pickSeq];

		return array();
	}

	public function getProb($probArr)
	{
		$result = null;
		$sum = array_sum($probArr);
		foreach ($probArr as $k => $a) {
			$rand = mt_rand(1, $sum);

			if ($rand <= $a) {
				$result = $k;
				break;
			} else {
				$sum -= $a;
			}
		}
		unset($probArr);
		return $result;
	}

	/**
	 * do connect
	 * 
	 * @param  string $dbname   
	 * @param  bool $force_master [description]
	 * @return void               [description]
	 */
	protected abstract function _connect($dbname, $force_master);

	protected abstract function _disconnect($dbname, $force_master);

	/**
	 * concrete Connection Var Name
	 * 
	 * @param  string $dbname 'user' etc
	 * @param  string $node     'master' or 'slave'
	 * @return string           var name
	 */
	protected function _fmtConnectionVarName($dbname, $node)
	{
		if (!$dbname || !$node) {
			throw new \InvalidArgumentException('args can\'t be empty!');
		}

		return self::CONNECTION_VAR_NAME_PREFIX.'_'.$dbname.'_'.$node;
	}

	protected function _getNodeConnection($dbname, $node)
    {
    	$symbol = $this->_fmtConnectionVarName($dbname, $node);
        if (isset($this->$symbol)) {
            return $this->$symbol;
        }

        return NULL;
    }
    
    protected function _setNodeConnection($dbname, $node, $value)
    {
        $symbol = $this->_fmtConnectionVarName($dbname, $node);
        $this->$symbol = $value;
    }

    /**
     * get relation db where clause 
     * 
     * @param  array $where conditional array to concrete where clause
     * @return string        where clause
     */
    public abstract function whereExpr($where);

	public abstract function insert($table, $data);

	public abstract function update($table, array $data, $condition);

	public abstract function delete($table, $condition);

}