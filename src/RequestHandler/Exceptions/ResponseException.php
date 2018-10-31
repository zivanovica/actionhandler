<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ResponseException extends BaseException
{
    const
        BAD_STATUS_CODE = 20001,
        BAD_DATA_TYPE = 20002,
        BAD_ERRORS_TYPE = 20003;

    protected $errors = [
        ResponseException::BAD_STATUS_CODE => 'Invalid status code',
        ResponseException::BAD_DATA_TYPE => 'Data must be an array',
        ResponseException::BAD_ERRORS_TYPE => 'Errors must be an array',
    ];

}