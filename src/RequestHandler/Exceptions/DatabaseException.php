<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DatabaseException extends BaseException
{

    const ERROR_PREPARING_QUERY = 50001;

    const ERROR_EXECUTING_QUERY = 50002;

    protected $_errors = [
        DatabaseException::ERROR_PREPARING_QUERY => 'Failed to prepare query',
        DatabaseException::ERROR_EXECUTING_QUERY => 'Failed to execute query',
    ];

}