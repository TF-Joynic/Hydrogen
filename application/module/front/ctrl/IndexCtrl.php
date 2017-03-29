<?php
/**
 * 使用Trait 解决？
 */
namespace application\module\front\ctrl;

use application\module\front\filter\PrintFilter;
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

	public function indexAct()
	{
        $request = $this->getRequest();
        $response = $this->getResponse();

        echo "Filter Test<br />";
        $filter = new XssFilter();
        $filter2 = new PrintFilter();

        $chain = new PassThroughFilterChain();
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