<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ModelException extends BaseException
{

    const ERROR_INVALID_FIELD = 40001;
    const ERROR_MISSING_PRIMARY = 40002;

    protected $_errors = [
        ModelException::ERROR_INVALID_FIELD => 'Field is not valid',
        ModelException::ERROR_MISSING_PRIMARY => 'Primary key is required'
    ];

}