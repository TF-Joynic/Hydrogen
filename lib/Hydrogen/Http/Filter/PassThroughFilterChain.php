<?php

namespace Hydrogen\Http\Filter;


use Hydrogen\Http\Exception\InstantiationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PassThroughFilterChain implements FilterChainInterface
{
    /**
     * @var FilterInterface
     */
    private $_filter;

    /**
     * @var FilterChainInterface
     */
    private $_filterChain;

    public function __construct(FilterInterface $filter, FilterChainInterface $filterChain)
    {
        if (null == $filter || null == $filterChain) {
            throw new InstantiationException('construct args can not be null!');
        }

        $this->_filter = $filter;
        $this->_filterChain = $filterChain;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->_filter;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        // TODO: Implement key() method.
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
        // TODO: Implement valid() method.
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    function doFilter(RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement doFilter() method.
    }
}