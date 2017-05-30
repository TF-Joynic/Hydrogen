<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Debug\Variable;

class BranchCtrl extends Ctrl
{
    public function masterAct()
    {
        $response = $this->getResponse();
//        $response->withHeader(HTTP_HEADER_CONTENT_TYPE, 'text/css');
//        var_dump($response->getHeader(HTTP_HEADER_CONTENT_TYPE));
//        Variable::dump($response-);

        echo "branch/master<br />";
//        var_dump('==='.$this->getRequest()->getAttribute("cd"));
        Variable::dump($this->getRequest()->getAttributes());exit;
    }
}