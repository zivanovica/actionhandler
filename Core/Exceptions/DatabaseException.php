<?php

namespace Core\Exceptions;

class DatabaseException extends AFrameworkException
{

    const ERROR_PREPARING_QUERY = 50001;

    const ERROR_EXECUTING_QUERY = 50002;

    protected $_errors = [
        DatabaseException::ERROR_PREPARING_QUERY => 'Failed to prepare query',
        DatabaseException::ERROR_EXECUTING_QUERY => 'Failed to execute query',
    ];

}