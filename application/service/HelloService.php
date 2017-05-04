<?php

use application\thrift\HelloServiceIf;

/**
 * Created by IntelliJ IDEA.
 * User: xiaolei
 * Date: 17/5/4
 * Time: 15:53
 */
class HelloService implements HelloServiceIf
{
    public function __construct(HelloServiceClient $client)
    {

    }

    /**
     * @param string $param
     * @return string
     */
    public function helloString($param)
    {
        // TODO: Implement helloString() method.
    }

    /**
     * @param int $param
     * @return int
     */
    public function helloInt($param)
    {
        // TODO: Implement helloInt() method.
    }

    /**
     * @param bool $param
     * @return bool
     */
    public function helloBoolean($param)
    {
        // TODO: Implement helloBoolean() method.
    }

    /**
     */
    public function helloVoid()
    {
        // TODO: Implement helloVoid() method.
    }

    /**
     * @return string
     */
    public function helloNull()
    {
        // TODO: Implement helloNull() method.
    }

    public function __destruct()
    {

    }
}