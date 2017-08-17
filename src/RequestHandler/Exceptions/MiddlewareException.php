<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class MiddlewareException extends BaseException
{

    const ERROR_INVALID_MIDDLEWARE = 30001;

    protected $_errors = [
        MiddlewareException::ERROR_INVALID_MIDDLEWARE => 'Class must implement IMiddleware interface'
    ];

}