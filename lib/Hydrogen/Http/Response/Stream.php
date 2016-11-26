<?php

namespace Hydrogen\Http\Response;

use Hydrogen\Http\Exception\InstantiationException;
use Hydrogen\Http\Exception\StreamManipulationException;
use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    const DEFAULT_STREAM_WRAPPER = 'php://temp';

    const MODE = 1;
    const USE_INCLUDE_PATH = 2;
    const CONTEXT = 3;

    public static $_allowed_options = array(
        self::MODE,
        self::USE_INCLUDE_PATH,
        self::CONTEXT
    );

    private $_stream = null;

    public function __construct($from, $options = array(), $default_mem_use = 4194304)
    {
        if (is_resource($from)) {
            $this->_stream = $from;
        } elseif ($from && is_string($from)) {
            $options = array_intersect_key(self::$_allowed_options, $options);

            $mode = isset($options[self::MODE]) ? $options[self::MODE] : 'rb';

            $use_include_path = isset($options[self::USE_INCLUDE_PATH])
                ? $options[self::USE_INCLUDE_PATH] : false;

            $context = isset($options[self::CONTEXT]) ? $options[self::CONTEXT] : null;

            if ($default_mem_use && is_int($default_mem_use)
                && false !== strpos($from, 'php://temp')) {

                $from = $from.'/maxmemory:'.$default_mem_use;

            }
            
            $handle = $context ? fopen($from, $mode, $use_include_path, $context) :  fopen($from, $mode, $use_include_path);
            if (false !== $handle) {
                $this->_stream = $handle;
            }
        }

        if (null === $this->_stream) {
            throw new InstantiationException('invalid args specified');
        }
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString()
    {
        $string = '';

        if (null !== $this->_stream) {
            try {
                $this->seek(0);
                $string = $this->getContents();
            } catch (StreamManipulationException $e) {
                $string = '';
            }
        }

        return $string;
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        null !== $this->_stream && fclose($this->_stream);
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        if (null === $this->_stream) {
            return null;
        }

        $resource = $this->_stream;
        $this->_stream = null;

        return $resource;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize()
    {
        if (null !== $this->_stream && (false !== $size = filesize($this->_stream))) {
            return $size;
        }

        return null;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell()
    {
        if (null !== $this->_stream && (false !== $pos = ftell($this->_stream))) {
            return $pos;
        }

        throw new StreamManipulationException('Failed to tell current stream position!');
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof()
    {
        return null === $this->_stream ? false : feof($this->_stream);
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable()
    {
        if (null !== $this->_stream) {
            return false;
        }

        $meta = stream_get_meta_data($this->_stream);
        return $meta['seekable'];
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (null === $this->_stream || -1 === fseek($this->_stream, $offset, $whence)) {
            throw new StreamManipulationException('Failed when attempt to seek stream');
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind()
    {
        if (null !== $this->_stream && $this->isSeekable()) {
            $this->seek(0);
        }

        throw new StreamManipulationException('Failed when attempt to rewind(seek) stream');
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable()
    {
        return null !== $this->_stream ? is_writable($this->_stream) : false;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string)
    {
        if (null !== $this->_stream && (false !== $len = fwrite($this->_stream, $string))) {
            return $len;
        }

        throw new StreamManipulationException('Failed to write string to stream');
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        return null !== $this->_stream ? is_readable($this->_stream) : false;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length)
    {
        if (null !== $this->_stream && (false !== $content = fread($this->_stream, $length))) {
            return $content;
        }

        throw new StreamManipulationException('Failed to read data from the stream');
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        if (null === $this->_stream) {
            throw new StreamManipulationException('Invalid stream!');
        }

        return stream_get_contents($this->_stream);
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        if (null !== $this->_stream) {
            return null;
        }

        $meta = stream_get_meta_data($this->_stream);
        return null === $key ? $meta : (isset($meta[$key]) ? $meta[$key] : null);
    }
}