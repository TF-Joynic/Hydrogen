<?php
/**
 * 使用Trait 解决？
 */
namespace application\module\front\ctrl;

use application\module\front\filter\PrintFilter;
use application\module\front\filter\WebSecurityFilterChain;
use application\module\front\filter\XssFilter;
use Hydrogen\Debug\Variable;
use Hydrogen\Http\Filter\PassThroughFilterChain;
use Hydrogen\Http\Request\Client\Curl;
use Hydrogen\Http\Request\RequestMethod;
use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\View;
use Hydrogen\Mvc\ViewModel\JsonViewModel;
use Hydrogen\Mvc\ViewModel\TplViewModel;
use application\module\front\interceptor\AuthenticationInterceptor;

class IndexCtrl extends FrontCtrl
{
    protected $_layout = 'main';

    /**
     * init operations
     */
    public function init()
    {

    }

    public function filters()
    {
        return array_merge(parent::filters(), array(
            WebSecurityFilterChain::class => array(
                'index' => RequestMethod::ALL ^ RequestMethod::HEAD
            ),
        ));
    }

    public function interceptors()
    {
        return array_merge(parent::interceptors(), array(
            AuthenticationInterceptor::class => array(
                'index' => RequestMethod::ALL ^ RequestMethod::HEAD,
                'about' => RequestMethod::ALL,
            )
        ));
    }

	public function indexAct()
	{
        echo "index<br />";

        echo Curl::OPT_VALUE_ARRAY."<br />";

        $request = $this->getRequest();
        $response = $this->getResponse();

        $filter = new XssFilter();
        $filter2 = new PrintFilter();

        $chain = new PassThroughFilterChain();

        $chainWebSe = new WebSecurityFilterChain();
        $chainWebSe->addFilter($filter);

        echo $chainWebSe->getId();exit;

        echo $chain->getId();
        echo "<br />";
        echo PassThroughFilterChain::class;exit;

        $chain->addFilter($filter);
        $chain->addFilter($filter2);

        $chain->doFilter($request, $response);
        exit();


        if ($request->isHead()) {
            $headerAccept = $request->getHeader(HTTP_HEADER_ACCEPT);
        }

        $var = new \stdClass();
        $var->name = 'Andy';
        $var->c = 12;

        $response->withHeader("ccd", "name");
        $view = $this->render('account/par', $var, true);

        var_dump($view);exit;
        return null;
	}

    public function aboutAct()
    {

    }
}