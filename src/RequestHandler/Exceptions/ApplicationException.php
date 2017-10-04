<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ApplicationException extends BaseException
{

    const ERROR_DUPLICATE_ACTION_HANDLER = 10001;
    const ERROR_INVALID_CONFIG = 10002;
    const ERROR_INVALID_REQUEST_HANDLER = 10003;

    protected $_errors = [
        ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER => 'Duplicate action',
        ApplicationException::ERROR_INVALID_CONFIG => 'Configuration file is not valid',
        ApplicationException::ERROR_INVALID_REQUEST_HANDLER => 'Given class does not implement IHandle interface',
    ];

}