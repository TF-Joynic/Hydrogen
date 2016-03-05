<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;

class ErrorCtrl extends Ctrl
{
    public function indexAct()
    {
        $request = $this->getRequest();
        var_dump($request->getContextAttr('module'), $request->getContextAttr('ctrl'),
            $request->getContextAttr('act'));exit;
        echo "Error occured";
    }
}