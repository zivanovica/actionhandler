<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class MiddlewareException extends BaseException
{

    const ERR_BAD_MIDDLEWARE = 30001;

    protected $_errors = [
        MiddlewareException::ERR_BAD_MIDDLEWARE => 'Class must implement IMiddleware interface'
    ];

}