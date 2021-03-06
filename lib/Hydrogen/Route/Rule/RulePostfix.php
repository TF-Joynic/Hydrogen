<?php

namespace Hydrogen\Route\Rule;


use Hydrogen\Load\Loader;
use Hydrogen\Route\Exception\InvalidArgumentException;
use Hydrogen\Http\Request\FrameworkServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * this rule with set content-type header according to postfix mapping
 *
 * Class RulePostfix
 * @package Hydrogen\Route\Rule
 */
class RulePostfix extends AbstractRule
{

    public function __construct($ruleStr)
    {
        if (!is_string($ruleStr) || 0 == strlen($postfix = ltrim($ruleStr, '.'))) {
            throw new InvalidArgumentException('Route Rule[postfix]: invalid rule string!');
        }

        $this->_terminable = false;
        $this->_ruleStr = $postfix;
    }

    /**
     * $path: user/profile.json
     *
     * @param $path
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool|\Closure
     */
    public function apply(&$path, RequestInterface $request, ResponseInterface $response)
    {
        if (false !== $postfixPos = strrpos($path, '.')) {
            $realPath = substr($path, 0, $postfixPos);
            $postfix = substr($path, $postfixPos + 1);

            if ($postfix == $this->_ruleStr) {
                $path = $realPath;

                Loader::import('lib/Hydrogen/Http/Response/MIME.php');
                // automatically setup Content-type
                if (null !== $content_type = getMIMEheader($postfix)) {
                    $response->withHeader(HTTP_HEADER_CONTENT_TYPE, $content_type);
                }

                $this->performCallback($request, $response);
            }
        }

        return false;
    }
}