<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

class ObjectFactoryException extends BaseException
{

    const ERROR_INVALID_INTERFACE = 80001;
    const ERROR_INVALID_CLASS = 80002;
    const ERROR_INTERFACE_MISMATCH = 80003;
    const ERROR_INVALID_TYPE = 80004;
    const ERROR_UNRESOLVED_PARAMETER = 80005;

    protected $_errors = [
        ObjectFactoryException::ERROR_INVALID_INTERFACE => 'Invalid interface provided.',
        ObjectFactoryException::ERROR_INVALID_CLASS => 'Invalid class name provided',
        ObjectFactoryException::ERROR_INTERFACE_MISMATCH => 'Class does not implements required interface.',
        ObjectFactoryException::ERROR_INVALID_TYPE => 'Requested object is mapped with invalid type',
        ObjectFactoryException::ERROR_UNRESOLVED_PARAMETER => 'ObjectFactory is unable to resolve parameter'
    ];
}