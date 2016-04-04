<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Debug\Variable;

class BranchCtrl extends Ctrl
{
    public function masterAct()
    {
        Variable::dump($this->getRequest()->getParams());exit;
    }
}