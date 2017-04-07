<?php

namespace Hydrogen\Http\Request;


class RequestMethod
{
    /**
     * Bummer! Contant expression is supported since PHP 5.6 :(
     * , thus we assign the final result here.
     */

    // 1 << 0
    const GET = 1;

    // 1 << 1
    const POST = 2;

    // 1 << 2
    const PUT = 4;

    // 1 << 3
    const PATCH = 8;

    // 1 << 4
    const DELETE = 16;


    // 1 << 5
    const HEAD = 32;

    // 1 << 6
    const OPTIONS = 64;


}