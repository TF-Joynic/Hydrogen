<?php

namespace Hydrogen\Http\Interceptor;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\ViewModel;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface InterceptorInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @return boolean
     */
    public function preHandle(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl);

    /**
     * before render
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @param ViewModel $viewModel
     * @return void
     */
    public function postHandle(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl, ViewModel $viewModel);

    /**
     * after render
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @return void
     */
    public function afterCompletion(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl);
}