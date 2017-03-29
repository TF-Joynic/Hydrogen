<?php

namespace Hydrogen\Http\Interceptor;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\ViewModel;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface InterceptorInterface
{
    public function preHandle(RequestInterface &$request, ResponseInterface $response, Ctrl $ctrl);

    // before render
    public function postHandle(RequestInterface &$request, ResponseInterface $response, Ctrl $ctrl, ViewModel $viewModel);

    // after render
    public function afterCompletion(RequestInterface &$request, ResponseInterface $response, Ctrl $ctrl);
}