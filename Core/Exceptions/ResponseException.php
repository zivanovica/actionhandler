<?php

namespace Core\Exceptions;

class ResponseException extends AFrameworkException
{

    const ERROR_INVALID_STATUS_CODE = 20001;
    const ERROR_INVALID_DATA_TYPE = 20002;
    const ERROR_INVALID_ERRORS_TYPE = 20003;

    protected $_errors = [
        ResponseException::ERROR_INVALID_STATUS_CODE => 'Invalid status code',
        ResponseException::ERROR_INVALID_DATA_TYPE => 'Data must be an array',
        ResponseException::ERROR_INVALID_ERRORS_TYPE => 'Errors must be an array',
    ];

}