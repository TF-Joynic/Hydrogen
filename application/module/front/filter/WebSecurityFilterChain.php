<?php

namespace application\module\front\filter;

use Hydrogen\Http\Filter\FilterChainInterface;
use Hydrogen\Http\Filter\PassThroughFilterChain;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebSecurityFilterChain extends PassThroughFilterChain
{

}