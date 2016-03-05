<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Debug\Variable;

class BranchCtrl extends Ctrl
{
    public function masterAct()
    {
        echo 'barnch master';exit;
        Variable::dump($this->getRequest()->getParams());exit;
        echo "<h3>*&nbsp;Branch - Master&nbsp;*</h3>";
    }
}