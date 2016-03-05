<?php
/**
 * Http Message Class
 */
namespace Hydrogen\Http;

use Hydrogen\Http\Exception\InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    const HTTP_HEADER_PREFIX = 'HTTP_';

    private $_protocol_version = '';

    /**
     * http headers and so on, keys and values are compatible with $_SERVER http header element (prefix: 'HTTP_')
     */
    private $_headers = array();

    public function __construct()
    {
        $http_version = '1.1';

        if (isset($_SERVER['SERVER_PROTOCOL'])) {  // server side
            $http_version = preg_replace('[^(\d|\.)]', '', $_SERVER['SERVER_PROTOCOL']);

            $this->extractServerRequestHeaders();
        }

        $this->_protocol_version = $http_version;
    }

    /**
     * extract header keys from the super global variable $_SERVER
     */
    private function extractServerRequestHeaders()
    {
        $headers = array();

        if (isset($_SERVER['SERVER_SOFTWARE'])
            && false !== stripos($_SERVER['SERVER_SOFTWARE'], 'apache')
            || isset($_SERVER['SERVER_SIGNATURE'])
            && false !== stripos($_SERVER['SERVER_SIGNATURE'], 'apache')) {

            $apache_headers = apache_request_headers();
            foreach ($apache_headers as $header => $value) {
                $headers[$this->sleepHeaderName($header)] = $value;
            }

        } else {
            foreach ($_SERVER as $server_key => $server_value) {
                if (0 === strpos($server_key, self::HTTP_HEADER_PREFIX)) {
                    $tmp_key = str_replace(' ', '-', ucwords(str_replace(array(self::HTTP_HEADER_PREFIX, '_'), array('', ' '), $server_key)));
                    $headers[$tmp_key] = $server_value;
                }
            }

        }

        $this->_headers = $headers;
    }

    private function getHttpHeaders($line = true)
    {
        $line_headers = $this->_headers;
        $headers = array();
        foreach ($line_headers as $line_header => $value) {
            $headers[$this->wakeupHeaderName($line_header)] = $value;
        }

        if (!$line)
            foreach ($headers as $k => &$v)
                $v = explode(',', $v);

        pre($headers);exit;
        return $headers;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->_protocol_version;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     * @return self
     */
    public function withProtocolVersion($version)
    {
        if (is_numeric($version)) {
            $this->_protocol_version = $version;
        }

        return $this;
    }

    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return array Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings
     *     for that header.
     */
    public function getHeaders()
    {
        $this->getHttpHeaders();
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name)
    {
        return isset($this->_headers[$name]) ? true : false;
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {
        $sleepHeaderName = $this->sleepHeaderName($name);

        return isset($this->_headers[$sleepHeaderName])
            ? explode(',', $this->_headers[$sleepHeaderName]) : array();
    }

    /**
     * Transform header name from the $_SERVER global variable into browser
     * understand form. for instance, 'ACCEPT_LANGUAGE'
     * or 'HTTP_ACCEPT_LANGUAGE' becomes 'Accept-Language'
     *
     * @param $name
     * @return mixed
     */
    private function wakeupHeaderName($name)
    {
        $name = str_replace(' ', '-', ucwords(str_replace(
            array(self::HTTP_HEADER_PREFIX, '_'), array('', ' '), $name)));

        return $name;
    }

    private function sleepHeaderName($name)
    {
        $name = strtoupper(str_replace(array(' ', '-'), '_', $name));

        return self::HTTP_HEADER_PREFIX.$name;
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name)
    {
        return isset($this->_headers[$name]) ? $this->_headers[$name] : '';
    }

    private function isValidHeaderValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                if (!is_string($v)) {
                    return false;
                }
            }
        } elseif (!is_string($value) || strlen($value) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {
        if (!$this->isValidHeaderValue($value)) {
            throw new InvalidArgumentException('invalid header names or values');
        }

        $name = $this->sleepHeaderName($name);
        $this->_headers[$name] = is_array($value) ? implode(',', $value) : $value;

        return $this;
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value)
    {
        if (!$this->isValidHeaderValue($value)) {
            throw new InvalidArgumentException('invalid header names or values');
        }

        $name = $this->sleepHeaderName($name);

        $append = '';
        if (is_array($value)) {
            $append = implode(',', $value);
        } else {
            $append = $value;
        }

        if ($append) {
            if (isset($headers[$name])) {
                    $this->_headers[$name] = $this->_headers[$name].','.$append;
            } else {
                $this->_headers[$name] = $append;
            }
        }

        return $this;
    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return self
     */
    public function withoutHeader($name)
    {
        $name = $this->sleepHeaderName($name);

        if (isset($this->_headers[$name])) {
            unset($this->_headers[$name]);
        }

        return $this;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     * @return self
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }
}