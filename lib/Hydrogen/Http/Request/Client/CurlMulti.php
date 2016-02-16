<?php

namespace Hydrogen\Http\Request\Client;

class CurlMulti
{
	private $_curl = null;
	private $_attachedHandle = array();

	public function __construct()
	{
		$this->_curl = curl_multi_init();
	}

	public function addHandle()
	{
		$curlHandles = func_get_args();
		foreach ($curlHandles as $curlHandle) {
            if (0 === curl_multi_add_handle($this->_curl, $curlHandle)) {
                $this->_attachedHandle[] = $curlHandle;
            }
        }
	}

	public function exec()
	{
		$running = null;
		do {
			curl_multi_exec($this->_curl, $running);
		} while(0 < $running);
	}

	public function getContent()
	{
		return curl_multi_getcontent($this->_curl);
	}

	public function infoRead($msgs_in_queue = null)
	{
		return curl_multi_info_read($this->_curl, $msgs_in_queue);
	}

	public function close()
	{
		foreach ($this->_attachedHandle as $ch) {
			curl_multi_remove_handle($this->_curl, $ch);
		}
		curl_multi_close($this->_curl);
	}

	public function __destruct()
	{
		$this->close();
	}
}