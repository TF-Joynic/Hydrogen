<?php

namespace Hydrogen\Mvc\View\Resolver;

abstract class AbstractResolverBridge
{
    protected $_resolverImpl;

    public abstract function bridgeResolver($resolverImpl);
}