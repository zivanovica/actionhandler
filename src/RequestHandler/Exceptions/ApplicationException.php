<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ApplicationException extends BaseException
{

    const ERR_DUPLICATE_ACTION_HANDLER = 10001;
    const ERR_BAD_CONFIG = 10002;
    const ERR_BAD_REQUEST_HANDLER = 10003;
    const ERR_INVALID_ROUTE = 10004;

    protected $_errors = [
        ApplicationException::ERR_DUPLICATE_ACTION_HANDLER => 'Duplicate action',
        ApplicationException::ERR_BAD_CONFIG => 'Configuration file is not valid',
        ApplicationException::ERR_BAD_REQUEST_HANDLER => 'Given class does not implement IHandle interface',
        ApplicationException::ERR_INVALID_ROUTE => 'Handler for requested route is not registered'
    ];

}