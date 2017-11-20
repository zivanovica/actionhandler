<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ModelException extends BaseException
{

    const ERR_BAD_FIELD = 40001;
    const ERR_MISSING_PRIMARY = 40002;

    protected $_errors = [
        ModelException::ERR_BAD_FIELD => 'Field is not valid',
        ModelException::ERR_MISSING_PRIMARY => 'Primary key is required'
    ];

}