<?php
/**
 * User: Aleksandar Zivkovic
 * Date: 10/31/18 1:31 PM
 */

namespace RequestHandler\Exceptions;


use RequestHandler\Modules\Exception\BaseException;

class ObservableException extends BaseException
{
    const INVALID_VALUE_TYPE = 120001;

    protected $errors = [
        self::INVALID_VALUE_TYPE => 'Provided value has wrong type'
    ];
}