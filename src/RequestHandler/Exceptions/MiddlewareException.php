<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class MiddlewareException extends BaseException
{
    const BAD_MIDDLEWARE = 30001;

    protected $_errors = [
        MiddlewareException::BAD_MIDDLEWARE => 'Class must implement IMiddleware interface'
    ];

}