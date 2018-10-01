<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ApplicationException extends BaseException
{
    const
        DUPLICATE_ACTION_HANDLER = 10001,
        BAD_CONFIG = 10002,
        BAD_REQUEST_HANDLER = 10003,
        INVALID_ROUTE = 10004;

    protected $_errors = [
        ApplicationException::DUPLICATE_ACTION_HANDLER => 'Duplicate action',
        ApplicationException::BAD_CONFIG => 'Configuration file is not valid',
        ApplicationException::BAD_REQUEST_HANDLER => 'Given class does not implement IHandle interface',
        ApplicationException::INVALID_ROUTE => 'Handler for requested route is not registered'
    ];
}