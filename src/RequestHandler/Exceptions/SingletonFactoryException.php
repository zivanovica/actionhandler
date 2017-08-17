<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class SingletonFactoryException extends BaseException
{

    const ERROR_INVALID_INTERFACE = 80001;
    const ERROR_INVALID_CLASS = 80002;
    const ERROR_INTERFACE_MISMATCH = 80003;
    const ERROR_INVALID_TYPE = 80004;

    protected $_errors = [
        SingletonFactoryException::ERROR_INVALID_INTERFACE => 'Invalid interface provided.',
        SingletonFactoryException::ERROR_INVALID_CLASS => 'Invalid class name provided',
        SingletonFactoryException::ERROR_INTERFACE_MISMATCH => 'Class does not implements required interface.',
        SingletonFactoryException::ERROR_INVALID_TYPE => 'Requested object is mapped with invalid type'
    ];
}