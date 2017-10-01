<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class RouterException extends BaseException
{

    const ERROR_INVALID_ROUTE_HANDLER = 90001;

    protected $_errors = [
        RouterException::ERROR_INVALID_ROUTE_HANDLER => 'Route handler must implement IHandle interface'
    ];
}