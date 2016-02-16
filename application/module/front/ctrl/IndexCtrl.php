<?php
/**
 * 使用Trait 解决？
 */
namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;

class IndexCtrl extends Ctrl
{
	public function indexAct()
	{
        $request = $this->getRequest();
        $response = $this->getResponse();
        var_dump($request);

        var_dump($response);
        if ($request->isHead()) {

            $headerAccept = $request->getHeader(HTTP_HEADER_ACCEPT);

        }
        file_put_contents('D:/log/request_method.log', 'HEAD' . PHP_EOL, FILE_APPEND);
		echo 'front index';
	}
}