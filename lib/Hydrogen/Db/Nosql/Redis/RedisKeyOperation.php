<?php

namespace Hydrogen\Db\Nosql\Redis;


use Hydrogen\Db\Nosql\RedisLikeOperations\AbstractKeyOperation;

class RedisKeyOperation extends AbstractKeyOperation
{

    /**
     * del specified key
     *
     * @param $key
     * @return mixed
     */
    public function del($key)
    {

    }

    public function dump($key)
    {
        // TODO: Implement dump() method.
    }
}