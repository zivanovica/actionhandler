<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ObjectFactoryException extends BaseException
{
    const
        BAD_CLASS = 80001,
        INTERFACE_MISMATCH = 80003,
        BAD_TYPE = 80004,
        UNRESOLVED_PARAMETER = 80005,
        CIRCULAR_INSTANCING = 80006,
        CLASS_INSTANTIATING_FAILED = 80007;


    protected $errors = [
        ObjectFactoryException::BAD_CLASS => 'Invalid class name provided',
        ObjectFactoryException::INTERFACE_MISMATCH => 'Class does not implements required interface.',
        ObjectFactoryException::BAD_TYPE => 'Requested object is mapped with invalid type',
        ObjectFactoryException::UNRESOLVED_PARAMETER => 'ObjectFactory is unable to resolve parameter',
        ObjectFactoryException::CIRCULAR_INSTANCING => 'Requested interface is not linked',
        ObjectFactoryException::CLASS_INSTANTIATING_FAILED => 'Failed to create reflection instance of class',
    ];
}