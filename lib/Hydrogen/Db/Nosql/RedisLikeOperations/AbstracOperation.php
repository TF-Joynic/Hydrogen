<?php

namespace Hydrogen\Db\Nosql\RedisLikeOperations;


abstract class AbstracOperation implements OperationInterface
{
    public abstract function getClient();
}