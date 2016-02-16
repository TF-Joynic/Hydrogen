<?php

namespace Hydrogen\Http\Request\Validation;

use Hydrogen\Http\Request\Request;

interface ValidatorInterface
{
	public function validate(Request $request);
}