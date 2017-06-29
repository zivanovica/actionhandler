<?php

namespace Core\Exceptions;

class ApplicationException extends AFrameworkException
{

    const ERROR_DUPLICATE_ACTION_HANDLER = 10001;
    const ERROR_INVALID_CONFIG = 10002;

    protected $_errors = [
        ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER => 'Duplicate action',
        ApplicationException::ERROR_INVALID_CONFIG => 'Configuration file is not valid'
    ];

}