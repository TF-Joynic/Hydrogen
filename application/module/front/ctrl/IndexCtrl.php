<?php
/**
 * 使用Trait 解决？
 */
namespace application\module\front\ctrl;

use Hydrogen\Load\Loader;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\JsonViewModel;
use Hydrogen\Mvc\ViewModel\TplViewModel;

class IndexCtrl extends Ctrl
{
    private $_layout = 'main';

	public function indexAct()
	{
        $request = $this->getRequest();
        $response = $this->getResponse();

        if ($request->isHead()) {
            $headerAccept = $request->getHeader(HTTP_HEADER_ACCEPT);
        }

        $var = new \stdClass();
        $var->name = 'Andy';
        $var->age = 12;

        $response->withHeader("ccd", "name");
        return new TplViewModel($var);

        return new JsonViewModel(array("a" => 1));
	}
}