<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ModelFilterException extends BaseException
{

    const ERROR_INVALID_MODEL_CLASS = 60001;

    protected $_errors = [
        ModelFilterException::ERROR_INVALID_MODEL_CLASS => 'Class doesn\'t extends Model'
    ];

}