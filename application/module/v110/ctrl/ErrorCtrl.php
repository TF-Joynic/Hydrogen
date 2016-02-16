<?php

namespace application\module\v110\ctrl;

use Hydrogen\Mvc\Ctrl\Ctrl;

class ErrorCtrl extends Ctrl
{
	public function indexAct()
	{
		$this->htmlPainter()->setSlogan('')->link();


	}
}