<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ModelFilterException extends BaseException
{

    const ERR_BAD_MODEL_CLASS = 60001;

    protected $_errors = [
        ModelFilterException::ERR_BAD_MODEL_CLASS => 'Class doesn\'t extends Model'
    ];

}