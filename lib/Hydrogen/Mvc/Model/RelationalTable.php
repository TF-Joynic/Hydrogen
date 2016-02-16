<?php
/**
 * Relational Database Opts
 */

namespace lib\Hydrogen\Mvc\Model;

use Hydrogen\Mvc\Model\Model;

class RelationalTable extends Model
{
    protected $_column_str = '';

    const __DRIVER__ = 'Hydrogen\\Db\\Relational\\PDO\\Mysql';

    public function select()
    {

    }

    public function all()
    {
        $sql = 'select * from '.static::__TABLE__;
        return $this->_db->fetchAll();
    }

    public function count()
    {
        $sql = 'select count(*) from '.static::__TABLE__;
        return $this->_db->fetchOne($sql);
    }
}