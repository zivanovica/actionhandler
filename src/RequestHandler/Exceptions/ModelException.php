<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ModelException extends BaseException
{
    const
        BAD_FIELD = 40001,
        MISSING_PRIMARY = 40002;

    protected $_errors = [
        ModelException::BAD_FIELD => 'Field is not valid',
        ModelException::MISSING_PRIMARY => 'Primary key is required'
    ];

}