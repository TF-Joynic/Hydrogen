<?php

namespace Hydrogen\Http\Request\Validation;

use Hydrogen\Http\Request\Request;

abstract class AbstractValidator implements ValidatorInterface
{
	protected $_errors = array();
	protected $_vars = array();
	
	public abstract function validate(Request $request);
	
	public function sanitize($value)
	{
		$value = strip_tags(trim($value));
		return $value;
	}
	
	public function addError($key, $value)
	{
		if (array_key_exists($key, $this->_errors)){
			if (is_array($this->_errors[$key]))
				$this->_errors[$key][] = $value;
			else
				$this->_errors[$key] = $value;
		}else{
		    $this->_errors[$key] = $value;
		}
	}
	
	public function getError($key)
	{
		if ($this->hasError($key))
			return $this->_errors[$key];
		
		return null;
	}
	
	public function getErrors()
	{
		return $this->_errors;
	}
	
	public function hasError($key = NULL)
	{
		if (NULL === $key || strlen($key) == 0)
			return count($this->_errors) > 0;
		else
			return array_key_exists($key, $this->_errors);
	}
	
	public function __set($name, $value)
	{
        $this->_vars[$name] = trim($value);
	}
	
	public function __get($name)
	{
		return array_key_exists($name, $this->_vars) ? $this->_vars[$name] : null;
	}
	
	public function __unset($name)
	{
	    if (array_key_exists($name, $this->_vars)){
            unset($this->_vars[$name]);
        }
	}
}