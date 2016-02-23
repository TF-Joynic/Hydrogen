<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;

class ErrorCtrl extends Ctrl
{
    public function indexAct()
    {
        echo "Error occured";
    }
}