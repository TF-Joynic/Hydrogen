<?php

namespace Hydrogen\Db\Nosql\RedisLikeOperations;


abstract class AbstractHashmapOperation implements OperationInterface
{
    public abstract function hdel($key);
    public abstract function hexists($key);
    public abstract function hget($key);
    public abstract function hgetall($key);
    public abstract function hincrby($key);
    public abstract function hincrbyFloat($key);
    public abstract function hkeys($key);
    public abstract function hlen($key);
    public abstract function hmget($key);
    public abstract function hmset($key);
    public abstract function hset($key);
    public abstract function hsetnx($key);
    public abstract function hvals($key);
    public abstract function hscan($key);
    public abstract function hstrlen($key);
}