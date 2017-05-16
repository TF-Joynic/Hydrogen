<?php

namespace Hydrogen\Db\Nosql\RedisLikeOperations;


abstract class AbstractKeyOperation implements OperationInterface
{


    /**
     * del specified key
     *
     * @param $key
     * @return mixed
     */
    public abstract function del($key);

    public abstract function dump($key);
}