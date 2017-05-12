<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Mvc\ViewModel\JsonViewModel;

class ErrorCtrl extends Ctrl
{
    public $_doRender = false;

    public function indexAct()
    {
        header("Content-type:text/html;charset=utf-8");
        $request = $this->getRequest();
        $response = $this->getResponse();

        ini_set('output_buffering', 0);  // 不生效，需要ini 配置里面修改

        echo "<h2>Error occured</h2>";
        echo "<h3><span>{$response->getStatusCode()}</span></h3>";

        echo "Module: <font color=\"blue\">{$request->getContextAttr('module')}</font> <br />
         Ctrl: <font color=\"blue\">{$request->getContextAttr('ctrl')}</font> <br />
         Act: <font color=\"blue\">{$request->getContextAttr('act')}</font> <br />
         not found!";

        $var = new \stdClass();
        $var->name = "Terrance";
        $var->age = 'Fung';

//        echo json_encode($var);exit;

        return new JsonViewModel($var);
    }
}