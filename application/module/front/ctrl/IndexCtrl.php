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
	public function indexAct()
	{
        $request = $this->getRequest();
        $response = $this->getResponse();

        $loadedFiles = Loader::getInstance()->getLoadedFile();

        if ($request->isHead()) {
            $headerAccept = $request->getHeader(HTTP_HEADER_ACCEPT);
        }

        $view = new \stdClass();
        $view->name = 'Andy';
        $view->age = 12;
        return new TplViewModel($view);

        return new JsonViewModel(array("a" => 1));
	}
}