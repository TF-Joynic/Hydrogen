<?php

namespace application\module\front\interceptor;

use Hydrogen\Http\Interceptor\InterceptorInterface;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\ViewModel;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationInterceptor implements InterceptorInterface
{

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @return boolean
     */
    public function preHandle(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl)
    {

    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @param ViewModel $viewModel
     * @return void
     */
    public function postHandle(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl, ViewModel $viewModel)
    {
        // TODO: Implement postHandle() method.
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param Ctrl $ctrl
     * @return void
     */
    public function afterCompletion(RequestInterface $request, ResponseInterface $response, Ctrl $ctrl)
    {
        // TODO: Implement afterCompletion() method.
    }
}