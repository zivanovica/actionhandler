<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class ObjectFactoryException extends BaseException
{

    const ERR_BAD_CLASS = 80001;
    const ERR_INTERFACE_MISMATCH = 80003;
    const ERR_BAD_TYPE = 80004;
    const ERR_UNRESOLVED_PARAMETER = 80005;

    protected $_errors = [
        ObjectFactoryException::ERR_BAD_CLASS => 'Invalid class name provided',
        ObjectFactoryException::ERR_INTERFACE_MISMATCH => 'Class does not implements required interface.',
        ObjectFactoryException::ERR_BAD_TYPE => 'Requested object is mapped with invalid type',
        ObjectFactoryException::ERR_UNRESOLVED_PARAMETER => 'ObjectFactory is unable to resolve parameter'
    ];
}