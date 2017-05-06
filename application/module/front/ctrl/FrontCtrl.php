<?php

namespace application\module\front\ctrl;

use application\module\front\filter\WebSecurityFilterChain;
use Hydrogen\Http\Request\RequestMethod;
use Hydrogen\Mvc\Ctrl\Ctrl;
use application\module\front\interceptor\AuthenticationInterceptor;

class FrontCtrl extends Ctrl
{
    public function filters()
    {
        return array(
            WebSecurityFilterChain::class => array(
                'index' => RequestMethod::ALL ^ RequestMethod::HEAD
            ),
        );
    }

    public function interceptors()
    {
        return array(
        );
    }

}