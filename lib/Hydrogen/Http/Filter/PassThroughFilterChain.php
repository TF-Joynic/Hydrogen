<?php

namespace Hydrogen\Http\Filter;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PassThroughFilterChain implements FilterChainInterface, \Iterator
{
    private $_pos = 0;

    /**
     * @var array| FilterInterface
     */
    private $_filters = array();

    /**
     * @var FilterInterface
     */
    private $_filter;

    /**
     * @var FilterChainInterface
     */

    public function addFilter(FilterInterface $filter)
    {
        $this->_filters[] = $filter;
    }

    public function doFilter(RequestInterface &$request, ResponseInterface &$response)
    {
        $filter = $this->current();
        if ($this->valid()) {
            $this->next();
            $filter->doFilter($request, $response, $this);
        } else {
            $this->next();
        }
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return FilterInterface
     * @since 5.0.0
     */
    public function current()
    {
        return isset($this->_filters[$this->_pos]) ? $this->_filters[$this->_pos] : null;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++ $this->_pos;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->_pos;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $current = $this->current();
        return null !== $current && $current instanceof FilterInterface;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->_pos = 0;
    }
}