<?php

namespace Hydrogen\Http\Request\Client;

use Hydrogen\Http\Exception\CurlException;

class Curl
{
	private $_curl = null;
	private $_url = null;
	private $_request_method = 'GET';

	const OPT_VALUE_BOOL = 1;
	const OPT_VALUE_INT = 2;
	const OPT_VALUE_STR = 3;
	const OPT_VALUE_ARRAY = 4;
	const OPT_VALUE_RESOURCE = 5;
	const OPT_VALUE_CALLBACKFUNC = 6;

	public function __construct($url = null)
	{
		$this->_curl = curl_init($url);
	}

    /**
     * set curl options
     *
     * @param $opt_key
     * @param $opt_value
     * @return $this
     * @throws CurlException
     */
	public function setOpt($opt_key, $opt_value)
	{
		if (!$opt_value) {
			throw new CurlException('You must fill in opt value');
		}

        $opt_key = strtoupper($opt_key);
		if (0 !== strpos($opt_key, 'CURLOPT_')) {
			$opt_key = 'CURLOPT_'.$opt_key;
		}

		if (!curl_setopt($this->_curl, constant($opt_key), $opt_value)) {
			throw new CurlException($opt_key.' Set failed!');
		}

		return $this;
	}

	public function setOptArr($options)
	{
		if (version_compare(PHP_VERSION, '5.1.3', '>=')) {
            if (is_array($options)) {
                foreach ($options as $option_key => $option_value)
                    if (!is_numeric($option_key)) {
                        $options[constant('CURLOPT_'.strtoupper($option_key))] = $option_value;
                        unset($options[$option_key]);
                    }
            }

            if (!curl_setopt_array($this->_curl, $options))
			    throw new CurlException('option arr set failed!');
		}

		return $this;
	}

	/**
	 * revert all ::setopt() operation
	 *
	 * @return $this
	 */
	public function reset()
	{	
		if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			curl_reset($this->_curl);
		}

		return $this;
	}

	public function escape($str)
	{
		return curl_escape($this->_curl, $str);
	}

	public function exec()
	{
		return curl_exec($this->_curl);
	}

	/**
	 * get curl handle after curl_init()
	 * 
	 * @return resource
	 */
	public function getHandle()
	{
		return $this->_curl;
	}

	public function copyHandle()
	{
		return curl_copy_handle($this->_curl);
	}

	/**
	 * @return string the latest error reason
	 */
	public function errno()
	{
		return curl_errno($this->_curl);
	}

	public function error()
	{
		return curl_error($this->_curl);
	}

	public function getInfo($opt)
	{
		return curl_getinfo($this->_curl, $opt);
	}

	public function version($age = CURLVERSION_NOW)
	{
		return curl_version($age);
	}

	public function close()
	{
		if (null !== $this->_curl) {
			@curl_close($this->_curl);
			$this->_curl = null;
		}
	}

	public function __clone()
	{
		// the handle of new instance should be different
		$this->_curl = $this->copyHandle(); 
	}

	public function __toString()
	{
		$fullUrl = $this->_url;

        $params = null;
		if ('GET' == $this->_request_method) {
			$fullUrl .= '?'.http_build_query($params);
		}

		return implode(' ', array(
			$this->_request_method,
			$fullUrl
		));
	}

	public function __destruct()
	{
		$this->close();
	}
}