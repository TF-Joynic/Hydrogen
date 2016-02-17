<?php

namespace application\module\front\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;

class Branch extends Ctrl
{
    public function masterAct()
    {
        echo "<h3>*&nbsp;Branch - Master&nbsp;*</h3>";
    }
}