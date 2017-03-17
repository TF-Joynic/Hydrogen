<?php

namespace Hydrogen\Http\Request\Validation;

use Psr\Http\Message\RequestInterface;

interface ValidatorInterface
{
	public function validate(RequestInterface $request);
}