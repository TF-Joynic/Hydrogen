<?php
/**
 * 使用Trait 解决？
 */
namespace application\module\front\ctrl;

use application\module\front\filter\PrintFilter;
use application\module\front\filter\WebSecurityFilterChain;
use application\module\front\filter\XssFilter;
use Hydrogen\Http\Filter\PassThroughFilterChain;
use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\View;
use Hydrogen\Mvc\ViewModel\JsonViewModel;
use Hydrogen\Mvc\ViewModel\TplViewModel;

class IndexCtrl extends Ctrl
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
        return array(
            WebSecurityFilterChain::class => array(
                'getOnly + index',
            ),
        );
    }

	public function indexAct()
	{
        $request = $this->getRequest();
        $response = $this->getResponse();

        echo "Filter Test<br />";
        $filter = new XssFilter();
        $filter2 = new PrintFilter();

        $chain = new PassThroughFilterChain();

        $chainWebSe = new WebSecurityFilterChain();
        echo WebSecurityFilterChain::class;exit;
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
}