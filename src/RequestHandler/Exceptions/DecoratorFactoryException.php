<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DecoratorFactoryException extends BaseException
{

    const ERR_DECORATOR_NOT_FOUND = 70001;
    const ERR_DECORATOR_NOT_VALID = 70002;
    const ERR_BAD_OBJECT_TYPE = 70003;
    const ERR_OBJECT_TYPE_MISMATCH = 70004;


    protected $_errors = [
        DecoratorFactoryException::ERR_DECORATOR_NOT_FOUND => 'Decorator class not found. Class',
        DecoratorFactoryException::ERR_DECORATOR_NOT_VALID => 'Class does not implement IDecorator interface. Class',
        DecoratorFactoryException::ERR_BAD_OBJECT_TYPE => 'Decorator "decorate" parameter is expected to be object',
        DecoratorFactoryException::ERR_OBJECT_TYPE_MISMATCH => 'Typed Decorator object mismatch'
    ];


}