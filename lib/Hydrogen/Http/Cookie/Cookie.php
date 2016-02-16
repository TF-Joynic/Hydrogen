<?php

namespace Hydrogen\Http\Cookie;

use Hydrogen\Http\Exception\UnknownAttributeException;

/**
 * @property int $expire expiration of the cookie
 * @property string $path cookie exist path
 * @property string $domain domain under which cookie will be set
 * @property boolean $secure cookie will only be set through HTTPS if this attr is true
 * @property boolean $httpOnly only be visible to HTTP Protocol, invisible to javascript.
 */
class Cookie
{
//	public $name = '';

	/**
	 * value of this cookie
	 * @var string
	 */
	public $value = '';

	private $_attrs = array(
		'expire' => 0,
		'path' => '/',
		'domain' => '',
		'secure' => false,
		'httpOnly' => false
	);

	public function __construct($name, $value = '')
	{
		$this->name = (string) $name;
		$this->value = (string) $value;

        $this->_attrs['domain'] = $_SERVER['SERVER_NAME'];
	}

	public function __set($key, $value)
	{
		if (array_key_exists($key, $this->_attrs)) {
			$this->$key = $value;
		} elseif ('value' == $key) {
            $this->value = $value;
		} else {

            throw new UnknownAttributeException('Unknown Attribute: '.$key);

        }
	}

	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : $this->_attrs[$key];
	}

	public function __toString()
	{
        return implode(PHP_EOL, array(
            'name: '.$this->name,
            'value: '.$this->value,
            'expire: '.$this->expire,
            'path: '.$this->path,
            'domain: '.$this->domain,
            'secure: '.$this->secure,
            'httpOnly: '.$this->httpOnly
        ));
	}
}