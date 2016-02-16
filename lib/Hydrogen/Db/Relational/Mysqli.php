<?php

namespace Hydrogen\Db\Relational;

/**
 * Class Mysqli
 *
 * @package Hydrogen\Db\Relational
 */
class Mysqli extends AbstractRelationalDb
{

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
     * do connect
     *
     * @param  string $dbname
     * @param  bool $force_master [description]
     * @return void               [description]
     */
    protected function _connect($dbname, $force_master)
    {
        // TODO: Implement _connect() method.
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

    public function update($table, array $data, $condition = array())
    {
        // TODO: Implement update() method.
    }

    public function delete($table, $condition)
    {
        // TODO: Implement delete() method.
    }
}