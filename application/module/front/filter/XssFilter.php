<?php

namespace application\module\front\filter;

use Hydrogen\Http\Filter\FilterChainInterface;
use Hydrogen\Http\Filter\FilterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class XssFilter implements FilterInterface
{

    public function init()
    {
        
    }

    public function getId()
    {
        return static::class;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param FilterChainInterface $filterChain
     * @return mixed
     */
    public function doFilter(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
    {
        if (null != $request) {
            $request = new XssRequestWrapper();
        }

        $filterChain->doFilter($request, $response);
    }

    private function escape($str)
    {
        if (!get_magic_quotes_gpc()) {
            $str = addslashes($str);
        }

        $str = htmlspecialchars($str, ENT_QUOTES);
        return $str;
    }

    protected function _sanitize($str)
    {
        if (is_string($str)) {
            if (0 == strlen($str)) {
                return $str;
            }

            $str = $this->escape($str);
        } elseif (is_array($str)) {
            foreach ($str as &$v) {
                $v = $this->_sanitize($v);
            }
        }

        return $str;
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }
}