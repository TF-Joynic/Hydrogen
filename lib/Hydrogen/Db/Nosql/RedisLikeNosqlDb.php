<?php

namespace Hydrogen\Db\Nosql;


abstract class RedisLikeNosqlDb extends AbstractNosqlDb
{
    public abstract function keyOperation();
}