<?php

namespace application\module\front\ctrl;

use Hydrogen\Debug\Variable;
use Hydrogen\Mvc\Ctrl\Ctrl;

class BranchCtrl extends Ctrl
{
    public function masterAct()
    {
        Variable::dump($this->getRequest()->getParams());exit;
        echo "<h3>*&nbsp;Branch - Master&nbsp;*</h3>";
    }
}