<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class RouterException extends BaseException
{
    const BAD_ROUTE_HANDLER = 90001;

    protected $errors = [
        RouterException::BAD_ROUTE_HANDLER => 'Route handler must implement IHandle interface'
    ];
}