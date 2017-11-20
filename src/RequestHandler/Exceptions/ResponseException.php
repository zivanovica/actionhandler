<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ResponseException extends BaseException
{

    const ERR_BAD_STATUS_CODE = 20001;
    const ERR_BAD_DATA_TYPE = 20002;
    const ERR_BAD_ERRORS_TYPE = 20003;

    protected $_errors = [
        ResponseException::ERR_BAD_STATUS_CODE => 'Invalid status code',
        ResponseException::ERR_BAD_DATA_TYPE => 'Data must be an array',
        ResponseException::ERR_BAD_ERRORS_TYPE => 'Errors must be an array',
    ];

}