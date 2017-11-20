<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class RouterException extends BaseException
{

    const ERR_BAD_ROUTE_HANDLER = 90001;

    protected $_errors = [
        RouterException::ERR_BAD_ROUTE_HANDLER => 'Route handler must implement IHandle interface'
    ];
}