<?php

namespace Hydrogen\Db\Relational\PDO;

use Hydrogen\Db\DbExpr;
use Hydrogen\Db\Exception\BuildWhereClauseFailedException;
use Hydrogen\Db\Exception\UnrecognizedSqlCommandException;

class Mysql extends AbstractPDO
{
	const DRIVER_TYPE = 'mysql';

    /*const COMMAND_INSERT = 'i';
    const COMMAND_SELECT = 's';
    const COMMAND_UPDATE = 'u';
    const COMMAND_DELETE = 'd';*/

    /**
     * PDO mysql connection to database constructor
     *     
     * @param string  $dbname       
     * @param boolean $force_master whether force select from master node
     */
    public function __construct($dbname, $force_master = false)
    {
        parent::__construct();
        $this->_dbname = $dbname;
        $this->_force_master = (bool) $force_master;
    }

	public function get()
	{
		echo "<pre>";
		print_r($this->_config);
		echo "</pre>";exit;
	}

    /**
     * query sql string
     *
     * @param $sql
     * @param array $bind
     * @return null|\PDOStatement
     * @throws UnrecognizedSqlCommandException
     */
    public function query($sql, $bind = array())
    {
        $sql = ltrim($sql);
        if (!is_null($bind)) {
            if (!is_array($bind)) {
                $bind = array($bind);
            }
        }/* elseif ($sql instanceof DbExpr) {
            $sql = $sql->__toString();
            $bind = $sql->getBind();
        }*/
        
        if (is_array($bind)) {
            foreach ($bind as $name => $value) {
                if (!is_int($name) && !preg_match('/^:/', $name)) {
                    $newName = ":$name";
                    unset($bind[$name]);
                    $bind[$newName] = $value;
                }
            }
        }

        $command = '';
        if (false === $command = strchr($sql, ' ', true)) {

            throw new UnrecognizedSqlCommandException('Can not
             recognize the sql CURD command');

        }
        $dbh = null;
        switch ($command) {
            case self::COMMAND_SELECT:
                $dbh = $this->getConnection($this->_dbname,
                 $this->_force_master);
                break;

            default:
                $dbh = $this->getConnection($this->_dbname, true);
                break;
        }

        $statement = $dbh->prepare($sql);

        if (is_null($bind))
            $statement->execute();
        else
            $statement->execute($bind);
        
        return $statement;
    }

    /**
     * where clause builder method
     *
     * array(
     *    'id__gt' => 123,
     *    'name__like' => 'wulin',
     *    'age__gte' => 8,
     *    'ts_create__between' => array(0, 123333)
     *    'updated_at__ne' => 1222222,
     *    'pid__in' => array(12222,2222),
     *    'sid__notin' => array(111)
     * )
     *
     * @param  array|string $where
     * @return array where clause string and bind array
     * @throws BuildWhereClauseFailedException
     */
    public function whereExpr($where)
    {
    	if (is_string($where)) {
			return array($where, NULL);
    	}

    	if (!is_array($where)) {
    		$where = array($where);
    	}

        $condStr = '';
        $bind = array();

    	foreach ($where as $field => $value) {
    		if (is_int($field)) {
    			if ($value instanceof DbExpr) {
    	    		$condStr .= " AND {$value}";
    	    		continue;
    	    	}
        	}

    		$named = "_{$field}";
        	$delimeter = '__';
        	$opt = '=';
        	if (false !== strpos($field, $delimeter)) {
        		$fieldArr = explode($delimeter, $field);
        		$field = $fieldArr[0];
        		$opt = $fieldArr[1];
        	}

        	switch ($opt) {
                case 'ne':
                case 'gt':
                case 'lt':
                case 'gte':
                case 'lte':
                    $condStr .= " AND {$field} {$opt} :{$named}";
                    $bind[$named] = $value;
                    break;
                
                case 'like':
                    $condStr .= " AND {$field} LIKE '%:{$named}%'";
                  	$bind[$named] = $value;
                    break;
                
                case 'in':
                case 'notin':
            		if (is_array($value)) {
    		      		$condStr .= " AND `{$field}` ";
    		      		if ('notin' == $opt) {
    	      				$condStr .= 'NOT';
    		      		}
    		      		$condStr .= 'IN (';
    			        $index = 0;
    		      	    $whereStrTemp = '';
    			    	foreach ($value as $v) {
    				        $whereStrTemp .= ":{$named}{$index},";
    				        $bind["{$named}{$index}"] = $v;
    				        $index ++;
    			        }
    			        
    			        $condStr .= substr($whereStrTemp, 0, -1);
    			        $condStr .= ')';
    		        } else {
    		      		$condStr .= " AND `{$field}` = :{$named}";
    			        $bind[$named] = $value;
    	     		}
                    break;
                    
                default:

                	throw new BuildWhereClauseFailedException('Illegal glue specified
                     between field and value :'. $value['glue']);

        	}
    	}

    	return array($condStr, $bind);
    }

    /**
    +-----------------+------------------+------+-----+---------+----------------+
    | Field           | Type             | Null | Key | Default | Extra          |
    +-----------------+------------------+------+-----+---------+----------------+
    | user_id         | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
    | email           | varchar(96)      | NO   | UNI | NULL    |                |
    | username        | varchar(255)     | NO   | UNI | NULL    |                |
    | first_name      | varchar(64)      | NO   |     |         |                |
    | last_name       | varchar(64)      | NO   |     |         |                |
    | password        | char(35)         | NO   |     | NULL    |                |
    | user_type       | varchar(32)      | NO   |     |         |                |
    | ts_created      | int(11)          | NO   |     | 0       |                |
    | last_login_time | datetime         | NO   |     | NULL    |                |
    +-----------------+------------------+------+-----+---------+----------------+
     *
     * @param $table
     */
    public function descTable($table)
    {
        // TODO: Implement descTable() method.
        $sql = "";
    }

    public function listTables()
    {
        $sql = "SELECT * ";
    }
}