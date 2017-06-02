<?php

namespace Hydrogen\Route\Dispatch;

use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface DispatcherInterface
{
	public function dispatch(RequestInterface $request, ResponseInterface $response);
}