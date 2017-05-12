<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Debug\Variable;

class BranchCtrl extends Ctrl
{
    public function masterAct()
    {
//        var_dump('==='.$this->getRequest()->getAttribute("cd"));
        Variable::dump($this->getRequest()->getAttributes());exit;
    }
}