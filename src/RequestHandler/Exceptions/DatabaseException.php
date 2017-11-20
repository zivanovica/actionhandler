<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DatabaseException extends BaseException
{

    const ERR_PREPARING_QUERY = 50001;

    const ERR_EXECUTING_QUERY = 50002;

    const ERR_BAD_PARAMETERS = 50003;

    protected $_errors = [
        DatabaseException::ERR_PREPARING_QUERY => 'Failed to prepare query',
        DatabaseException::ERR_EXECUTING_QUERY => 'Failed to execute query',
        DatabaseException::ERR_BAD_PARAMETERS => 'Missing required connection parameter'
    ];

}