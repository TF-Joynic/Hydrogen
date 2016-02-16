<?php

namespace Hydrogen\Db\Relational\PDO;

use Hydrogen\Db\DbExpr;
use Hydrogen\Db\Exception\DbConnectionException;
use Hydrogen\Db\Relational\AbstractRelationalDb;
use Hydrogen\Db\Exception\UnsupportedException;
use Hydrogen\Db\Exception\UnexpectedDbNodeException;
use Hydrogen\Db\Exception\UndefinedConfigNodeException;
use Hydrogen\Db\Exception\UndefinedArrayKeyException;
use \PDO as PDO;

/**
 * Class AbstractPDO
 *
 * @package Hydrogen\Db\Relational\PDO
 */
abstract class AbstractPDO extends AbstractRelationalDb
{
    const DRIVER_TYPE = '';
    const DEFAULT_FETCHMODE = PDO::FETCH_ASSOC;

    public function __construct()
    {
        parent::__construct();
        $dbdriver = static::DRIVER_TYPE;
        if (null === $dbdriver || empty($dbdriver)) {

            throw new \UnexpectedValueException(
                'db driver type can NOT be null or empty!'
            );
            
        }
        $this->_dbdriver = strtolower($dbdriver);
    }

	protected function _connect($dbname, $force_master)
	{
        if (!$dbname || !is_bool($force_master)) {

            throw new \InvalidArgumentException('invalid args!');

        }

		$node = $force_master ? self::NODE_MASTER : self::NODE_SLAVE;
		$_connection = $this->_getNodeConnection($dbname, $node);
		if ($_connection) {
			return;
		}

		$dsn = $this->_dsn($dbname, $node);

        if (!extension_loaded('pdo')) {
            throw new UnsupportedException('PDO extension has not been installed!');
        }
        
        /*echo "<pre>";
        echo '--'.$this->_dbdriver;
        print_r(PDO::getAvailableDrivers());
        echo "</pre>";*/

        if (!in_array($this->_dbdriver, PDO::getAvailableDrivers())) {

            throw new \Exception('Specified Driver Type: '.$this->_dbdriver.'
             is not supported. Checkout the installation list');

        }
        
        try {
            $dbh = new PDO(
                $dsn,
                $this->_config['username'],
                $this->_config['password'],
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND =>
                    'SET NAMES '.$this->_config['charset']
                )
            );
            
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            /*$dbh->setAttribute(PDO::ATTR_STATEMENT_CLASS, array(
                'MyPDOStatement',
                array()
            ));*/
            
            $this->_setNodeConnection($dbname, $node, $dbh);
        } catch (\PDOException $e) {

            // TODO fix workaround can't user $e->getMessage() # bug?
            throw new DbConnectionException(' err occured when try to connect db:
            '/*.$e->getMessage()*/, $e->getCode()/*, $e*/);

        }
	}

    /**
     * @param $dbname
     * @param $force_master
     */
    protected function _disconnect($dbname, $force_master)
    {
        if (!$this->isConnected()) {
            return ;
        }
        $node = $force_master ? self::NODE_MASTER : self::NODE_SLAVE;
        $this->_setNodeConnection($dbname, $node, null);
    }

    /**
     * @param $dbname
     * @param $node
     * @return string
     * @throws UndefinedConfigNodeException
     * @throws UnexpectedDbNodeException
     */
    protected function _dsn($dbname, $node)
    {
        // get db config array
        $config = $this->_config;

        if (!in_array($node, array(self::NODE_MASTER, self::NODE_SLAVE))) {

            throw new UnexpectedDbNodeException("DB host node must be
             EITHER 'master' OR 'slave'.");
            
        }

        if ($node == self::NODE_MASTER) { // master
            $config = $config[$dbname][self::NODE_MASTER];
        } else { // slave
            if (!isset($config[$dbname][self::NODE_SLAVE])) {
                throw new UndefinedConfigNodeException('undefined node: '.
                    $dbname.' -> slave');
            }

            $config = $this->pickSlaveNode($config[$dbname][self::NODE_SLAVE]);
            if (!$config || !is_array($config)) {
                throw new \UnexpectedValueException('pick slave node failed!');
            }
        }

        $this->_config = $config;

        unset(
            $config['username'],
            $config['password']
        );

        $dbh = array();
        foreach ($config as $k => $v) {
            $dbh[] = "$k=$v";
        }
        return $this->_dbdriver.':dbname='.$dbname.';'.implode(';', $dbh);
    }

    /**
     * execute sql directly to database
     *
     * @param $sql
     * @return int
     */
    public function exec($sql)
    {
        if ($sql && is_string($sql)) {

            return $this->getConnection($this->_dbname, $this->_force_master)
                ->exec($sql);

        }

        return 0;
    }

    /**
     * query sql string
     *
     * @param $sql
     * @param array $bind
     * @return null|\PDOStatement
     */
    public abstract function query($sql, $bind = array());

    public function fetchRow($sql, $bind = array(), $fetchMode = NULL)
    {
        if (!$fetchMode) {
            $fetchMode = PDO::FETCH_ASSOC;
        }

        return $this->query($sql, $bind)->fetch($fetchMode);
    }

    public function fetchAll($sql, $bind = array(), $fetchMode = NULL)
    {
        if (!$fetchMode) {
            $fetchMode = PDO::FETCH_ASSOC;
        }

        return $this->query($sql, $bind)->fetchAll($fetchMode);
    }

    public function fetchOne($sql, $bind = array())
    {
        return $this->query($sql, $bind)->fetchColumn(0);
    }

    public function fetchPairs($sql, $bind = array())
    {
        $result = array();
        $stmt = $this->query($sql, $bind);
        while ($row = $stmt->fetch( PDO::FETCH_NUM)) {
            $result[$row[0]] = $row[1];
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $bind
     * @param int|string $assoc_col
     * @return array
     */
    public function fetchAssoc($sql, $bind = array(), $assoc_col = 0)
    {
        $stmt = $this->query($sql, $bind);
        $data = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $assoc = '';
            if (is_numeric($assoc_col)) {
                $tmp = array_values(array_slice($row, $assoc_col, 1));
                $assoc = isset($tmp[0]) ? $tmp[0] : '';
            } else {
                $assoc = isset($row[$assoc_col]) ? $row[$assoc_col] : '';
            }

            if (!isset($assoc)) {
                throw new UndefinedArrayKeyException('key: '.$assoc_col.' undefined or out of row data range!');
            }
            $data[$assoc] = $row;
        }

        return $data;
    }

    public function fetchCol($sql, $bind = array())
    {
        return $this->query($sql, $bind)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insert($table, $data)
    {
        $set = array();
        foreach ( $data as $k => $v ) {
            $set[] = "`$k` = ?";
        }

        $sql = "INSERT INTO `$table` SET " . implode(',', $set);
        $bind = array_values($data);
        $this->query($sql, $bind);

        return $this->getConnection($this->_dbname, true)->lastInsertId();
    }

	/**
	 * update record(s)
	 * 
	 * @param  string  $table               the table name
	 * @param  array   $data                update field => value array
	 * @param  array   $condition           where condition
	 * @return  int       affected row count
	 */
	public function update($table, array $data, $condition)
	{
        $set = array();
        foreach ( $data as $k => $v ) {
            if (is_int($k) && ($v instanceof DbExpr)){
                $set[] = ''.$v;
            }else{
                $set[] = "`$k` = :{$k}";
            }
        }

        $sql = "UPDATE `$table` SET " . implode( ', ', $set );

        $bind = array();
        if (!empty($condition)) {
            list($whereStr, $where_bind) = $this->whereExpr($condition);
            $sql .= ' WHERE '.$whereStr;

            $bind = $data + $where_bind;
        }else{
            $bind = $data;
        }

        return $this->query($sql, $bind)->rowCount();
	}

	/**
	 * delete record(s) from table
	 * 
	 * @param  string  $table               the table name
	 * @param  array   $condition           where condition
	 * @return int                       affected row count
	 */
	public function delete($table, $condition)
	{
        $sql = "delete from `{$table}`";
        $bind = NULL;

        if (!empty($condition)) {
            list($whereStr, $bind) = $this->whereExpr($condition);
            $sql .= ' WHERE '.$whereStr;
        }

        return  $this->query($sql, $bind)->rowCount();
	}

    /**
     * PDO mysql uses the \PDO::beginTransaction() method directly
     *
     * @return void
     */
    public function beginTransaction()
    {

        $this->getConnection($this->_dbname, $this->_force_master)
            ->beginTransaction();

    }

    public function commit()
    {

        $this->getConnection($this->_dbname, $this->_force_master)
            ->commit();

    }

    public function rollback()
    {

        $this->getConnection($this->_dbname, $this->_force_master)
            ->rollBack();

    }
}