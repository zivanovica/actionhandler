<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DatabaseException extends BaseException
{
    const
        PREPARING_QUERY = 50001,
        EXECUTING_QUERY = 50002,
        BAD_PARAMETERS = 50003;

    protected $_errors = [
        DatabaseException::PREPARING_QUERY => 'Failed to prepare query',
        DatabaseException::EXECUTING_QUERY => 'Failed to execute query',
        DatabaseException::BAD_PARAMETERS => 'Missing required connection parameter'
    ];

}